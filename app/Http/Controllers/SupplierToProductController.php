<?php

namespace App\Http\Controllers;

use Validator;
use App\Supplier;
use App\Product;
use App\SupplierToProduct;
use Response;
use Auth;
use Helper;
use DB;
use Redirect;
use Session;
use Illuminate\Http\Request;

class SupplierToProductController extends Controller {

    public function index(Request $request) {
        $supplierList = Supplier::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $supplierArr = ['0' => __('label.SELECT_SUPPLIER_OPT')] + $supplierList;
        $productArr = $productRelateToSupplier = [];
        $inactiveProductArr = [];
        $productWiseSupplierArr = [];

        if (!empty($request->supplier_id)) {
            //get all product list
            $productArr = Product::select('product.id', 'product.name')
                            ->orderBy('name', 'asc')->get()->toArray();
            
            $productWiseSupplierInfo = SupplierToProduct::join('supplier', 'supplier.id', '=', 'supplier_to_product.supplier_id')
                    ->select('supplier_to_product.product_id', 'supplier_to_product.supplier_id')
                    ->get();

            if (!$productWiseSupplierInfo->isEmpty()) {
                foreach ($productWiseSupplierInfo as $info) {
                    $productWiseSupplierArr[$info->product_id][$info->supplier_id] = $info->name;
                }
            }
            $inactiveProductArr = Product::where('status', '2')->pluck('id')->toArray();
            $relatedProductArr = SupplierToProduct::select('supplier_to_product.product_id')
                    ->where('supplier_to_product.supplier_id', $request->supplier_id)
                    ->get();
            if (!$relatedProductArr->isEmpty()) {
                foreach ($relatedProductArr as $relatedProduct) {
                    $productRelateToSupplier[$relatedProduct->product_id] = $relatedProduct->product_id;
                }
            }
        }
        

        return view('supplierToProduct.index')->with(compact('supplierArr', 'productArr', 'productRelateToSupplier', 'request', 'inactiveProductArr', 'productWiseSupplierArr'));
    }

    public function getProductsToRelate(Request $request) {

        $productArr = $productRelateToSupplier = [];
        $productArr = Product::select('product.id', 'product.name')
                        ->orderBy('name', 'asc')->get();

        $productWiseSupplierInfo = SupplierToProduct::join('supplier', 'supplier.id', '=', 'supplier_to_product.supplier_id')
                ->select('supplier_to_product.product_id', 'supplier_to_product.supplier_id', 'supplier.name')
                ->get();

        $productWiseSupplierArr = [];
        if (!$productWiseSupplierInfo->isEmpty()) {
            foreach ($productWiseSupplierInfo as $info) {
                $productWiseSupplierArr[$info->product_id][$info->supplier_id] = $info->name;
            }
        }

//        echo '<pre>';
//            print_r($productWiseSupplierArr);
//            exit();
        $inactiveProductArr = Product::where('status', '2')->pluck('id')->toArray();
        $relatedProductArr = SupplierToProduct::select('supplier_to_product.product_id')
                ->where('supplier_to_product.supplier_id', $request->supplier_id)
                ->get();

        if (!$relatedProductArr->isEmpty()) {
            foreach ($relatedProductArr as $relatedProduct) {
                $productRelateToSupplier[$relatedProduct->product_id] = $relatedProduct->product_id;
            }
        }

        $view = view('supplierToProduct.showProducts', compact('productArr', 'productRelateToSupplier', 'request', 'inactiveProductArr'
                        , 'productWiseSupplierArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getRelatedProducts(Request $request) {
        // Set Name of Selected Supplier
        $supplier = Supplier::select('supplier.name')
                        ->where('supplier.id', $request->supplier_id)->first();
        $relatedProductArr = SupplierToProduct::select('supplier_to_product.product_id')
                ->where('supplier_to_product.supplier_id', $request->supplier_id)
                ->get();
        // Make array selected Product of related Brand's  
        $productRelateToSupplier = [];
        if (!$relatedProductArr->isEmpty()) {
            foreach ($relatedProductArr as $relatedProduct) {
                $productRelateToSupplier[$relatedProduct->product_id] = $relatedProduct->product_id;
            }
        }
        // Get Details of Related Brand
        $productArr = [];
        if (isset($productRelateToSupplier)) {
            $productArr = Product::whereIn('product.id', $productRelateToSupplier)
                            ->select('product.name', 'product.id')
                            ->where('status', '1')
                            ->orderBy('product.name', 'asc')->get()->toArray();
        }
        $inactiveProductArr = Product::where('status', '2')->pluck('id')->toArray();
        $view = view('supplierToProduct.showRelatedProducts', compact('productArr'
                        , 'relatedProductArr', 'productRelateToSupplier', 'request', 'supplier'
                        , 'inactiveProductArr'))->render();
        return response()->json(['html' => $view]);
    }
    
    public function getRelatedSuppliers(Request $request) {
        // Set Name of Selected Supplier
        $product = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                        ->select('product.name', 'product_category.name as category_name')
                        ->where('product.id', $request->product_id)->first();
        $productWiseSupplierInfo = SupplierToProduct::join('supplier', 'supplier.id', '=', 'supplier_to_product.supplier_id')
                ->select('supplier_to_product.supplier_id', 'supplier.name','supplier.code','supplier.address')
                ->where('supplier_to_product.product_id',$request->product_id)
                ->get();
        $inactiveSupplierArr = Supplier::where('status', '2')->pluck('id')->toArray();
//                echo '<pre>';
//        print_r($productWiseSupplierInfo->toArray());
//        exit();
        $view = view('supplierToProduct.showRelatedSuppliers', compact('product', 'productWiseSupplierInfo','inactiveSupplierArr'))->render();
        return response()->json(['html' => $view]);
    }
    
    
    public function relateSupplierToProduct(Request $request) {
        $rules = [
            'supplier_id' => 'required|not_in:0',
            'product' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $i = 0;
        $target = [];
        if (!empty($request->product)) {
            foreach ($request->product as $productId) {
                //data entry to product pricing table
                $target[$i]['supplier_id'] = $request->supplier_id;
                $target[$i]['product_id'] = $productId;
                $target[$i]['created_by'] = Auth::user()->id;
                $target[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

        //delete before inserted 
        SupplierToProduct::where('supplier_id', $request->supplier_id)->delete();

        if (SupplierToProduct::insert($target)) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_HAS_BEEN_RELATED_TO_BRAND_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_RELATE_PRODUCT_TO_BRAND')), 401);
        }
    }

}
