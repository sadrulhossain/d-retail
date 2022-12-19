<?php

namespace App\Http\Controllers;

use Validator;
use App\Order;
use App\OrderDetails;
use App\CourierService;
use App\Branch;
use App\SetCourier;
use App\Delivery;
use App\Customer;
use App\Invoice;
use App\Product;
use App\ProductSKUCode;
use App\ProductReturn;
use App\ProductReturnDetails;
use App\CompanyInformation;
use App\DeliveryDetails;
use App\ProductAttribute;
use App\WhToLocalWhManager;
use App\WarehouseToSr;
use App\InvoiceDetails;
use App\WarehouseToRetailer;
use App\WarehouseStore;
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

class OrderPlacedInDeliveryController extends Controller {

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
        $orderNoList = Order::whereIn('status', ['3']);

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

//        $targetArr = Order::whereIn('order.status', ['3']);

        $targetArr = Order::whereIn('order.status', ['3'])
                ->leftJoin('invoice', 'invoice.order_id', 'order.id')
                ->join('users', 'users.id', 'order.sr_id')
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
            return redirect('/admin/orderPlacedInDelivery?page=' . $page);
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
                ->join('product_sku_code', 'product_sku_code.id', 'order_details.sku_id')
                ->join('brand', 'brand.id', 'product.brand_id')
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->whereIn('order.id', $orderIdArr)
                ->select('order_details.*', 'product_sku_code.sku', 'brand.name as brand_name'
                        , 'product_sku_code.available_quantity', 'retailer.name as retailer_name'
                        , 'product.name as product_name', 'product_sku_code.attribute')
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


        //     echo '<pre>';
        //    print_r($orderArr);
        //    exit;
        return view('orderPlacedInDelivery.index')->with(compact('request', 'qpArr', 'targetArr', 'orderArr', 'orderNoList', 'retailerList', 'srList'));
    }

    public function filter(Request $request) {
        $url = 'from_date=' . $request->from_date . '&to_date=' . $request->to_date
                . '&order_no=' . urlencode($request->order_no)
                . '&retailer_id=' . $request->retailer_id . '&sr_id=' . $request->sr_id;
        return Redirect::to('admin/orderPlacedInDelivery?' . $url);
    }

    public function viewStockDemand(Request $request) {
        $loadView = 'orderPlacedInDelivery.showStockDemand';
        return Common::getStockDemand($request, $loadView);
    }

    public function confirmDelivery(Request $request) {
//        echo '<pre>';
//        print_r($request->all());
//        exit;
        $target = Order::find($request->id);

        if (!empty($target)) {
            $target->status = $request->status;
            $target->save();
            return Response::json(array('heading' => 'Success', 'message' => __('label.ORDER_MARKED_AS_DELIVERED_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('heading' => 'Error', 'message' => __('label.ORDER_COULD_NOT_BE_MARKED_AS_DELIVERED')), 401);
        }
    }

    public function getInvoice(Request $request, $id) {

        $delivery = Delivery::join('order', 'order.id', 'delivery.order_id')
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->select('order.id as order_id', 'retailer.id as retailer_id', 'delivery.paying_amount'
                        , 'retailer.name as retailer_name', 'delivery.id as delivery_id', 'order.warehouse_id'
                )
                ->where('order.id', $id)
                ->first();

        $invoiceDate = date('Y-m-d');



        $deliveryDetailInfo = DeliveryDetails::join('delivery', 'delivery.id', 'delivery_details.delivery_id')
                ->join('order', 'order.id', 'delivery.order_id')
                ->join('product_sku_code', 'product_sku_code.id', '=', 'delivery_details.sku_id')
                ->join('product', 'product.id', '=', 'delivery_details.product_id')
                ->where('delivery.id', !empty($delivery->delivery_id) ? $delivery->delivery_id : 0)
                ->select('order.id as order_id', 'delivery_details.sku_id'
                        , 'delivery_details.unit_price', 'delivery_details.quantity', 'delivery_details.total_price'
                        , 'delivery.paying_amount', 'product.name as product_name', 'product_sku_code.attribute'
                        , 'delivery_details.id as delivery_details_id')
                ->get();

        $attrList = ProductAttribute::where('status', '1')
                ->pluck('name', 'id')
                ->toArray();

        if (!$deliveryDetailInfo->isEmpty()) {
            foreach ($deliveryDetailInfo as $details) {
                $attributeIdArr = !empty($details->attribute) ? explode(',', $details->attribute) : [];

                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $details->product_name .= (!empty($attrList[$attrId]) ? ' ' . $attrList[$attrId] : '');
                    }
                }
            }
        }
//        echo '<pre>';        print_r($deliveryDetailInfo->toArray()); exit;
        $invoiceInfo = Invoice::where('order_id', $id)->select('invoice_no', 'date', 'special_note')->first();

//        echo '<pre>';        print_r($orderDetailInfo); exit;

        return view('orderPlacedInDelivery.showInvoice')->with(compact('id', 'delivery', 'deliveryDetailInfo', 'invoiceInfo', 'invoiceDate'));
    }

    public function invoiceGenerate(Request $request) {


        $rules = [
            'invoice_number' => 'required|unique:invoice,invoice_no,' . $request->order_id . ',order_id',
            'date' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $order = Order::where('order.id', $request->order_id)
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->select('order.id as order_id', 'order.grand_total', 'retailer.id as retailer_id'
                        , 'retailer.name as retailer_name')
                ->first();


        $deliveryDetailInfo = DeliveryDetails::join('delivery', 'delivery.id', 'delivery_details.delivery_id')
                ->join('product', 'product.id', 'delivery_details.product_id')
                ->join('product_sku_code', 'product_sku_code.id', 'delivery_details.sku_id')
                ->where('delivery.order_id', $request->order_id)
                ->select('delivery_details.id as delivery_details_id'
                        , 'delivery_details.product_id', 'delivery_details.sku_id'
                        , 'product.name as product_name', 'delivery_details.unit_price'
                        , 'delivery_details.quantity', 'delivery_details.total_price')
                ->get();


        $view = view('orderPlacedInDelivery.showPreviewModal', compact('request', 'order', 'deliveryDetailInfo'))->render();
        return response()->json(['html' => $view]);
    }

    public function storeInvoice(Request $request) {

        $productArr = $request->product_name;

        $orderDetailInfo = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('delivery', 'delivery.order_id', 'order.id')
                ->join('delivery_details', 'delivery_details.delivery_id', 'delivery.id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('product_sku_code', 'product_sku_code.id', 'order_details.sku_id')
                ->where('order.id', $request->order_id)
                ->select('product_sku_code.id as sku_id', 'order_details.id as order_details_id'
                        , 'product.name as product_name'
                        , 'order_details.unit_price', 'order_details.quantity'
                        , 'order_details.total_price', 'delivery.id as delivery_id'
                        , 'delivery_details.id as delivery_details_id')
                ->get();

        $invoice = Invoice::where('order_id', $request->order_id)->first();


        $target = !empty($invoice) ? Invoice::find($invoice->id) : new Invoice;

        $target->invoice_no = $request->invoice_number;
        $target->retailer_id = $request->retailer_id;
        $target->order_id = $request->order_id;
        $target->delivery_id = $request->delivery_id;
        $target->date = Helper::dateFormatConvert($request->date);
        $target->special_note = $request->special_note;
        $target->net_receivable = $request->paying_amount;
        $target->payment_status = '0';
        $target->updated_at = date('Y-m-d H:i:s');
        $target->updated_by = Auth::user()->id;


        DB::beginTransaction();
        try {
            if ($target->save()) {
                $data = [];
                $i = 0;
                if (!empty($productArr)) {
                    foreach ($productArr as $key => $productId) {
                        $data[$i]['invoice_id'] = $target->id;
                        $data[$i]['delivery_details_id'] = $request->delivery_details_id[$key];
                        $data[$i]['shipment_qty'] = $request->shipment_qty[$key];
                        $data[$i]['amount'] = $request->amount[$key];
                        $i++;
                    }
                }


                //Insert data to the Product Details Table
                $invoiceDetails = InvoiceDetails::where('invoice_id', $target->id)->first();
                if (!empty($invoiceDetails)) {
                    InvoiceDetails::where('invoice_id', $target->id)->delete();
                }

                $detailsInsertStatus = InvoiceDetails::insert($data);

                if (!$detailsInsertStatus) {//If failed to Insert in Consumption Details Table, Roll Back Insertion operation of Master Table
                    DB::rollback();
                    return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
                }
                DB::commit();
                return Response::json(['success' => true], 200);
            } //EOF-IF Target->SAVE()
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
        }
    }

    public function invoicePreview(Request $request, $id) {

        $order = Order::where('order.id', $id)
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->join('invoice', 'invoice.order_id', 'order.id')
                ->select('order.id as order_id', 'order.grand_total', 'retailer.id as retailer_id'
                        , 'retailer.name as retailer_name', 'retailer.email', 'retailer.phone'
                        , 'order.shipping_address', 'invoice.invoice_no', 'invoice.date as invoice_date'
                        , 'invoice.vat', 'invoice.special_note', 'order.delivery_charge'
                        , 'order.vat')
                ->first();


        $orderDetailInfo = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('product_sku_code', 'product_sku_code.id', 'order_details.sku_id')
                ->where('order.id', $id)
                ->select('order_details.id as order_details_id'
                        , 'order_details.product_id', 'product_sku_code.id as sku_id'
                        , 'product.name as product_name', 'order.vat'
                        , 'order_details.unit_price', 'order_details.quantity'
                        , 'order_details.total_price', 'order.delivery_charge')
                ->get();
        if ($request->view == 'print') {
            return view('orderPlacedInDelivery.printInvoice')->with(compact('order', 'orderDetailInfo'));
        }

        return view('orderPlacedInDelivery.showInvoicePreview')->with(compact('order', 'orderDetailInfo'));
    }

    public function getProductReturn(Request $request) {
        $order = Order::where('order.id', $request->order_id)
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->select('order.id as order_id', 'order.grand_total', 'retailer.id as retailer_id'
                        , 'retailer.name as retailer_name', 'order.order_no', 'order.warehouse_id')
                ->first();

        $deliveryDetailInfo = DeliveryDetails::join('delivery', 'delivery.id', 'delivery_details.delivery_id')
                ->join('product', 'product.id', 'delivery_details.product_id')
                ->join('product_sku_code', 'product_sku_code.id', 'delivery_details.sku_id')
                ->where('delivery.order_id', $request->order_id)
                ->select('delivery.order_id', 'delivery_details.sku_id', 'product_sku_code.sku'
                        , 'product.name as product_name', 'product.id as product_id'
                        , 'delivery_details.unit_price', 'delivery_details.quantity', 'delivery_details.total_price'
                        , 'delivery.paying_amount', 'delivery_details.id as delivery_details_id')
                ->get();

        $view = view('orderPlacedInDelivery.showProductReturn', compact('request', 'order', 'deliveryDetailInfo'))->render();
        return response()->json(['html' => $view]);
    }

    public function setProductReturn(Request $request) {
        $target = new ProductReturn;
        $target->order_id = $request->order_id;
        $target->updated_by = Auth::user()->id;
        $target->updated_at = date('Y-m-d H:i:s');

        $warehouse_id = $request->warehouse_id;
//        echo "<pre>";
//        print_r($target);
//        exit;
        DB::beginTransaction();
        try {
            if ($target->save()) {
                $data = [];
                $i = 0;

                foreach ($request->product_sku as $key => $details) {
                    $data[$i]['return_id'] = $target->id;
                    $data[$i]['delivery_details_id'] = $details['delivery_details_id'];
                    $data[$i]['product_id'] = $details['product_id'];
                    $data[$i]['sku_id'] = $key;
                    $data[$i]['unit_price'] = $details['unit_price'];
                    $data[$i]['quantity'] = $details['quantity'];
                    $data[$i]['total_price'] = $details['total_price'];
                    $i++;
                }


                /* insert data into product check in details table */
                $insertProductReturnInfo = ProductReturnDetails::insert($data);

                if (!$insertProductReturnInfo) {//If failed to Insert in Consumption Details Table, Roll Back Insertion operation of Master Table
                    //ProductConsumptionMaster::where('id', $target->id)->delete();
                    DB::rollback();
                    return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.PRODUCT_RRTURNED_FAILED')], 401);
                } else {
                    $productDetails = ProductReturnDetails::where('return_id', $target->id)->lockForUpdate()->get();
                    if (!empty($productDetails)) {
                        foreach ($productDetails as $item) {
                            WarehouseStore::where('sku_id', $item->sku_id)->where('warehouse_id', $warehouse_id)->increment('quantity', $item->quantity);
                        }
                        Order::where('id', $request->order_id)->update(['status' => '4']);
                    } else {
                        DB::rollback();
                        return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.PRODUCT_RRTURNED_FAILED')], 401);
                        //$error .= __('label.QUANTITY_IS_ALREADY_CONSUMED_FOR') . $availableArr[$data['product_id']]['name']. '<br />';
                    }
                    DB::commit();
                    return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_RRTURNED_SUCCESSFULLY')), 200);
                }
//                    ProductCheckInDetails::insert($data);
//                    DB::commit();
//                    return Response::json(['success' => true], 200);
            } //EOF-IF Target->SAVE()
        } catch (\Throwable $e) {
            DB::rollback();
//                print_r($e->getMessage());
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.PRODUCT_RRTURNED_FAILED')], 401);
        }
    }

    public function printInvoice(Request $request, $id) {
        $loadView = 'orderPlacedInDelivery.printInvoice';
        return Common::printInvoice($request, $id, $loadView);
    }

}
