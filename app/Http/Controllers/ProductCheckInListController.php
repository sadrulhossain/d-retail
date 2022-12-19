<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\ProductSKUCode;
use App\ProductCheckInMaster;
use App\ProductCheckInDetails;
use App\Department;
use Response;
use Common;
use DB;
use Helper;
use Redirect;
use Session;
use Auth;
use Illuminate\Http\Request;

class ProductCheckInListController extends Controller {

    private $viewStatusArr = [0 => 'Pending for Approval', 1 => 'Checked In'];
    private $statusArr = [
        0 => ['status' => 'Pending for Approval', 'label' => 'warning'], 1 => ['status' => 'Approved', 'label' => 'success']
    ];
    private $sourceArr = [
        0 => ['status' => 'CheckIn', 'label' => 'warning'], 1 => ['status' => 'Initial Balance Set', 'label' => 'success']
    ];

    public function index(Request $request) {
        $qpArr = $request->all();
        //        dd($qpArr);
        $challanNoArr = ProductCheckInMaster::select('challan_no')->orderBy('id', 'asc')->get();
        $refNoArr = ProductCheckInMaster::select('ref_no')->orderBy('id', 'asc')->get();
        $targetArr = ProductCheckInMaster::join('users', 'users.id', '=', 'product_checkin_master.created_by');
        //begin filtering
        if (!empty($request->ref_no)) {
            $targetArr = $targetArr->where('product_checkin_master.ref_no', 'LIKE', '%' . $request->ref_no . '%');
        }

        if (!empty($request->challan_no)) {
            $targetArr = $targetArr->where('product_checkin_master.challan_no', '=', $request->challan_no);
        }

        if (!empty($request->checkin_date)) {
            $checkinDate = Helper::dateTimeFormatConvert($request->checkin_date);
            $targetArr = $targetArr->where('product_checkin_master.checkin_date', '=', $checkinDate);
        }

        //end filtering

        $targetArr = $targetArr->select('product_checkin_master.*', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as user_full_name"))
                        ->orderBy('product_checkin_master.checkin_date', 'desc')->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/productcheckinlist?page=' . $page);
        }

        return view('productCheckIn.checkInList')->with(compact('targetArr', 'challanNoArr', 'refNoArr'));
    }

    public function filter(Request $request) {
        $url = 'checkin_date=' . $request->checkin_date . '&ref_no=' . $request->ref_no
                . '&challan_no=' . $request->challan_no;
        return Redirect::to('admin/productCheckInList?' . $url);
    }

    public function getProductDetails(Request $request) {
        $target = ProductCheckInMaster::join('users', 'users.id', '=', 'product_checkin_master.created_by')
                        ->select('product_checkin_master.*', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as user_full_name"))
                        //                        ->where('product_checkin_master.ref_no', $request->ref_no)
                        ->where('product_checkin_master.id', $request->master_id)->first();

        $targetArr = ProductCheckInDetails::where('master_id', $target['id'])
                ->join('product_sku_code', 'product_sku_code.id', '=', 'product_checkin_details.sku_id')
                ->join('product', 'product.id', '=', 'product_checkin_details.product_id')
                ->join('brand', 'brand.id', '=', 'product_checkin_details.brand_id')
                ->join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                ->join('supplier', 'supplier.id', '=', 'product_checkin_details.supplier_id')
                ->join('product_category', 'product_category.id', '=', 'product.product_category_id')
                ->select('product.name as product_name', 'brand.name as brand_name', 'product_category.name as category_name', 'supplier.name as supplier_name', 'supplier.address as saddress', 'product_checkin_details.quantity', 'product_checkin_details.rate', 'product_unit.name as unit_name', 'product_sku_code.sku')
                ->get();

        $statusArr = $this->viewStatusArr;
        $sourceArr = $this->sourceArr;

        $view = view('productCheckIn.productDetails', compact('targetArr', 'target', 'statusArr', 'sourceArr'))->render();
        return response()->json(['html' => $view]);
    }

    
//    public function approve(Request $request) {
//
//        
//        $target = ProductCheckInMaster::find($request->id);
//        $target->approval_status = $request->approve;
////        return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_APPROVED_SUCCESSFULLY')), 200);
//        DB::beginTransaction();
//        try {
//            if ($target->save()) {
//
//                $productDetails = ProductCheckInDetails::where('master_id', $target->id)->lockForUpdate()->get();
//                if (!empty($productDetails)) {
//                    foreach ($productDetails as $item) {
//                        ProductSKUCode::where('id', $item->sku_id)->increment('available_quantity', $item->quantity);
//                    }
//                } else {
//                    DB::rollback();
//                    return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.FAILED_TO_APPROVE_PRODUCT_PURCHASE')], 401);
//                }
//                DB::commit();
//                return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_PURCHASE_HAS_BEEN_APPROVED_SUCCESSFULLY')), 200);
//            } //EOF-IF Target->SAVE()
//        } catch (\Throwable $e) {
//            DB::rollback();
//            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.FAILED_TO_APPROVE_PRODUCT_PURCHASE')], 401);
//        }
//    }
//
//    public function deny(Request $request) {
////        if (!in_array(Auth::user()->group_id, [1, 11])) {
////            return Response::json(['success' => false, 'heading' => 'Unauthorize', 'message' => __('label.YOU_ARE_NOT_AUTHORIZE_TO_PERFORM_THIS_ACTION')], 401);
////        }
//        $target = ProductCheckInMaster::find($request->id);
//        if ($target->delete()) {
//            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_PURCHASE_DENIED_SUCCESSFULLY')), 200);
//        }
//        return Response::json(array('heading' => 'Error', 'message' => __('label.COULD_NOT_DENAIED_PURCHASE_PRODUCT')), 401);
//    }

}
