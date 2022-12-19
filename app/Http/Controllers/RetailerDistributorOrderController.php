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
use App\SrToRetailer;
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

class RetailerDistributorOrderController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        //inquiry Details
        $fromDate = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) : '';
        $toDate = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) : '';
        $orderNoList = Order::join('retailer', 'retailer.id', 'order.retailer_id')
                ->where('retailer.user_id', Auth::user()->id);

        $orderNoList = $orderNoList->pluck('order_no', 'order_no')->toArray();

        $orderNoList = ['0' => __('label.SELECT_ORDER_NO_OPT')] + $orderNoList;
//        $retailerList = SrToRetailer::join('retailer', 'retailer.id', 'sr_to_retailer.retailer_id')
//                        ->where('sr_to_retailer.sr_id', Auth::user()->id)
//                        ->pluck('retailer.name', 'retailer.id')->toArray();
//        $srList = WarehouseToSr::join('users', 'users.id', 'warehouse_to_sr.sr_id');
//        if (in_array(Auth::user()->group_id, [12, 15])) {
//            $srList = $srList->whereIn('warehouse_to_sr.warehouse_id', $whList);
//        }
//        $srList = $srList->select(DB::raw("CONCAT(users.first_name,' ',users.last_name) AS user_name"), 'users.id')
//                        ->pluck('user_name', 'users.id')->toArray();
//
//        $retailerList = ['0' => __('label.SELECT_RETAILER')] + $retailerList;
//        $srList = ['0' => __('label.SELECT_SR')] + $srList;

        $targetArr = Order::join('retailer', 'retailer.id', 'order.retailer_id')
//                ->join('users', 'users.id', 'retailer.user_id')
                ->join('users', 'users.id', 'order.sr_id')
                ->where('retailer.user_id', Auth::user()->id);
//                ->select('order.*')->get();
//        echo '<pre>';        print_r($targetArr); exit;

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

        $targetArr = $targetArr->select(DB::raw("CONCAT(users.first_name,' ',users.last_name) AS user_name")
                , 'order.id as order_id', 'order.order_no', 'order.status', 'order.grand_total', 'order.payment_collection'
                , 'order.sr_id', 'order.created_at', 'retailer.name as retailer_name');

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
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

//        $srName = User::where('user_id',$targetArr);
//        
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/retailerDistributorOrder?page=' . $page);
        }


        $orderArr = $orderIdArr = [];
        if (!$targetArr->isEmpty()) {
            foreach ($targetArr as $item) {
                $orderArr[$item->order_id] = $item->toArray();
                $orderIdArr[$item->order_id] = $item->order_id;
            }
        }

        $orderDetailArr = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('product_sku_code', 'product_sku_code.id', 'order_details.sku_id')
                ->leftJoin('wh_store', function ($join) {
                    $join->on('wh_store.warehouse_id', '=', 'order.warehouse_id');
                    $join->on('wh_store.sku_id', '=', 'order_details.sku_id');
                })
                ->join('product', 'product.id', 'product_sku_code.product_id')
                ->join('brand', 'brand.id', 'product.brand_id')
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->whereIn('order.id', $orderIdArr)
                ->select('order_details.*', 'product_sku_code.sku'
                        , 'wh_store.quantity as available_quantity', 'product.name as product_name'
                        , 'brand.name as brand_name', 'retailer.name as retailer_name', 'product_sku_code.attribute')
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

        return view('retailerDistributorOrder.index')->with(compact('request', 'qpArr', 'orderArr', 'orderNoList', 'targetArr'));
    }

    public function filter(Request $request) {
        $url = 'from_date=' . $request->from_date . '&to_date=' . $request->to_date
                . '&order_no=' . urlencode($request->order_no);
        return Redirect::to('admin/retailerDistributorOrder?' . $url);
    }

    public function create(Request $request) {

        //passing param for custom function
        $qpArr = $request->all();
        $creationDate = date('Y-m-d');
        $srList = ['0' => __('label.SELECT_SR')];

        $warehouse = WarehouseToSr::join('warehouse', 'warehouse.id', 'warehouse_to_sr.warehouse_id')
                ->where('warehouse_to_sr.sr_id', Auth::user()->id)
                ->select('warehouse_to_sr.warehouse_id', 'warehouse.name as warehouse_name')
                ->first();
        if (!empty($warehouse)) {
            $warehouseId = $warehouse->warehouse_id;
        } else {
            $warehouseId = 0;
        }


        $retailerList = ['0' => __('label.SELECT_RETAILER')] + SrToRetailer::join('retailer', 'retailer.id', 'sr_to_retailer.retailer_id')
                        ->where('sr_to_retailer.sr_id', Auth::user()->id)
                        ->pluck('retailer.name', 'retailer.id')->toArray();

        if (empty($warehouse)) {
            $void['header'] = __('label.CREATE_NEW_ORDER');
            $void['body'] = __('label.YOU_ARE_NOT_ASSIGN_TO_ANY_WAREHOUSE');
            return view('layouts.void', compact('void'));
        }

        $targetArr = WarehouseStore::join('product_sku_code', 'product_sku_code.id', '=', 'wh_store.sku_id')
                        ->where('wh_store.warehouse_id', $warehouseId)
                        ->select('product_sku_code.sku', 'product_sku_code.id'
                                , 'product_sku_code.selling_price', 'product_sku_code.product_id'
                                , 'wh_store.quantity as available_quantity')->get();

        return view('retailerDistributorOrder.create')->with(compact('qpArr', 'creationDate', 'srList', 'retailerList', 'targetArr', 'warehouse'));
    }

    public function confirmOrder(Request $request) {
        $target = Order::find($request->id);

        if (!empty($target)) {
            $target->status = $request->status;
            $target->save();
            return Response::json(array('heading' => 'Success', 'message' => __('label.ORDER_CONFIRMED_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('heading' => 'Error', 'message' => __('label.ORDER_COULD_NOT_BE_CONFIRMED')), 401);
        }
    }

    public function startProcessing(Request $request) {
//        echo '<pre>';
//        print_r($request->all());
//        exit;
        $target = Order::find($request->id);

        if (!empty($target)) {
            $target->status = $request->status;
            $target->save();
            return Response::json(array('heading' => 'Success', 'message' => __('label.STARTED_ORDER_PROCESSING_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('heading' => 'Error', 'message' => __('label.ORDER_PROCESSING_COULD_NOT_BE_STARTED')), 401);
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

    public function viewStockDemand(Request $request) {
        $loadView = 'retailerDistributorOrder.showStockDemand';
        return Common::getStockDemand($request, $loadView);
    }

    public function store(Request $request) {
        $skuArr = $request->sku;

        if (empty($skuArr)) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => __('label.PLEASE_SELECT_ATLEAST_ONE_SKU')), 401);
        }
        $rules = $message = array();
        $rules = [
            'retailer' => 'required|not_in:0',
            'creation_date' => 'required',
        ];
        $message['retailer.not_in'] = __('label.RETAILER_FIELD_IS_REQUIRED');
        $message['creation_date.required'] = __('label.DATE_FIELD_IS_REQUIRED');

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json(['success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()], 400);
        }


        $orderNo = Common::generateOrderNo();

        // End Generate unique order no.


        $target = new Order;
        $target->retailer_id = $request->retailer;
        $target->sr_id = $request->sr_id;
        $target->warehouse_id = $request->warehouse_id;
        $target->order_no = $orderNo;
        $target->payment_collection = !empty($request->payment_collection) ? $request->payment_collection : '0';
        $target->grand_total = $request->grand_total_price;
        $target->status = '0';
        $target->created_at = date('Y-m-d H:i:s');
        $target->updated_at = date('Y-m-d H:i:s');
        $target->updated_by = Auth::user()->id;

        DB::beginTransaction();
        try {
            if ($target->save()) {

                $data = [];
                $i = 0;
                if (!empty($skuArr)) {
                    foreach ($skuArr as $key => $skuId) {
                        $data[$i]['order_id'] = $target->id;
                        $data[$i]['product_id'] = $request->product_id[$key];
                        $data[$i]['sku_id'] = $key;
                        $data[$i]['unit_price'] = $request->product_price[$key];
                        $data[$i]['quantity'] = $request->product_quantity[$key];
                        $data[$i]['customer_demand'] = $request->customer_demand[$key];
                        $data[$i]['over_demanded'] = (($request->product_quantity[$key] == $request->available_qty[$key]) && ($request->customer_demand[$key] > $request->product_quantity[$key])) ? '1' : '0';
                        $data[$i]['total_price'] = $request->product_total_price[$key];
                        $i++;
                    }
                }

                //Insert data to the Product Details Table

                OrderDetails::insert($data);
                DB::commit();
                return Response::json(['success' => true], 200);
            } //EOF-IF Target->SAVE()
        } catch (\Throwable $e) {

            DB::rollback();
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
        }
    }

    public function edit(Request $request, $id) {
        //passing param for custom function
        $qpArr = $request->all();
        $creationDate = date('Y-m-d');

        $orderDetails = Order::join('retailer', 'retailer.id', 'order.retailer_id')
                        ->join('warehouse', 'warehouse.id', 'order.warehouse_id')
                        ->where('order.id', $id)
                        ->select('warehouse.id as warehouse_id', 'warehouse.name as warehouse_name'
                                , 'retailer.id as retailer_id', 'order.created_at', 'order.id as order_id')->first();

        $retailerList = ['0' => __('label.SELECT_RETAILER')] + WarehouseToRetailer::join('retailer', 'retailer.id', 'warehouse_to_retailer.retailer_id')
                        ->where('warehouse_to_retailer.warehouse_id', $orderDetails->warehouse_id)
                        ->pluck('retailer.name', 'retailer.id')->toArray();

        $targetArr = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('product_sku_code', 'product_sku_code.id', 'order_details.sku_id')
                ->where('order.id', $id)
                ->select('order_details.quantity', 'product_sku_code.sku', 'product_sku_code.id'
                        , 'product_sku_code.selling_price', 'product_sku_code.product_id'
                        , 'order_details.quantity', 'order_details.total_price', 'order_details.unit_price'
                        , 'order_details.product_id', 'order.grand_total')
                ->get();

//        $prevSkuList = OrderDetails::where('order_id',$id)
//                ->pluck('order_id','sku_id')->toArray();
//                echo "<pre>";
//        print_r($prevSkuList);
//        exit();

        return view('retailerDistributorOrder.edit')->with(compact('qpArr', 'retailerList', 'targetArr', 'creationDate', 'orderDetails'));
    }

    public function update(Request $request) {

        $skuArr = $request->sku;

        if (empty($skuArr)) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => __('label.PLEASE_SELECT_ATLEAST_ONE_SKU')), 401);
        }
        $rules = $message = array();
        $rules = [
            'retailer_id' => 'required|not_in:0',
            'creation_date' => 'required',
        ];
        $message['retailer_id.not_in'] = __('label.RETAILER_FIELD_IS_REQUIRED');
        $message['creation_date.required'] = __('label.DATE_FIELD_IS_REQUIRED');

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json(['success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()], 400);
        }


        $target = Order::find($request->order_id);

        $target->retailer_id = $request->retailer_id;
        $target->sr_id = $request->sr_id;
        $target->warehouse_id = $request->warehouse_id;
        $target->payment_collection = !empty($request->payment_collection) ? $request->payment_collection : '0';
        $target->order_no = !empty($target->order_no) ? $target->order_no : '';
        $target->grand_total = $request->grand_total_price;
        $target->status = '0';
        $target->updated_at = date('Y-m-d H:i:s');
        $target->updated_by = Auth::user()->id;

        DB::beginTransaction();
        try {
            if ($target->save()) {
                $data = [];
                $i = 0;
                if (!empty($skuArr)) {
                    foreach ($skuArr as $key => $skuId) {
                        $data[$i]['order_id'] = $target->id;
                        $data[$i]['product_id'] = $request->product_id[$key];
                        $data[$i]['sku_id'] = $key;
                        $data[$i]['unit_price'] = $request->product_price[$key];
                        $data[$i]['quantity'] = $request->product_quantity[$key];
                        $data[$i]['total_price'] = $request->product_total_price[$key];
                        $i++;
                    }
                }

                //Insert data to the Product Details Table
                OrderDetails::where('order_id', $request->order_id)->delete();
                OrderDetails::insert($data);
//                Order::where('order.id',$request->order_id)->update(['grand_total' => $request->grand_total_price]);
                ////////////////////////////////
//                    DB::commit();
                DB::commit();
                return Response::json(['success' => true], 200);
            } //EOF-IF Target->SAVE()
        } catch (\Throwable $e) {

            DB::rollback();

            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
        }
    }

    public function destroy(Request $request, $id) {

        $target = Order::find($id);

        OrderDetails::where('order_id', $id)->delete();

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
        $dependencyArr = [
            'OrderDetails' => 'order_id',
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('admin/order' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Session::flash('error', __('label.NEW_ORDER_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.NEW_ORDER_COULD_NOT_BE_DELETED'));
        }
        return redirect('admin/retailerDistributorOrder' . $pageNumber);
    }

}
