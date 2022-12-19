<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\User;
use App\ProductWhReturnDetails;
use App\ProductWhReturn;
use App\Warehouse;
use Helper;
use DB;
use Session;
use Redirect;

class ProductWhReturnListController extends Controller
{
    private $viewStatusArr = [0 => 'Pending for Approval', 1 => 'Approved'];
    private $statusArr = [0 => ['status' => 'Pending for Approval', 'label' => 'warning']
        , 1 => ['status' => 'Approved', 'label' => 'success']];
    
    public function index(Request $request) {
        $qpArr = $request->all();
        $productArr = Product::pluck('name', 'id')->toArray();
        $statusList = array('' => __('label.SELECT_STATUS_OPT'), '0' => 'Pending for Approval', '1' => 'Approved');
        $refNoArr = ProductWhReturn::select('reference_no')->orderBy('reference_no', 'asc')->get();

        $targetArr = ProductWhReturn::join('users', 'users.id', '=', 'product_wh_return.created_by')
                                        ->join('warehouse','warehouse.id', '=', 'product_wh_return.warehouse_id');;

        $warehouseList = ['0' => __('label.SELECT_WAREHOUSE_OPT')] + Warehouse::where('allowed_for_central_warehouse', '!=', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        //begin filtering
        if (!empty($request->ref_no)) {
            $targetArr = $targetArr->where('product_wh_return.reference_no', 'LIKE', '%' . $request->ref_no . '%');
        }
        if (!empty($request->warehouse_id)) {
            $targetArr = $targetArr->where('warehouse.id', '=', $request->warehouse_id);
        }
        if (!empty($request->return_date)) {
            $returnDate = Helper::dateTimeFormatConvert($request->return_date);
            $targetArr = $targetArr->where('product_wh_return.return_date', '=', $returnDate);
        }
        //end filtering
        $targetArr = $targetArr->select('product_wh_return.*', 'product_wh_return.created_at', 'product_wh_return.return_date','warehouse.name as warehouse_name'
                , DB::raw("CONCAT(users.first_name,' ',users.last_name) AS name"))
                        ->orderBy('product_wh_return.return_date', 'desc')->paginate(Session::get('paginatorCount'));

        $statusArr = $this->statusArr;
//change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/productWhReturnList?page=' . $page);
        }
        return view('productWhReturn.whReturnList')->with(compact('targetArr', 'statusArr', 'productArr', 'refNoArr', 'statusList','warehouseList'));
    }
    
    public function filter(Request $request) {
//        dd($request->warehouse_id);
        $url = 'ref_no=' . $request->ref_no . '&return_date=' . $request->return_date . '&warehouse_id=' . $request->warehouse_id;
        return Redirect::to('admin/stockWhReturnList?' . $url);
    }
    
    public function getProductDetails(Request $request) {

        $userArr = User::where('status', 1)->select(DB::raw("CONCAT(first_name,' ',last_name) AS name"), 'id')
                        ->pluck('name', 'id')->toArray();

//        $transferInfo = ProductTransferMaster::where('product_transfer_master.id', $request->transfer_id)
//                        ->join('warehouse','warehouse.id', '=', 'product_transfer_master.warehouse_id')
//                        ->select('product_transfer_master.*','warehouse.name as warehouse_name')->first();
        // get Transfer Primary Data
        $returnInfo = ProductWhReturn::where('product_wh_return.id', $request->return_id)
                        ->join('warehouse','warehouse.id', '=', 'product_wh_return.warehouse_id')
                        ->select('product_wh_return.*','warehouse.name as warehouse_name')->first();
//        dd($returnInfo);

        $returnDetailsArr = ProductWhReturnDetails::where('product_wh_return_details.product_wh_return_id', '=', $request->return_id)
                        ->join('product_sku_code', 'product_sku_code.sku', '=', 'product_wh_return_details.sku')
                        ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                        ->join('product_category', 'product_category.id', '=', 'product.product_category_id')
                        ->select('product.name as product_name', 'product_category.name as category_name'
                                , 'product_wh_return_details.*')->get();
        
//                echo '<pre>';
//        print_r($returnDetailsArr->toArray());
//        exit;

        
        //Fetch Lot Information and form Node: End
        $statusArr = [0 => 'Pending for Approval', 1 => 'Approved'];

        $view = view('productWhReturn.productDetails', compact('returnDetailsArr', 'returnInfo', 'statusArr', 'userArr'))->render();
        return response()->json(['html' => $view]);
    }
}
