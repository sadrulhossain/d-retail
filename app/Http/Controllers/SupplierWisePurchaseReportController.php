<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\ProductCheckInMaster;
use App\ProductCheckInDetails;
use App\ProductAttribute;
use App\WhToLocalWhManager;
use App\TmToWarehouse;
use App\SupplierToProduct;
use App\WarehouseToRetailer;
use App\WarehouseToSr;
use Illuminate\Support\Facades\Auth;
use PDF;
use Common;
use Helper;

class SupplierWisePurchaseReportController extends Controller {

    private $controller = 'SupplierWisePurchaseReport';

    public function index(Request $request) {
        $supplierList = ['0' => __('label.SELECT_SUPPLIER_OPT')] + ProductCheckInDetails::join('supplier', 'supplier.id', 'product_checkin_details.supplier_id')
                        ->pluck('supplier.name', 'supplier.id')->toArray();

        $fromDate = $toDate = '';
        $totalAmount = 0;
        $targetArr = [];
        if ($request->generate == 'true') {
            $fromDate = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) : '';
            $toDate = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) : '';

            $targetArr = ProductCheckInDetails::join('product_checkin_master', 'product_checkin_master.id', 'product_checkin_details.master_id')
                    ->join('product', 'product.id', '=', 'product_checkin_details.product_id')
                    ->join('product_sku_code', 'product_sku_code.id', '=', 'product_checkin_details.sku_id')
                    ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                    ->join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                    ->where('product_checkin_details.supplier_id', $request->supplier_id)
                    ->whereBetween('product_checkin_master.checkin_date', [$fromDate, $toDate])
                    ->select('product.name as product', 'brand.name as brand', 'product_sku_code.sku as sku_code'
                            , 'product_checkin_master.checkin_date as date', 'product_checkin_master.ref_no'
                            , 'product_checkin_master.challan_no', 'product_checkin_details.quantity'
                            , 'product_checkin_details.rate', 'product_checkin_details.amount'
                            , 'product_unit.name as unit')
                    ->orderBy('product_checkin_master.checkin_date', 'desc')
                    ->get();

            if (!$targetArr->isEmpty()) {
                foreach ($targetArr as $info) {
                    $totalAmount += (!empty($info->amount) ? $info->amount : 0);
                }
            }
        }

        
        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[130][6])) {
                return redirect('dashboard');
            }
            return view('report.supplierWisePurchase.print.index')->with(compact('request', 'targetArr', 'totalAmount'
                                    , 'supplierList', 'fromDate', 'toDate'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[130][9])) {
                return redirect('/dashboard');
            }
            $pdf = PDF::loadView('report.supplierWisePurchase.print.index', compact('request', 'targetArr', 'totalAmount'
                                    , 'supplierList', 'fromDate', 'toDate'))
                    ->setPaper('a3', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('stock_summary_report.pdf');
//            return $pdf->stream();
        } else {
            return view('report.supplierWisePurchase.index')->with(compact('request', 'targetArr', 'totalAmount', 'supplierList'
                                    , 'fromDate', 'toDate'));
        }
    }

    public function filter(Request $request) {
        $messages = [];
        $rules = [
            'supplier_id' => 'required|not_in:0',
            'from_date' => 'required',
            'to_date' => 'required',
        ];

        $messages = [
            'from_date.required' => __('label.THE_FROM_DATE_FIELD_IS_REQUIRED'),
            'to_date.required' => __('label.THE_TO_DATE_FIELD_IS_REQUIRED'),
        ];
        $url = 'supplier_id=' . $request->supplier_id . '&from_date=' . $request->from_date . '&to_date=' . $request->to_date;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('admin/supplierWisePurchaseReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }


        return Redirect::to('admin/supplierWisePurchaseReport?generate=true&' . $url);
    }

}
