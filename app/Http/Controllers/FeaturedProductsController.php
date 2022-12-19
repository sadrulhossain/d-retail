<?php

namespace App\Http\Controllers;

use Validator;
use App\Customer;
use App\Product;
use App\ProductOffer;
use App\ProductSKUCode;
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

class FeaturedProductsController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        //inquiry Details
        $targetArr = ProductSKUCode::join('product', 'product.id', 'product_sku_code.product_id')
                ->join('brand', 'brand.id', 'product.brand_id');


        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('product.name', 'LIKE', '%' . $searchText . '%');
            });
        }

        $targetArr = $targetArr->select('product_sku_code.id', 'product_sku_code.sku', 'product.id as product_id', 'product.name as product_name'
                        , 'brand.name as brand_name')
                ->get();


        $prevData = ProductOffer::where('category', 1)->select('sku_data')->first();
        $prevSku = [];
        if (!empty($prevData)) {
            $prevSku = json_decode($prevData->sku_data, true);
        }
//        echo '<pre>';
//        print_r($prevSku);
//        exit;


        return view('featuredProducts.index')->with(compact('request', 'qpArr', 'targetArr', 'prevSku'));
    }

    public function saveProducts(Request $request) {


        $skuArr = $request->sku;

        if (empty($skuArr)) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => __('label.PLEASE_SELECT_ATLEAST_ONE_PRODUCT')), 401);
        }

        $data = [];
        if (!empty($skuArr)) {
            foreach ($skuArr as $key => $skuId) {
                $data[$key]['sku_id'] = $skuId;
            }
        }


//        echo '<pre>';
//        print_r(json_encode($data));
//        exit;

        ProductOffer::where('category', 1)
                ->delete();


        $target = new ProductOffer;
        $target->category = 1;
        $target->sku_data = json_encode($data);
        $target->updated_by = Auth::user()->id;
        $target->updated_at = date('Y-m-d H:i:s');

        if ($target->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.SKU_ASSIGNED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.SKU_COULD_NOT_BE_ASSIGNED')], 401);
        }
    }

    public function getSelectedSKU(Request $request) {
        
        $prevData = ProductOffer::where('category', 1)->select('sku_data')->first();
        $prevSku = [];
        if (!empty($prevData)) {
            $prevSku = json_decode($prevData->sku_data, true);
        }

        $targetArr = ProductSKUCode::join('product', 'product.id', 'product_sku_code.product_id')
                ->join('brand', 'brand.id', 'product.brand_id')
                ->whereIn('product_sku_code.id', $prevSku)
                ->select('product_sku_code.id', 'product_sku_code.sku', 'product.id as product_id', 'product.name as product_name'
                        , 'brand.name as brand_name')
                ->get();

//            echo '<pre>';            print_r($targetArr);exit;


        $view = view('featuredProducts.showSelectedSKU', compact('targetArr'))->render();
        return response()->json(['html' => $view]);
    }

}
