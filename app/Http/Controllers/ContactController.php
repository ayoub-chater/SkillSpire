<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactController extends Controller
{
    public function sendMail(Request $request){

        $mail = new PHPMailer(true);
   
        try {
   
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');
            $mail->Port = env('MAIL_PORT');
   
            
            $mail->addAddress('anassbarik03@gmail.com', 'Anass Barik');
            $mail->addReplyTo($request->email,$request->name);
   
            $mail->isHTML(false);
            $mail->Subject = 'Demande d\'info';
            $mail->Body    = $request->message;

        
   
            if( !$mail->send() ) {
                return response()->json(['message' => 'Message could not be sent.'],400);
            }
              
            else {
                return response()->json(['message' => 'Message sent successfully.'],200);
            }
   
        } catch (Exception $e) {
             return back()->with('message','Message could not be sent.');
        }
    
    }
}
