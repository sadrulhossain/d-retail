<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductWhReturnDetails;
use App\ProductWhReturn;
use App\ProductTransferDetails;
use App\ProductSKUCode;
use App\Warehouse;
use App\WarehouseStore;
use App\Product;
use App\UserToWarehouse;
use DB;
use Auth;
use Response;

class ProductWhReturnController extends Controller {

    public function create() {

        $productSkuArr = ['0' => __('label.SELECT_PRODUCT_SKU_OPT')];
        $product = $brand = '';
        $whReturnTime = date('H:i:s');
        $whReturnDate = date('Y-m-d');
        $whReturnArr = ProductWhReturn::select(DB::raw('count(id) as total'))->where('return_date', $whReturnDate)->first();
        $warehouseList = ['0' => __('label.SELECT_WAREHOUSE_OPT')] + Warehouse::where('allowed_for_central_warehouse', '!=', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();

        $whReturn = $whReturnArr->total + 1;
        $referenceNo = 'PRT-' . date('ymd', strtotime($whReturnDate)) . str_pad($whReturn, 4, '0', STR_PAD_LEFT);
        return view('productWhReturn.whReturn')->with(compact('productSkuArr', 'product', 'brand', 'referenceNo', 'whReturnDate', 'whReturnTime', 'warehouseList'));
    }

    public function getProductName(Request $request) {
        $target = ProductSKUCode::where('id', $request->sku_id)->first();
        $productQty = WarehouseStore::where('sku_id', $request->sku_id)->first();

        if (!empty($productQty)) {
            $quantity = $productQty->quantity;
        }

        $productDetail = Product::where('product.id', $target->product_id)
                ->select('product.name as product_name')
                ->first();
        $unitInfo = Product::join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                        ->select('product_unit.name as unit_name')->where('product.id', $target->product_id)->first();

        $loadView = 'productWhReturn.showProductName';
        $view = view($loadView, compact('productDetail'))->render();

        return response()->json(['html' => $view, 'quantity' => $quantity, 'unit_name' => $unitInfo->unit_name]);
    }

    public function purchaseNew(Request $request) {
        $target = ProductSKUCode::where('id', $request->sku)->first();
        $productInfo = Product::join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')->select('product.name as pname'
                                , 'product_unit.name as unit_name')
                        ->where('product.id', $target->product_id)->first();
        return response()->json(['productName' => $productInfo->pname, 'productUnit' => $productInfo->unit_name, 'productSku' => $target->sku]);
    }

    public function getSkuList(Request $request) {
        $productSkuArr = ['0' => __('label.SELECT_PRODUCT_SKU_OPT')] + WarehouseStore::join('product_sku_code', 'product_sku_code.id', '=', 'wh_store.sku_id')
                        ->where('wh_store.warehouse_id', $request->warehouse_id)
                        ->pluck('product_sku_code.sku', 'wh_store.sku_id')->toArray();


        $view = view('productWhReturn.showSku', compact('productSkuArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function returnProduct(Request $request) {

        $rules = $messages = [];
        $rules['product_id'] = 'required|not_in:0';



        $target = new ProductWhReturn;
        $target->reference_no = $request->reference_no;
        $target->return_date = $request->wh_return_date;
        $target->warehouse_id = $request->warehouse_id;
        $target->remarks = $request->remarks;
        $target->created_by = Auth::user()->id;
        $target->created_at = date('Y-m-d H:i:s');

        if (!empty($request->add_btn)) {

            DB::beginTransaction();
            try {
                if ($target->save()) {
                    $data = [];
                    $i = 0;
                    foreach ($request->product_sku as $key => $productSku) {
                        $data[$i]['product_wh_return_id'] = $target->id;
                        $data[$i]['sku_id'] = $key;
                        $data[$i]['sku'] = $productSku;
                        $data[$i]['quantity'] = $request->quantity[$key];
                        $i++;
                    }
                    $detailInsertStatus = ProductWhReturnDetails::insert($data);


                    if (!$detailInsertStatus) {//If failed to Insert in Consumption Details Table, Roll Back Insertion operation of Master Table
                        DB::rollback();
                        return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
                    } else {
                        $productDetails = ProductWhReturnDetails::where('product_wh_return_id', $target->id)->lockForUpdate()->get();
                        if (!empty($productDetails)) {
                            foreach ($productDetails as $item) {
                                ProductSKUCode::where('id', $item->sku_id)->increment('available_quantity', $item->quantity);
                                WarehouseStore::where('warehouse_id',$request->warehouse_id)->where('sku_id', $item->sku_id)->decrement('quantity', $item->quantity);
                            }
                        } else {
                            DB::rollback();
                            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
                        }
                        DB::commit();
                        return Response::json(['success' => true], 200);
                    }
                } //EOF-IF Target->SAVE()
            } catch (\Throwable $e) {
                DB::rollback();
                return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
            }
        } else {
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.YOU_HAVE_NO_NEW_PRODUCT_FOR_SAVE')], 401);
        }
    }

}
