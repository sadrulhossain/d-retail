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

class ReturnProductController extends Controller {

    public function create(Request $request) {
        $purchaseReferenceList = ['0' => __('label.SELECT_PURCHASE_REF')] + ProductCheckInMaster::pluck('ref_no', 'id')->toArray();
        $returnDate = date('Y-m-d');
        $returnProductArr = ReturnProductMaster::select(DB::raw('count(id) as total'))->where('return_date', $returnDate)->first();
        $returnProductArr = $returnProductArr->total + 1;
        $referenceNo = 'RS-' . date('ymd', strtotime($returnDate)) . str_pad($returnProductArr, 4, '0', STR_PAD_LEFT);

        return view('returnProduct.index', compact('purchaseReferenceList', 'referenceNo'));
    }

    public function getProduct(Request $request) {


        $supplier = Supplier::join('product_checkin_details', 'product_checkin_details.supplier_id', 'supplier.id')
                        ->where('product_checkin_details.master_id', $request->purchase_reference_id)->first();

        $productArr = ProductCheckInDetails::join('product_sku_code', 'product_sku_code.id', 'product_checkin_details.sku_id')
                        ->where('product_checkin_details.master_id', $request->purchase_reference_id)
                        ->select('product_checkin_details.*', 'product_sku_code.sku as name', 'product_checkin_details.quantity as purchase_quantity'
                                , 'product_sku_code.available_quantity as available_quantity', 'product_sku_code.id as sku_id')
                        ->get()->toArray();

//        echo "<pre>";
//        print_r($productArr);
//        exit;


        $view = view('returnProduct.showProductTable', compact('productArr', 'supplier'))->render();
        return response()->json(['html' => $view]);
    }

    public function store(Request $request) {
//        
//        echo "<pre>";
//        print_r($request->all());
//        exit;

        $rules = [
            'purchase_reference_id' => 'required|not_in:0',
            'supplier_id' => 'required|not_in:0',
            'return_date' => 'required',
            'ref_no' => 'required|unique:return_product_master,reference_no',
        ];

        $messages = [
            'return_date.required' => 'Return Date is required.',
        ];

        $productArr = $request->product;
        $validator = Validator::make($request->all(), $rules, $messages);

        if (empty($productArr)) {
            return Response::json(array('success' => false, 'heading' => _('label.VALIDATION_ERROR'), 'message' => __('label.PLEASE_RETURN_AT_LEAST_ONE_PRODUCT')), 401);
        }
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
        }


        $row = 1;
        foreach ($request->return_quantity as $key => $quantity) {
            if (empty($quantity)) {
                return Response::json(array('success' => false, 'heading' => _('label.VALIDATION_ERROR'), 'message' => 'Return quantity is required at row: ' . $row), 401);
            }
            ++$row;
        }
        
        $target = new ReturnProductMaster;
        $target->reference_no = $request->ref_no;
        $target->purchase_reference_id = $request->purchase_reference_id;
        $target->return_date = !empty($request->return_date) ? Helper::dateFormatConvert($request->return_date) : null;
        $target->supplier_id = $request->supplier_id;
        $target->created_at = date('Y-m-d H:i:s');
        $target->created_by = Auth::user()->id;

        DB::beginTransaction();

        try {
            if ($target->save()) {
                $data = [];
                $i = 1;
                if (!empty($request->product)) {
                    foreach ($request->product as $skuId => $skuId) {
                        $data[$i]['return_product_master_id'] = $target->id;
                        $data[$i]['sku_id'] = $skuId;
                        $data[$i]['purchase_quantity'] = $request->purchase_quantity[$skuId];
                        $data[$i]['available_quantity'] = $request->remaining_quantity[$skuId];
                        $data[$i]['return_quantity'] = $request->return_quantity[$skuId];
                        $i++;
                    }
                    ReturnProductDetails::insert($data);
                    foreach ($request->product as $skuId => $skuId) {
                        ProductSKUCode::where('id', $skuId)->decrement('available_quantity', $request->return_quantity[$skuId]);
                    }
                }
            }
            DB::commit();
            return Response::json(array('heading' => 'Success', 'message' => __('label.RETURN_PRODUCT_SAVED_SUCCESSFULLY')), 200);
        } catch (Exception $ex) {
            DB::rollback();
            return Response::json(array('success' => false, 'message' => __('label.RETURN_PRODUCT_COULD_NOT_BE_CREATED')), 401);
        }
    }

}
