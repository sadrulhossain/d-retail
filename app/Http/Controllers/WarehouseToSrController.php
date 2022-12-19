<?php

namespace App\Http\Controllers;

use Validator;
use App\Supplier;
use App\Product;
use App\WarehouseToSr;
use App\Warehouse;
use Response;
use Auth;
use Helper;
use DB;
use Redirect;
use Session;
use App\User;
use Illuminate\Http\Request;

class WarehouseToSrController extends Controller {

    public function index(Request $request) {
        $warehouseList = ['0' => __('label.SELECT_WAREHOUSE')] + Warehouse::where('status', '1')
                        ->where('allowed_for_central_warehouse', '0')
                        ->orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();

        $srArr = $warehouseRelateToSr = [];
        $inactiveSrArr = [];
        $srWisewarehouseArr = [];
        $otherSrWhArr = [];

        if (!empty($request->warehouse_id)) {
            $srArr = User::where('status', '1')
                            ->orderBy('first_name', 'asc')
                            ->where('group_id', 14)
                            ->select(DB::raw('CONCAT(first_name, " ", last_name) AS full_name'), 'id')
                            ->get()->toArray();

            $inactiveSrArr = User::where('status', '2')
                            ->orderBy('first_name', 'asc')
                            ->where('group_id', 14)->pluck('id')->toArray();

            $relatedSrArr = WarehouseToSr::select('warehouse_to_sr.sr_id')
                    ->where('warehouse_to_sr.warehouse_id', $request->warehouse_id)
                    ->get();

            if (!$relatedSrArr->isEmpty()) {
                foreach ($relatedSrArr as $relatedSr) {
                    $warehouseRelateToSr[$relatedSr->sr_id] = $relatedSr->sr_id;
                }
            }


            $otherSrWhInfo = WarehouseToSr::join('warehouse', 'warehouse.id', 'warehouse_to_sr.warehouse_id')
                            ->select('warehouse_to_sr.warehouse_id', 'warehouse_to_sr.sr_id', 'warehouse.name as warehouse')
                            ->where('warehouse_to_sr.warehouse_id', '!=', $request->warehouse_id)->get();
            
            if (!$otherSrWhInfo->isEmpty()) {
                foreach ($otherSrWhInfo as $data) {
                    $otherSrWhArr[$data->sr_id] = $data->warehouse;
                }
            }
        }

        return view('warehouseToSR.index')->with(compact('warehouseList', 'srArr', 'warehouseRelateToSr', 'inactiveSrArr', 'request', 'otherSrWhArr'));
    }

    public function getSrToRelate(Request $request) {

        $srArr = $warehouseRelateToSr = [];

        $srArr = User::where('status', '1')
                        ->orderBy('first_name', 'asc')
                        ->where('group_id', 14)
                        ->select(DB::raw('CONCAT(first_name, " ", last_name) AS full_name'), 'id')->get();

        $inactiveSrArr = User::where('status', '2')
                        ->orderBy('first_name', 'asc')
                        ->where('group_id', 14)->pluck('id')->toArray();

        $relatedSrArr = WarehouseToSr::select('warehouse_to_sr.sr_id')
                ->where('warehouse_to_sr.warehouse_id', $request->warehouse_id)
                ->get();



        if (!$relatedSrArr->isEmpty()) {
            foreach ($relatedSrArr as $relatedSr) {
                $warehouseRelateToSr[$relatedSr->sr_id] = $relatedSr->sr_id;
            }
        }

        $otherSrWhInfo = WarehouseToSr::join('warehouse', 'warehouse.id', 'warehouse_to_sr.warehouse_id')
                        ->select('warehouse_to_sr.warehouse_id', 'warehouse_to_sr.sr_id', 'warehouse.name as warehouse')
                        ->where('warehouse_to_sr.warehouse_id', '!=', $request->warehouse_id)->get();

        $otherSrWhArr = [];

        if (!$otherSrWhInfo->isEmpty()) {
            foreach ($otherSrWhInfo as $data) {
                $otherSrWhArr[$data->sr_id] = $data->warehouse;
            }
        }


        $view = view('warehouseToSR.showSR', compact('srArr', 'warehouseRelateToSr', 'request', 'inactiveSrArr', 'otherSrWhArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getRelatedSr(Request $request) {

        $warehouse = Warehouse::where('id', $request->warehouse_id)
                ->first();

        $relatedSrArr = WarehouseToSr::select('warehouse_to_sr.sr_id')
                ->where('warehouse_to_sr.warehouse_id', $request->warehouse_id)
                ->get();

        $warehouseRelateToSr = [];

        if (!$relatedSrArr->isEmpty()) {
            foreach ($relatedSrArr as $relatedSr) {
                $warehouseRelateToSr[$relatedSr->sr_id] = $relatedSr->sr_id;
            }
        }

        $srArr = [];
        if (isset($warehouseRelateToSr)) {
            $srArr = User::orderBy('first_name', 'asc')
                            ->whereIn('id', $warehouseRelateToSr)
                            ->select(DB::raw('CONCAT(first_name, " ", last_name) AS full_name'), 'id')
                            ->get()->toArray();
        }

        $inactiveSrArr = User::where('status', '2')
                        ->orderBy('first_name', 'asc')
                        ->where('group_id', 14)->pluck('id')->toArray();

        $view = view('warehouseToSR.showRelatedSR', compact('warehouse', 'srArr'
                        , 'warehouseRelateToSr', 'request'
                        , 'inactiveSrArr'))->render();

        return response()->json(['html' => $view]);
    }

    public function relateWarehouseToSr(Request $request) {
        $rules = [
            'warehouse_id' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        
        if (empty($request->sr)) {
            return Response::json(array('success' => false, 'message' => __('label.PLEASE_CHOOSE_ATLEAST_ONE_SR')), 401);
        }

        $i = 0;
        $target = [];
        if (!empty($request->sr)) {
            foreach ($request->sr as $srId) {
                //data entry to product pricing table
                $target[$i]['warehouse_id'] = $request->warehouse_id;
                $target[$i]['sr_id'] = $srId;
                $target[$i]['created_by'] = Auth::user()->id;
                $target[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

        //delete before inserted 
        WarehouseToSr::where('warehouse_id', $request->warehouse_id)->delete();

        if (WarehouseToSr::insert($target)) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.SR_HAS_BEEN_RELATED_TO_WAREHOUSE_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_RELATE_SR_TO_WAREHOUSE')), 401);
        }
    }

}
