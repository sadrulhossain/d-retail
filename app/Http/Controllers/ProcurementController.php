<?php

namespace App\Http\Controllers;

use Validator;
use App\ProductSKUCode;
use App\ProductCheckInMaster;
use App\Product;
use App\Supplier;
use App\ProcurementMaster;
use App\ProcurementDetails;
use App\WorkOrderMaster;
use App\WorkOrderDetails;
use App\CompanyInformation;
use Illuminate\Http\Request;
use DB;
use Auth;
use PDF;
use Session;
use Helper;
use Response;

class ProcurementController extends Controller {

    public function create() {
        $productSkuArr = ['0' => __('label.SELECT_PRODUCT_SKU_OPT')] + ProductSKUCode::orderBy('sku', 'asc')->pluck('sku', 'id')->toArray();
        $reqDate = date('Y-m-d');
        $procurementArr = ProcurementMaster::select(DB::raw('count(id) as total'))->where('req_date', $reqDate)->first();
        $procurementArr = $procurementArr->total + 1;
        $referenceNo = 'P-' . date('ymd', strtotime($reqDate)) . str_pad($procurementArr, 4, '0', STR_PAD_LEFT);
        return view('procurement.index')->with(compact('productSkuArr', 'reqDate', 'referenceNo'));
    }

    public function getProcurement(Request $request) {
        $target = ProductSKUCode::where('id', $request->sku)->first();
        $productInfo = Product::join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')->select('product.name as pname'
                                , 'product_unit.name as unit_name')
                        ->where('product.id', $target->product_id)->first();
        return response()->json(['productName' => $productInfo->pname, 'productUnit' => $request->rate, 'productSku' => $target->sku, 'productQuantity' => $request->quantity, 'totalPrice' => $request->totalPrice]);
    }

    public function getProcurementUnitPrice(Request $request) {

        $productDetail = Product::join('product_sku_code', 'product_sku_code.product_id', '=', 'product.id')
                ->join('brand', 'brand.id', '=', 'product.brand_id')
                ->where('product_sku_code.id', $request->sku_id)
                ->select('brand.name as brand_name', 'product.name as product_name'
                        , 'brand.id as brand_id', 'product.id as product_id', 'product_sku_code.purchase_price')
                ->first();
        if ($productDetail) {
            return response()->json(['unitPrice' => $productDetail->purchase_price]);
        }
        return response()->json(['unitPrice' => 0.00]);
    }

    public function store(Request $request) {

//        echo "<pre>";
//        print_r("ok");
//        print_r($request->all());
//        exit;
//
        $rules['sku'] = 'required|not_in:0';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $target = new ProcurementMaster;
        $target->reference = $request->reference;
        $target->req_date = !empty($request->req_date) ? Helper::dateFormatConvert($request->req_date) : null;
        $target->total = $request->total;
        $target->approval_status = '0';
        $target->approved_at = null;
        $target->approved_by = '0';

        if (!empty($request->add_btn)) {
            DB::beginTransaction();
            try {
                if ($target->save()) {
                    $data = [];
                    $i = 0;

                    if (!empty($request->sku)) {
                        foreach ($request->sku as $key => $productSku) {
                            $data[$i]['procurement_master_id'] = $target->id;
                            $data[$i]['sku_id'] = $key;
                            $data[$i]['sku'] = $productSku;
                            $data[$i]['quantity'] = $request->quantity[$key];
                            $data[$i]['unit_price'] = $request->rate[$key];
                            $data[$i]['total_price'] = $request->amount[$key];
                            $i++;
                        }
                    }
                    ProcurementDetails::insert($data);
                }

                DB::commit();
                return Response::json(['success' => true], 200);
            } catch (\Throwable $e) {
                DB::rollback();
                return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
            }
        } else {
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.YOU_HAVE_NO_NEW_PRODUCT_FOR_SAVE')], 401);
        }
    }

}
