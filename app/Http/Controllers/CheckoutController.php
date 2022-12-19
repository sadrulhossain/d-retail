<?php

namespace App\Http\Controllers;

use Validator;
use App\Wishlist; //model class
use App\Customer;
use App\Product;
use App\Order;
use App\OrderDetails;
use App\ProductSKUCode;
use App\WarehouseStore;
use App\User;
use App\SrToRetailer;
use Common;
use Session;
use Redirect;
use Auth;
use File;
use Response;
use Image;
use Helper;
use Cart;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CheckoutController extends Controller {

    public function index() {
        if (Auth::Check()) {
            $productPopularProduct = DB::table('product')
                    ->leftJoin('product_to_product_offer', 'product_to_product_offer.product_id', 'product.id')
                    ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                    ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
                    ->leftJoin('product_sku_code', 'product_sku_code.product_id', 'product.id')
                    ->where('product_to_product_offer.popular_product', '1')
                    ->select('product.id as productId', 'product_sku_code.sku as sku', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage'
                            , DB::raw('MAX(selling_price) as price'))
                    ->groupBy('product.id', 'product.name', 'brand.name', 'product_image.image', 'product_sku_code.sku')
                    ->get();
            if (!$productPopularProduct->isEmpty()) {
                foreach ($productPopularProduct as $data) {
                    $data->productImage = json_decode($data->productImage, true);
                }
            }
            $customer = Auth::user();
            return view('frontend.checkout')->with(compact('customer', 'productPopularProduct'));
        } else {
            return redirect('/loginAndRegister');
        }
    }

    public function placeOrder(Request $request) {

        if (Auth::Check()) {
            if (!in_array(Auth::user()->group_id, [14, 18, 19])) {
                return Response::json(array('success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.YOU_ARE_NOT_ELIGIBLE_TO_MAKE_ORDER')), 401);
            } else {
                if (in_array(Auth::user()->group_id, [18, 19])) {
                    if (!Auth::user()->retailer->warehouseToRetailer) {
                        return Response::json(array('success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.YOU_ARE_NOT_ASSIGN_TO_WAREHOUSE')), 401);
                    }
                    if (!Auth::user()->retailer->sr) {
                        return Response::json(array('success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.NO_SR_IS_ASSIGN_FOR_YOU')), 401);
                    }
                }
            }
        }


        $rules = $message = array();
        $rules = [
            'retailer_id' => 'required|not_in:0',
        ];
        $message['retailer_id.not_in'] = __('label.RETAILER_FIELD_IS_REQUIRED');


        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json(['success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()], 400);
        }

        // Generate unique order no.
        $order_no = Common::generateOrderNo();
        // End Generate unique order no.
        
        if(Auth::user()->group_id == 14){
            $sr_id = Auth::user()->id;
        }else{
            $sr_id = \App\SrToRetailer::where('retailer_id',Auth::user()->retailer->id)->first()->sr_id;
        }

        $target = new Order;
        $target->retailer_id = $request->retailer_id;
        $target->sr_id = $sr_id;
        $target->warehouse_id = $request->warehouse_id;
        $target->order_no = $order_no;
        $target->grand_total = Cart::total();
        $target->note = $request->note;
        $target->status = '0';
        $target->created_at = date('Y-m-d H:i:s');
        $target->updated_at = date('Y-m-d H:i:s');
        $target->updated_by = Auth::user()->id;


        $skuIdArr = [];
        $content = Cart::content();
        if (!$content->isEmpty()) {
            foreach ($content as $items) {
                $skuIdArr[$items->id] = $items->id;
            }
        } else {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => __('label.PLEASE_SELECT_ATLEAST_ONE_SKU')), 401);
        }

        $productIdArr = ProductSKUCode::whereIn('id', $skuIdArr)
                ->pluck('product_id', 'id')
                ->toArray();

        $availableQtyArr = WarehouseStore::where('warehouse_id', $request->warehouse_id)
                ->whereIn('sku_id', $skuIdArr)
                ->pluck('quantity', 'sku_id')
                ->toArray();


        DB::beginTransaction();
        try {
            if ($target->save()) {
                $data = [];
                $i = 1;
                if (!$content->isEmpty()) {
                    foreach ($content as $item) {
                        $data[$i]['order_id'] = $target->id;
                        $data[$i]['product_id'] = $productIdArr[$item->id];
                        $data[$i]['sku_id'] = $item->id;
                        $data[$i]['unit_price'] = $item->price;
                        $data[$i]['quantity'] = $item->qty;
                        $data[$i]['customer_demand'] = $request->customer_demand[$item->id];
                        $data[$i]['over_demanded'] = (($item->qty == $availableQtyArr[$item->id]) && ($request->customer_demand[$item->id] > $item->qty)) ? '1' : '0';
                        $data[$i]['total_price'] = $item->subtotal;
                        $i++;
                    }
                    OrderDetails::insert($data);
                }
            }
            
            DB::commit();
            Cart::destroy();
            return Response::json(['success' => true], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            print(json_encode($e));
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
        }
    }

}
