<?php

namespace App\Http\Controllers;

use Validator;
use App\Wishlist; //model class
use App\Customer;
use App\Product;
use App\Retailer;
use App\ProductSKUCode;
use App\CompanyInformation;
use App\ProductAttribute;
use App\SrToRetailer;
use App\WarehouseToSr;
use App\WarehouseToRetailer;
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

class CartController extends Controller {

    public function index() {
        $retailerList = ['0' => __('label.SELECT_RETAILER')] + SrToRetailer::join('retailer', 'retailer.id', 'sr_to_retailer.retailer_id')
                        ->where('sr_to_retailer.sr_id', Auth::user()->id)
                        ->pluck('retailer.name', 'retailer.id')->toArray();

        if (in_array(Auth::user()->group_id, [18, 19])) {
            $warehouseInfo = WarehouseToRetailer::select('warehouse_id')->where('retailer_id', Auth::user()->retailer->id)->first();
        } else {
            $warehouseInfo = WarehouseToSr::select('warehouse_id')->where('sr_id', Auth::user()->id)->first();
        }

        $content = Cart::content();
        $companyInfo = CompanyInformation::first();

        if (in_array(Auth::user()->group_id, [18, 19])) {
            if (!empty(Auth::user()->retailer->type)) {

                foreach ($content as $key => $cartValue) {
                    $productSku = ProductSKUCode::find($cartValue->id);
                    if (Auth::user()->retailer->type == '1') {
                        $price = $productSku->selling_price;
                        Cart::update($cartValue->rowId, ['price' => $price]);
                    } elseif (Auth::user()->retailer->type == '2') {
                        $price = $productSku->distributor_price;
                        Cart::update($cartValue->rowId, ['price' => $price]);
                    }
                }
            }
        }

        $view = view('frontend.layouts.default.cartBar', compact('content', 'companyInfo', 'retailerList', 'warehouseInfo'))->render();
        return response()->json(['html' => $view]);
    }

    public function addToCart(Request $request, $id) {


        if (Auth::Check()) {
            if (!in_array(Auth::user()->group_id, [14, 18, 19])) {
                return Response::json(array('success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.YOU_ARE_NOT_ELIGIBLE_TO_MAKE_ORDER')), 401);
            } else {
                if (in_array(Auth::user()->group_id, [18, 19])) {
                    if (!Auth::user()->retailer->warehouseToRetailer) {
                        return Response::json(array('success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.YOU_ARE_NOT_ASSIGN_TO_WAREHOUSE')), 401);
                    }
                    if (!Auth::user()->retailer->sr) {
                        return Response::json(array('success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.NO_SR_IS_ASSIGN_FOR_YOU')), 401);
                    }
                }
            }
        }



        $qty = $request->qty;
        $retailerList = ['0' => __('label.SELECT_RETAILER')] + SrToRetailer::join('retailer', 'retailer.id', 'sr_to_retailer.retailer_id')
                        ->where('sr_to_retailer.sr_id', Auth::user()->id)
                        ->pluck('retailer.name', 'retailer.id')->toArray();
        if (in_array(Auth::user()->group_id, [18, 19])) {
            $warehouseInfo = WarehouseToRetailer::select('warehouse_id')->where('retailer_id', Auth::user()->retailer->id)->first();
        } else {
            $warehouseInfo = WarehouseToSr::select('warehouse_id')->where('sr_id', Auth::user()->id)->first();
        }


//        if (Auth::Check()) {
        $target = Product::leftJoin('product_image', 'product_image.product_id', 'product.id')
                ->join('product_sku_code', 'product_sku_code.product_id', 'product.id')
                ->join('wh_store', 'wh_store.sku_id', 'product_sku_code.id')
                ->join('product_unit', 'product_unit.id', 'product.product_unit_id')
                ->where('product.id', $id)
                ->where('product_sku_code.sku', $request->sku_code)
                ->select('product.id as productId', 'product.name as productName', 'product_image.image as productImage'
                        , 'product_sku_code.distributor_price as distributor_price', 'product_sku_code.selling_price as price', 'product_sku_code.id as skuId'
                        , 'product_sku_code.attribute', 'product_unit.name as unit_name', 'wh_store.quantity as available_qty')
                ->first();

        $attrList = ProductAttribute::where('status', '1')
                ->pluck('name', 'id')
                ->toArray();

        $data = [];
        if (!empty($target)) {
            $target->productImage = json_decode($target->productImage, true);

            $attributeIdArr = !empty($target->attribute) ? explode(',', $target->attribute) : [];

            $target->productAttribute = '';
            if (!empty($attributeIdArr)) {
                foreach ($attributeIdArr as $key => $attrId) {
                    $target->productAttribute .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                }
            }

            $producName = $target->productName . ' ' . $target->productAttribute;

            $data['id'] = $target->skuId;
            $data['name'] = $producName;
            $data['qty'] = isset($qty) ? $qty : 1;
            $data['weight'] = 1;
            $data['price'] = Auth::user()->group_id == 18 ? $target->distributor_price : $target->price;
            $data['options']['unit'] = $target->unit_name;
            $data['options']['image'] = $target->productImage[0];
            Cart::add($data);
            $cartCount = view('frontend.cartCount')->render();
            $content = Cart::content();
            $companyInfo = CompanyInformation::first();

            $view = view('frontend.layouts.default.cartBar', compact('content', 'companyInfo', 'retailerList', 'warehouseInfo'))->render();

            return response()->json(['cartBar' => $view, 'cartCount' => $cartCount]);
        }
//        } else {
//            return Response::json(array('success' => false, 'message' => __('label.PLEASE_LOG_IN_FIRST')), 401);
//        }
    }

    public function removeCart(Request $request, $rowId) {
        $retailerList = ['0' => __('label.SELECT_RETAILER')] + SrToRetailer::join('retailer', 'retailer.id', 'sr_to_retailer.retailer_id')
                        ->where('sr_to_retailer.sr_id', Auth::user()->id)
                        ->pluck('retailer.name', 'retailer.id')->toArray();

        if (in_array(Auth::user()->group_id, [18, 19])) {
            $warehouseInfo = WarehouseToRetailer::select('warehouse_id')->where('retailer_id', Auth::user()->retailer->id)->first();
        } else {
            $warehouseInfo = WarehouseToSr::select('warehouse_id')->where('sr_id', Auth::user()->id)->first();
        }
        Cart::remove($rowId);
        $cartCount = view('frontend.cartCount')->render();
        $content = Cart::content();
        $companyInfo = CompanyInformation::first();

        $view = view('frontend.layouts.default.cartBar', compact('content', 'companyInfo', 'warehouseInfo', 'retailerList'))->render();

        return response()->json(['cartBar' => $view, 'cartCount' => $cartCount]);
    }

    public function updateCart(Request $request) {

        Cart::update($request->key, $request->qty);
        $retailerList = ['0' => __('label.SELECT_RETAILER')] + SrToRetailer::join('retailer', 'retailer.id', 'sr_to_retailer.retailer_id')
                        ->where('sr_to_retailer.sr_id', Auth::user()->id)
                        ->pluck('retailer.name', 'retailer.id')->toArray();

        if (in_array(Auth::user()->group_id, [18, 19])) {
            $warehouseInfo = WarehouseToRetailer::select('warehouse_id')->where('retailer_id', Auth::user()->retailer->id)->first();
        } else {
            $warehouseInfo = WarehouseToSr::select('warehouse_id')->where('sr_id', Auth::user()->id)->first();
        }
        $subTotal = Cart::subtotal();
        $vat = Cart::tax();
        $total = Cart::total();
        $cartCount = view('frontend.cartCount')->render();
        $content = Cart::content();
        $companyInfo = CompanyInformation::first();
//        print_r(json_encode($content)); exit;
        $view = view('frontend.layouts.default.cartBar', compact('content', 'companyInfo', 'warehouseInfo', 'retailerList'))->render();

        return response()->json(['cartBar' => $view, 'cartCount' => $cartCount, 'subTotal' => $subTotal
                    , 'vat' => $vat, 'total' => $total]);
    }

    public function updateSrCart(Request $request) {

        $retailer_id = $request->retailer_id;
        $retailerData = Retailer::where('id', $request->retailer_id)->where('approval_status', '1')->first();
        $retailer = $retailerData->type ?? '1';
        $retailerList = ['0' => __('label.SELECT_RETAILER')] + SrToRetailer::join('retailer', 'retailer.id', 'sr_to_retailer.retailer_id')
                        ->where('sr_to_retailer.sr_id', Auth::user()->id)
                        ->pluck('retailer.name', 'retailer.id')->toArray();

        if (in_array(Auth::user()->group_id, [18, 19])) {
            $warehouseInfo = WarehouseToRetailer::select('warehouse_id')->where('retailer_id', Auth::user()->retailer->id)->first();
        } else {
            $warehouseInfo = WarehouseToSr::select('warehouse_id')->where('sr_id', Auth::user()->id)->first();
        }
        $content = Cart::content();
        $companyInfo = CompanyInformation::first();

        foreach ($content as $key => $cartValue) {
            $productSku = ProductSKUCode::find($cartValue->id);
            if ($retailer == '1') {
                $price = $productSku->selling_price;
                Cart::update($cartValue->rowId, ['price' => $price]);
            } elseif ($retailer == '2') {
                $price = $productSku->distributor_price;
                Cart::update($cartValue->rowId, ['price' => $price]);
            }
        }
        $subTotal = Cart::subtotal();
        $vat = Cart::tax();
        $total = Cart::total();
        $cartCount = view('frontend.cartCount')->render();
        $content = Cart::content();
        $companyInfo = CompanyInformation::first();
//        print_r(json_encode($content)); exit;
        $view = view('frontend.layouts.default.cartBar', compact('content', 'companyInfo', 'warehouseInfo', 'retailerList', 'retailer_id'))->render();
        return response()->json(['cartBar' => $view, 'cartCount' => $cartCount, 'subTotal' => $subTotal
                    , 'vat' => $vat, 'total' => $total]);
    }

    public function clearCart() {
        Cart::destroy();
        $retailerList = ['0' => __('label.SELECT_RETAILER')] + SrToRetailer::join('retailer', 'retailer.id', 'sr_to_retailer.retailer_id')
                        ->where('sr_to_retailer.sr_id', Auth::user()->id)
                        ->pluck('retailer.name', 'retailer.id')->toArray();

        $warehouseInfo = WarehouseToSr::select('warehouse_id')->where('sr_id', Auth::user()->id)->first();
        $cartCount = view('frontend.cartCount')->render();
        $content = Cart::content();
        $companyInfo = CompanyInformation::first();

        $view = view('frontend.layouts.default.cartBar', compact('content', 'companyInfo', 'warehouseInfo', 'retailerList'))->render();

        return response()->json(['cartBar' => $view, 'cartCount' => $cartCount]);
    }

}
