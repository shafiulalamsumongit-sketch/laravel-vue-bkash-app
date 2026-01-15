<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class WalletController extends Controller
{
    public function createApiToken(Request $request, $user)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'username' => config('bkash.username'),
            'password' => config('bkash.password'),
        ])->post(config('bkash.base_url') . '/tokenized/checkout/token/grant', [
            'app_key' => config('bkash.app_key'),
            'app_secret' => config('bkash.app_secret'),
        ]);
        return $response->json();
    }

    public function checkoutCreate($request, $apiToken)
    {
        // create a Business logic layer - BkashService Class
        $user = $request->user();
        return $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $apiToken['id_token'],
            'X-App-Key' => config('bkash.app_key'),
        ])->post(config('bkash.base_url') . '/tokenized/checkout/create', [
            'mode' => '0000',
            'payerReference' => 'CUST-1000' . auth()->id() . rand(1, 100),
            'callbackURL' => config('bkash.agreement_callback_url'),
            'currency' => 'BDT',
            'amount' => '10.0',
            'merchantInvoiceNumber' => 'INV-1000' . auth()->id() . rand(1, 100),
        ]);
    }

    public function createAgreement(Request $request)
    {
        $user = $request->user();
        $apiToken = $this->createApiToken($request, $user);
        if (isset($apiToken['id_token']) && !empty($apiToken['id_token'])) {
            $response = $this->checkoutCreate($request, $apiToken);
            $responseCreateAgreement = $response->json();
            return $response->json();
        } else {
            return response()->json([
                'bkashURL' => '',
                'successCallbackURL' => '',
                'failureCallbackURL' => '',
                'cancelledCallbackURL' => '',
            ]);
        }
    }

    public function executeAgreement(Request $request)
    {
        $user = $request->user();
        $wallet = Wallet::where('user_id', auth()->id())->first();
        if (isset($wallet['token']) && !empty($wallet['token'])) {
            return response()->json([
                'statusCode' => 'error',
                'statusMessage' => 'Agreement already exists between payer and merchant.',
            ]);
        }
        $apiToken = $this->createApiToken($request, $user);
        if (isset($apiToken['id_token']) && !empty($apiToken['id_token'])) {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => $apiToken['id_token'],
                'X-App-Key' => config('bkash.app_key'),
            ])->post(config('bkash.base_url') . '/tokenized/checkout/execute', [
                'paymentID' => $request->payment_id
            ]);
            $responseCreateAgreement = $response->json();
            if ($responseCreateAgreement['statusCode'] == '2050') {
                return response()->json([
                    'statusCode' => '2050',
                    'statusMessage' => 'Agreement already exists between payer and merchant',
                ]);
            }
            if ($responseCreateAgreement['statusCode'] == '0000' && $responseCreateAgreement['agreementID'] != '') {
                $wallet = Wallet::create([
                    'user_id' => $user->id,
                    // 'token' => Crypt::encryptString($responseCreateAgreement['agreementID']),
                    'token' => $responseCreateAgreement['agreementID'],
                    'masked' => $responseCreateAgreement['payerAccount'],
                    'balance' => 2000.0
                ]);
            }
            return response()->json($responseCreateAgreement);
        } else {
            return response()->json([
                'statusCode' => 'error',
                'statusMessage' => 'Agreement can not be created.',
            ]);
        }
    }

    public function createPayment(Request $request)
    {
        $user = $request->user();
        $orderId = 'min-' . $request->order_id;

        $lockTtl = 10 * 60;  // 10 minutes
        $lockKey = '{' . auth()->id() . "}:{$orderId}";
        $lockKey = "payment:state:$lockKey";

        if (Redis::get($lockKey) == 'pending') {
            return response()->json([
                'statusCode' => 'error',
                'statusMessage' => 'Order id is in used pending.',
            ]);
        }
        Redis::setex($lockKey, $lockTtl, 'pending');

        $wallet = Wallet::where('user_id', auth()->id())->first();
        if (!isset($wallet['token']) && empty($wallet['token'])) {
            return response()->json([
                'statusCode' => 'error',
                'statusMessage' => 'Agreement wallet not created yet. Please make an agreement at first.',
            ]);
        }

        $agreementToken = $wallet['token'];
        $apiToken = $this->createApiToken($request, $user);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $apiToken['id_token'],
            'X-App-Key' => config('bkash.app_key'),
        ])->post(config('bkash.base_url') . '/tokenized/checkout/create', [
            'mode' => '0001',
            'payerReference' => $orderId,
            'callbackURL' => config('bkash.payment_callback_url'),
            'agreementID' => $agreementToken,
            'currency' => 'BDT',
            'amount' => number_format($request->amount, 2, '.', ''),
            'intent' => 'sale',
            'merchantInvoiceNumber' => $orderId,
        ]);
        return $response->json();
    }

    public function statusPayment(Request $request)
    {
        $user = $request->user();
        $apiToken = $this->createApiToken($request, $user);
        $wallet = Wallet::where('user_id', auth()->id())->first();
        if (!isset($wallet['id']) && empty($wallet['id'])) {
            return response()->json([
                'statusCode' => 'error',
                'statusMessage' => 'Agreement not created yet.',
            ]);
        }
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => $apiToken['id_token'],
            'X-App-Key' => config('bkash.app_key'),
        ])->post(config('bkash.base_url') . '/tokenized/checkout/payment/status', [
            'paymentID' => $request->payment_id
        ]);
        $transactionData = $response->json();
        $user = $request->user();
        $orderId = 'min-' . $request->order_id;
        $lockKey = 'payment:state:{' . auth()->id() . "}:{$transactionData['merchantInvoice']}";
        if (Redis::get($lockKey) == 'completed') {
            return response()->json([
                'statusCode' => 'found',
                'message' => 'Payment already completed.',
                'statusMessage' => $transactionData,
            ]);
        }
        if (!array_key_exists('trx_iD', $transactionData)) {
            try {
                $apiToken = $this->createApiToken($request, $user);  // 2nd
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => $apiToken['id_token'],
                    'X-App-Key' => config('bkash.app_key'),
                ])->post(config('bkash.base_url') . '/tokenized/checkout/execute', [
                    'paymentID' => $request->payment_id
                ]);
                $transactionData = $response->json();
            } catch (Exception $e) {
                return response()->json([
                    'statusCode' => 'error',
                    'statusMessage' => 'Caught exception: ',
                    $e->getMessage(),
                    "\n",
                ]);
            }
        }
        $walletTransaction = Transaction::where('payment_id', $request->payment_id)->first();
        if (!isset($walletTransaction['payment_id']) && empty($walletTransaction['payment_id'])) {
            $transaction = Transaction::create([
                'wallet_id' => $wallet['id'],
                'type' => 'debit',
                'amount' => $transactionData['amount'],
                'description' => $transactionData['amount'],
                'payment_id' => $transactionData['paymentID'],
                'agreement_id' => $transactionData['agreementID'],
                'trx_iD' => $transactionData['trxID'],
                'merchant_invoice' => $transactionData['merchantInvoiceNumber'],
                'transaction_status' => $transactionData['transactionStatus'],
                'service_fee' => 1,
                'credited_amount' => $transactionData['amount'],
                'maxRefundable_amount' => $transactionData['maxRefundableAmount'],
                'status_code' => $transactionData['statusCode'],
                'status_message' => $transactionData['statusMessage'],
            ]);
        }
        Redis::set($lockKey, 'completed');
        // Redis::del($lockKey);
        return response()->json([
            'statusCode' => 'found',
            'stage' => 3,
            'lockKey' => 'completed',
            'statusMessage' => $transactionData,
        ]);
    }

    public function createRefund(Request $request)
    {
        $user = $request->user();
        $apiToken = $this->createApiToken($request, $user);
        $wallet = Wallet::where('user_id', auth()->id())->first();
        if (!isset($wallet['id']) && empty($wallet['id'])) {
            return response()->json([
                'statusCode' => 'error',
                'statusMessage' => 'Agreement not created yet.',
            ]);
        }
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => $apiToken['id_token'],
            'X-App-Key' => config('bkash.app_key'),
        ])->post(config('bkash.tokenaized_url') . '/v2/tokenized-checkout/refund/payment/transaction', [
            'paymentId' => $request->payment_id,
            'trxId' => $request->trx_id,
            'refundAmount' => '1.0',  // test refund amount
            'sku' => 'ORD-100' . rand(1, 100),
            'reason' => 'Test Refund'
        ]);
        return $response->json();
    }

    public function transactionHistories(Request $request)
    {
        $user = $request->user();
        $wallet = $request
            ->user()
            ->wallet()
            ->where('user_id', auth()->id())
            ->first();
        $transactions = $wallet
            ->transactions()
            ->latest()
            ->paginate(1);
        return response()->json([
            'wallet' => $wallet,
            'transactions' => $transactions
        ]);
    }

    public function transactionDownload11(Request $request)
    {
        $user = $request->user();
        $wallet = $request
            ->user()
            ->wallet()
            ->where('user_id', auth()->id())
            ->first();
        $transactions = $wallet
            ->transactions();

        $gotenbergUrl = 'http://localhost:3000/forms/chromium/convert/html';
        $html = <<<HTML
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Gotenberg PDF</title>
                    <style>
                        body { font-family: Arial; }
                        h1 { color: green; }
                    </style>
                </head>
                <body>
                    <h1>Hello Gotenberg 🚀</h1>
                    <p>PDF generated using PHP + Docker</p>
                </body>
                </html>
            HTML;
        // create temp html file
        $tmpFile = tempnam(sys_get_temp_dir(), 'gt_') . '.html';
        file_put_contents($tmpFile, $html);
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $gotenbergUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'files' => new \CURLFile($tmpFile, 'text/html', 'index.html'),
            ],
        ]);
        $pdf = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        unlink($tmpFile);
        if ($httpCode !== 200) {
            die('PDF generation failed');
        }
        file_put_contents('output000000.pdf', $pdf);
        $filePath = 'output000000.pdf';  // Adjust the path as necessary
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }
        $headers = [
            'Content-Type' => 'application/pdf',
        ];
        return response()->download($filePath, 'output000000.pdf', $headers);
    }

    public function transactionDownload(Request $request)
    {
        $user = $request->user();
        $wallet = $request
            ->user()
            ->wallet()
            ->where('user_id', auth()->id())
            ->first();

        $transactions = $wallet
            ? $wallet->transactions()->get()
            : collect();

     

        $data['user'] = $user;
        $data['transactions'] = $transactions;
        $html = View::make('transaction_pdf', compact('user', 'transactions'))->render();

        $user = $request->user();
        $gotenbergUrl = 'http://localhost:3000/forms/chromium/convert/html';
        $response = Http::attach(
            'files', $html, 'index.html'
        )->post($gotenbergUrl, [
            'paperWidth' => 8.27,
            'paperHeight' => 11.69,
        ]);
        $fileName = 'transactions_' . time() . '.pdf';
        Storage::disk('public')->put($fileName, $response->body());
        return response()->download(
            storage_path('app/public/' . $fileName),
            $fileName,
            ['Content-Type' => 'application/pdf']
        )->deleteFileAfterSend(false);
    }
}
