<?php

namespace App\Http\Controllers;

use Validator;
use App\Supplier;
use App\Product;
use App\Retailer;
use App\WarehouseToRetailer;
use App\Warehouse;
use Response;
use Auth;
use Helper;
use DB;
use Redirect;
use Session;
use App\User;
use Illuminate\Http\Request;

class WarehouseToRetailerController extends Controller {

    public function index(Request $request) {
        $warehouseList = ['0' => __('label.SELECT_WAREHOUSE')] + Warehouse::where('status', '1')
                        ->where('allowed_for_central_warehouse', '0')
                        ->orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();

        $retailerArr = $warehouseRelateToRetailer = [];
        $inactiveRetailerArr = [];
        $retailerWisewarehouseArr = [];
        $otherRetailerWhArr = [];

        if (!empty($request->warehouse_id)) {
            $retailerArr = Retailer::where('status', '1')->where('approval_status', '1')
                            ->orderBy('order', 'asc')
                            ->select('name', 'id')
                            ->get()->toArray();

            $inactiveRetailerArr = Retailer::where('status', '2')->where('approval_status', '1')
                            ->orderBy('name', 'asc')
                            ->pluck('id')->toArray();

            $relatedRetailerArr = WarehouseToRetailer::select('warehouse_to_retailer.retailer_id')
                    ->where('warehouse_to_retailer.warehouse_id', $request->warehouse_id)
                    ->get();

            if (!$relatedRetailerArr->isEmpty()) {
                foreach ($relatedRetailerArr as $relatedRetailer) {
                    $warehouseRelateToRetailer[$relatedRetailer->retailer_id] = $relatedRetailer->retailer_id;
                }
            }


            $otherRetailerWhInfo = WarehouseToRetailer::join('warehouse', 'warehouse.id', 'warehouse_to_retailer.warehouse_id')
                            ->select('warehouse_to_retailer.warehouse_id', 'warehouse_to_retailer.retailer_id', 'warehouse.name as warehouse')
                            ->where('warehouse_to_retailer.warehouse_id', '!=', $request->warehouse_id)->get();

            if (!$otherRetailerWhInfo->isEmpty()) {
                foreach ($otherRetailerWhInfo as $data) {
                    $otherRetailerWhArr[$data->retailer_id] = $data->warehouse;
                }
            }
        }

        return view('warehouseToRetailer.index')->with(compact('warehouseList', 'retailerArr', 'warehouseRelateToRetailer', 'inactiveRetailerArr', 'request', 'otherRetailerWhArr'));
    }

    public function getRetailerToRelate(Request $request) {

        $retailerArr = $warehouseRelateToRetailer = [];

        $retailerArr = Retailer::orderBy('order', 'asc')
                ->select('name', 'id')
                ->get();

        $inactiveRetailerArr = Retailer::where('status', '2')->where('approval_status', '1')
                        ->orderBy('order', 'asc')
                        ->pluck('id')->toArray();

        $relatedRetailerArr = WarehouseToRetailer::select('warehouse_to_retailer.retailer_id')
                ->where('warehouse_to_retailer.warehouse_id', $request->warehouse_id)
                ->get();



        if (!$relatedRetailerArr->isEmpty()) {
            foreach ($relatedRetailerArr as $relatedRetailer) {
                $warehouseRelateToRetailer[$relatedRetailer->retailer_id] = $relatedRetailer->retailer_id;
            }
        }

        $otherRetailerWhInfo = WarehouseToRetailer::join('warehouse', 'warehouse.id', 'warehouse_to_retailer.warehouse_id')
                        ->select('warehouse_to_retailer.warehouse_id', 'warehouse_to_retailer.retailer_id', 'warehouse.name as warehouse')
                        ->where('warehouse_to_retailer.warehouse_id', '!=', $request->warehouse_id)->get();

        $otherRetailerWhArr = [];

        if (!$otherRetailerWhInfo->isEmpty()) {
            foreach ($otherRetailerWhInfo as $data) {
                $otherRetailerWhArr[$data->retailer_id] = $data->warehouse;
            }
        }

        $view = view('warehouseToRetailer.showRetailer', compact('retailerArr', 'warehouseRelateToRetailer', 'request', 'inactiveRetailerArr', 'otherRetailerWhArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getRelatedRetailer(Request $request) {

        $warehouse = Warehouse::where('id', $request->warehouse_id)
                ->first();

        $relatedRetailerArr = WarehouseToRetailer::select('warehouse_to_retailer.retailer_id')
                ->where('warehouse_to_retailer.warehouse_id', $request->warehouse_id)
                ->get();

        $warehouseRelateToRetailer = [];

        if (!$relatedRetailerArr->isEmpty()) {
            foreach ($relatedRetailerArr as $relatedRetailer) {
                $warehouseRelateToRetailer[$relatedRetailer->retailer_id] = $relatedRetailer->retailer_id;
            }
        }

        $retailerArr = [];
        if (isset($warehouseRelateToRetailer)) {
            $retailerArr = Retailer::where('status', '1')->where('approval_status', '1')
                            ->orderBy('order', 'asc')
                            ->select('name', 'id')
                            ->whereIn('id', $warehouseRelateToRetailer)
                            ->get()->toArray();

            $inactiveRetailerArr = Retailer::where('status', '2')->where('approval_status', '1')
                            ->orderBy('order', 'asc')
                            ->pluck('id')->toArray();

            $view = view('warehouseToRetailer.showRelatedRetailer', compact('warehouse', 'retailerArr'
                            , 'warehouseRelateToRetailer', 'request'
                            , 'inactiveRetailerArr'))->render();

            return response()->json(['html' => $view]);
        }
    }

    function relateWarehouseToRetailer(Request $request) {
        $rules = [
            'warehouse_id' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        if (empty($request->retailer)) {
            return Response::json(array('success' => false, 'message' => __('label.PLEASE_CHOOSE_ATLEAST_ONE_RETAILER')), 401);
        }

        $i = 0;
        $target = [];
        if (!empty($request->retailer)) {
            foreach ($request->retailer as $retailerId) {
                //data entry to product pricing table
                $target[$i]['warehouse_id'] = $request->warehouse_id;
                $target[$i]['retailer_id'] = $retailerId;
                $target[$i]['created_by'] = Auth::user()->id;
                $target[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

        //delete before inserted 
        WarehouseToRetailer::where('warehouse_id', $request->warehouse_id)->delete();

        if (WarehouseToRetailer::insert($target)) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.RETAILER_HAS_BEEN_RELATED_TO_WAREHOUSE_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_RELATE_RETAILER_TO_WAREHOUSE')), 401);
        }
    }

}
