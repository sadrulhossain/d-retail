<?php

namespace App\Http\Controllers;

use Validator;
use App\Order;
use App\Warehouse;
use App\OrderDetails;
use App\Customer;
use App\Product;
use App\ProductSKUCode;
use App\WarehouseStore;
use App\WarehouseToSr;
use App\WarehouseToRetailer;
use App\WhToLocalWhManager;
use App\TmToWarehouse;
use App\ProductAttribute;
use Session;
use Redirect;
use Auth;
use Common;
use Input;
use Helper;
use Image;
use File;
use Response;
use DB;
use PDF;
use Illuminate\Http\Request;

class SalesStatusReportController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $whList = [];
        if (Auth::user()->group_id == 12) {
            $whList = WhToLocalWhManager::where('lwm_id', Auth::user()->id)->pluck('warehouse_id', 'warehouse_id')->toArray();
        } elseif (Auth::user()->group_id == 15) {
            $whList = TmToWarehouse::where('tm_id', Auth::user()->id)->pluck('warehouse_id', 'warehouse_id')->toArray();
        }

        //inquiry Details
        $fromDate = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) : '';
        $toDate = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) : '';
        $orderNoList = Order::whereIn('status', ['0', '5']);
        if (in_array(Auth::user()->group_id, [12, 15])) {
            $orderNoList = $orderNoList->whereIn('warehouse_id', $whList);
        }
        $orderNoList = $orderNoList->pluck('order_no', 'order_no')->toArray();

        $retailerList = WarehouseToRetailer::join('retailer', 'retailer.id', 'warehouse_to_retailer.retailer_id');

        if (in_array(Auth::user()->group_id, [12, 15])) {
            if (Auth::user()->group_id == 12) {
                $retailerList = $retailerList->join('wh_to_local_wh_manager', function($join) {
                    $join->on('wh_to_local_wh_manager.warehouse_id', '=', 'warehouse_to_retailer.warehouse_id')
                            ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id);
                });
            } elseif (Auth::user()->group_id == 15) {
                $retailerList = $retailerList->join('tm_to_warehouse', function($join) {
                    $join->on('tm_to_warehouse.warehouse_id', '=', 'warehouse_to_retailer.warehouse_id')
                            ->where('tm_to_warehouse.tm_id', Auth::user()->id);
                });
            }
        }
        $retailerList = $retailerList->orderBy('retailer.name')
                        ->pluck('retailer.name', 'retailer.id')->toArray();

        $srList = WarehouseToSr::join('users', 'users.id', 'warehouse_to_sr.sr_id');
        if (in_array(Auth::user()->group_id, [12, 15])) {
            $srList = $srList->whereIn('warehouse_to_sr.warehouse_id', $whList);
        }
        $srList = $srList->select(DB::raw("CONCAT(users.first_name,' ',users.last_name) AS user_name"), 'users.id')
                        ->pluck('user_name', 'users.id')->toArray();
        $orderNoList = ['0' => __('label.SELECT_ORDER_NO_OPT')] + $orderNoList;

        $retailerList = ['0' => __('label.SELECT_RETAILER')] + $retailerList;
        $srList = ['0' => __('label.SELECT_SR')] + $srList;

        $targetArr = Order::whereIn('order.status', ['0', '5'])
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->join('users', 'users.id', 'order.sr_id');

        if (Auth::user()->group_id == 14) {
            $targetArr = $targetArr->where('order.sr_id', Auth::user()->id);
        } elseif (!empty($request->sr_id)) {
            $targetArr = $targetArr->where('order.sr_id', $request->sr_id);
        }

        if (Auth::user()->group_id == 12) {
            $wh = WhToLocalWhManager::where('lwm_id', Auth::user()->id)->select('warehouse_id as id')->first();
            $targetArr = $targetArr->where('order.warehouse_id', $wh->id ?? 0);
        } elseif (Auth::user()->group_id == 15) {
            $whList = TmToWarehouse::where('tm_id', Auth::user()->id)->pluck('warehouse_id', 'warehouse_id')->toArray();
            $targetArr = $targetArr->whereIn('order.warehouse_id', $whList);
        }

        if ($request->generate == 'true') {
            if (!empty($fromDate)) {
                $targetArr->whereDate('order.updated_at', '>=', $fromDate);
            }
            if (!empty($toDate)) {
                $targetArr->whereDate('order.updated_at', '<=', $toDate);
            }
            if (!empty($request->order_no)) {
                $targetArr = $targetArr->where('order.order_no', $request->order_no);
            }
            if (!empty($request->retailer_id)) {
                $targetArr = $targetArr->where('order.retailer_id', $request->retailer_id);
            }

            $targetArr = $targetArr->select(DB::raw("CONCAT(users.first_name,' ',users.last_name) AS user_name"), 'users.id as user_id'
                            , 'order.id as order_id', 'order.order_no', 'order.status', 'order.grand_total'
                            , 'order.sr_id', 'order.created_at', 'retailer.name as retailer_name')
                    ->get();



            $orderArr = $orderIdArr = [];
            if (!$targetArr->isEmpty()) {
                foreach ($targetArr as $item) {
                    $orderArr[$item->order_id] = $item->toArray();
                    $orderIdArr[$item->order_id] = $item->order_id;
                }
            }

            $orderDetailArr = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                    ->join('product_sku_code', 'product_sku_code.id', 'order_details.sku_id')
                    ->leftJoin('wh_store', function($join) {
                        $join->on('wh_store.warehouse_id', '=', 'order.warehouse_id');
                        $join->on('wh_store.sku_id', '=', 'order_details.sku_id');
                    })
                    ->join('product', 'product.id', 'product_sku_code.product_id')
                    ->join('brand', 'brand.id', 'product.brand_id')
                    ->join('retailer', 'retailer.id', 'order.retailer_id')
                    ->whereIn('order.id', $orderIdArr)
                    ->select('order_details.*', 'product_sku_code.sku', 'order.status as order_status'
                            , 'wh_store.quantity as available_quantity', 'product.name as product_name'
                            , 'brand.name as brand_name', 'retailer.name as retailer_name', 'product_sku_code.attribute')
                    ->get();
            $attrList = ProductAttribute::where('status', '1')
                    ->pluck('name', 'id')
                    ->toArray();
//            
            $salesSummuryArr = $myArr = [];

            if (!$orderDetailArr->isEmpty()) {
                foreach ($orderDetailArr as $item) {
                    $attributeIdArr = !empty($item->attribute) ? explode(',', $item->attribute) : [];
                    $myArr += $item->toArray();
                    if (!empty($attributeIdArr)) {
                        foreach ($attributeIdArr as $key => $attrId) {
                            $item->product_name .= (!empty($attrList[$attrId]) ? ' ' . $attrList[$attrId] : '');
                        }
                    }
                    $orderArr[$item->order_id]['products'][$item->id] = $item->toArray();
                    if ($item->order_status == '0') {
                        $salesSummuryArr['pending']['volume'] = !empty($salesSummuryArr['pending']['volume']) ? $salesSummuryArr['pending']['volume'] : 0;
                        $salesSummuryArr['pending']['volume'] += (!empty($item->quantity) ? $item->quantity : 0);
                        $salesSummuryArr['pending']['amount'] = !empty($salesSummuryArr['pending']['amount']) ? $salesSummuryArr['pending']['amount'] : 0;
                        $salesSummuryArr['pending']['amount'] += (!empty($item->total_price) ? $item->total_price : 0);
                    } elseif ($item->order_status == '5') {
                        $salesSummuryArr['delivered']['volume'] = !empty($salesSummuryArr['delivered']['volume']) ? $salesSummuryArr['delivered']['volume'] : 0;
                        $salesSummuryArr['delivered']['volume'] += (!empty($item->quantity) ? $item->quantity : 0);
                        $salesSummuryArr['delivered']['amount'] = !empty($salesSummuryArr['delivered']['amount']) ? $salesSummuryArr['delivered']['amount'] : 0;
                        $salesSummuryArr['delivered']['amount'] += (!empty($item->total_price) ? $item->total_price : 0);
                    }
                    $salesSummuryArr['delivered']['totalVolume'] = !empty($salesSummuryArr['delivered']['totalVolume']) ? $salesSummuryArr['delivered']['totalVolume'] : 0;
                    $salesSummuryArr['delivered']['totalVolume'] += (!empty($item->quantity) ? $item->quantity : 0);
                    $salesSummuryArr['delivered']['totalAmount'] = !empty($salesSummuryArr['delivered']['totalAmount']) ? $salesSummuryArr['delivered']['totalAmount'] : 0;
                    $salesSummuryArr['delivered']['totalAmount'] += (!empty($item->total_price) ? $item->total_price : 0);
                }
            }
//            echo '<pre>';print_r($orderDetailArr);exit;
            if ($request->view == 'print') {
                if (!empty($userAccessArr[128][6])) {
                    return redirect('/dashboard');
                }
                return view('report.salesStatus.print.index')->with(compact('request', 'qpArr', 'orderArr', 'salesSummuryArr', 'orderNoList', 'retailerList', 'srList'));
            } elseif ($request->view == 'pdf') {
                if (!empty($userAccessArr[128][9])) {
                    return redirect('/dashboard');
                }
                $pdf = PDF::loadView('report.salesStatus.print.index',compact('request', 'qpArr', 'orderArr', 'salesSummuryArr', 'orderNoList', 'retailerList', 'srList'))
                        ->setPaper('a3', 'landscape')
                        ->setOptions(['defaultFont' => 'sans-serif']);
//            return $pdf->download('sales_status_report.pdf');
                return $pdf->stream();
            }


            return view('report.salesStatus.index')->with(compact('request', 'qpArr', 'orderArr', 'salesSummuryArr', 'orderNoList', 'retailerList', 'srList'));
        } else {
            return view('report.salesStatus.index')->with(compact('request', 'qpArr', 'orderNoList', 'retailerList', 'srList'));
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
        $url = 'from_date=' . $request->from_date 
                . '&to_date=' . $request->to_date
                . '&order_no=' . $request->order_no
                . '&retailer_id=' . $request->retailer_id
                . '&sr_id=' . $request->sr_id;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('admin/salesStatusReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }


        return Redirect::to('admin/salesStatusReport?generate=true&' . $url);
    }

    //get shipment detail 
    public function getShipmentDetails(Request $request) {
        $loadView = 'report.salesStatus.showShipmentDetails';
        return Common::getShipmentFullDetail($request, $loadView);
    }

    public function getShipmentDetailsPrint(Request $request) {
        $loadView = 'report.salesStatus.print.shipmentDetails';
        return Common::getShipmentFullDetail($request, $loadView);
    }

}
