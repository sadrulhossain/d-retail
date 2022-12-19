<?php
namespace App\Http\Controllers;
use Validator;
use App\Supplier;
use App\Product;
use App\SupplierToProduct;
use App\ProductSKUCode;
use Response;
use Auth;
use Helper;
use DB;
use Redirect;
use Session;
use Common;
use Illuminate\Http\Request;

class ProductSKUCodeController extends Controller {

    public function index(Request $request) {
        $productList = Product::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productArr = ['0' => __('label.SELECT_PRODUCT_OPT')] + $productList;
        return view('productSKUCode.index')->with(compact('productArr'));
    }
    
    public function getCategoryBrand(Request $request){
       
        $product = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                          ->join('brand', 'brand.id', '=', 'product.brand_id')
                          ->select('product_category.name as category_name', 'brand.name as brand_name', 'product.product_code as code')
                          ->where('product.id', $request->product_id)->first();
        
        $productSKU = ProductSKUCode::select('id','sku_code')->where('product_id',$request->product_id)->get();
        
        $view = view('productSKUCode.showCategoryBrand', compact('product','productSKU'))->render();
        return response()->json(['html' => $view]);
    }
    
    public function getAssignedSKUCodes(Request $request) {
        // Set Name of Selected Supplier
        $product = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                        ->select('product.name', 'product.product_code as code', 'product_category.name as category_name')
                        ->where('product.id', $request->product_id)->first();
        $productSKU = ProductSKUCode::select('id','sku_code')->where('product_id',$request->product_id)->get();
        
        $view = view('productSKUCode.showAssignedSKUCodes', compact('product','productSKU'))->render();
        return response()->json(['html' => $view]);
    }
    
    
    public function relateProductToSKUCode(Request $request) {
        
        $rules = [
            'product_id' => 'required|not_in:0',
            'sku_code' => 'required|unique:product_sku_code',
        ];
        
//        echo '<pre>';
//        print_r($request);
//        exit();

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $target = new ProductSKUCode;
        $target->product_id = $request->product_id;
        $target->sku_code = $request->sku_code;
        
        if ($target->save()) {
            return Response::json(['success' => true], 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.SKU_COULD_NOT_BE_CREATED')), 401);
        }
    }


}
