<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductCheckInMaster;
use App\ProductTransferMaster;
use App\ProductTransferDetails;
use App\ProductSKUCode;
use App\WarehouseStore;
use App\Warehouse;
use App\Product;
use App\WhToLocalWhManager;
use DB;
use Auth;
use Response;
use Common;

class ProductTransferController extends Controller {

    public function create() {

        $productSkuArr = ['0' => __('label.SELECT_PRODUCT_SKU_OPT')] + ProductSKUCode::orderBy('sku', 'asc')->pluck('sku', 'id')->toArray();
        $supplierArr = ['0' => __('label.SELECT_SUPPLIER_OPT')];
        $brand = '';
        $product = '';
        $transferTime = date('H:i:s');
        $transferDate = date('Y-m-d');
        $transferArr = ProductTransferMaster::select(DB::raw('count(id) as total'))->where('transfer_date', $transferDate)->first();

        $warehouseList = Warehouse::where('allowed_for_central_warehouse', '!=', '1')
                ->orderBy('order', 'asc');
        $warehouse = null;
        if (Auth::user()->group_id == 12) {
            $warehouse = WhToLocalWhManager::join('warehouse', 'warehouse.id', 'wh_to_local_wh_manager.warehouse_id')
                    ->select('warehouse.name as wh', 'wh_to_local_wh_manager.warehouse_id as wh_id')
                    ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id)
                    ->first();

            $productSkuArr = ['0' => __('label.SELECT_PRODUCT_SKU_OPT')] + WarehouseStore::join('product_sku_code', 'product_sku_code.id', 'wh_store.sku_id')
                            ->where('wh_store.warehouse_id', $warehouse->wh_id ?? 0)->orderBy('product_sku_code.sku', 'asc')
                            ->pluck('product_sku_code.sku', 'product_sku_code.id')->toArray();

            $warehouseList = $warehouseList->where('id', '<>', $warehouse->wh_id ?? 0);
        }
        $warehouseList = $warehouseList->pluck('name', 'id')->toArray();
        $warehouseList = ['0' => __('label.SELECT_WAREHOUSE_OPT')] + $warehouseList;

        $transfer = $transferArr->total + 1;
        $referenceNo = 'PTR-' . date('ymd', strtotime($transferDate)) . str_pad($transfer, 4, '0', STR_PAD_LEFT);
        $centralWarehouse = Warehouse::where('allowed_for_central_warehouse', '=', '1')->first();

        $warehouseNameList = $warehouseId = [];

//        echo "<pre>";
//        print_r($warehouseId);
//        exit;

        return view('productTransfer.transfer')->with(compact('productSkuArr', 'product', 'supplierArr', 'brand'
                                , 'referenceNo', 'transferDate', 'transferTime', 'warehouseList'
                                , 'centralWarehouse', 'warehouse'));
    }

    public function getProductName(Request $request) {
        //product wise supplier
        $target = ProductSKUCode::where('id', $request->sku_id)->first();

        if (Auth::user()->group_id == 12) {
            $warehouse = WhToLocalWhManager::select('warehouse_id as wh_id')
                    ->where('lwm_id', Auth::user()->id)
                    ->first();
            $target = WarehouseStore::join('product_sku_code', 'product_sku_code.id', 'wh_store.sku_id')
                    ->where('wh_store.warehouse_id', $warehouse->wh_id ?? 0)
                    ->where('wh_store.sku_id', $request->sku_id)
                    ->select('wh_store.quantity as available_quantity', 'product_sku_code.product_id')
                    ->first();
        }
        
        $freezeStockArr = Common::getFreezeStock($warehouse->wh_id ?? 0);
//        echo '<pre>';
//        print_r($freezeStockArr);
//        exit;
        $freezeStock = !empty($freezeStockArr[$request->sku_id]) ? $freezeStockArr[$request->sku_id] : 0;
        $availableQty = ($target->available_quantity ?? 0)-$freezeStock;

        $productDetail = Product::where('product.id', $target->product_id)
                ->select('product.name as product_name')
                ->first();
        $unitInfo = Product::join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                ->select('product_unit.name as unit_name')
                ->where('product.id', $target->product_id)
                ->first();

        $loadView = 'productTransfer.showProductName';
        $view = view($loadView, compact('productDetail'))->render();
        return response()->json(['html' => $view, 'quantity' => $availableQty, 'unit_name' => $unitInfo->unit_name ?? '']);
    }

    public function purchaseNew(Request $request) {

        $target = ProductSKUCode::where('id', $request->sku)->first();
        $productInfo = Product::join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                        ->select('product.name as pname', 'product_unit.name as unit_name')
                        ->where('product.id', $target->product_id)->first();
        return response()->json(['productName' => $productInfo->pname, 'productUnit' => $productInfo->unit_name, 'productSku' => $target->sku]);
//        return Common::purchaseNew($request);
    }

    public function transferProduct(Request $request) {

        $rules = $messages = [];
        $rules['product_id'] = 'required|not_in:0';

        $target = new ProductTransferMaster;
        $target->reference_no = $request->reference_no;
        $target->transfer_date = $request->transfer_date;
        $target->warehouse_id = $request->warehouse_id;
        $target->tr_warehouse_id = $request->tr_warehouse_id;
        $target->remarks = $request->remarks;
        $target->approval_status = $request->tr_warehouse_id == 0 ? '1' : '0';
        $target->approved_at = $request->tr_warehouse_id == 0 ? date('Y-m-d H:i:s') : null;
        $target->approved_by = $request->tr_warehouse_id == 0 ? Auth::user()->id : 0;
        $target->created_by = Auth::user()->id;
        $target->created_at = date('Y-m-d H:i:s');

        if (!empty($request->add_btn)) {

            DB::beginTransaction();
            try {
                if ($target->save()) {
                    //echo $target->id." Inserted";
                    //sleep(10);
                    $data = [];
                    $i = 0;
                    foreach ($request->product_sku as $key => $productSku) {
                        $data[$i]['master_id'] = $target->id;
                        $data[$i]['sku_id'] = $key;
                        $data[$i]['sku'] = $productSku;
                        $data[$i]['quantity'] = $request->quantity[$key];
                        $i++;
                    }

                    //Insert data to the Product Details Table

                    $detailInsertStatus = ProductTransferDetails::insert($data);

                    $data1 = [];
                    $j = 0;

                    if (!$detailInsertStatus) {//If failed to Insert in Consumption Details Table, Roll Back Insertion operation of Master Table
                        //ProductAdjustmentMaster::where('id', $target->id)->delete();
                        DB::rollback();
                        return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
                    } else {
                        if ($request->tr_warehouse_id == 0) {
                            foreach ($request->product_sku as $key => $productSku) {
                                $whStore = WarehouseStore::where('sku_id', $key)->where('warehouse_id', $request->warehouse_id)->first();

                                $storeUpdate = !empty($whStore->id) ? WarehouseStore::find($whStore->id) : new WarehouseStore;
                                $whQty = !empty($whStore->quantity) ? $whStore->quantity : 0;
                                $storeUpdate->warehouse_id = $request->warehouse_id;
                                $storeUpdate->sku_id = $key;
                                $storeUpdate->quantity = $whQty + $request->quantity[$key];
                                $storeUpdate->updated_by = Auth::user()->id;
                                $storeUpdate->updated_at = date('Y-m-d H:i:s');

                                if (!$storeUpdate->save()) {//If failed to Insert in Consumption Details Table, Roll Back Insertion operation of Master Table
                                    DB::rollback();
                                    return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
                                }



//                            if ($decrementWhStock) {//If failed to Insert in Consumption Details Table, Roll Back Insertion operation of Master Table
//                                DB::rollback();
//                                return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
//                            }
                            }

                            $productDetails = ProductTransferDetails::where('master_id', $target->id)->lockForUpdate()->get();
                            if (!empty($productDetails)) {
                                foreach ($productDetails as $item) {
                                    ProductSKUCode::where('id', $item->sku_id)->decrement('available_quantity', $item->quantity);
                                }
                            } else {
                                DB::rollback();
                                return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
                                //$error .= __('label.QUANTITY_IS_ALREADY_CONSUMED_FOR') . $availableArr[$data['product_id']]['name']. '<br />';
                            }
                        }
                        DB::commit();
                        return Response::json(['success' => true], 200);
                    }
                    ////////////////////////////////
//                    DB::commit();
                } //EOF-IF Target->SAVE()
            } catch (\Throwable $e) {
                DB::rollback();
                //print_r($e->getMessage());

                return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
            }
        } else {
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.YOU_HAVE_NO_NEW_PRODUCT_FOR_SAVE')], 401);
        }
    }

}
