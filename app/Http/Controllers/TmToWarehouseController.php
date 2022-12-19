<?php

namespace App\Http\Controllers;

use Validator;
use App\Supplier;
use App\Product;
use App\TmToWarehouse;
use App\Warehouse;
use Response;
use Auth;
use Helper;
use DB;
use Redirect;
use Session;
use App\User;
use Illuminate\Http\Request;

class TmToWarehouseController extends Controller {

    public function index(Request $request) {
        $tmList = ['0' => __('label.SELECT_TM')] + User::where('status', '1')
                        ->orderBy('first_name', 'asc')
                        ->where('group_id', 15)
                        ->select(DB::raw('CONCAT(first_name," ", last_name) AS full_name'), 'id')
                        ->pluck('full_name', 'id')->toArray();
        
        $warehouseArr = $tmRelateToWarehouse = $warehouseRelateToTm = [];
        $inactiveWarehouseArr = [];
        $warehouseWiseTmArr = $otherTmWhArr = [];

        if (!empty($request->tm_id)) {
            $warehouseArr = Warehouse::where('allowed_for_central_warehouse', '0')->select('warehouse.id', 'warehouse.name')
                            ->orderBy('order', 'asc')->get()->toArray();


            $inactiveWarehouseArr = Warehouse::where('status', '2')
                            ->where('allowed_for_central_warehouse', '0')
                            ->pluck('id')->toArray();

            $relatedWarehouseArr = TmToWarehouse::select('tm_to_warehouse.warehouse_id')
                    ->where('tm_to_warehouse.tm_id', $request->tm_id)
                    ->get();

            if (!$relatedWarehouseArr->isEmpty()) {
                foreach ($relatedWarehouseArr as $relatedWarehouse) {
                    $warehouseRelateToTm[$relatedWarehouse->warehouse_id] = $relatedWarehouse->warehouse_id;
                }
            }

            $otherTmWhInfo = TmToWarehouse::join('users', 'users.id', 'tm_to_warehouse.tm_id')
                            ->select('tm_to_warehouse.tm_id as tm_id', 'tm_to_warehouse.warehouse_id as warehouse_id', DB::raw('CONCAT(users.first_name, users.last_name) AS tm'))
                            ->where('tm_to_warehouse.tm_id', '!=', $request->tm_id)->get();

            if (!$otherTmWhInfo->isEmpty()) {
                foreach ($otherTmWhInfo as $data) {
                    $otherTmWhArr[$data->warehouse_id] = $data->tm;
                }
            }
        }

        return view('tmToWarehouse.index')->with(compact('tmList', 'warehouseArr', 'warehouseRelateToTm', 'inactiveWarehouseArr', 'otherTmWhArr', 'request'));
    }

    public function getWarehouseToRelate(Request $request) {

        $warehouseArr = $warehouseRelateToTm = [];
        $warehouseArr = Warehouse::where('allowed_for_central_warehouse', '0')->select('id', 'name')
                        ->orderBy('order', 'asc')->get();
        $inactiveWarehouseArr = Warehouse::where('status', '2')
                        ->where('allowed_for_central_warehouse', '0')
                        ->pluck('id')->toArray();
        $relatedWarehouseArr = TmToWarehouse::select('tm_to_warehouse.warehouse_id')
                ->where('tm_to_warehouse.tm_id', $request->tm_id)
                ->get();

        $otherTmWhArr = [];

        if (!$relatedWarehouseArr->isEmpty()) {
            foreach ($relatedWarehouseArr as $relatedWarehouse) {
                $warehouseRelateToTm[$relatedWarehouse->warehouse_id] = $relatedWarehouse->warehouse_id;
            }
        }

        $otherTmWhInfo = TmToWarehouse::join('users', 'users.id', 'tm_to_warehouse.tm_id')
                        ->select('tm_to_warehouse.tm_id as tm_id', 'tm_to_warehouse.warehouse_id as warehouse_id', DB::raw('CONCAT(users.first_name, users.last_name) AS tm'))
                        ->where('tm_to_warehouse.tm_id', '!=', $request->tm_id)->get();

        if (!$otherTmWhInfo->isEmpty()) {
            foreach ($otherTmWhInfo as $data) {
                $otherTmWhArr[$data->warehouse_id] = $data->tm;
            }
        }

        $view = view('tmToWarehouse.showWarehouses', compact('warehouseArr', 'warehouseRelateToTm', 'request', 'inactiveWarehouseArr', 'otherTmWhArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getRelatedWarehouse(Request $request) {
        $tm = User::where('id', $request->tm_id)
                ->select(DB::raw('CONCAT(first_name, " ", last_name) AS full_name'), 'id')
                ->first();

        $relatedWarehouseArr = TmToWarehouse::select('tm_to_warehouse.warehouse_id')
                ->where('tm_to_warehouse.tm_id', $request->tm_id)
                ->get();

        $warehouseRelateToTm = [];

        if (!$relatedWarehouseArr->isEmpty()) {
            foreach ($relatedWarehouseArr as $relatedWarehouse) {
                $warehouseRelateToTm[$relatedWarehouse->warehouse_id] = $relatedWarehouse->warehouse_id;
            }
        }

        $warehouseArr = [];
        if (isset($warehouseRelateToTm)) {
            $warehouseArr = Warehouse::leftJoin('division', 'division.id', 'warehouse.division_id')
                            ->leftJoin('district', 'district.id', 'warehouse.district_id')
                            ->leftJoin('thana', 'thana.id', 'warehouse.thana_id')
                            ->whereIn('warehouse.id', $warehouseRelateToTm)
                            ->select('warehouse.name as warehouse_name', 'warehouse.id as id', 'warehouse.address as address', 'division.name as division'
                                    , 'district.name as district', 'thana.name as thana')
                            ->where('warehouse.status', '1')
                            ->orderBy('warehouse.name', 'asc')->get()->toArray();
        }

        $inactiveWarehouseArr = Warehouse::where('status', '2')->pluck('id')->toArray();

        $view = view('tmToWarehouse.showRelatedWarehouses', compact('tm', 'warehouseArr'
                        , 'relatedWarehouseArr', 'warehouseRelateToTm', 'request'
                        , 'inactiveWarehouseArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function relateTmToWarehouse(Request $request) {
        $rules = [
            'tm_id' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        if (empty($request->warehouse)) {
            return Response::json(array('success' => false, 'message' => __('label.PLEASE_CHOOSE_ATLEAST_ONE_WAREHOSE')), 401);
        }

        $i = 0;
        $target = [];
        if (!empty($request->warehouse)) {
            foreach ($request->warehouse as $warehouseId) {
                //data entry to product pricing table
                $target[$i]['tm_id'] = $request->tm_id;
                $target[$i]['warehouse_id'] = $warehouseId;
                $target[$i]['created_by'] = Auth::user()->id;
                $target[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

        //delete before inserted 
        TmToWarehouse::where('tm_id', $request->tm_id)->delete();

        if (TmToWarehouse::insert($target)) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.WAREHOUSE_HAS_BEEN_RELATED_TO_TM_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_RELATE_WAREHOUSE_TO_TM')), 401);
        }
    }

}
