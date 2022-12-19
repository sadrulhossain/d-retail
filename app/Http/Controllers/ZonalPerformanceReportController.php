<?php

namespace App\Http\Controllers;

use Validator;
use Session;
use Redirect;
use App\Warehouse;
use App\WhToLocalWhManager;
use App\TmToWarehouse;
use App\Product;
use App\ProductSKUCode;
use App\ProductCheckInDetails;
use App\ProductCheckInMaster;
use App\ProductAdjustmentMaster;
use App\ProductAdjustmentDetails;
use App\ProductReturn;
use App\ProductReturnDetails;
use App\ProductWhReturn;
use App\ProductWhReturnDetails;
use App\ProductTransferMaster;
use App\ProductTransferDetails;
use App\CompanyInformation;
use App\Zone;
use App\Order;
use Helper;
use DB;
use Auth;
use Common;
use PDF;
use Input;
use Illuminate\Http\Request;

class ZonalPerformanceReportController extends Controller {

    //Stock Summary Report
    public function index(Request $request) {


        $fromDate = $toDate = '';

        $targetArr = [];
        $zoneList = [];

        if ($request->generate == 'true') {
            $fromDate = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) : '';
            $toDate = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) : '';

            //current checkin
            $zoneList = Zone::orderBy('order', 'asc')->where('status', 1)->pluck('name', 'id')->toArray();

            $orderInfo = Order::join('retailer', 'retailer.id', '=', 'order.retailer_id')
                            ->where('order.status', '!=', '8')
                            ->whereBetween('order.created_at', [$fromDate, $toDate])
                            ->select('order.id', 'retailer.zone_id as zone_id', 'order.grand_total', 'order.status')
                            ->orderBy('id', 'desc')->get();

            if (!empty($orderInfo)) {
                foreach ($orderInfo as $info) {

                    $targetArr[$info->zone_id]['no_of_order'] = !empty($targetArr[$info->zone_id]['no_of_order']) ? $targetArr[$info->zone_id]['no_of_order'] : 0;
                    $targetArr[$info->zone_id]['no_of_order'] += 1;

                    $targetArr[$info->zone_id]['sales_volume'] = !empty($targetArr[$info->zone_id]['sales_volume']) ? $targetArr[$info->zone_id]['sales_volume'] : 0;
                    $targetArr[$info->zone_id]['sales_volume'] += $info->grand_total;

                    if ($info->status == 5) {
                        $targetArr[$info->zone_id]['delivered_volume'] = !empty($targetArr[$info->zone_id]['delivered_volume']) ? $targetArr[$info->zone_id]['delivered_volume'] : 0;
                        $targetArr[$info->zone_id]['delivered_volume'] += $info->grand_total;
                    }
                    if ($info->status == 0) {
                        $targetArr[$info->zone_id]['pending_volume'] = !empty($targetArr[$info->zone_id]['pending_volume']) ? $targetArr[$info->zone_id]['pending_volume'] : 0;
                        $targetArr[$info->zone_id]['pending_volume'] += $info->grand_total;
                    }
                }
            }
        }
        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[139][6])) {
                return redirect('dashboard');
            }
            return view('report.zonalPerformance.print.index')->with(compact('request', 'targetArr', 'fromDate', 'toDate', 'zoneList'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[139][9])) {
                return redirect('/dashboard');
            }
            $pdf = PDF::loadView('report.zonalPerformance.print.index', compact('request', 'targetArr', 'fromDate', 'toDate', 'zoneList'))
                    ->setPaper('a3', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('zone_performance' . '-' . date('YmdHis') . '.pdf');
//            return $pdf->stream();
        } else {
            return view('report.zonalPerformance.index')->with(compact('request', 'targetArr', 'fromDate', 'toDate', 'zoneList'));
        }
    }

    public function filter(Request $request) {

        $messages = [];
        $rules = [
            'from_date' => 'required',
            'to_date' => 'required',
        ];

        $messages = [
            'from_date.required' => __('label.THE_FROM_DATE_FIELD_IS_REQUIRED'),
            'to_date.required' => __('label.THE_TO_DATE_FIELD_IS_REQUIRED'),
        ];
        $url = 'from_date=' . $request->from_date . '&to_date=' . $request->to_date;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('admin/zonalPerformanceReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }
        return Redirect::to('admin/zonalPerformanceReport?generate=true&' . $url);
    }

}
