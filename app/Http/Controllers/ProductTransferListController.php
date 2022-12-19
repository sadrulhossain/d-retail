<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\User;
use App\ProductTransferMaster;
use App\ProductTransferDetails;
use App\Warehouse;
use App\WarehouseStore;
use App\WhToLocalWhManager;
use Helper;
use DB;
use Auth;
use Session;
use Redirect;
use Response;

class ProductTransferListController extends Controller {

    private $viewStatusArr = [0 => 'Pending for Approval', 1 => 'Approved'];
    private $statusArr = [0 => ['status' => 'Pending for Approval', 'label' => 'warning']
        , 1 => ['status' => 'Approved', 'label' => 'success']];

    public function index(Request $request) {

//        if (!empty($request->warehouse_id)) {
//            dd($request->warehouse_id);
//        }

        $qpArr = $request->all();
        $productArr = Product::pluck('name', 'id')->toArray();
        $statusList = array('' => __('label.SELECT_STATUS_OPT'), '0' => 'Pending for Approval', '1' => 'Approved');
        $refNoArr = ProductTransferMaster::select('reference_no')->orderBy('reference_no', 'asc')->get();

        $targetArr = ProductTransferMaster::join('users', 'users.id', '=', 'product_transfer_master.created_by')
                ->join('warehouse', 'warehouse.id', '=', 'product_transfer_master.warehouse_id');

        $warehouseList = Warehouse::where('allowed_for_central_warehouse', '!=', '1')->orderBy('order', 'asc')->pluck('name', 'id')
                ->toArray();
        $warehouseIdList = [];
        if (Auth::user()->group_id == 12) {
            $warehouseList = WhToLocalWhManager::join('warehouse', 'warehouse.id', 'wh_to_local_wh_manager.warehouse_id')
                    ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id);
            $warehouseIdList = $warehouseList->pluck('warehouse.id', 'warehouse.id')->toArray();
            if (!empty($warehouseIdList)) {
                $targetArr = $targetArr->whereIn('product_transfer_master.warehouse_id', $warehouseIdList)
                        ->orWhereIn('product_transfer_master.tr_warehouse_id', $warehouseIdList);
            }

            $warehouseList = $warehouseList->pluck('warehouse.name', 'warehouse.id')->toArray();
        }
        $warehouseList = ['0' => __('label.SELECT_WAREHOUSE_OPT')] + $warehouseList;

        //begin filtering
        if (!empty($request->ref_no)) {
            $targetArr = $targetArr->where('product_transfer_master.reference_no', 'LIKE', '%' . $request->ref_no . '%');
        }
        if (!empty($request->warehouse_id)) {
            $targetArr = $targetArr->where('product_transfer_master.warehouse_id', '=', $request->warehouse_id);
        }
        if (!empty($request->transfer_date)) {
            $transferDate = Helper::dateTimeFormatConvert($request->transfer_date);
            $targetArr = $targetArr->where('product_transfer_master.transfer_date', '=', $transferDate);
        }
        //end filtering
        $targetArr = $targetArr->select('product_transfer_master.*', 'product_transfer_master.created_at'
                                , 'product_transfer_master.transfer_date', 'warehouse.name as warehouse_name'
                                , DB::raw("CONCAT(users.first_name,' ',users.last_name) AS name"))
                        ->orderBy('product_transfer_master.transfer_date', 'desc')->paginate(Session::get('paginatorCount'));

        $statusArr = $this->statusArr;
//change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/productTransferList?page=' . $page);
        }
        return view('productTransfer.transferList')->with(compact('targetArr', 'statusArr', 'productArr', 'refNoArr'
                                , 'statusList', 'warehouseList', 'warehouseIdList'));
    }

    public function filter(Request $request) {
//        dd($request->warehouse_id);
        $url = 'ref_no=' . $request->ref_no . '&transfer_date=' . $request->transfer_date . '&warehouse_id=' . $request->warehouse_id;
        return Redirect::to('admin/stockTransferList?' . $url);
    }

    public function getProductDetails(Request $request) {

        $userArr = User::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"), 'id')
                ->where('status', 1)
                ->pluck('name', 'id')
                ->toArray();

        // get Transfer Primary Data
        $transferInfo = ProductTransferMaster::join('warehouse', 'warehouse.id', '=', 'product_transfer_master.warehouse_id')
                        ->where('product_transfer_master.id', $request->transfer_id)
                        ->select('product_transfer_master.*', 'warehouse.name as warehouse_name')->first();

        $transferDetailsArr = ProductTransferDetails::where('product_transfer_details.master_id', '=', $request->transfer_id)
                        ->join('product_sku_code', 'product_sku_code.sku', '=', 'product_transfer_details.sku')
                        ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                        ->join('product_category', 'product_category.id', '=', 'product.product_category_id')
                        ->select('product.name as product_name', 'product_category.name as category_name'
                                , 'product_transfer_details.*')->get();

        //Fetch Lot Information and form Node: End
        $statusArr = [0 => 'Pending for Approval', 1 => 'Approved'];

        $view = view('productTransfer.productDetails', compact('transferDetailsArr', 'transferInfo', 'statusArr', 'userArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function approve(Request $request) {

        $trWarehouseInfo = ProductTransferMaster::where('id', $request->product_transfer_master_Id)->first();
        $trWarehouseId = $trWarehouseInfo['tr_warehouse_id'] ?? 0;
        $warehouseId = $trWarehouseInfo['warehouse_id'] ?? 0;

        $transferDetailsArr = ProductTransferDetails::where('master_id', $request->product_transfer_master_Id)
                        ->pluck('quantity', 'sku_id')->toArray();

        $trWarehouseInfo = ProductTransferMaster::join('product_transfer_details', 'product_transfer_details.master_id', 'product_transfer_master.id')
                        ->where('product_transfer_master.tr_warehouse_id', $trWarehouseId)
                        ->select('product_transfer_details.sku_id', 'product_transfer_details.quantity')
                        ->get()->toArray();
        $warehouseInfo = ProductTransferMaster::join('product_transfer_details', 'product_transfer_details.master_id', 'product_transfer_master.id')
                        ->where('product_transfer_master.warehouse_id', $warehouseId)
                        ->select('product_transfer_details.sku_id', 'product_transfer_details.quantity')
                        ->get()->toArray();

        $target = ProductTransferMaster::find($request->product_transfer_master_Id);

        $target->approval_status = '1';
        $target->approved_at = date('Y-m-d H:i:s');
        $target->approved_by = Auth::user()->id;

        DB::beginTransaction();
        try {

            if ($target->save()) {
                if (!empty($transferDetailsArr)) {
                    foreach ($transferDetailsArr as $skuId => $qty) {

                        //for releasing wh stock
                        WarehouseStore::where('sku_id', $skuId)->decrement('quantity', $qty);

                        //for add to wh
                        $whStore = WarehouseStore::where('sku_id', $skuId)->where('warehouse_id', $warehouseId)->first();

                        $storeUpdate = !empty($whStore->id) ? WarehouseStore::find($whStore->id) : new WarehouseStore;
                        $whQty = !empty($whStore->quantity) ? $whStore->quantity : 0;
                        $storeUpdate->warehouse_id = $warehouseId;
                        $storeUpdate->sku_id = $skuId;
                        $storeUpdate->quantity = $whQty + $qty;
                        $storeUpdate->updated_by = Auth::user()->id;
                        $storeUpdate->updated_at = date('Y-m-d H:i:s');

                        if (!$storeUpdate->save()) {//If failed to Insert in Consumption Details Table, Roll Back Insertion operation of Master Table
                            DB::rollback();
                            return Response::json(array('success' => false, 'message' => __('label.PRODUCT_TRANSFER_COULD_NOT_BE_APPROVED')), 401);
                        }
                    }
                }
            }
            DB::commit();
            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_TRANSFER_HAS_BEEN_APPROVED')), 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(array('success' => false, 'message' => __('label.PRODUCT_TRANSFER_COULD_NOT_BE_APPROVED')), 401);
        }
    }

    public function deny(Request $request) {
        $target = ProductTransferMaster::find($request->product_transfer_master_Id);

        $target->approval_status = '2';

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_TRANSFER_HAS_BEEN_DENIED')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.PRODUCT_TRANSFER_COULD_NOT_BE_DENIED')), 401);
        }
    }

}
