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
use App\Cluster;
use App\Order;
use Helper;
use DB;
use Auth;
use Common;
use PDF;
use Input;
use Illuminate\Http\Request;

class ClusterPerformanceReportController extends Controller {

    //Stock Summary Report
    public function index(Request $request) {


        $fromDate = $toDate = '';

        $targetArr = [];
        $clusterList=[];

        if ($request->generate == 'true') {
            $fromDate = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) : '';
            $toDate = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) : '';

            //current checkin
            $clusterList = Cluster::orderBy('order', 'asc')->where('status', 1)->pluck('name','id')->toArray();

            $orderInfo = Order::join('retailer', 'retailer.id', '=', 'order.retailer_id')
                            ->where('order.status', '!=', '8')
                            ->whereBetween('order.created_at', [$fromDate, $toDate])
                            ->select('order.id', 'retailer.cluster_id as cluster_id', 'order.grand_total', 'order.status')
                            ->orderBy('id', 'desc')->get();

            if (!empty($orderInfo)) {
                foreach ($orderInfo as $info) {

                    $targetArr[$info->cluster_id]['no_of_order'] = !empty($targetArr[$info->cluster_id]['no_of_order']) ? $targetArr[$info->cluster_id]['no_of_order'] : 0;
                    $targetArr[$info->cluster_id]['no_of_order'] += 1;

                    $targetArr[$info->cluster_id]['sales_volume'] = !empty($targetArr[$info->cluster_id]['sales_volume']) ? $targetArr[$info->cluster_id]['sales_volume'] : 0;
                    $targetArr[$info->cluster_id]['sales_volume'] += $info->grand_total;

                    if ($info->status == 5) {
                        $targetArr[$info->cluster_id]['delivered_volume'] = !empty($targetArr[$info->cluster_id]['delivered_volume']) ? $targetArr[$info->cluster_id]['delivered_volume'] : 0;
                        $targetArr[$info->cluster_id]['delivered_volume'] += $info->grand_total;
                    }
                    if ($info->status == 0) {
                        $targetArr[$info->cluster_id]['pending_volume'] = !empty($targetArr[$info->cluster_id]['pending_volume']) ? $targetArr[$info->cluster_id]['pending_volume'] : 0;
                        $targetArr[$info->cluster_id]['pending_volume'] += $info->grand_total;
                    }
                }
            }

//            return $targetArr;
        }
        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[138][6])) {
                return redirect('dashboard');
            }
            return view('report.clusterPerformance.print.index')->with(compact('request','targetArr','fromDate', 'toDate','clusterList'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[138][9])) {
                return redirect('/dashboard');
            }
            $pdf = PDF::loadView('report.clusterPerformance.print.index', compact('request','targetArr','fromDate', 'toDate','clusterList'))
                    ->setPaper('a3', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('cluster_performance' . '-' . date('YmdHis') . '.pdf');
//            return $pdf->stream();
        } else {
            return view('report.clusterPerformance.index')->with(compact('request','targetArr','fromDate', 'toDate','clusterList'));
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
            return redirect('admin/clusterPerformanceReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }


        return Redirect::to('admin/clusterPerformanceReport?generate=true&' . $url);
    }

}
