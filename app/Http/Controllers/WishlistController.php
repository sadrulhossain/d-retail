<?php

namespace App\Http\Controllers;

use Validator;
use App\Wishlist; //model class
use App\Customer;
use App\Product;
use App\ProductAttribute;
use App\User;
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

class WishlistController extends Controller {

    public function index() {

        $target = [];
        if (Auth::Check()) {
            $customerId = Customer::select('id')->where('user_id', $userId = Auth::user()->id)->first();
            $target = DB::table('wishlist')
                            ->join('product_sku_code', 'product_sku_code.id', '=', 'wishlist.sku_id')
                            ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                            ->leftJoin('brand', 'brand.id', 'product.brand_id')
                            ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
                            ->where('wishlist.customer_id', $customerId->id)
                            ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage'
                                    , 'product_sku_code.selling_price as price', 'product_sku_code.sku', 'product_sku_code.attribute'
                                    , 'wishlist.id as wishItemId', 'product_sku_code.id as sku_id')
                            ->get()->toArray();
        }

        $attrList = ProductAttribute::where('status', '1')
                ->pluck('name', 'id')
                ->toArray();
        if (!empty($target)) {
            foreach ($target as $data) {
                $data->productImage = json_decode($data->productImage, true);

                $attributeIdArr = !empty($data->attribute) ? explode(',', $data->attribute) : [];
                $data->productAttribute = '';

                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $data->productAttribute .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                    }
                }
            }
        }

//        echo '<pre>';
//        print_r($target);
//        exit;
        return view('frontend.wishlist')->with(compact('target'));
    }

    public function add($id) {
        if (Auth::Check()) {
            $customerId = Customer::select('id')->where('user_id', $userId = Auth::user()->id)->first();
            $check = Wishlist::where('sku_id', $id)->where('customer_id', $customerId->id)->first();
            if (!empty($check)) {
                return Response::json(array('success' => false, 'message' => __('label.ALREADY_EXISTS_IN_YOUR_WISHLIST')), 401);
            } else {
                $target = new Wishlist;
                $target->sku_id = $id;
                $target->customer_id = $customerId->id;
                if ($target->save()) {
                    $wishItemCount = DB::table('wishlist')->where('customer_id', $customerId->id)->count();
                    $wishlistCount = view('frontend.wishlistCount', compact('wishItemCount'))->render();
                    return response()->json(['wishlistCount' => $wishlistCount]);
                } else {
                    return Response::json(array('success' => false, 'message' => __('label.PRODUCT_COULD_NOT_BE_ADDED_TO_WISHLIST')), 401);
                }
            }
        } else {
            return Response::json(array('success' => false, 'message' => __('label.PLEASE_LOG_IN_FIRST')), 401);
        }
    }

    public function remove($id) {
        $target = Wishlist::find($id);
        if (empty($target)) {
            return Response::json(array('success' => false, 'message' => __('label.INVALID_DATA_ID')), 401);
        }
        //END OF Dependency
        if ($target->delete()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_REMOVED_FROM_WISHLIST')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.PRODUCT_COULD_NOT_BE_REMOVED_FROM_WISHLIST')), 401);
        }
    }

}
