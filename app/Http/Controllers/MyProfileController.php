<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Customer;
use Common;
use Session;
use Redirect;
use Auth;
use File;
use Response;
use Image;
use Helper;
use Cart;
use DB;
use Illuminate\Http\Request;

class MyProfileController extends Controller {
    
    
    public function index() {
        $targetArr = User::select('users.first_name','users.last_name', 'users.username', 'users.photo'
                        , 'users.email', 'users.phone', 'users.checkin_source')
                ->where('users.id', '=', Auth::user()->id)
                ->first();
        return view('frontend.myProfile')->with(compact('targetArr'));
    }

    public function editMyProfile(Request $request) {
        $targetArr = User::select('users.first_name','users.last_name', 'users.email', 'users.phone')
                ->where('users.id', '=', Auth::user()->id)
                ->first();


        $view = view('frontend.showEditProfile', compact('targetArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function updateProfile(Request $request) {
        $logoName = $successMsg = $errMsg = '';
        $rules = $message = [];
        if ($request->stat == '2') {
            if (empty($request->email)) {
                return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => __('label.THE_EMAIL_FIELD_IS_EMPTY')), 401);
            }
            if (empty($request->phone)) {
                return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => __('label.THE_MOBILE_FIELD_IS_EMPTY')), 401);
            }
        } elseif ($request->stat == '1') {
            if (!empty($request->logo)) {
                $rules['logo'] = 'required|max:1024|mimes:jpeg,png,jpg';
            }
            $validator = Validator::make($request->all(), $rules, $message);
            if ($validator->fails()) {
                return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
            }
            $file = $request->file('logo');
            if (!empty($file)) {
                $logoName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
                $file->move('public/frontend/assets/images/userImg', $logoName);
            }
        }


        $target = $newTarget = [];
        if ($request->stat == '2') {
            $target['username'] = $request->phone;
            $target['email'] = $request->email;
            $target['phone'] = $request->phone;

            $newTarget['name'] = $request->name;
            $newTarget['username'] = $request->phone;
            $newTarget['email'] = $request->email;
            $newTarget['phone'] = $request->phone;

            $successMsg = __('label.PROFILE_UPDATED_SUCCESSFULLY');
            $errMsg = __('label.PROFILE_UPDATED_FAILED');
        } elseif ($request->stat == '1') {
            $target['photo'] = !empty($logoName) ? $logoName : '';

            $successMsg = __('label.PROFILE_PHOTO_UPDATED_SUCCESSFULLY');
            $errMsg = __('label.PROFILE_PHOTO_UPDATED_FAILED');
        }
        DB::beginTransaction();
        try {
            if ($request->stat == '2') {
                Customer::where('user_id', Auth::user()->id)->update($newTarget);
            }

            User::where('id', Auth::user()->id)->update($target);
            
            DB::commit();
            return Response::json(array('heading' => 'Success', 'message' => $successMsg), 201);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(array('success' => false, 'message' => $errMsg), 401);
        }
    }

}
