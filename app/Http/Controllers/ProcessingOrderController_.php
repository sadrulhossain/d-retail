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
use App\InvoiceDetails;
use App\Receive;
use App\Product;
use App\ProductSKUCode;
use App\ProductReturn;
use App\ProductReturnDetails;
use App\CompanyInformation;
use App\ProductAttribute;
use App\WarehouseToSr;
use App\WarehouseStore;
use App\WhToLocalWhManager;
use App\DeliveryDetails;
use App\TmToWarehouse;
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

class ProcessingOrderController extends Controller {

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
        $orderNoList = Order::whereIn('status', ['0','2']);

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
        $targetArr = Order::whereIn('order.status', ['0'])
                ->leftJoin('invoice', 'invoice.order_id', 'order.id')
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

        $targetArr = $targetArr->select(DB::raw("CONCAT(users.first_name,' ',users.last_name) AS user_name"), 'users.id as user_id', 'order.id as order_id', 'order.status', 'order.created_at', 'order.grand_total'
                        , 'retailer.name as retailer_name', 'invoice.id as invoice_id', 'order.order_no')
                ->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/processingOrder?page=' . $page);
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
                ->leftJoin('wh_store', function($join) {
                    $join->on('wh_store.warehouse_id', '=', 'order.warehouse_id');
                    $join->on('wh_store.sku_id', '=', 'order_details.sku_id');
                })
                ->join('brand', 'brand.id', 'product.brand_id')
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->whereIn('order.id', $orderIdArr)
                ->select('order_details.*', 'product_sku_code.sku', 'product.name as product_name'
                        , 'wh_store.quantity as available_quantity', 'brand.name as brand_name'
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


        return view('processingOrder.index')->with(compact('request', 'qpArr', 'targetArr', 'orderArr', 'orderNoList', 'retailerList', 'srList'));
    }

    public function filter(Request $request) {
        $url = 'from_date=' . $request->from_date . '&to_date=' . $request->to_date
                . '&order_no=' . urlencode($request->order_no)
                . '&retailer_id=' . $request->retailer_id . '&sr_id=' . $request->sr_id;
        return Redirect::to('admin/processingOrder?' . $url);
    }

    public function viewStockDemand(Request $request) {
        $loadView = 'processingOrder.showStockDemand';
        return Common::getStockDemand($request, $loadView);
    }

    /* public function getSetDelivery(Request $request, $id) {

      $orderDetailInfo = OrderDetails::join('product', 'product.id', 'order_details.product_id')
      ->join('product_sku_code', 'product_sku_code.id', 'order_details.sku_id')
      ->join('order', 'order.id', 'order_details.order_id')
      ->leftJoin('wh_store', function($join) {
      $join->on('wh_store.warehouse_id', '=', 'order.warehouse_id');
      $join->on('wh_store.sku_id', '=', 'order_details.sku_id');
      })
      ->where('order_details.order_id', $id)
      ->select('wh_store.quantity as available_quantity', 'product_sku_code.sku', 'product_sku_code.id as sku_id', 'order_details.quantity'
      , 'order_details.unit_price', 'order_details.quantity', 'order_details.total_price', 'order_details.id'
      , 'order.grand_total', 'order.warehouse_id', 'product.id as product_id'
      , 'wh_store.quantity as available_quantity', 'order_details.customer_demand')
      ->get();

      $orderNo = Order::where('order.id', $id)->select('order.order_no', 'order.retailer_id')->first();
      $deliveryDate = date('Y-m-d');


      //        dd($orderNo);

      $product = [];
      $i = 0;
      if (!$orderDetailInfo->isEmpty()) {
      foreach ($orderDetailInfo as $item) {
      if (($item->available_quantity) < ($item->quantity)) {
      $product[$i] = $item->sku;
      $i++;
      }
      }
      }
      $productList = implode(', ', $product);

      if (!empty($product)) {
      $body = __('label.THE_FOLLOWING_PRODUCTS_ARE_OUT_OF_STOCK', ['s_are' => sizeof($product) > 1 ? 's are' : ' is']) . "<br/><ul>";
      foreach ($product as $sku) {
      $body .= "<li> " . $sku . "</li>";
      }
      $body .= "</ul>";
      $void['header'] = __('label.SET_DELIVERY');
      $void['body'] = $body;
      return view('layouts.void')->with(compact('void'));
      }

      return view('processingOrder.showSetDelivery')->with(compact('request', 'id', 'orderNo', 'orderDetailInfo', 'deliveryDate'));
      } */

    public function getHeadOffice(Request $request) {

        $courierInfo = CourierService::select('address', 'number', 'email')
                        ->where('id', $request->courier_id)->first();
//        echo '<pre>';
//        print_r($courierInfo);
//        exit;

        $html = view('processingOrder.showHeadOffice', compact('courierInfo'))->render();

        return response()->json(['html' => $html]);
    }

    public function getBranch(Request $request) {
        $branchList = Branch::where('status', '1')->where('courier_id', $request->courier_id)->pluck('name', 'id')->toArray();
//        echo '<pre>';
//        print_r($branchList);
//        exit;

        $html = view('processingOrder.showBranch', compact('branchList'))->render();

        return response()->json(['html' => $html]);
    }

    public function getBranchDetail(Request $request) {
        $branchInfo = Branch::where('courier_id', $request->courier_id)
                ->where('id', $request->branch_id)
                ->select('email', 'branch_contact_no', 'location_details')
                ->first();
//        echo '<pre>';
//        print_r($branchList);
//        exit;

        $html = view('processingOrder.showBranchInfo', compact('branchInfo'))->render();

        return response()->json(['html' => $html]);
    }

    public function saveSetDelivery(Request $request) {

        $infoArr = !empty($request->delivery_info) ? json_decode($request->delivery_info, true) : [];
        /* $validator = Validator::make($request->all(), [
          'bl_no' => 'required|unique:delivery,bl_no,',
          'bl_date' => 'required',
          ]);
          if ($validator->fails()) {
          return Response::json(['success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()], 400);
          } */


        $deliveryInfo = new Delivery;
        $deliveryInfo->order_id = $request->order_id;
        $deliveryInfo->bl_no = $infoArr['bl_no'];
        $deliveryInfo->bl_date = Helper::dateFormatConvert($infoArr['bl_date']);
        $deliveryInfo->express_tracking_no = !empty($infoArr['express_tracking_no']) ? $infoArr['express_tracking_no'] : null;
        $deliveryInfo->container_no = !empty($infoArr['container_no']) ? $infoArr['container_no'] : null;
        $deliveryInfo->payment_status = '0';
        $deliveryInfo->payment_mode = $request->payment_mode;
        $deliveryInfo->paying_amount = !empty($infoArr['grand_total_price']) ? $infoArr['grand_total_price'] : null;
        $deliveryInfo->updated_at = date('Y-m-d H:i:s');
        $deliveryInfo->updated_by = Auth::user()->id;

        
        DB::beginTransaction();
        try {
            if ($deliveryInfo->save()) {
                if (!empty($infoArr['delivery'])) {
                    $data = [];
                    $i = 1;
                    foreach ($infoArr['delivery'] as $orderDetailsId => $details) {
                        $data[$i]['delivery_id'] = $deliveryInfo->id;
                        $data[$i]['order_details_id'] = $orderDetailsId;
                        $data[$i]['product_id'] = $details['product_id'];
                        $data[$i]['sku_id'] = $details['sku_id'];
                        $data[$i]['unit_price'] = $details['unit_price'];
                        $data[$i]['quantity'] = $details['quantity'];
                        $data[$i]['total_price'] = $details['total_price'];
                        $i++;
                    }//foreach

                    $insertDeliveryInfo = DeliveryDetails::insert($data);

                    $deliverDetailsArr = [];

                    if (!$insertDeliveryInfo) {//If failed to Insert in Consumption Details Table, Roll Back Insertion operation of Master Table
                        DB::rollback();
                        return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DELIVERY_COULD_NOT_BE_SET')], 401);
                    } else {
                        $productDetails = DeliveryDetails::where('delivery_id', $deliveryInfo->id)->lockForUpdate()->get();


                        if (!empty($productDetails)) {
                            foreach ($productDetails as $item) {
                                WarehouseStore::where('sku_id', $item->sku_id)
                                        ->where('warehouse_id', $item->warehouse_id)
                                        ->decrement('quantity', $item->quantity);
                                $deliverDetailsArr[$item->id] = $item->toArray();
                            }

                            //Generate Invoice :: START
                            $invoiceInfo = new Invoice;

                            $invoiceInfo->invoice_no = 'INV-' . $deliveryInfo->bl_no;
                            $invoiceInfo->retailer_id = $infoArr['retailer_id'];
                            $invoiceInfo->order_id = $request->order_id;
                            $invoiceInfo->delivery_id = $deliveryInfo->id;
                            $invoiceInfo->date = Helper::dateFormatConvert($infoArr['bl_date']);
                            $invoiceInfo->special_note = '';
                            $invoiceInfo->net_receivable = $infoArr['grand_total_price'];
                            $invoiceInfo->payment_status = '0';
                            $invoiceInfo->updated_at = date('Y-m-d H:i:s');
                            $invoiceInfo->updated_by = Auth::user()->id;


                            if ($invoiceInfo->save()) {
                                $invoiceDetailsData = [];
                                $j = 1;
                                if (!empty($deliverDetailsArr)) {
                                    foreach ($deliverDetailsArr as $detailsId => $deliveryDetailsInfo) {
                                        $invoiceDetailsData[$j]['invoice_id'] = !empty($invoiceInfo->id) ? $invoiceInfo->id : 0;
                                        $invoiceDetailsData[$j]['delivery_details_id'] = $detailsId;
                                        $invoiceDetailsData[$j]['shipment_qty'] = $deliveryDetailsInfo['quantity'];
                                        $invoiceDetailsData[$j]['amount'] = $deliveryDetailsInfo['total_price'];
                                        $j++;
                                    }
                                }

                                //Insert data to the Invoice Details Table
                                $invoiceDetails = InvoiceDetails::where('invoice_id', $invoiceInfo->id)->first();
                                if (!empty($invoiceDetails)) {
                                    InvoiceDetails::where('invoice_id', $invoiceInfo->id)->delete();
                                }

                                $detailsInsertStatus = InvoiceDetails::insert($invoiceDetailsData);

                                if (!$detailsInsertStatus) {//If failed to Insert in Consumption Details Table, Roll Back Insertion operation of Master Table
                                    DB::rollback();
                                    return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVEsssssss')], 401);
                                } else {

                                    if ($request->payment_mode == '1') {
                                        $recieveInfo = new Receive;
                                        $recieveInfo->invoice_id = $invoiceInfo->id;
                                        $recieveInfo->retailer_id = $infoArr['retailer_id'];
                                        $recieveInfo->order_id = $request->order_id;
                                        $recieveInfo->delivery_id = $deliveryInfo->id;
                                        $recieveInfo->collection_amount = $infoArr['grand_total_price'];
                                        $recieveInfo->created_at = date('Y-m-d H:i:s');
                                        $recieveInfo->created_by = Auth::user()->id;
                                        if ($recieveInfo->save()) {
                                            Invoice::where('id', $recieveInfo->invoice_id)->update(['payment_status' => '1']);
                                            Delivery::where('id', $recieveInfo->delivery_id)->update(['payment_status' => '1']);
                                        }
                                    }
                                }
                            } //EOF-IF Target->SAVE()
                            //Generate Invoice :: END
                            Order::where('id', $request->order_id)->update(['status' => '5']);
                        } else {
                            DB::rollback();
                            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DELIVERY_COULD_NOT_BE_SET')], 401);
                        }
                    }
                }
            } //EOF-IF Target->SAVE
            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.ORDER_DELIVERED_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
        }
    }

    public function cancel(Request $request) {
        $target = Order::find($request->id);

        if (!empty($target)) {
            $target->status = $request->status;
            $target->save();
            return Response::json(array('heading' => 'Success', 'message' => __('label.ORDER_CANCELLED_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('heading' => 'Error', 'message' => __('label.ORDER_COULD_NOT_BE_CANCELLED')), 401);
        }
    }

    public function confirmDelivery(Request $request) {
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

        $order = Order::where('order.id', $id)
                ->join('customer', 'customer.id', 'order.customer_id')
                ->join('set_courier', 'set_courier.order_id', 'order.id')
                ->select('order.id as order_id', 'order.paying_amount', 'customer.id as customer_id'
                        , 'set_courier.courier_id', 'customer.name as customer_name', 'customer.email'
                        , 'customer.phone', 'order.shipping_address', 'order.vat', 'order.delivery_charge')
                ->first();

        $orderDetailInfo = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('product_sku_code', 'product_sku_code.id', 'order_details.sku_id')
                ->where('order.id', $id)
                ->select('order.id as order_id', 'product_sku_code.id as sku_id', 'product.name'
                        , 'order_details.unit_price', 'order_details.quantity', 'order_details.total_price'
                        , 'order.paying_amount', 'order.vat', 'order.delivery_charge')
                ->get();

        $invoiceInfo = Invoice::where('order_id', $id)->select('invoice_number', 'date', 'special_note')->first();


        return view('processingOrder.showInvoice')->with(compact('id', 'order', 'orderDetailInfo', 'invoiceInfo'));
    }

    public function invoiceGenerate(Request $request) {


        $rules = [
            'invoice_number' => 'required|unique:invoice,invoice_number,' . $request->order_id . ',order_id',
            'date' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $order = Order::where('order.id', $request->order_id)
                ->join('customer', 'customer.id', 'order.customer_id')
                ->select('order.id as order_id', 'order.paying_amount', 'customer.id as customer_id'
                        , 'customer.name as customer_name', 'customer.email', 'customer.phone'
                        , 'order.shipping_address', 'order.vat', 'order.delivery_charge')
                ->first();

//        echo '<pre>';
//        print_r($order);
//        exit;

        $orderDetailInfo = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('product_sku_code', 'product_sku_code.id', 'order_details.sku_id')
                ->where('order.id', $request->order_id)
                ->select('order_details.id as order_details_id'
                        , 'order_details.product_id', 'product_sku_code.id as sku_id'
                        , 'product.name as product_name', 'order.vat'
                        , 'order_details.unit_price', 'order_details.quantity'
                        , 'order_details.total_price', 'order.delivery_charge')
                ->get();
        //Endof Arr Data


        $view = view('processingOrder.showPreviewModal', compact('request', 'order', 'orderDetailInfo'))->render();
        return response()->json(['html' => $view]);
    }

    public function storeInvoice(Request $request) {

//        echo '<pre>';
//        print_r($request->all());
//        exit;

        $orderDetailInfo = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('product_sku_code', 'product_sku_code.id', 'order_details.sku_id')
                ->where('order.id', $request->order_id)
                ->select('product_sku_code.id as sku_id', 'order_details.id as order_details_id'
                        , 'product.name as product_name', 'order.delivery_charge'
                        , 'order_details.unit_price', 'order_details.quantity'
                        , 'order_details.total_price', 'order.delivery_charge')
                ->get();

        $data = [];
        if (!empty($orderDetailInfo)) {
            foreach ($orderDetailInfo as $order) {
                $data[$order->order_details_id]['sku_id'] = $order->sku_id;
                $data[$order->order_details_id]['product_name'] = $order->product_name;
                $data[$order->order_details_id]['unit_price'] = $order->unit_price;
                $data[$order->order_details_id]['quantity'] = $order->quantity;
            }
        }

        $target = new Invoice;
        $target->invoice_number = $request->invoice_number;
        $target->date = Helper::dateFormatConvert($request->date);
        $target->special_note = $request->special_note;
        $target->order_id = $request->order_id;
        $target->customer_id = $request->customer_id;
        $target->courier_id = $request->courier_id;
        $target->order_details = json_encode($data);
        $target->sub_total = $request->sub_total;
        $target->vat = $request->vat;
        $target->total_amount = $request->net_payable;
        $target->updated_at = date('Y-m-d H:i:s');
        $target->updated_by = Auth::user()->id;

//        echo '<pre>';
//        print_r($target->toArray());
//        exit;

        Invoice::where('order_id', $request->order_id)->delete();
        if ($target->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.INVOICE_CREATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.INVOICE_COULD_NOT_BE_CREATED')], 401);
        }
    }

    public function invoicePreview(Request $request, $id) {

        $order = Order::where('order.id', $id)
                ->join('customer', 'customer.id', 'order.customer_id')
                ->join('invoice', 'invoice.order_id', 'order.id')
                ->select('order.id as order_id', 'order.paying_amount', 'customer.id as customer_id'
                        , 'customer.name as customer_name', 'customer.email', 'customer.phone'
                        , 'order.shipping_address', 'invoice.invoice_number', 'invoice.date as invoice_date'
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
            return view('processingOrder.printInvoice')->with(compact('order', 'orderDetailInfo'));
        }

        return view('processingOrder.showInvoicePreview')->with(compact('order', 'orderDetailInfo'));
    }

    public function getProductReturn(Request $request) {
        $order = Order::where('order.id', $request->order_id)
                ->join('customer', 'customer.id', 'order.customer_id')
                ->join('set_courier', 'set_courier.order_id', 'order.id')
                ->select('order.id as order_id', 'order.paying_amount', 'customer.id as customer_id'
                        , 'set_courier.courier_id', 'customer.name as customer_name', 'customer.email'
                        , 'customer.phone', 'order.shipping_address', 'order.vat', 'order.order_no')
                ->first();

        $orderDetailInfo = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('product_sku_code', 'product_sku_code.id', 'order_details.sku_id')
                ->where('order.id', $request->order_id)
                ->select('order.id as order_id', 'product_sku_code.id as sku_id', 'product_sku_code.sku'
                        , 'product.name as product_name', 'product.id as product_id'
                        , 'order_details.unit_price', 'order_details.quantity', 'order_details.total_price'
                        , 'order.paying_amount', 'order.vat', 'order_details.id as order_details_id')
                ->get();


        $view = view('processingOrder.showProductReturn', compact('request', 'order', 'orderDetailInfo'))->render();
        return response()->json(['html' => $view]);
    }

    public function setProductReturn(Request $request) {
        $target = new ProductReturn;
        $target->order_id = $request->order_id;
        $target->updated_by = Auth::user()->id;
        $target->updated_at = date('Y-m-d H:i:s');

        DB::beginTransaction();
        try {
            if ($target->save()) {
                $data = [];
                $i = 0;

                foreach ($request->product_sku as $key => $details) {
                    $data[$i]['return_id'] = $target->id;
                    $data[$i]['order_details_id'] = $details['order_details_id'];
                    $data[$i]['product_id'] = $details['product_id'];
                    $data[$i]['sku_id'] = $key;
                    $data[$i]['unit_price'] = $details['unit_price'];
                    $data[$i]['quantity'] = $details['quantity'];
                    $data[$i]['total_price'] = $details['total_price'];
                    $i++;
                }

//                    echo '<pre>';
//                    print_r($data);
//                    exit;

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
                            ProductSKUCode::where('id', $item->sku_id)->increment('available_quantity', $item->quantity);
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

        $order = Order::where('order.id', $id)
                ->join('customer', 'customer.id', 'order.customer_id')
                ->join('invoice', 'invoice.order_id', 'order.id')
                ->select('order.id as order_id', 'order.paying_amount', 'customer.id as customer_id'
                        , 'customer.name as customer_name', 'customer.email', 'customer.phone'
                        , 'order.shipping_address', 'invoice.invoice_number', 'invoice.date as invoice_date'
                        , 'order.vat', 'invoice.special_note', 'order.delivery_charge')
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


        if ($request->view == 'printCustomer') {
            $companyInfo = CompanyInformation::first();
            $companyNumber = '';
            if (!empty($companyInfo)) {
                $phoneNumberDecode = json_decode($companyInfo->phone_number, true);
                $companyNumber = Helper::arrayTostring($phoneNumberDecode);
            }
            $imagePath = '/public/img/small_logo.png';
        } else {
            $companyInfo = SetCourier::join('courier_service', 'courier_service.id', 'set_courier.courier_id')
                            ->where('set_courier.order_id', $id)
                            ->select('courier_service.*')->first();
            $companyNumber = $companyInfo->number;
            $imagePath = '';
        }

        return view('processingOrder.printInvoice')->with(compact('order', 'orderDetailInfo'
                                , 'companyInfo', 'companyNumber', 'imagePath'));
    }

    public function getSetDelivery(Request $request) {
        $loadView = 'processingOrder.showDeliveryInformation';
        return Common::getDeliveryInformation($request, $loadView);
    }

    public function showPaymentInfo(Request $request) {
        $loadView = 'processingOrder.showPaymentInformation';
        return Common::getPaymentInformation($request, $loadView);
    }

    public function showConfirmDelivery(Request $request) {
        $loadView = 'processingOrder.showConfirmDelivery';
        return Common::getDeliveryConfirmInformation($request, $loadView);
    }

}
