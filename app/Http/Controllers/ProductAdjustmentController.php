<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductAdjustmentMaster;
use App\ProductAdjustmentDetails;
use App\Configuration;
use App\User;
use App\ProductSKUCode;
use DB;
use URL;
use Auth;
use Validator;
use Response;
use Session;
use Helper;
use Common;
use Redirect;
use Illuminate\Http\Request;

class ProductAdjustmentController extends Controller {

    private $viewStatusArr = [0 => 'Pending for Approval', 1 => 'Approved'];
    private $statusArr = [0 => ['status' => 'Pending for Approval', 'label' => 'warning']
        , 1 => ['status' => 'Approved', 'label' => 'success']];

    public function create() {
        $productSkuArr = ['0' => __('label.SELECT_PRODUCT_SKU_OPT')] + ProductSKUCode::orderBy('sku', 'asc')->pluck('sku', 'id')->toArray();
        $product = '';
        $adjustmentDate = date('Y-m-d');
        $adjustmentTime = date('H:i:s');

        $consumeArr = ProductAdjustmentMaster::select(DB::raw('count(id) as total'))
                        ->where('adjustment_date', $adjustmentDate)->first();

        $voucherId = $consumeArr->total + 1;
        $referenceNo = 'SAD-' . date('ymd', strtotime($adjustmentDate)) . str_pad($voucherId, 4, '0', STR_PAD_LEFT);
        return view('productConsumption.consume')->with(compact('productSkuArr', 'referenceNo', 'adjustmentDate', 'product'
                                , 'adjustmentTime'));
    }

    public function adjustProduct(Request $request) {
            
        if (isset($_SERVER['CONTENT_LENGTH']) && ($_SERVER['CONTENT_LENGTH'] > ((int) ini_get('post_max_size') * 1024 * 1024))) {
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 413);
        }

        $rules = $messages = [];

        if (!empty($request->attachment)) {
            $rules['attachment'] = 'max:2048|mimes:pdf';
            $messages['attachment.max'] = __('label.THE_ATTACHMENT_EXCEEDED_MAXIMUM_FILE_SIZE');
            $messages['attachment.mimes'] = __('label.THE_ATTACHMENT_FILE_IS_NOT_VALID');
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }


        $target = new ProductAdjustmentMaster;
        $target->reference_no = $request->reference_no;
        $target->adjustment_date = $request->adjustment_date;
        $target->attachment = $location ?? '';
        $target->remarks = $request->remarks;
        $target->created_by = Auth::user()->id;
        $target->created_at = date('Y-m-d H:i:s');



        if (!empty($request->add_btn)) {
            DB::beginTransaction();
            try {
                if ($target->save()) {
                    if ($request->hasfile('attachment')) {
                        $file = $request->file('attachment');
                        $fileName = 'Attchment_' . Auth::user()->id . '_' . $request->product . '_' . $request->reference_no . '.' . $file->getClientOriginalExtension();
                        ;
                        $location = 'public/uploads/productAdjustment/' . $fileName;

                        if (file_exists($location)) {
                            unlink($location);
                        }

                        $path = $file->move('public/uploads/productAdjustment/', $fileName);
                    }
                    $data = [];
                    $i = 0;
                    foreach ($request->product_sku as $key => $productSku) {
                        $data[$i]['master_id'] = $target->id;
                        $data[$i]['sku_id'] = $key;
                        $data[$i]['sku'] = $productSku;
                        $data[$i]['quantity'] = $request->quantity[$key];
                        $i++;
                    }

                    $detailInsertStatus = ProductAdjustmentDetails::insert($data);

                    if (!$detailInsertStatus) {//If failed to Insert in Consumption Details Table, Roll Back Insertion operation of Master Table
                        //ProductAdjustmentMaster::where('id', $target->id)->delete();
                        DB::rollback();
                        return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
                    } else {
                        $productDetails = ProductAdjustmentDetails::where('master_id', $target->id)->lockForUpdate()->get();
                        if (!empty($productDetails)) {
                            foreach ($productDetails as $item) {
                                ProductSKUCode::where('id', $item->sku_id)->decrement('available_quantity', $item->quantity);
                            }
                        } else {
                            DB::rollback();
                            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
                            //$error .= __('label.QUANTITY_IS_ALREADY_CONSUMED_FOR') . $availableArr[$data['product_id']]['name']. '<br />';
                        }
                        DB::commit();
                        return Response::json(['success' => true], 200);
                    }
                    ////////////////////////////////
                    //DB::commit();
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

    public function productHints(Request $request) {
        $target = ProductSKUCode::where('id', $request->sku)->first();
//                echo '<pre>';
//        print_r($target->toArray());
//        exit;
        $unitInfo = Product::join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                        ->select('product_unit.name as unit_name')->where('product.id', $target->product_id)->first();
        return response()->json(['quantity' => $target->available_quantity, 'unit_name' => $unitInfo->unit_name]);
    }

    public function purchaseNew(Request $request) {
        $target = ProductSKUCode::where('id', $request->sku)->first();
        $productInfo = Product::join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')->select('product.name as pname'
                                , 'product_unit.name as unit_name')
                        ->where('product.id', $target->product_id)->first();
        return response()->json(['productName' => $productInfo->pname, 'productUnit' => $productInfo->unit_name, 'productSku' => $target->sku]);
//        return Common::purchaseNew($request);
    }

    public function getProductName(Request $request) {
        //product wise supplier
        $target = ProductSKUCode::where('id', $request->sku_id)->first();

        $productDetail = Product::where('product.id', $target->product_id)
                ->select('product.name as product_name')
                ->first();
        $unitInfo = Product::join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                        ->select('product_unit.name as unit_name')->where('product.id', $target->product_id)->first();

        $loadView = 'productConsumption.showProductName';
        $view = view($loadView, compact('productDetail'))->render();
        return response()->json(['html' => $view, 'quantity' => $target->available_quantity, 'unit_name' => $unitInfo->unit_name]);
    }

}

//EOF -Class
