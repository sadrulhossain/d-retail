<?php

namespace App\Http\Controllers;

use Validator;
use App\SrToRetailer;
use App\WarehouseToSr;
use App\WarehouseToRetailer;
use App\Retailer;
use Response;
use Auth;
use Helper;
use DB;
use Redirect;
use Session;
use App\User;
use Illuminate\Http\Request;

class SrToRetailerController extends Controller {

    public function index(Request $request) {

        $retailerArr = $srRelateToRetailer = [];
        $inactiveRetailerArr = [];
        $retailerWiseSrArr = [];
        $otherRetailerWhArr = [];


        $srList = ['0' => __('label.SELECT_SR')] + User::where('status', '1')
                        ->where('group_id', 14)
                        ->select(DB::raw('CONCAT(first_name, " ", last_name) as name'), 'id')
                        ->orderBy('first_name', 'asc')
                        ->pluck('name', 'id')
                        ->toArray();


        if (!empty($request->sr_id)) {
            $retailerArr = WarehouseToRetailer::join('retailer', 'retailer.id', 'warehouse_to_retailer.retailer_id')
                            ->join('warehouse_to_sr', 'warehouse_to_sr.warehouse_id', 'warehouse_to_retailer.warehouse_id')
                            ->where('warehouse_to_sr.sr_id', $request->sr_id)->where('retailer.status', '1')
                            ->orderBy('retailer.order', 'asc')
                            ->select('retailer.name', 'retailer.id')->get();

            $inactiveRetailerArr = Retailer::where('status', '2')->where('approval_status', '1')
                            ->orderBy('order', 'asc')
                            ->pluck('id')->toArray();

            $relatedRetailerArr = SrToRetailer::select('sr_to_retailer.retailer_id')
                    ->where('sr_to_retailer.sr_id', $request->sr_id)
                    ->get();

            if (!$relatedRetailerArr->isEmpty()) {
                foreach ($relatedRetailerArr as $relatedRetailer) {
                    $srRelateToRetailer[$relatedRetailer->retailer_id] = $relatedRetailer->retailer_id;
                }
            }


            $otherRetailerWhInfo = SrToRetailer::join('users', 'users.id', 'sr_to_retailer.sr_id')
                            ->select('sr_to_retailer.sr_id', 'sr_to_retailer.retailer_id', DB::raw('CONCAT(users.first_name, " ", users.last_name) AS sr'))
                            ->where('sr_to_retailer.sr_id', '!=', $request->sr_id)->get();

            if (!$otherRetailerWhInfo->isEmpty()) {
                foreach ($otherRetailerWhInfo as $data) {
                    $otherRetailerWhArr[$data->retailer_id] = $data->sr;
                }
            }
        }

        return view('srToRetailer.index')->with(compact('srList', 'retailerArr', 'srRelateToRetailer', 'inactiveRetailerArr', 'request', 'otherRetailerWhArr'));
    }

    public function getRetailerToRelate(Request $request) {

        $retailerArr = $srRelateToRetailer = [];

        $retailerArr = WarehouseToRetailer::join('retailer', 'retailer.id', 'warehouse_to_retailer.retailer_id')
                        ->join('warehouse_to_sr', 'warehouse_to_sr.warehouse_id', 'warehouse_to_retailer.warehouse_id')
                        ->where('warehouse_to_sr.sr_id', $request->sr_id)->where('retailer.status', '1')
                        ->orderBy('retailer.order', 'asc')
                        ->select('retailer.name', 'retailer.id')->get();

        $inactiveRetailerArr = Retailer::where('status', '2')->where('approval_status', '1')
                        ->orderBy('order', 'asc')
                        ->pluck('id')->toArray();

        $relatedRetailerArr = SrToRetailer::select('sr_to_retailer.retailer_id')
                ->where('sr_to_retailer.sr_id', $request->sr_id)
                ->get();



        if (!$relatedRetailerArr->isEmpty()) {
            foreach ($relatedRetailerArr as $relatedRetailer) {
                $srRelateToRetailer[$relatedRetailer->retailer_id] = $relatedRetailer->retailer_id;
            }
        }

        $otherRetailerWhInfo = SrToRetailer::join('users', 'users.id', 'sr_to_retailer.sr_id')
                        ->select('sr_to_retailer.sr_id', 'sr_to_retailer.retailer_id', DB::raw('CONCAT(users.first_name, " ", users.last_name) AS sr'))
                        ->where('sr_to_retailer.sr_id', '!=', $request->sr_id)->get();

        $otherRetailerWhArr = [];

        if (!$otherRetailerWhInfo->isEmpty()) {
            foreach ($otherRetailerWhInfo as $data) {
                $otherRetailerWhArr[$data->retailer_id] = $data->sr;
            }
        }


        $view = view('srToRetailer.showRetailer', compact('retailerArr', 'srRelateToRetailer', 'request', 'inactiveRetailerArr', 'otherRetailerWhArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getRelatedRetailer(Request $request) {
        $warehouse = WarehouseToSr::join('warehouse', 'warehouse.id', 'warehouse_to_sr.warehouse_id')
                ->select('warehouse.name')
                    ->where('warehouse_to_sr.sr_id', $request->sr_id)
                    ->first();
       
        $sr = User::where('id', $request->sr_id)->select('id', DB::raw('CONCAT(first_name, " ", last_name) AS full_name'))
                ->first();

        $relatedRetailerArr = SrToRetailer::select('sr_to_retailer.retailer_id')
                ->where('sr_to_retailer.sr_id', $request->sr_id)
                ->get();

        $srRelateToRetailer = [];

        if (!$relatedRetailerArr->isEmpty()) {
            foreach ($relatedRetailerArr as $relatedRetailer) {
                $srRelateToRetailer[$relatedRetailer->retailer_id] = $relatedRetailer->retailer_id;
            }
        }

        $retailerArr = [];
        if (isset($srRelateToRetailer)) {
            $retailerArr = Retailer::where('status', '1')->where('approval_status', '1')
                            ->orderBy('order', 'asc')
                            ->select('name', 'id')
                            ->whereIn('id', $srRelateToRetailer)
                            ->get()->toArray();
        }

        $inactiveRetailerArr = Retailer::where('status', '1')->where('approval_status', '1')->pluck('id')->toArray();

        $view = view('srToRetailer.showRelatedRetailer', compact('sr', 'retailerArr'
                        , 'srRelateToRetailer', 'request'
                        , 'inactiveRetailerArr','warehouse'))->render();

        return response()->json(['html' => $view]);
    }

    public function relateSrToRetailer(Request $request) {
        $rules = [
            'sr_id' => 'required|not_in:0',
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
                $target[$i]['sr_id'] = $request->sr_id;
                $target[$i]['retailer_id'] = $retailerId;
                $target[$i]['created_by'] = Auth::user()->id;
                $target[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

        //delete before inserted 
        SrToRetailer::where('sr_id', $request->sr_id)->delete();

        if (SrToRetailer::insert($target)) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.RETAILER_HAS_BEEN_RELATED_TO_SR_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_RELATE_RETAILER_TO_SR')), 401);
        }
    }

}
