<?php

namespace App\Http\Controllers;

use Validator;
use Session;
use Redirect;
use App\Product;
use App\Supplier;
use App\ProductSKUCode;
use App\ProductCheckInDetails;
use App\ProductCheckInMaster;
use App\CompanyInformation;
use Helper;
use DB;
use Auth;
use Common;
use PDF;
use Input;
use Illuminate\Http\Request;

class SupplierSummaryReportController extends Controller {

    //Stock Summary Report
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

        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }
        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[108][6])) {
                return redirect('dashboard');
            }
            return view('report.supplierSummary.print.index')->with(compact('request', 'targetArr', 'totalAmount'
                                    , 'supplierList', 'konitaInfo', 'phoneNumber', 'fromDate', 'toDate'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[108][9])) {
                return redirect('/dashboard');
            }
            $pdf = PDF::loadView('report.supplierSummary.print.index', compact('request', 'targetArr', 'totalAmount'
                                    , 'supplierList', 'konitaInfo', 'phoneNumber', 'fromDate', 'toDate'))
                    ->setPaper('a3', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('stock_summary_report.pdf');
//            return $pdf->stream();
        } else {
            return view('report.supplierSummary.index')->with(compact('request', 'targetArr', 'totalAmount', 'supplierList'
                                    , 'konitaInfo', 'phoneNumber', 'fromDate', 'toDate'));
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
            return redirect('admin/supplierSummaryReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }


        return Redirect::to('admin/supplierSummaryReport?generate=true&' . $url);
    }

}
