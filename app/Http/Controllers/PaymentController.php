<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Omnipay\Omnipay;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    private $gateway;

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(true);
    }

    public function pay(Request $request)
    {
        // $request->session()->put('payment_data', $request->all());

        try {
            
            // $validator = Validator::make($request->all(), [
            //     'participant_id' => 'required|exists:participants,id',
            //     'formation_id' => 'required|exists:formations,id',
            //     'status' => 'required|string|max:255',
            //     'payment_proof' => 'required|string|max:255',
            //     'justification' => 'string|max:255',
            // ]);
    
            // if ($validator->fails()) {
            //     return response()->json($validator->errors());
            // }
    
            // $centre = Inscription::create($validator->validated());
    
            // return response()->json(['message' => 'Inscription created successfully' , 'inscription' => $centre]);

            $response = $this->gateway
                ->purchase([
                    'amount' => $request->amount,
                    'currency' => env('PAYPAL_CURRENCY'),
                    'returnUrl' => url('inscriptions/success'),
                    'cancelUrl' => url('inscriptions/error'),
                ])
                ->send();

            if ($response->isRedirect()) {
                $response->redirect();
            } else {
                return $response->getMessage();
            }
        } catch (\throwable $th) {
            return $th->getMessage();
        }
    }

    public function success(Request $request)
    {
        if ($request->input('paymentId') && $request->input('PayerID')) {
            $transaction = $this->gateway->completePurchase([
                'payer_id' => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId'),
            ]);
            $response = $transaction->send();

            if ($response->isSuccessful()) {
                $arr_body = $response->getData();
                


                DB::beginTransaction();

                try {
                    $payment = new Payment();
                    $payment->payment_id = $arr_body['id'];
                    $payment->payer_id =
                        $arr_body['payer']['payer_info']['payer_id'];
                    $payment->payer_email =
                        $arr_body['payer']['payer_info']['email'];
                    $payment->amount =
                        $arr_body['transactions'][0]['amount']['total'];
                    $payment->currency = env('PAYPAL_CURRENCY');
                    $payment->payment_status = $arr_body['state'];
                    $payment->save();


                    



                    

                    DB::commit();
                    return response(['message' => 'Payment completed successfully'],200);
                    // return view('guests.success', [
                    //     'id' => $arr_body['id'],
                    // ]);
                } catch (\Exception $e) {
                    DB::rollback();
                    throw $e;
                }

                // Clear the session
                // $request->session()->forget('payment_data');
            } else {
                return response(['message' => 'Payment failed'],400);
                // return view('guests.error');
            }
        } else {

            return response(['message' => 'Payment failed'],400);
            // return view('guests.error');
        }
    }
    public function error()
    {
        return response(['message' => 'Payment failed'],400);
        // return view('guests.decline');
    }
}
