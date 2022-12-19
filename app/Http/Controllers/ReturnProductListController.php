<?php

namespace App\Http\Controllers;

use App\ProductCheckInMaster;
use App\ProductCheckInDetails;
use App\ReturnProductMaster;
use App\ReturnProductDetails;
use App\ProductSKUCode;
use App\Product;
use App\Supplier;
use Validator;
use Response;
use Helper;
use DB;
use Session;
use Redirect;
use Auth;
use Illuminate\Http\Request;

class ReturnProductListController extends Controller {

    public function index(Request $request) {
        $purchaseReferenceList = ProductCheckInMaster::pluck('ref_no', 'id')->toArray();
        $supplierList = Supplier::pluck('name', 'id')->toArray();
        $refNoArr = ReturnProductMaster::select('reference_no as ref_no')->orderBy('id', 'asc')->get();

        $targetArr = ReturnProductMaster::join('users', 'users.id', '=', 'return_product_master.created_by')
                ->join('product_checkin_master', 'product_checkin_master.id', '=', 'return_product_master.purchase_reference_id')
                ->join('supplier', 'supplier.id', '=', 'return_product_master.supplier_id');

        if (!empty($request->ref_no)) {
            $targetArr = $targetArr->where('return_product_master.reference_no', 'LIKE', '%' . $request->ref_no . '%');
        }
        $targetArr = $targetArr->select('return_product_master.*', 'product_checkin_master.ref_no as purchase_ref_no'
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as returned_by"), 'supplier.name as supplier')
                ->orderBy('return_product_master.return_date', 'desc')
                ->paginate(Session::get('paginatorCount'));

//        echo "<pre>";
//        print_r($targetArr);
//        exit;

        return view('returnProduct.returnProductList', compact('targetArr', 'purchaseReferenceList', 'supplierList', 'refNoArr'));
    }

    public function getReturnProductModal(Request $request) {
        $return = ReturnProductMaster::join('users', 'users.id', '=', 'return_product_master.created_by')
                ->join('product_checkin_master', 'product_checkin_master.id', '=', 'return_product_master.purchase_reference_id')
                ->join('supplier', 'supplier.id', '=', 'return_product_master.supplier_id')
                ->where('return_product_master.id', $request->return_id)
                ->select('return_product_master.*', 'product_checkin_master.ref_no as purchase_ref_no'
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as returened_by")
                        , 'supplier.name as supplier')
                ->first();
        $returnProductInfo = ReturnProductDetails::join('product_sku_code', 'product_sku_code.id', 'return_product_details.sku_id')
                        ->where('return_product_details.return_product_master_id', $request->return_id)
                        ->select('return_product_details.*', 'product_sku_code.sku')
                        ->get()->toArray();

//        echo "<pre>";
//        print_r($returnProductInfo);
//        exit;

        $view = view('returnProduct.returnProductDetails', compact('returnProductInfo', 'return'))->render();
        return response()->json(['html' => $view]);
    }

    public function filter(Request $request) {
        $url = 'ref_no=' . $request->ref_no;
        return Redirect::to('admin/returnProductList?' . $url);
    }

}
