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
            'payerReference' => 'CUST-1000' . $user['id'] . rand(1, 100),
            'callbackURL' => config('bkash.agreement_callback_url'),
            'currency' => 'BDT',
            'amount' => '10.0',
            'merchantInvoiceNumber' => 'INV-1000' . $user['id'] . rand(1, 100),
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
        $wallet = Wallet::where('user_id', $user['id'])->first();
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
        $lockKey = "{$user['id']}:{$orderId}";
        $lockKey = "payment:state:$lockKey";

        if (Redis::get($lockKey) == 'pending') {
            return response()->json([
                'statusCode' => 'error',
                'statusMessage' => 'Order id is in used pending.',
            ]);
        }
        Redis::setex($lockKey, $lockTtl, 'pending');

        $wallet = Wallet::where('user_id', $user['id'])->first();
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
        $wallet = Wallet::where('user_id', $user['id'])->first();
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
        $lockKey = "payment:state:{$user['id']}:{$transactionData['merchantInvoice']}";
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
        $wallet = Wallet::where('user_id', $user['id'])->first();
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
}
