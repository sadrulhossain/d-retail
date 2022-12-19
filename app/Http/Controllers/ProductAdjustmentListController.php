<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductAdjustmentMaster;
use App\ProductAdjustmentDetails;
use App\ProductSKUCode;
use App\Configuration;
use App\User;
use DB;
use Auth;
use Validator;
use Response;
use Session;
use Common;
use Helper;
use Redirect;
use Illuminate\Http\Request;

class ProductAdjustmentListController extends Controller {

    private $viewStatusArr = [0 => 'Pending for Approval', 1 => 'Approved'];
    private $statusArr = [0 => ['status' => 'Pending for Approval', 'label' => 'warning']
        , 1 => ['status' => 'Approved', 'label' => 'success']];

    public function index(Request $request) {
        $qpArr = $request->all();
        $productArr = Product::pluck('name', 'id')->toArray();
        $statusList = array('' => __('label.SELECT_STATUS_OPT'), '0' => 'Pending for Approval', '1' => 'Approved');
        $refNoArr = ProductAdjustmentMaster::select('reference_no')->orderBy('reference_no', 'asc')->get();

        $targetArr = ProductAdjustmentMaster::join('users', 'users.id', '=', 'product_adjustment_master.created_by');

        //begin filtering
        if (!empty($request->ref_no)) {
            $targetArr = $targetArr->where('product_adjustment_master.reference_no', 'LIKE', '%' . $request->ref_no . '%');
        }
        if (!empty($request->adjustment_date)) {
            $adjustmentDate = Helper::dateTimeFormatConvert($request->adjustment_date);
            $targetArr = $targetArr->where('product_adjustment_master.adjustment_date', '=', $adjustmentDate);
        }
        //end filtering
        $targetArr = $targetArr->select('product_adjustment_master.*', 'product_adjustment_master.created_at', 'product_adjustment_master.adjustment_date'
                , DB::raw("CONCAT(users.first_name,' ',users.last_name) AS name"))
                        ->orderBy('product_adjustment_master.adjustment_date', 'desc')->paginate(Session::get('paginatorCount'));

        $statusArr = $this->statusArr;
//change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/productAdjustmentList?page=' . $page);
        }
        return view('productConsumption.consumptionList')->with(compact('targetArr', 'statusArr', 'productArr', 'refNoArr', 'statusList'));
    }

    public function filter(Request $request) {
        $url = 'ref_no=' . $request->ref_no . '&adjustment_date=' . $request->adjustment_date;
        return Redirect::to('admin/productAdjustmentList?' . $url);
    }
    
    public function getProductAdjustmentDetails(Request $request) {

        $userArr = User::where('status', 1)->select(DB::raw("CONCAT(first_name,' ',last_name) AS name"), 'id')
                        ->pluck('name', 'id')->toArray();

        // get Adjustment Primary Data
        $adjustmentInfo = ProductAdjustmentMaster::select('product_adjustment_master.*')
                        ->where('product_adjustment_master.id', $request->adjustment_id)->first();

        $adjustmentDetailsArr = ProductAdjustmentDetails::where('product_adjustment_details.master_id', '=', $request->adjustment_id)
                        ->join('product_sku_code', 'product_sku_code.sku', '=', 'product_adjustment_details.sku')
                        ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                        ->join('product_category', 'product_category.id', '=', 'product.product_category_id')
                        ->select('product.name as product_name', 'product_category.name as category_name'
                                , 'product_adjustment_details.*')->get();
        
//                echo '<pre>';
//        print_r($adjustmentDetailsArr->toArray());
//        exit;

//        $lotInfoArr = ProductConsumptionDetails::join('pro_consumption_lot_wise_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id')
//                        ->where('pro_consumption_details.master_id', '=', $request->adjustment_id)
//                        ->select('pro_consumption_details.id as details_id', 'pro_consumption_details.product_id', 'pro_consumption_lot_wise_details.lot_number', 'pro_consumption_lot_wise_details.quantity'
//                                , 'pro_consumption_lot_wise_details.rate', 'pro_consumption_lot_wise_details.amount')->get();
//
//        //Fetch Lot Information and form Node: Start
//        $productWithLotArr = [];
//        if (!$lotInfoArr->isEmpty()) {
//            $i = 0;
//            foreach ($lotInfoArr as $target) {
//                $productWithLotArr[$target->product_id][$target->details_id][$i]['lot_number'] = $target->lot_number;
//                $productWithLotArr[$target->product_id][$target->details_id][$i]['quantity'] = $target->quantity;
//                $productWithLotArr[$target->product_id][$target->details_id][$i]['rate'] = $target->rate;
//                $productWithLotArr[$target->product_id][$target->details_id][$i]['amount'] = $target->amount;
//                $i++;
//            }//foreach           
//        }
        
        //Fetch Lot Information and form Node: End
        $statusArr = [0 => 'Pending for Approval', 1 => 'Approved'];

        $view = view('productConsumption.productDetails', compact('adjustmentDetailsArr', 'adjustmentInfo', 'statusArr', 'userArr'))->render();
        return response()->json(['html' => $view]);
    }
}//EOF -Class
