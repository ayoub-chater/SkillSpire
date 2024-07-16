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
        try {
            
            // $validator = Validator::make($request->all(), [
            //     'user_id' => 'required|exists:users,id',
            //     'formation_id' => 'required|exists:formations,id',
            // ]);
    
            // if ($validator->fails()) {
            //     return response()->json($validator->errors());
            // }
    
            // Inscription::create($request->all());
    
            // return response()->json(['message' => 'Inscription created successfully' , 'inscription' => $centre]);

            $data = $request->all();
            $inscriptions = [];
            foreach($data['inscriptions'] as $item){
                
                $inscriptions[] = [
                    'user_id' => $item['user_id'],
                    'formation_id' => $item['formation']['id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $request->session()->put('user_id',$item['user_id']);
            }

            DB::table('inscriptions')->insert($inscriptions);

            $response = $this->gateway
                ->purchase([
                    'amount' => $request->amount,
                    'currency' => env('PAYPAL_CURRENCY'),
                    'returnUrl' => url('/api/success'),
                    'cancelUrl' => url('/api/error'),
                ])
                ->send();

            if ($response->isRedirect()) {
                return response()->json(['redirectUrl' => $response->getRedirectUrl() ,'data' => $request->all()]);

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
                    $payment->status = $arr_body['state'];
                    $payment->save();

                    $user_id = $request->session()->get('user_id');
                    
                    $inscriptions = Inscription::where('user_id', $user_id)
                    ->where('status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                    foreach($inscriptions as $inscription){
                        $inscription->status = "paid";
                        $inscription->save();

                    }


                    
                    DB::commit();
                    $success = 'Payment completed successfully.';
                    // $id= $inscription->formation_id;
                    return redirect()->to("http://localhost:5173/success?message=$success");
                    // return response(['success' => 'Payment completed successfully'],200);

                } catch (\Exception $e) {
                    DB::rollback();
                    throw $e;
                }


            } else {
                    $user_id = $request->session()->get('user_id');
                                        
                    $inscriptions = Inscription::where('user_id', $user_id)
                    ->where('status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                    foreach($inscriptions as $inscription){
                        $inscription->status = "canceled";
                        $inscription->save();

                    }

                    $error = 'Payment Failed.';

                    return redirect()->to("http://localhost:5173/cart?error=$error");
            }
        } else {
                    $user_id = $request->session()->get('user_id');
            
                    $inscriptions = Inscription::where('user_id', $user_id)
                    ->where('status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                    foreach($inscriptions as $inscription){
                        $inscription->status = "canceled";
                        $inscription->save();

                    }

            $error = 'Payment Failed.';

            return redirect()->to("http://localhost:5173/cart?error=$error");
        }
    }
    public function error(Request $request)
    {
        $user_id = $request->session()->get('user_id');
                    
        $inscriptions = Inscription::where('user_id', $user_id)
                    ->where('status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                    foreach($inscriptions as $inscription){
                        $inscription->status = "canceled";
                        $inscription->save();

                    }

        $error = 'Payment Failed.';
       
        return redirect()->to("http://localhost:5173/cart?error=$error");
    }
}
