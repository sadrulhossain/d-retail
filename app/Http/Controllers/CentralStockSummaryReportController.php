<?php

namespace App\Http\Controllers;

use Validator;
use Session;
use Redirect;
use App\Product;
use App\Supplier;
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

class CentralStockSummaryReportController extends Controller {

    //Stock Summary Report
    public function index(Request $request) {
        $productArr = Product::orderBy('name', 'asc')->where('status', '1');
        $productIdList = $productArr->pluck('id', 'id')->toArray();
        $productArr = $productArr->pluck('name', 'id')->toArray();
//        $supplierArr = Supplier::orderBy('name','asc')->pluck('name','id')->toArray();
//        $manufacturerArr = Manufacturer::orderBy('name','asc')->pluck('name','id')->toArray();
        if ($request->generate == 'true') {
            if (!empty($request->product)) {
                $productIdList = explode(",", $request->product);
            }
        }
        $targetArr = ProductSKUCode::join('product', 'product.id', '=', 'product_sku_code.product_id')
                ->join('product_category', 'product_category.id', '=', 'product.product_category_id')
                ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                ->join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                ->where('product.status', '1')
                ->whereIn('product.id', $productIdList)
                ->select('product.name as product', 'product_sku_code.available_quantity', 'product_sku_code.sku'
                        , 'product_unit.name as unit', 'product_category.name as product_category'
                        , 'brand.name as brand')
                ->orderBy('product', 'asc')
                ->orderBy('sku', 'asc')
                ->get();
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }
        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[108][6])) {
                return redirect('dashboard');
            }
            return view('report.centralStockSummary.print.index')->with(compact('request', 'targetArr', 'productArr'
                                    , 'konitaInfo', 'phoneNumber'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[108][9])) {
                return redirect('/dashboard');
            }
            $pdf = PDF::loadView('report.centralStockSummary.print.index', compact('request', 'targetArr', 'productArr'
                                    , 'konitaInfo', 'phoneNumber'))
                    ->setPaper('a3', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('central_stock_summary_report.pdf');
//            return $pdf->stream();
        } else {
            return view('report.centralStockSummary.index')->with(compact('request', 'targetArr', 'productArr'
                                    , 'konitaInfo', 'phoneNumber'));
        }
    }

    public function filter(Request $request) {
        $product = !empty($request->product) ? implode(",", $request->product) : '';
        return redirect("admin/centralStockSummaryReport?generate=true&product=".$product);
    }

}
