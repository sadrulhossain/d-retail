<?php

namespace App\Http\Controllers;

use Validator;
use App\Brand;
use App\Product;
use App\ProductToBrand;
use Response;
use Auth;
use Helper;
use DB;
use Redirect;
use Session;
use Illuminate\Http\Request;

class ProductToBrandController extends Controller {

    public function index(Request $request) {
        $productList = Product::where('competitors_product', '0');
        $productList = $productList->where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productArr = ['0' => __('label.SELECT_PRODUCT_OPT')] + $productList;
        $brandArr = $brandRelateToProduct = [];
        $inactiveBrandArr = [];
        if (!empty($request->get('product_id'))) {
            //get all product list
            $brandArr = Brand::select('brand.id', 'brand.name', 'brand.logo')
                            ->orderBy('name', 'asc')->get()->toArray();

            $inactiveBrandArr = Brand::where('status', '2')->pluck('id')->toArray();

            $relatedBrandArr = ProductToBrand::select('product_to_brand.brand_id')
                    ->where('product_to_brand.product_id', $request->get('product_id'))
                    ->get();

            if (!$relatedBrandArr->isEmpty()) {
                foreach ($relatedBrandArr as $relatedBrand) {
                    $brandRelateToProduct[$relatedBrand->brand_id] = $relatedBrand->brand_id;
                }
            }
        }

        return view('productToBrand.index')->with(compact('productArr', 'brandArr', 'brandRelateToProduct', 'request', 'inactiveBrandArr'));
    }

    public function getBrandsToRelate(Request $request) {

        $brandArr = $brandRelateToProduct = [];
        $brandArr = Brand::select('brand.id', 'brand.name', 'brand.logo')
                        ->orderBy('name', 'asc')->get();

        $inactiveBrandArr = Brand::where('status', '2')->pluck('id')->toArray();

        $relatedBrandArr = ProductToBrand::select('product_to_brand.brand_id')
                ->where('product_to_brand.product_id', $request->product_id)
                ->get();
        if (!$relatedBrandArr->isEmpty()) {
            foreach ($relatedBrandArr as $relatedBrand) {
                $brandRelateToProduct[$relatedBrand->brand_id] = $relatedBrand->brand_id;
            }
        }
        $view = view('productToBrand.showBrands', compact('brandArr', 'request', 'brandRelateToProduct', 'inactiveBrandArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getRelatedBrands(Request $request) {
        // Set Name of Selected Supplier
        $product = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                        ->select('product.name', 'product.product_code as code', 'product_category.name as category_name')
                        ->where('product.id', $request->product_id)->first();
        $relatedBrandArr = ProductToBrand::select('product_to_brand.brand_id')
                ->where('product_to_brand.product_id', $request->product_id)
                ->get();
        // Make array selected Product of related Brand's  
        $brandRelateToProduct = [];
        if (!$relatedBrandArr->isEmpty()) {
            foreach ($relatedBrandArr as $relatedBrand) {
                $brandRelateToProduct[$relatedBrand->brand_id] = $relatedBrand->brand_id;
            }
        }
        // Get Details of Related Brand
        $brandArr = [];
        if (isset($brandRelateToProduct)) {
            $brandArr = Brand::whereIn('brand.id', $brandRelateToProduct)
                            ->select('brand.name', 'brand.logo', 'brand.id')
                            ->where('status', '1')
                            ->orderBy('brand.name', 'asc')->get()->toArray();
        }
        $inactiveBrandArr = Brand::where('status', '2')->pluck('id')->toArray();
        $view = view('productToBrand.showRelatedBrands', compact('brandArr'
                        , 'relatedBrandArr', 'brandRelateToProduct', 'request', 'product'
                        , 'inactiveBrandArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function relateProductToBrand(Request $request) {
        $rules = [
            'product_id' => 'required|not_in:0',
            'brand' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $i = 0;
        $target = [];
        if (!empty($request->brand)) {
            foreach ($request->brand as $brandId) {
                //data entry to product pricing table
                $target[$i]['product_id'] = $request->product_id;
                $target[$i]['brand_id'] = $brandId;
                $target[$i]['created_by'] = Auth::user()->id;
                $target[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

        //delete before inserted 
        ProductToBrand::where('product_id', $request->product_id)->delete();

        if (ProductToBrand::insert($target)) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_HAS_BEEN_RELATED_TO_BRAND_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_RELATE_PRODUCT_TO_BRAND')), 401);
        }
    }

}
