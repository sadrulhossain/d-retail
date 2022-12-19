<?php

namespace App\Http\Controllers;

use Validator;
use App\Order;
use App\OrderDetails;
use App\CourierService;
use App\Branch;
use App\SetCourier;
use App\Delivery;
use App\DeliveryDetails;
use App\Customer;
use App\Invoice;
use App\Product;
use App\ProductSKUCode;
use App\ProductReturn;
use App\ProductReturnDetails;
use App\CompanyInformation;
use App\WhToLocalWhManager;
use App\WarehouseToSr;
use App\ProductAttribute;
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
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeliveredOrderController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        //inquiry Details

        $whList = [];
        if (Auth::user()->group_id == 12) {
            $whList = WhToLocalWhManager::where('lwm_id', Auth::user()->id)->pluck('warehouse_id', 'warehouse_id')->toArray();
        } elseif (Auth::user()->group_id == 15) {
            $whList = TmToWarehouse::where('tm_id', Auth::user()->id)->pluck('warehouse_id', 'warehouse_id')->toArray();
        }

        $fromDate = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) : '';
        $toDate = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) : '';
        $orderNoList = Order::whereIn('status', ['5']);

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

//        $targetArr = Order::whereIn('order.status', ['5']);

        $targetArr = Order::whereIn('order.status', ['5'])
                ->join('users', 'users.id', 'order.sr_id')
                ->leftJoin('invoice', 'invoice.order_id', 'order.id')
                ->join('retailer', 'retailer.id', 'order.retailer_id');

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
                        , 'order.id as order_id', 'order.status', 'order.created_at'
                        , 'retailer.name as retailer_name', 'invoice.id as invoice_id'
                        , 'order.order_no', 'order.grand_total')
                ->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/deliveredOrder?page=' . $page);
        }
        
        $orderArr = $orderIdArr = [];
        if (!$targetArr->isEmpty()) {
            foreach ($targetArr as $item) {
                $orderArr[$item->order_id] = $item->toArray();
                $orderIdArr[$item->order_id] = $item->order_id;
            }
        }
        
        $deliveryInfo = Delivery::join('order', 'order.id', 'delivery.order_id')
                ->whereIn('order.id', $orderIdArr)
                ->select('delivery.order_id', 'delivery.id as delivery_id', 'delivery.bl_no', 'delivery.bl_date', 'delivery.payment_status'
                        , 'delivery.payment_mode')
                ->orderBy('order.created_at', 'desc')
                ->orderBy('delivery.bl_date', 'asc')
                ->get();

        $deliveryArr = [];
        $paymentModeList = Common::getPaymentModeList();
        if (!empty($deliveryInfo)) {
            foreach ($deliveryInfo as $delivery) {
                $deliveryArr[$delivery->order_id][$delivery->delivery_id] = $delivery->toArray();
                $deliveryArr[$delivery->order_id][$delivery->delivery_id]['payment_status'] = !empty($delivery->payment_status) ? __('label.PAID') : __('label.UNPAID');
                $deliveryArr[$delivery->order_id][$delivery->delivery_id]['payment_mode'] = !empty($delivery->payment_mode) && !empty($paymentModeList[$delivery->payment_mode]) ? $paymentModeList[$delivery->payment_mode] : '';
                
            }
        }

        $orderArr = $orderIdArr = [];
        if (!$targetArr->isEmpty()) {
            foreach ($targetArr as $item) {
                $orderArr[$item->order_id] = $item->toArray();
                $orderIdArr[$item->order_id] = $item->order_id;
            }
        }
//        echo '<pre>';        print_r($orderArr);exit;
        $orderDetailArr = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('product_sku_code', 'product_sku_code.id', 'order_details.sku_id')
                ->join('brand', 'brand.id', 'product.brand_id')
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->whereIn('order.id', $orderIdArr)
                ->select('order_details.*', 'product_sku_code.sku', 'brand.name as brand_name'
                        , 'product_sku_code.available_quantity', 'product.name as product_name'
                        , 'retailer.name as retailer_name', 'product_sku_code.attribute')
                ->get();
//        $deliveryDetails = DeliveryDetails::join('delivery', 'delivery.id', '=', 'delivery_details.delivery_id')
//                        ->select('delivery_details.product_id', 'delivery.order_id',DB::raw('SUM(delivery_details.quantity) as delivered_qty'))
//                        ->groupBy('delivery_details.product_id', 'delivery.order_id')
////                ->toSql();
//                        ->get();

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


        //     echo '<pre>';
        //    print_r($orderArr);
        //    exit;
        return view('deliveredOrder.index')->with(compact('request', 'qpArr', 'orderArr', 'targetArr', 'orderNoList', 'retailerList', 'srList','deliveryArr'));
    }

    public function filter(Request $request) {
        $url = 'from_date=' . $request->from_date . '&to_date=' . $request->to_date
                . '&order_no=' . urlencode($request->order_no)
                . '&retailer_id=' . $request->retailer_id . '&sr_id=' . $request->sr_id;
        return Redirect::to('admin/deliveredOrder?' . $url);
    }
    
    public function getDeliveryDetails(Request $request) {
        $loadView = 'deliveredOrder.showDeliveryDetails';
        return Common::getDeliveryDetails($request, $loadView);
        
    }
    
    public function printInvoice(Request $request, $id) {
        $loadView = 'deliveredOrder.printInvoice';
        return Common::printInvoice($request, $id, $loadView);
    }

}
