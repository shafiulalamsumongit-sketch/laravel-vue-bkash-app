<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;


class WalletController extends Controller
{
    /**
     * Process
     * 1. Grant Token
     *
     */
    public function createAgreement (Request $request)
    {
        $user = $request->user();

        /** Grant Token */      
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'username' => config('bkash.username'),
            'password' => config('bkash.password'),
        ])->post(config('bkash.base_url') . '/tokenized/checkout/token/grant', [
            'app_key' => config('bkash.app_key'),
            'app_secret' => config('bkash.app_secret'),
        ]);
        $responseTokenGrant = $response->json();

        /** Create Agreement */ 
        if(isset($responseTokenGrant['id_token']) && !empty($responseTokenGrant['id_token'])){
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => $responseTokenGrant['id_token'],
                    'X-App-Key' => config('bkash.app_key'),
                ])->post(config('bkash.base_url') . '/tokenized/checkout/create', [
                    'mode' => "0000",
                    'payerReference' => "CUST-1000".$user['id'].rand(1,100),
                    "callbackURL"=>  config('bkash.callback_url'),    
                    "currency"=>  "BDT",
                    "amount"=>  "10.0",
                    "merchantInvoiceNumber"=>  "INV-1000".$user['id'].rand(1,100),
                ]);
                $responseCreateAgreement = $response->json();
                return $response->json();
        }else{
            return response()->json([
            'bkashURL' => '',
            'successCallbackURL' => '',
            'failureCallbackURL' => '',
            'cancelledCallbackURL' => '',
            ]);
        }        
    }

    
    public function executeAgreement (Request $request)
    {
        $user = $request->user();

        /** Check Agreement already exists */   
        $wallet = Wallet::where('user_id', $user['id'])->first(); 

        if(isset($wallet['token']) && !empty($wallet['token'])){
          return response()->json([
                'statusCode' => 'error',
                'statusMessage' => "Agreement already exists between payer and merchant.",
          ]);
        }

        /** Grant Token */      
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'username' => config('bkash.username'),
            'password' => config('bkash.password'),
        ])->post(config('bkash.base_url') . '/tokenized/checkout/token/grant', [
            'app_key' => config('bkash.app_key'),
            'app_secret' => config('bkash.app_secret'),
        ]);
        $responseTokenGrant = $response->json();


        /** Create Agreement */ 
        if(isset($responseTokenGrant['id_token']) && !empty($responseTokenGrant['id_token'])){
                $response = Http::withHeaders([                   
                    'Accept' => 'application/json',
                    'Authorization' => $responseTokenGrant['id_token'],
                    'X-App-Key' => config('bkash.app_key'),
                ])->post(config('bkash.base_url') . '/tokenized/checkout/execute', [
                    'paymentID' => $request->payment_id
                ]);
                $responseCreateAgreement = $response->json();

                if($responseCreateAgreement['statusCode'] == "2050"){
                              return response()->json([
                                'statusCode' => '2050',
                                'statusMessage' => "Agreement already exists between payer and merchant",
                                ]);

                }
                if($responseCreateAgreement['statusCode'] == "0000" && $responseCreateAgreement['agreementID'] != "" ){
                    // Store the Agreement
                    $wallet = Wallet::create([
                        'user_id' => $user->id,
                        'token'   => Crypt::encryptString($responseCreateAgreement['agreementID']), 
                        'masked'  => $responseCreateAgreement['payerAccount'],
                        'balance'  => 2000.0
                    ]);
               }
           
           return response()->json($responseCreateAgreement);

        }else{
            //"statusCode":"2050","statusMessage":"Agreement already exists between payer and merchant"
            return response()->json([
            'statusCode' => 'error',
            'statusMessage' => "Agreement can not be created.",
            ]);
        }        
    }

}