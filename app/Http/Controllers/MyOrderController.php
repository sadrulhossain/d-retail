<?php

namespace App\Http\Controllers;

use Validator;
use App\Wishlist; //model class
use App\Customer;
use App\Product;
use App\OrderDetails;
use App\Order;
use App\ProductAttribute;
use App\User;
use Common;
use Session;
use Redirect;
use Auth;
use File;
use Response;
use Image;
use Helper;
use Cart;
use DB;
use Illuminate\Http\Request;

class MyOrderController extends Controller {

    public function index() {

        $onGoingTargetArr = [];
        $returnedTargetArr = [];
        $deliveredTargetArr = [];
        $cancelledTargetArr = [];
        $onGoingTargetArrCount = 0;
        $returnedTargetArrCount = 0;
        $deliveredTargetArrCount = 0;
        $cancelledTargetArrCount = 0;
        $attrList = ProductAttribute::where('status', '1')
                        ->pluck('name', 'id')->toArray();
        $customerId = DB::table('customer')->select('id')->where('user_id', $userId = Auth::user()->id)->first();
        $onGoingOrderData = Order::whereNotIn('order.status', ['4', '5', '8'])
                ->where('order.customer_id', $customerId->id)
                ->select('order.id as order_id')
                ->get();
        $returnedOrderData = Order::where('order.status', '4')
                ->where('order.customer_id', $customerId->id)
                ->select('order.id as order_id')
                ->get();
        $deliveredOrderData = Order::where('order.status', '5')
                ->where('order.customer_id', $customerId->id)
                ->select('order.id as order_id')
                ->get();
        $cancelledOrderData = Order::where('order.status', '8')
                ->where('order.customer_id', $customerId->id)
                ->select('order.id as order_id')
                ->get();

        $returnedOrderInfo = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('brand', 'brand.id', 'product.brand_id')
                ->where('order.status', '4')
                ->where('order.customer_id', $customerId->id)
                ->select('order.id as order_id', 'order.order_no', 'order.shipping_address', 'order.vat'
                        , 'order.payment_type', 'order.status as order_status', 'order.paying_amount', 'order.created_at'
                        , 'order.vat', 'order_details.*', 'product.name as product', 'brand.name as brand')
                ->get();
        $deliveredOrderInfo = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('brand', 'brand.id', 'product.brand_id')
                ->where('order.status', '5')
                ->where('order.customer_id', $customerId->id)
                ->select('order.id as order_id', 'order.order_no', 'order.shipping_address', 'order.vat'
                        , 'order.payment_type', 'order.status as order_status', 'order.paying_amount', 'order.created_at'
                        , 'order.vat', 'order_details.*', 'product.name as product', 'brand.name as brand')
                ->get();
        $cancelledOrderInfo = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('brand', 'brand.id', 'product.brand_id')
                ->where('order.status', '8')
                ->where('order.customer_id', $customerId->id)
                ->select('order.id as order_id','order.updated_at', 'order.order_no', 'order.shipping_address', 'order.vat'
                        , 'order.payment_type', 'order.status as order_status', 'order.paying_amount', 'order.created_at'
                        , 'order.vat', 'order_details.*', 'product.name as product', 'brand.name as brand')
                ->get();


        $onGoingOrderInfo = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('brand', 'brand.id', 'product.brand_id')
                ->whereNotIn('order.status', ['4', '5', '8'])
                ->where('order.customer_id', $customerId->id)
                ->select('order.id as order_id', 'order.order_no', 'order.shipping_address', 'order.vat'
                        , 'order.payment_type', 'order.status as order_status', 'order.paying_amount', 'order.created_at'
                        , 'order.vat', 'order_details.*', 'product.name as product', 'brand.name as brand')
                ->get();
        
//        dd($onGoingOrderInfo);
//        echo "<pre>";
//        print_r($onGoingOrderInfo);
//        exit;


        if (!$onGoingOrderInfo->isEmpty()) {
            foreach ($onGoingOrderInfo as $item) {
                $onGoingTargetArr[$item->order_id]['order_no'] = $item->order_no ?? '';
                $onGoingTargetArr[$item->order_id]['shipping_address'] = $item->shipping_address;
                $onGoingTargetArr[$item->order_id]['payment_type'] = $item->payment_type ?? '0';
                $onGoingTargetArr[$item->order_id]['vat'] = $item->vat;
                $onGoingTargetArr[$item->order_id]['status'] = $item->order_status;
                $onGoingTargetArr[$item->order_id]['paying_amount'] = $item->paying_amount;
                $onGoingTargetArr[$item->order_id]['created_at'] = $item->created_at;

                $attributeIdArr = !empty($item->attribute) ? explode(',', $item->attribute) : [];
//                dd($attributeIdArr);
                $onGoingTargetArr[$item->order_id]['item'][$item->sku_id]['product'] = $item->product;

                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $onGoingTargetArr[$item->order_id]['item'][$item->sku_id]['product'] .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                    }
                }

                $onGoingTargetArr[$item->order_id]['item'][$item->sku_id]['brand'] = $item->brand;
                $onGoingTargetArr[$item->order_id]['item'][$item->sku_id]['unit_price'] = $item->unit_price;
                $onGoingTargetArr[$item->order_id]['item'][$item->sku_id]['quantity'] = $item->quantity;
                $onGoingTargetArr[$item->order_id]['item'][$item->sku_id]['total_price'] = $item->total_price;
                $onGoingTargetArr[$item->order_id]['rowspan'] = !empty($onGoingTargetArr[$item->order_id]['rowspan']) ? $onGoingTargetArr[$item->order_id]['rowspan'] : 0;
                $onGoingTargetArr[$item->order_id]['rowspan'] += 1;
                $onGoingTargetArr['count'] = ++$onGoingTargetArrCount;
            }
        }
        if (!$returnedOrderInfo->isEmpty()) {
            foreach ($returnedOrderInfo as $item) {
                $returnedTargetArr[$item->order_id]['order_no'] = $item->order_no ?? '';
                $returnedTargetArr[$item->order_id]['shipping_address'] = $item->shipping_address;
                $returnedTargetArr[$item->order_id]['payment_type'] = $item->payment_type ?? '0';
                $returnedTargetArr[$item->order_id]['vat'] = $item->vat;
                $returnedTargetArr[$item->order_id]['status'] = $item->order_status;
                $returnedTargetArr[$item->order_id]['paying_amount'] = $item->paying_amount;
                $returnedTargetArr[$item->order_id]['created_at'] = $item->created_at;
                $returnedTargetArr[$item->order_id]['updated_at'] = $item->updated_at;
                
                $attributeIdArr = !empty($item->attribute) ? explode(',', $item->attribute) : [];
//                dd($attributeIdArr);
                $returnedTargetArr[$item->order_id]['item'][$item->sku_id]['product'] = $item->product;

                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $returnedTargetArr[$item->order_id]['item'][$item->sku_id]['product'] .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                    }
                }

                $returnedTargetArr[$item->order_id]['item'][$item->sku_id]['brand'] = $item->brand;
                $returnedTargetArr[$item->order_id]['item'][$item->sku_id]['unit_price'] = $item->unit_price;
                $returnedTargetArr[$item->order_id]['item'][$item->sku_id]['quantity'] = $item->quantity;
                $returnedTargetArr[$item->order_id]['item'][$item->sku_id]['total_price'] = $item->total_price;
                $returnedTargetArr[$item->order_id]['rowspan'] = !empty($returnedTargetArr[$item->order_id]['rowspan']) ? $returnedTargetArr[$item->order_id]['rowspan'] : 0;
                $returnedTargetArr[$item->order_id]['rowspan'] += 1;
                $returnedTargetArr['count'] = ++$returnedTargetArrCount;
            }
        }
        if (!$deliveredOrderInfo->isEmpty()) {
            foreach ($deliveredOrderInfo as $item) {
                $deliveredTargetArr[$item->order_id]['order_no'] = $item->order_no ?? '';
                $deliveredTargetArr[$item->order_id]['shipping_address'] = $item->shipping_address;
                $deliveredTargetArr[$item->order_id]['payment_type'] = $item->payment_type ?? '0';
                $deliveredTargetArr[$item->order_id]['vat'] = $item->vat;
                $deliveredTargetArr[$item->order_id]['status'] = $item->order_status;
                $deliveredTargetArr[$item->order_id]['paying_amount'] = $item->paying_amount;
                $deliveredTargetArr[$item->order_id]['created_at'] = $item->created_at;
                $deliveredTargetArr[$item->order_id]['updated_at'] = $item->updated_at;

                $attributeIdArr = !empty($item->attribute) ? explode(',', $item->attribute) : [];
//                dd($attributeIdArr);
                $deliveredTargetArr[$item->order_id]['item'][$item->sku_id]['product'] = $item->product;

                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $deliveredTargetArr[$item->order_id]['item'][$item->sku_id]['product'] .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                    }
                }

                $deliveredTargetArr[$item->order_id]['item'][$item->sku_id]['brand'] = $item->brand;
                $deliveredTargetArr[$item->order_id]['item'][$item->sku_id]['unit_price'] = $item->unit_price;
                $deliveredTargetArr[$item->order_id]['item'][$item->sku_id]['quantity'] = $item->quantity;
                $deliveredTargetArr[$item->order_id]['item'][$item->sku_id]['total_price'] = $item->total_price;
                $deliveredTargetArr[$item->order_id]['rowspan'] = !empty($deliveredTargetArr[$item->order_id]['rowspan']) ? $deliveredTargetArr[$item->order_id]['rowspan'] : 0;
                $deliveredTargetArr[$item->order_id]['rowspan'] += 1;
                $deliveredTargetArr['count'] = ++$deliveredTargetArrCount;
            }
        }
        if (!$cancelledOrderInfo->isEmpty()) {
            foreach ($cancelledOrderInfo as $item) {
                $cancelledTargetArr[$item->order_id]['order_no'] = $item->order_no ?? '';
                $cancelledTargetArr[$item->order_id]['shipping_address'] = $item->shipping_address;
                $cancelledTargetArr[$item->order_id]['payment_type'] = $item->payment_type ?? '0';
                $cancelledTargetArr[$item->order_id]['vat'] = $item->vat;
                $cancelledTargetArr[$item->order_id]['status'] = $item->order_status;
                $cancelledTargetArr[$item->order_id]['paying_amount'] = $item->paying_amount;
                $cancelledTargetArr[$item->order_id]['created_at'] = $item->created_at;
                $cancelledTargetArr[$item->order_id]['updated_at'] = $item->updated_at;

                $attributeIdArr = !empty($item->attribute) ? explode(',', $item->attribute) : [];
//                dd($attributeIdArr);
                $cancelledTargetArr[$item->order_id]['item'][$item->sku_id]['product'] = $item->product;

                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $cancelledTargetArr[$item->order_id]['item'][$item->sku_id]['product'] .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                    }
                }

                $cancelledTargetArr[$item->order_id]['item'][$item->sku_id]['brand'] = $item->brand;
                $cancelledTargetArr[$item->order_id]['item'][$item->sku_id]['unit_price'] = $item->unit_price;
                $cancelledTargetArr[$item->order_id]['item'][$item->sku_id]['quantity'] = $item->quantity;
                $cancelledTargetArr[$item->order_id]['item'][$item->sku_id]['total_price'] = $item->total_price;
                $cancelledTargetArr[$item->order_id]['rowspan'] = !empty($cancelledTargetArr[$item->order_id]['rowspan']) ? $cancelledTargetArr[$item->order_id]['rowspan'] : 0;
                $cancelledTargetArr[$item->order_id]['rowspan'] += 1;
                $cancelledTargetArr['count'] = ++$cancelledTargetArrCount;
            }
        }

//        echo '<pre>';
//        print_r($onGoingOrderData->toArray());
//        print_r($onGoingTargetArr);
//        exit;
        return view('frontend.myOrder')->with(compact('onGoingTargetArr', 'onGoingOrderData','deliveredTargetArr','deliveredOrderData','returnedTargetArr','returnedOrderData','cancelledTargetArr','cancelledOrderData'));
    }

}
