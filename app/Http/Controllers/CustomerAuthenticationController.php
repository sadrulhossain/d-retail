<?php

namespace App\Http\Controllers;

use App\Product;
use App\Brand;
use App\ProductImage;
use App\ProductSKUCode;
use App\Customer;
use App\User;
use App\Retailer;
use Validator;
use Common;
use Auth;
use Session;
use Redirect;
use Helper;
use Response;
use DB;
use Hash;
use URL;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;

class CustomerAuthenticationController extends Controller {

    private $controller = 'CustomerAuthentication';

    use AuthenticatesUsers;

    public function __construct() {
        Validator::extend('complexPassword', function ($attribute, $value, $parameters) {
            $password = $parameters[1];

            if (preg_match('/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[!@#$%^&*()])(?=\S*[\d])\S*$/', $password)) {
                return true;
            }
            return false;
        });
    }

    public function registerCustomer(Request $request) {
        //        return $request;
        $rules = $message = array();
        $rules = [
            'name' => 'required|unique:retailer',
            'code' => 'required|unique:retailer',
            'address' => 'required',
            'order' => 'required|not_in:0',
            'type' => 'required|not_in:0',
            //            'cluster_id' => 'required|not_in:0',
            //            'zone_id' => 'required|not_in:0',
            'username' => 'required|unique:users|alpha_num',
            'password' => 'required|complex_password:,' . $request->password,
            'conf_password' => 'required|same:password'
        ];

        $message = array(
            'password.complex_password' => __('label.WEAK_PASSWORD_FOLLOW_PASSWORD_INSTRUCTION'),
            'username.unique' => __('label.USERNAME_ALREADY_EXISTS'),
        );
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
            // return redirect()->route($request->this_route)->withInput()->withErrors($validator);
        }

        if ($request->otpCode != $request->sentOtp) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => "Invalid Otp Code"), 400);
        }

        $newTarget = new User;
        $newTarget->username = $request->username;
        $newTarget->email = $request->email;
        $newTarget->phone = $request->phone;
        $newTarget->first_name = $request->name;
        $newTarget->nick_name = $request->code;
        $newTarget->checkin_source = 1;
        $newTarget->status = '2';   // 1=Active, 2=Inactive
        $newTarget->group_id = $request->type == '1' ? 19 : 18; // type: 1 = Distributor, 2 = Retailer
        $password = Hash::make($request->password);
        $newTarget->password = $password;

        DB::beginTransaction();
        try {
            if ($newTarget->save()) {
                $target = new Retailer;
                $target->user_id = $newTarget->id;
                $target->name = $request->name;
                $target->code = $request->code;
                $target->email = $request->email;
                $target->phone = $request->phone;
                $target->type = $request->type ?? '1';
                $target->cluster_id = $request->cluster_id ?? 0;
                $target->zone_id = $request->zone_id ?? 0;
                $target->division = $request->division ?? 0;
                $target->district = $request->district ?? 0;
                $target->thana = $request->thana ?? 0;
                $target->longitude = $request->longitude ?? '';
                $target->latitude = $request->latitude ?? '';
                $target->address = $request->address;
                $target->username = $request->username;
                $target->owner_name = $request->owner_name;
                $target->nid_passport = $request->nid_passport;
                $target->order = $request->order;
                $target->status = '1';
                $target->approval_status = '0'; // 0=Pending, 1= Approved
                $target->by_rtl_dist = '1'; // 0= No, 1=Yes
                $target->password = $password;
                $target->created_by = 0;
                $target->updated_by = 0;
                $target->save();
            }
            DB::commit();
            Session::flash('success', __('label.ACCOUNT_CREATED_SUCCESSFULLY_PLEASE_WAIT_72_HOURS_FOR_APPROVAL'));
            return Response::json(array('heading' => 'Success', 'message' => __('label.ACCOUNT_CREATED_SUCCESSFULLY')), 201);

            // return redirect('/login');
            //            $credentials = $request->only('username', 'password');
            //
            //            if (Auth::attempt($credentials)) {
            //                Session::flash('success', __('label.SUCCESSFULLY_LOGGED_IN'));
            //                if (!empty($request->go_to_payment)) {
            //                    return redirect('/checkout');
            //                } else {
            //                    return redirect('/');
            //                }
            //            } else {
            //                Session::flash('error', __('label.THESE_CREDENTIALS_DO_NOT_MATCH_OUR_RECORDS'));
            //                return redirect('/login');
            //            }
        } catch (\Throwable $e) {
            DB::rollback();
            Session::flash('success', __('label.RETAILER_COULD_NOT_BE_CREATED'));
            return Response::json(array('heading' => 'Error', 'message' => __('label.RETAILER_COULD_NOT_BE_CREATED')), 401);
            // return back();
        }
    }

    public function authenticateCustomer(Request $request) {
        $credentials = $request->only('username', 'password');
        $credentials['group_id'] = [14, 18, 19];
        $credentials['status'] = '1';

//        $retailer = Retailer::with('warehouseToRetailer', 'sr')->where('username', $request->username)->first();
//        if (!empty($retailer)) {
//            if (!$retailer->warehouseToRetailer || !$retailer->sr) {
//                Session::flash('error', __('label.THESE_CREDENTIALS_DO_NOT_MATCH_OUR_RECORDS'));
//                return back();
//            }
//        }
//        if (!$retailer->warehouseToRetailer) {
//            Session::flash('error', __('label.NO_WAREHOUSE_IS_ASSIGN_TO_YOU'));
//            return back();
//        }
//        if (!$retailer->sr) {
//            Session::flash('error', __('label.NO_SR_IS_ASSIGN_TO_YOU'));
//            return back();
//        }
        
        if (Auth::attempt($credentials)) {
            Session::flash('success', __('label.SUCCESSFULLY_LOGGED_IN'));
            if (!empty($request->go_to_payment)) {
                return redirect('/checkout');
            } else {
                return redirect('/');
            }
        } else {
            Session::flash('error', __('label.THESE_CREDENTIALS_DO_NOT_MATCH_OUR_RECORDS'));
            if (!empty($request->go_to_payment)) {
                return redirect('/loginAndRegister');
            } else {
                return redirect('/login');
            }
        }
    }

    public function logoutCustomer() {
        Auth::logout();
        return redirect('/');
    }

    public function googleLogin(Request $request) {
        $googleId = $request->google_id;
        $email = $request->email;
        $name = $request->full_name;
        $photo = $request->photo;
        $userName = strtok($email, '@');

        $findUser = User::where('google_id', $googleId)->first();
        if (!empty($findUser)) {
            Auth::login($findUser);
            $message = __('label.SUCCESSFULLY_LOGGED_IN');
            return response()->json(['success' => true, 'message' => $message], 200);
        } else {
            $newTarget = new User;
            $newTarget->username = $userName;
            $newTarget->email = $email;
            $newTarget->photo = $photo;
            $newTarget->google_id = $googleId;
            $newTarget->checkin_source = '2';
            $newTarget->status = '1';
            $newTarget->group_id = 9;
            DB::beginTransaction();
            try {
                if ($newTarget->save()) {

                    $target = new Customer;
                    $target->name = $name;
                    $target->username = $userName;
                    $target->email = $email;
                    $target->user_id = $newTarget->id;
                    $target->status = '1';
                    $target->save();
                }
                DB::commit();
                $message = __('label.CUSTOMER_CREATED_SUCCESSFULLY');

                Auth::login($newTarget);
                return response()->json(['success' => true, 'message' => $message], 200);
            } catch (\Throwable $e) {
                DB::rollback();
                $message = __('label.CUSTOMER_COULD_NOT_BE_CREATED');
                return response()->json(['success' => false, 'message' => $message, 'route' => '/'], 401);
            }
        }
    }

    public function facebookLogin(Request $request) {
        $fbId = $request->fb_id;
        $email = !empty($request->email) ? $request->email : "";
        $name = $request->full_name;
        $photo = $request->photo;
        $userName = strtok($email, '@');

        $findUser = User::where('facebook_id', $fbId)->first();
        if (!empty($findUser)) {
            Auth::login($findUser);
            $message = __('label.SUCCESSFULLY_LOGGED_IN');
            return response()->json(['success' => true, 'message' => $message], 200);
        } else {
            $newTarget = new User;
            $newTarget->username = $userName;
            $newTarget->email = $email;
            $newTarget->photo = $photo;
            $newTarget->facebook_id = $fbId;
            $newTarget->checkin_source = '3';
            $newTarget->status = '1';
            $newTarget->group_id = 9;
            DB::beginTransaction();
            try {
                if ($newTarget->save()) {

                    $target = new Customer;
                    $target->name = $name;
                    $target->username = $userName;
                    $target->email = $email;
                    $target->user_id = $newTarget->id;
                    $target->status = '1';
                    $target->save();
                }
                DB::commit();
                $message = __('label.CUSTOMER_CREATED_SUCCESSFULLY');

                Auth::login($newTarget);
                return response()->json(['success' => true, 'message' => $message], 200);
            } catch (\Throwable $e) {
                DB::rollback();
                $message = __('label.CUSTOMER_COULD_NOT_BE_CREATED');
                return response()->json(['success' => false, 'message' => $message, 'route' => '/'], 401);
            }
        }
    }

    public function showVerifyNumber(Request $request) {
        //showVerifyNumber showVerifyNumber
        $phone = "88" . $request->phone;
        $SixDigitRandomNumber = rand(100000, 999999);
        $responseArr = json_decode($this->message($phone, $SixDigitRandomNumber), true);
        $html = view('frontend.showVerifyNumber', compact('SixDigitRandomNumber', 'phone', 'responseArr'))->render();
        return Response::json(["html" => $html]);
    }

    private function message($phone, $SixDigitRandomNumber) {

        $ch = curl_init();

        $route['sms_route'] = "http://103.230.63.50/bulksms/api/";
        $route['channel_username'] = env('CHANNEL_USERNAME');
        $route['channel_password'] = env('CHANNEL_PASSWORD');
        $mobileNo = $phone;

        $contentType = 1;

        $apiUrl = $route['sms_route'];
        $digit12 = '26' . date('YmdHis');

        $requesteid = $digit12;

        $message = "Your One-Time Password (OTP) for Arroz Transaction is {$SixDigitRandomNumber} Validity for OTP is 5 minutes.";

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "authUser=" . $route['channel_username'] . "&authAccess=" . $route['channel_password'] . "&destination=" . $mobileNo . "&text=" . urlencode($message) . "&requestId=" . $requesteid . " &contentType=" . $contentType);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);

        return $server_output;
    }

}
