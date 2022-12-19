<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\ProductCheckInMaster;
use App\ProductCheckInDetails;
use App\ProductContainer;
use App\ProductSKUCode;
use App\WorkOrderMaster;
use Response;
use Common;
use DB;
use Redirect;
use Helper;
use Session;
use Auth;
use App\SupplierToProduct;
use App\Supplier;
use App\UserBranch;
use Illuminate\Http\Request;

class ProductCheckInController extends Controller {

    private $viewStatusArr = [0 => 'Pending for Approval', 1 => 'Checked In'];
    private $statusArr = [0 => ['status' => 'Pending for Approval', 'label' => 'warning']
        , 1 => ['status' => 'Approved', 'label' => 'success']];
    private $sourceArr = [0 => ['status' => 'CheckIn', 'label' => 'warning']
        , 1 => ['status' => 'Initial Balance Set', 'label' => 'success']];

    public function create() { 

        $productSkuArr = ['0' => __('label.SELECT_PRODUCT_SKU_OPT')] + ProductSKUCode::orderBy('sku', 'asc')->pluck('sku', 'id')->toArray();
        $supplierArr = ['0' => __('label.SELECT_SUPPLIER_OPT')];
        $workOrderReference = ['0' => __('label.SELECT_WORK_ORDER_REF_OPT')] + WorkOrderMaster::pluck('reference','id')->toArray();
        
        $brand = '';
        $product = '';
        $purchaseTime = date('H:i:s');
        $checkinDate = date('Y-m-d');
        $productContainerList = ['0' => __('label.SELECT_CONTAINER_TYPE')] + ProductContainer::orderBy('name')->get()->pluck('name', 'id')->toArray();
        $checkInArr = ProductCheckInMaster::select(DB::raw('count(id) as total'))->where('checkin_date', $checkinDate)->first();
        $checkIn = $checkInArr->total + 1;
        $referenceNo = 'PO-' . date('ymd', strtotime($checkinDate)) . str_pad($checkIn, 4, '0', STR_PAD_LEFT);
        return view('productCheckIn.purchase')->with(compact('productContainerList','workOrderReference', 'productSkuArr', 'product', 'supplierArr', 'brand', 'referenceNo', 'checkinDate', 'purchaseTime'));
    }

    public function getProductBrand(Request $request) {
        //product wise supplier
        $target = ProductSKUCode::where('id', $request->sku_id)->first();

        $productDetail = Product::join('brand', 'brand.id', '=', 'product.brand_id')
                ->join('product_sku_code','product.id','product_sku_code.product_id')
                ->where('product.id', $target->product_id)
                ->select('brand.name as brand_name', 'product.name as product_name'
                        , 'brand.id as brand_id', 'product.id as product_id','product_sku_code.purchase_price')
                ->first();
        $supplierArr = ['0' => __('label.SELECT_SUPPLIER_OPT')] + SupplierToProduct::join('supplier', 'supplier.id', '=', 'supplier_to_product.supplier_id')
                        ->where('product_id', $target->product_id)->pluck('name', 'supplier.id')->toArray();

        $unitInfo = Product::join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                        ->select('product_unit.name as unit_name')->where('product.id', $target->product_id)->first();

        $loadView = 'productCheckIn.showProductBrand';
        $view = view($loadView, compact('productDetail', 'supplierArr'))->render();
        return response()->json(['html' => $view, 'quantity' => $target->available_quantity, 'rate' => $productDetail->purchase_price, 'unit_name' => $unitInfo->unit_name, 'selling_price' => $target->selling_price]);
    }

    public function getSupplierBrand(Request $request) {
        //product wise supplier
//        echo '<pre>';
//        print_r($request->all());
//        exit;
        $loadView = 'productCheckIn.showSupplierBrand';
        $supplierArr = ['0' => __('label.SELECT_SUPPLIER_OPT')] + SupplierToProduct::join('supplier', 'supplier.id', '=', 'supplier_to_product.supplier_id')
                        ->where('product_id', $request->product_id)->pluck('name', 'supplier.id')->toArray();
        $brand = Product::join('brand', 'brand.id', '=', 'product.brand_id')
                ->where('product.id', $request->product_id)
                ->select('brand.name')
                ->first();
        $view = view($loadView, compact('supplierArr', 'brand'))->render();
        return response()->json(['html' => $view]);
    }

    public function getSupplierAddress(Request $request) {
        //product wise supplier
        $loadView = 'productCheckIn.showSupplierAddress';
        $target = Supplier::find($request->supplier_id);
        $address = $target->address;
        $view = view($loadView, compact('address'))->render();
        return response()->json(['html' => $view]);
    }

    public function purchaseNew(Request $request) {


        $target = ProductSKUCode::where('id', $request->sku)->first();

        $productInfo = Product::join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')->select('product.name as pname'
                                , 'product_unit.name as unit_name')
                        ->where('product.id', $target->product_id)->first();
//        echo "<pre>";
//        print_r($productInfo);
//        exit;
        return response()->json(['productName' => $productInfo->pname, 'productUnit' => $productInfo->unit_name, 'productSku' => $target->sku]);
    }

    public function purchaseProduct(Request $request) {

     
        
        $rules['challan_no'] = 'required|unique:product_checkin_master';
        $rules['product_container_id'] = 'required|not_in:0';
//        $rules['product_id'] = 'required|not_in:0';

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        /* data insertion into product check in master table */
        $target = new ProductCheckInMaster;
        $target->ref_no = $request->ref_no;
        $target->challan_no = $request->challan_no;
        $target->work_order_ref = $request->work_order_ref_id;
        $target->product_container_id = $request->product_container_id;
        $target->checkin_date = !empty($request->checkin_date) ? Helper::dateFormatConvert($request->checkin_date) : date("Y-m-d");
        $target->shipping_date = !empty($request->shipping_date) ? Helper::dateFormatConvert($request->shipping_date) : date("Y-m-d");
//        $target->approval_status = in_array(Auth::user()->group_id,[1, 11]) ? '1' : '0';
        $target->created_by = Auth::user()->id;
        $target->updated_by = Auth::user()->id;
        $target->created_at = date('Y-m-d H:i:s');
        if (!empty($request->add_btn)) {

            DB::beginTransaction();
            try {
                if ($target->save()) {
                    $data = [];
                    $i = 0;

                    foreach ($request->sku as $key => $productSku) {
                        $data[$i]['master_id'] = $target->id;
                        $data[$i]['sku_id'] = $key;
                        $data[$i]['sku'] = $productSku;
                        $data[$i]['supplier_id'] = $request->supplier_id[$key];
                        $data[$i]['product_id'] = $request->product[$key];
                        $data[$i]['brand_id'] = $request->brand[$key];
                        $data[$i]['quantity'] = $request->quantity[$key];
                        $data[$i]['rate'] = $request->rate[$key];
                        $data[$i]['amount'] = $request->amount[$key];
                        $data[$i]['remaining_quantity'] = $request->quantity[$key];
                        $i++;
                    }


                    /* insert data into product check in details table */
                    $insertNewProductCheckInInfo = ProductCheckInDetails::insert($data);

                    if (!$insertNewProductCheckInInfo) {//If failed to Insert in Consumption Details Table, Roll Back Insertion operation of Master Table
                        //ProductConsumptionMaster::where('id', $target->id)->delete();
                        DB::rollback();
                        return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
                    } else {
                        $productDetails = ProductCheckInDetails::where('master_id', $target->id)->lockForUpdate()->get();
                        if (!empty($productDetails)) {
                            foreach ($productDetails as $item) {
//                                if (in_array(Auth::user()->group_id, [1, 11])) {
                                ProductSKUCode::where('id', $item->sku_id)->increment('available_quantity', $item->quantity);
//                                }
                            }
                        } else {
                            DB::rollback();
                            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
                            //$error .= __('label.QUANTITY_IS_ALREADY_CONSUMED_FOR') . $availableArr[$data['product_id']]['name']. '<br />';
                        }
                        DB::commit();
                        return Response::json(['success' => true], 200);
                    }
//                    ProductCheckInDetails::insert($data);
//                    DB::commit();
//                    return Response::json(['success' => true], 200);
                } //EOF-IF Target->SAVE()
            } catch (\Throwable $e) {
                DB::rollback();
                print_r($e->getMessage());
                return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
            }
        } else {
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.YOU_HAVE_NO_NEW_PRODUCT_FOR_SAVE')], 401);
        }
    }

    public function productHints(Request $request) {

        $target = ProductSKUCode::where('id', $request->sku)->first();

        $unitInfo = Product::join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                        ->select('product_unit.name as unit_name')->where('product.id', $target->product_id)->first();

        return response()->json(['quantity' => $target->available_quantity, 'unit_name' => $unitInfo->unit_name]);
//        return Common::productHints($request);
    }

}
