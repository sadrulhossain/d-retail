<?php

namespace App\Http\Controllers;

use Validator;
use App\Order;
use App\OrderDetails;
use App\Customer;
use App\Product;
use Session;
use Redirect;
use Auth;
use Common;
use Input;
use Helper;
use Image;
use File;
use Response;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class OrderTrackingController extends Controller {

    public function index() {
        //passing param for custom function
        if (View::exists('frontend.orderList')) {
            if (Auth::Check() && Auth::user()->group_id == 9) {
                $orderInfo = '';
                $customerId = Customer::select('id')->where('user_id', $userId = Auth::user()->id)->first();
                if (!empty($customerId)) {
                    $orderInfo = Order::where('customer_id', $customerId->id)->get();
                }
//                echo '<pre>';
//                print_r($orderInfo->toArray());
//                exit;
                return view('frontend.orderList')->with(compact('orderInfo'));
            }
        }
        abort(404);
    }

    public function filter(Request $request) {
        $url = '&from_date=' . $request->from_date . '&to_date=' . $request->to_date;
        return Redirect::to('pendingOrder?' . $url);
    }

    public function updateStatus(Request $request) {
//        echo '<pre>';
//        print_r($request->all());
//        exit;
        $target = Order::find($request->id);

        if (!empty($target)) {
            $target->status = $request->status;
            $target->save();
            return Response::json(array('heading' => 'Success', 'message' => __('label.ORDER_CONFIRMED')), 201);
        } else {
            return Response::json(array('heading' => 'Error', 'message' => __('label.ORDER_COULD_NOT_BE_CONFIRMED')), 401);
        }
    }

}
