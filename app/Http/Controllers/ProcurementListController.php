<?php

namespace App\Http\Controllers;

use Validator;
use App\ProductSKUCode;
use App\ProductCheckInMaster;
use App\Product;
use App\Supplier;
use App\ProcurementMaster;
use App\ProcurementDetails;
use App\WorkOrderMaster;
use App\WorkOrderDetails;
use App\CompanyInformation;
use Illuminate\Http\Request;
use DB;
use Auth;
use PDF;
use Session;
use Helper;
use Response;
use Redirect;

class ProcurementListController extends Controller {

    public function index(Request $request) {


        $procurementMasterId = WorkOrderMaster::pluck('procurement_master_id', 'procurement_master_id')->toArray();

//        echo "<pre>";
//        print_r($workOrderReference);
//        exit;

        $targetArr = ProcurementMaster::join('users', 'users.id', '=', 'procurement_master.created_by');
        if (!empty($request->reference)) {
            $searchRef = $request->reference;
            $targetArr->where(function ($query) use ($searchRef) {
                $query->where('procurement_master.reference', 'LIKE', '%' . $searchRef . '%');
            });
        }

        $reqFrom = !empty($request->req_date_from) ? date("Y-m-d", strtotime($request->req_date_from)) : '';
        $reqTo = !empty($request->req_date_to) ? date("Y-m-d", strtotime($request->req_date_to)) : '';

        if (!empty($reqFrom) && !empty($reqTo)) {
            $targetArr = $targetArr->whereBetween('procurement_master.req_date', [$reqFrom, $reqTo]);
        } else {

            if (!empty($reqFrom)) {
                $targetArr = $targetArr->where('procurement_master.req_date', '>=', $reqFrom);
            }
            if (!empty($reqTo)) {
                $targetArr = $targetArr->where('procurement_master.req_date', '<=', $reqTo);
            }
        }



        $targetArr = $targetArr->select('procurement_master.*', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as user_full_name"))
                        ->orderBy('procurement_master.req_date', 'desc')->paginate(Session::get('paginatorCount'));

//        echo "<pre>";
//        print_r($targetArr);
//        exit;


        return view('procurement.procurementList', compact('targetArr', 'procurementMasterId'));
    }

    public function filter(Request $request) {

        $rules = $messages = [];

        $url = 'reference=' . urlencode($request->reference) . '&req_date_from=' . $request->req_date_from . '&req_date_to=' . $request->req_date_to;

        return Redirect::to('admin/procurementList?' . $url);
    }

    public function approve(Request $request) {
        $target = ProcurementMaster::find($request->procurement_id);

        $target->approval_status = '1';
        $target->approved_at = date('Y-m-d H:i:s');
        $target->approved_by = Auth::user()->id;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PROCUREMENT_APPROVED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.PROCUREMENT_COULD_NOT_BE_APPROVED')), 401);
        }
    }

    public function deny(Request $request) {

        $proDetailsIdArr = ProcurementDetails::where('procurement_master_id', $request->procurement_id)
                        ->pluck('id', 'id')->toArray();

        DB::beginTransaction();
        try {
            if (ProcurementMaster::where('id', $request->procurement_id)->delete()) {
                ProcurementDetails::whereIn('id', $proDetailsIdArr)->delete();
            }

            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.PROCUREMENT_DENIED_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.PROCUREMENT_COULD_NOT_BE_DENIED')], 401);
        }
    }

    public function getProcurementModal(Request $request) {

        $procurementInfo = ProcurementMaster::where('id', $request->procurement_id)->first();

        $targetArr = ProcurementDetails::where('procurement_master_id', $request->procurement_id)
                        ->select('procurement_details.*')->get();

//        echo "<pre>";
//        print_r($targetArr);
//        exit;


        $view = view('procurement.procurementDetails', compact('procurementInfo', 'targetArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function workOrder(Request $request, $id) {
        $id = $id;
        $issueDate = date('Y-m-d');
        $workOrderRe = WorkOrderMaster::select(DB::raw('count(id) as total'))->where('issue_date', $issueDate)->first();
        $workOrderRefArr = $workOrderRe->total + 1;

        $reference = 'WO-' . date('ymd', strtotime($issueDate)) . str_pad($workOrderRefArr, 4, '0', STR_PAD_LEFT);

        $supplierArr = ['0' => __('label.SELECT_SUPPLIER_OPT')] + Supplier::pluck('name', 'id')->toArray();

        $procurement = ProcurementDetails::where('procurement_master_id', $id)->select('procurement_details.*')->get();

        $skuList = ProcurementDetails::where('procurement_master_id', $id)->select('sku_id', 'sku')->get();

        $procurementArr = [];
        if (!empty($procurement)) {
            foreach ($procurement as $info) {
                $procurementArr[$info->sku_id]['sku'] = $info->sku;
                $procurementArr[$info->sku_id]['quantity'] = $info->quantity;
                $procurementArr[$info->sku_id]['unit_price'] = $info->unit_price;
                $procurementArr[$info->sku_id]['total_price'] = $info->total_price;
            }
        }

//          echo "<pre>";
//          print_r($skuList);
//          exit;

        return view('procurement.workOrder', compact('id', 'supplierArr', 'procurementArr', 'skuList', 'reference', 'issueDate'));
    }

    public function workOrderInsert(Request $request) {
//        echo "<pre>";
//        print_r($request->all());
//        exit;

        $skuName = $request->sku_name;

        $rules = $messages = [];
        if (!empty($request->sku)) {
            foreach ($request->sku as $skuId => $skuId) {
                $rules['supplier'] = 'required|not_in:0';
                $rules['issue_date'] = 'required';
                $rules['quantity.' . $skuId] = 'required';
                $messages['supplier' . 'required'] = __('label.SUPPLIER_IS_REQUIRED_WORKORDER');
                $messages['issue_date' . 'required'] = __('label.ISSUE_DATE_IS_REQUIRED');
                $messages['quantity.' . $skuId . '.required'] = __('label.QUANITY_IS_REQUIRED_FOR_SKU', ['sku_name' => $skuName[$skuId]]);
            }
        } else {
            return Response::json(array('success' => false, 'heading' => _('label.VALIDATION_ERROR'), 'message' => __('label.PLEASE_SET_WO_FOR_AT_LEAST_ONE_SKU')), 401);
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(['success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()], 400);
        }


        $target = new WorkOrderMaster;
        $target->reference = $request->reference;
        $target->procurement_master_id = $request->procurement_master_id;
        $target->supplier_id = $request->supplier;
        $target->issue_date = !empty($request->issue_date) ? Helper::dateFormatConvert($request->issue_date) : '0000-00-00';
        $target->grand_total = $request->grand_total;
        $target->updated_at = date('Y-m-d H:i:s');
        $target->updated_by = Auth::user()->id;

        if (!empty($request->sku)) {
            DB::beginTransaction();
            try {
                if ($target->save()) {
                    $data = [];
                    $i = 0;

                    if (!empty($request->sku)) {
                        foreach ($request->sku as $skuId => $skuId) {
                            $data[$i]['work_order_master_id'] = $target->id;
                            $data[$i]['sku_id'] = $skuId;
                            $data[$i]['quantity'] = $request->quantity[$skuId];
                            $data[$i]['unit_price'] = $request->unit_price[$skuId];
                            $data[$i]['total_price'] = $request->total_price[$skuId];
                            $i++;
                        }
                    }
                    WorkOrderDetails::insert($data);
                    DB::commit();
                    return Response::json(['success' => true], 200);
                }
            } catch (\Throwable $e) {
                DB::rollback();
                return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
            }
        } else {
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.YOU_HAVE_NO_NEW_PRODUCT_FOR_SAVE')], 401);
        }
    }

    public function getWorkOrderModal(Request $request) {

        $targetArr = WorkOrderDetails::join('work_order_master', 'work_order_master.id', 'work_order_details.work_order_master_id')
                        ->where('work_order_master.procurement_master_id', $request->procurement_master_id)
                        ->select('work_order_details.*', 'work_order_master.*')->get()->toArray();

        $info = WorkOrderMaster::where('procurement_master_id', $request->procurement_master_id)->first();
        $supplierList = Supplier::pluck('name', 'id')->toArray();
        $skuList = ProductSKUCode::pluck('sku', 'id')->toArray();

//        echo "<pre>";
//        print_r($skuList);
//        exit;

        $view = view('procurement.workOrderModal', compact('targetArr', 'info', 'supplierList', 'skuList'))->render();
        return response()->json(['html' => $view]);
    }

    public function workOrderPrint(Request $request) {
//        print_r($request->workorder_master_id);
//        exit;

        $workOrderMasterId = $request->workorder_master_id;

        $companyInfo = CompanyInformation::first();

        $targetArr = WorkOrderDetails::join('work_order_master', 'work_order_master.id', 'work_order_details.work_order_master_id')
                        ->where('work_order_details.work_order_master_id', $workOrderMasterId)
                        ->select('work_order_details.*', 'work_order_master.*')->get()->toArray();

        $info = WorkOrderMaster::where('id', $request->workorder_master_id)->first();
        $supplierList = Supplier::pluck('name', 'id')->toArray();
        $skuList = ProductSKUCode::pluck('sku', 'id')->toArray();

        $companyInfo = CompanyInformation::first();
        $companyNumber = '';
        if (!empty($companyInfo)) {
            $phoneNumberDecode = json_decode($companyInfo->phone_number, true);
            $companyNumber = Helper::arrayTostring($phoneNumberDecode);
        }
        $imagePath = '/public/img/small_logo.png';

        return view('procurement.print.index', compact('targetArr', 'info', 'supplierList', 'skuList'
                        , 'companyInfo', 'companyNumber', 'imagePath'));
    }

    public function workOrderPdf(Request $request) {
//        print_r($request->workorder_master_id);
//        exit;
        $workOrderMasterId = $request->workorder_master_id;

        $companyInfo = CompanyInformation::first();

        $targetArr = WorkOrderDetails::join('work_order_master', 'work_order_master.id', 'work_order_details.work_order_master_id')
                        ->where('work_order_details.work_order_master_id', $workOrderMasterId)
                        ->select('work_order_details.*', 'work_order_master.*')->get()->toArray();

        $info = WorkOrderMaster::where('id', $request->workorder_master_id)->first();
        $supplierList = Supplier::pluck('name', 'id')->toArray();
        $skuList = ProductSKUCode::pluck('sku', 'id')->toArray();

        $companyInfo = CompanyInformation::first();
        $companyNumber = '';
        if (!empty($companyInfo)) {
            $phoneNumberDecode = json_decode($companyInfo->phone_number, true);
            $companyNumber = Helper::arrayTostring($phoneNumberDecode);
        }
        $imagePath = '/public/img/small_logo.png';

        $pdf = PDF::loadView('procurement.print.index', compact('targetArr', 'info', 'supplierList', 'skuList'
                                , 'companyInfo', 'companyNumber', 'imagePath'))
                ->setPaper('a4', 'portrait')
                ->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('WorkOrder-' . $info->reference . '.pdf');
    }

}
