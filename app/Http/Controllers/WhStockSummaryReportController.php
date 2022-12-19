<?php

namespace App\Http\Controllers;

use Validator;
use Session;
use Redirect;
use App\Product;
use App\Warehouse;
use App\WarehouseStore;
use App\WhToLocalWhManager;
use App\ProductSKUCode;
use App\ProductCheckInDetails;
use App\CompanyInformation;
use Helper;
use DB;
use Auth;
use Common;
use PDF;
use Input;
use Illuminate\Http\Request;

class WhStockSummaryReportController extends Controller {

    //Stock Summary Report
    public function index(Request $request) {
        $whIdList = [];
        if (in_array(Auth::user()->group_id, [1, 11])) {
            $whList = ['0' => __('label.ALL_WAREHOUSES')] + Warehouse::where('allowed_for_central_warehouse', '0')
                            ->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        } elseif (in_array(Auth::user()->group_id, [12])) {
            $whList = WhToLocalWhManager::join('warehouse', 'warehouse.id', 'wh_to_local_wh_manager.warehouse_id')
                            ->where('warehouse.allowed_for_central_warehouse', '0')
                            ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id);
            $whIdList = $whList->pluck('warehouse.id', 'warehouse.id')->toArray();
            $whList = $whList->pluck('warehouse.name', 'warehouse.id')->toArray();
        }

        $productArr = Product::orderBy('name', 'asc')->where('status', '1');
        $productIdList = $productArr->pluck('id', 'id')->toArray();
        $productArr = $productArr->pluck('name', 'id')->toArray();

        $whId = 0;
        if ($request->generate == 'true') {
            if (!empty($request->wh_id)) {
                $whId = $request->wh_id;

                $productArr = WarehouseStore::join('product_sku_code', 'product_sku_code.id', '=', 'wh_store.sku_id')
                                ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                                ->where('wh_store.warehouse_id', $whId)
                                ->orderBy('product.name', 'asc')->where('product.status', '1');
                $productIdList = $productArr->pluck('product.id', 'product.id')->toArray();
                $productArr = $productArr->pluck('product.name', 'product.id')->toArray();
            }
            if (!empty($request->product)) {
                $productIdList = explode(",", $request->product);
            }
        }
        $targetArr = WarehouseStore::join('product_sku_code', 'product_sku_code.id', '=', 'wh_store.sku_id')
                        ->join('warehouse', 'warehouse.id', '=', 'wh_store.warehouse_id')
                        ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                        ->join('product_category', 'product_category.id', '=', 'product.product_category_id')
                        ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                        ->join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                        ->where('product.status', '1')->whereIn('product.id', $productIdList);
        if (!empty($whId)) {
            $targetArr = $targetArr->where('wh_store.warehouse_id', $whId);
        } else {
            if (in_array(Auth::user()->group_id, [12])){
                $targetArr = $targetArr->whereIn('wh_store.warehouse_id', $whIdList);
            }
        }
        $targetArr = $targetArr->select('product.name as product', 'wh_store.quantity as available_quantity', 'product_sku_code.sku'
                        , 'product_unit.name as unit', 'product_category.name as product_category'
                        , 'brand.name as brand', 'warehouse.name as warehouse', 'brand.name as brand'
                        , 'wh_store.warehouse_id')
                ->orderBy('warehouse.order', 'asc')
                ->orderBy('product.name', 'asc')
                ->orderBy('product_sku_code.sku', 'asc')
                ->get();
        //echo '<pre>';print_r($targetArr);exit;

        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }
        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[119][6])) {
                return redirect('dashboard');
            }
            return view('report.whStockSummary.print.index')->with(compact('request', 'targetArr', 'productArr'
                                    , 'konitaInfo', 'phoneNumber', 'whList'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[119][9])) {
                return redirect('/dashboard');
            }
            $pdf = PDF::loadView('report.whStockSummary.print.index', compact('request', 'targetArr', 'productArr'
                                    , 'konitaInfo', 'phoneNumber', 'whList'))
                    ->setPaper('a3', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('wh_stock_summary_report.pdf');
//            return $pdf->stream();
        } else {
            return view('report.whStockSummary.index')->with(compact('request', 'targetArr', 'productArr'
                                    , 'konitaInfo', 'phoneNumber', 'whList'));
        }
    }

    public function getProduct(Request $request) {
        $productArr = WarehouseStore::join('product_sku_code', 'product_sku_code.id', '=', 'wh_store.sku_id')
                        ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                        ->where('wh_store.warehouse_id', $request->wh_id)
                        ->orderBy('product.name', 'asc')->where('product.status', '1')
                        ->pluck('product.name', 'product.id')->toArray();
        $view = view('report.whStockSummary.showProducts', compact('productArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function filter(Request $request) {
        $product = !empty($request->product) ? implode(",", $request->product) : '';
        return redirect('admin/whStockSummaryReport?generate=true&wh_id=' . $request->wh_id
                . '&product=' . $product);
    }

}
