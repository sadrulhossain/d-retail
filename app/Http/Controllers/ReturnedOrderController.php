<?php

namespace App\Http\Controllers;

use Validator;
use App\Order;
use App\OrderDetails;
use App\Customer;
use App\Product;
use App\ProductSKUCode;
use App\ProductAttribute;
use App\WhToLocalWhManager;
use App\WarehouseToSr;
use App\WarehouseToRetailer;
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
use Illuminate\Http\Request;

class ReturnedOrderController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $whList = [];
        if (Auth::user()->group_id == 12) {
            $whList = WhToLocalWhManager::where('lwm_id', Auth::user()->id)->pluck('warehouse_id', 'warehouse_id')->toArray();
        } elseif (Auth::user()->group_id == 15) {
            $whList = TmToWarehouse::where('tm_id', Auth::user()->id)->pluck('warehouse_id', 'warehouse_id')->toArray();
        }

        $fromDate = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) : '';
        $toDate = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) : '';
        $orderNoList = Order::whereIn('status', ['4']);

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

        //inquiry Details
        $targetArr = Order::where('order.status', '4')
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->join('users', 'users.id', 'order.sr_id');

        if (Auth::user()->group_id == 12) {
            $wh = WhToLocalWhManager::where('lwm_id', Auth::user()->id)->select('warehouse_id as id')->first();
            $targetArr = $targetArr->where('order.warehouse_id', $wh->id ?? 0);
        } elseif (Auth::user()->group_id == 15) {
            $whList = TmToWarehouse::where('tm_id', Auth::user()->id)->pluck('warehouse_id', 'warehouse_id')->toArray();
            $targetArr = $targetArr->whereIn('order.warehouse_id', $whList);
        }
        if (!empty($fromDate)) {
            $targetArr->whereDate('order.created_at', '>=', $fromDate);
        }
        if (!empty($toDate)) {
            $targetArr->whereDate('order.created_at', '<=', $toDate);
        }
        if (!empty($request->order_no)) {
            $targetArr = $targetArr->where('order.order_no', $request->order_no);
        }
        if (!empty($request->retailer_id)) {
            $targetArr = $targetArr->where('order.retailer_id', $request->retailer_id);
        }
        if (!empty($request->sr_id)) {
            $targetArr = $targetArr->where('order.sr_id', $request->sr_id);
        }

        $targetArr = $targetArr->select(DB::raw("CONCAT(users.first_name,' ',users.last_name) AS user_name")
                        , 'order.id as order_id', 'order.grand_total', 'order.order_no', 'order.status'
                        , 'order.created_at', 'retailer.name as retailer_name')
                ->paginate(Session::get('paginatorCount'));



        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/returnedOrder?page=' . $page);
        }


        $orderArr = $orderIdArr = [];
        if (!$targetArr->isEmpty()) {
            foreach ($targetArr as $item) {
                $orderArr[$item->order_id] = $item->toArray();
                $orderIdArr[$item->order_id] = $item->order_id;
            }
        }

        $orderDetailArr = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('product_sku_code', 'product_sku_code.product_id', 'product.id')
                ->join('brand', 'brand.id', 'product.brand_id')
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->whereIn('order.id', $orderIdArr)
                ->select('order_details.*', 'product_sku_code.sku', 'product_sku_code.available_quantity'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'retailer.name as retailer_name', 'product_sku_code.attribute')
                ->get();

        $attrList = ProductAttribute::where('status', '1')
                ->pluck('name', 'id')
                ->toArray();

        if (!$orderDetailArr->isEmpty()) {
            foreach ($orderDetailArr as $item) {
                $attributeIdArr = !empty($item->attribute) ? explode(',', $item->attribute) : [];

                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $item->product_name .= (!empty($attrList[$attrId]) ? ' ' . $attrList[$attrId] : '');
                    }
                }

                $orderArr[$item->order_id]['products'][$item->id] = $item->toArray();
            }
        }

//        echo '<pre>';
//        print_r($orderArr);
//        exit;
        return view('returnedOrder.index')->with(compact('request', 'qpArr', 'targetArr', 'orderArr', 'orderNoList', 'retailerList', 'srList'));
    }

    public function filter(Request $request) {
        $url = 'from_date=' . $request->from_date . '&to_date=' . $request->to_date
                . '&order_no=' . urlencode($request->order_no)
                . '&retailer_id=' . $request->retailer_id . '&sr_id=' . $request->sr_id;
        return Redirect::to('admin/returnedOrder?' . $url);
    }

}
