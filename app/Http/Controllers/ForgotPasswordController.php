<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use URL;
use App\User;
use Hash;
use Mail;
use Response;

class ForgotPasswordController extends Controller {

    public function __construct() {
        
    }

    public function requestForgotPassword() {
        return view('frontend.forgotPassword');
    }

    public function forgotPassword(Request $request) {
//echo '<pre>';
//print_r($request->all());
//exit;
        if (empty($request->email)) {
            return Response::json(array('success' => false, 'message' => 'Invalid Email Address!'), 401);
        }

        //Search for Email
        $target = User::where('email', $request->email)->first();

        if (empty($target)) {
            return Response::json(array('success' => false, 'message' => 'Unknown Email Address!'), 401);
        }

        //Set recovery link
        $recoveryLink = md5($target->id . 's3cretH@sh' . date('ymdhis'));
        User::where('id', $target->id)->update(array('recovery_attempt' => date('Y-m-d H:i:s'), 'recovery_link' => $recoveryLink));

        //Send Email
        $url = URL::to('/recoverPassword/' . $recoveryLink);

        $eContent = "Dear " . $target->first_name . ' ' . $target->last_name . "<br /><br />"
                . "Please, click the following link to reset your password.<br />"
                . "<a href=\"$url\" target=\"_blank\">Reset Password</a><br /><br />"
                . "If you are not aware of this email, or think you have got this email by mistake, please, ignore it.<br /><br />"
                . "Thanks<br />"
                . "Safe Care";

        $data['eContent'] = $eContent;

        $subject = 'Safe Care Password Recovery';

        Mail::send('frontend.emails.forget_password', $data, function($message) use($target, $subject) {
            $message->to($target->email, $target->first_name)->subject($subject);
        });

        return Response::json(array('success' => true, 'message' => 'A Recovery Link has been sent to your email.'), 200);
    }

    public function recoverPassword($ref = null) {


        //validate request
        $target = User::where('recovery_link', $ref)->first();

        if (empty($target)) {
            return view('errors.403');
        }


        return view('frontend.recoverPassword')->with(compact('ref'));
        ;
    }

    public function resetPassword(Request $request) {
//        echo '<pre>';
//        print_r($request->all());
//        exit;
        if (empty($request->ref)) {
            return Response::json(array('success' => false, 'message' => 'Invalid Request!'), 401);
        }


        $target = User::where('recovery_link', $request->ref)->first();


        if (!preg_match('/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[`~!?@#$%^&*()\-_=+{}|;:,<.>])(?=\S*[\d])\S*$/', $request->password)) {
            return Response::json(array('success' => false, 'message' => __('label.WEAK_PASSWORD_FOLLOW_PASSWORD_INSTRUCTION')), 401);
        }



        if ($request->password != $request->conf_password) {

            return Response::json(array('success' => false, 'message' => 'Password confirmation doesn\'t match!'), 401);
        } else {

            User::where('id', $target->id)->update(array('password' => Hash::make($request->password), 'recovery_attempt' => null, 'recovery_link' => null));

            return Response::json(array('success' => true, 'message' => 'Password has been updated! You will be redirected to login page.'), 200);
        }
    }

}
