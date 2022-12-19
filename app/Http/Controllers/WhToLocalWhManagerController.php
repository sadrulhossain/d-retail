<?php

namespace App\Http\Controllers;

use Validator;
use App\Supplier;
use App\Product;
use App\WhToLocalWhManager;
use App\Warehouse;
use Response;
use Auth;
use Helper;
use DB;
use Redirect;
use Session;
use App\User;
use Illuminate\Http\Request;

class WhToLocalWhManagerController extends Controller {

    public function index(Request $request) {
        $warehouseList = Warehouse::where('status', '1')->where('allowed_for_central_warehouse', '0')
                        ->orderBy('order', 'asc')->pluck('name', 'id')->toArray();

        $lwmList = ['0' => __('label.SELECT_LWM_OPT')] + User::where('status', '1')->where('group_id', 12)
                        ->where('status', '1')
                        ->select(DB::raw('CONCAT(first_name, " ", last_name) AS full_name'), 'id')
                        ->orderBy('first_name', 'asc')
                        ->pluck('full_name', 'id')->toArray();

        $relatedLwmArr = WhToLocalWhManager::pluck('lwm_id', 'warehouse_id')->toArray();

        return view('whToLocalWhManager.index')->with(compact('warehouseList', 'lwmList', 'relatedLwmArr'
                                , 'request'));
    }

    public function relateWhToLWM(Request $request) {
        $whArr = $request->warehouse_id;
        $whNameArr = $request->warehouse;
        $lwmArr = $request->lwm_id;

        $rules = $messages = [];
        if (!empty($whArr)) {
            foreach ($whArr as $whId => $whId) {
                $rules['lwm_id.' . $whId] = 'required|not_in:0';
                $messages['lwm_id.' . $whId . '.not_in'] = __('label.PLEASE_CHOOSE_WH_MANAGER_FOR_WAREHOUSE', ['wh' => $whNameArr[$whId] ?? '']);
            }
        } else {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => __('label.PLEASE_CHOOSE_ATLEAST_ONE_WAREHOUSE')), 401);
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $i = 0;
        $target = [];
        if (!empty($whArr)) {
            foreach ($whArr as $whId => $whId) {
                $target[$i]['warehouse_id'] = $whId;
                $target[$i]['lwm_id'] = $lwmArr[$whId];
                $target[$i]['created_by'] = Auth::user()->id;
                $target[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

        //delete before inserted 
        WhToLocalWhManager::truncate();

        if (WhToLocalWhManager::insert($target)) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.LWM_HAS_BEEN_RELATED_TO_WAREHOUSE_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_RELATE_LWM_TO_WAREHOUSE')), 401);
        }
    }

    public function showRelatedLWhManager() {
        $warehouseList = Warehouse::where('status', '1')->where('allowed_for_central_warehouse', '0')
                        ->orderBy('order', 'asc')->pluck('name', 'id')->toArray();

        $lwmList = ['0' => __('label.SELECT_LWM_OPT')] + User::where('status', '1')->where('group_id', 12)
                        ->where('status', '1')
                        ->select(DB::raw('CONCAT(first_name, " ", last_name) AS full_name'), 'id')
                        ->orderBy('first_name', 'asc')
                        ->pluck('full_name', 'id')->toArray();
        $relatedLwmArr = WhToLocalWhManager::pluck('lwm_id', 'warehouse_id')->toArray();
        
        $view = view('whToLocalWhManager.showRelatedThanaWhManager')->with(compact('warehouseList', 'lwmList', 'relatedLwmArr'))->render();
        return response()->json(['html' => $view]);
    }

}
