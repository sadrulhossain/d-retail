<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use App\Model\Product;
use App\Model\Sale;
use PDF;
use Excel;
use App\Exports\ExcelExport;

class SalesController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $sales = Sale::orderBy('id','desc')->paginate(3);

        $productData = [];
        if (!$sales->isEmpty()) {
            foreach ($sales as $sale) {
                $productData[$sale->id] = json_decode($sale->product_data, TRUE);
            }
        }
		
        $productRowSpan = [];
        if (!empty($productData)) {
            foreach ($productData as $salesId => $salesItem) {
                foreach ($salesItem as $key => $item) {
                    $productRowSpan[$salesId] = !empty($productRowSpan[$salesId]) ? $productRowSpan[$salesId] : 0;
                    $productRowSpan[$salesId] += 1;
                }
            }
        }
        $productList = Product::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        return view('admin.sales.salesList', compact('sales', 'productData', 'productRowSpan', 'productList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $productArr = Product::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productList = ['0' => __('lang.SELECT_PRODUCT_OTP')] + $productArr;
        return view('admin.sales.salesCreate', compact('productList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validatedData = $request->validate([
            'customer_name' => 'required|unique:sales|string|max:255',
        ]);
        $proArray = array();
        if (!empty($request->product)) {
            foreach ($request->product as $key => $products) {
                $proArray[$key]['product_id'] = $products['name'] ?? 0;
                $proArray[$key]['unit_price'] = $products['unit_price'] ?? 0.00;
                $proArray[$key]['quantity'] = $products['quantity'] ?? 0.00;
                $proArray[$key]['total_price'] = $products['total_price'] ?? 0.00;
            }
        }
        $sales = new Sale();
        $sales->product_data = json_encode($proArray);
        $sales->customer_name = $request->customer_name;
        if ($sales->save()) {
            return Redirect()->route('sales')->with('status', 'Sales Created Successfully');
        } else {
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function newRow(Request $request) {
        $productArr = Product::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productList = ['0' => __('lang.SELECT_PRODUCT_OTP')] + $productArr;

        $view = view('admin.sales.newRow', compact('productList'))->render();
        return response()->json(['html' => $view]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function producUnitPrice(Request $request) {
        $unitPriceList = Product::where('id', $request->id)->pluck('unit_price')->toArray();
        return json_encode($unitPriceList);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Download invoice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function invoiceGenerate(Request $request, $id) {
        $sales = Sale::findOrFail($id);

        $productData = [];
        if (!is_null($sales)) {
            foreach ($sales as $sale) {
                $productData[$sales->id] = json_decode($sales->product_data, TRUE);
            }
        }

        $productList = Product::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        if ($request->view == 'print') {
            return view('admin.sales.invoice', compact('sales', 'productData', 'productList'));
        } elseif ($request->view == 'pdf') {
            $pdf = PDF::loadView('admin.sales.invoice', compact('sales', 'productData', 'productList'));
            // For image path
            $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            return $pdf->download('invoice_'. uniqid().'.pdf');
//            return $pdf->stream('invoice.pdf');
        } elseif ($request->view == 'xlsx') {
            return Excel::download(new ExcelExport('admin.sales.invoice', compact('sales', 'productData', 'productList')), 'invoice.xlsx');
        }
    }

    /**
     * Display Sales Report of The Resource
     *
     * @return \Illuminate\Http\Response
     */
    public function salesReport() {
        $saleInfo = Sale::select('product_data', 'created_at')->get();
        $sixMonthsAgo = date('Y-m-d', strtotime('-5 Month'));
        $today = date('Y-m-d');
        
        $startDay = new DateTime($sixMonthsAgo);
        $endDay = new DateTime($today);
        
        $grossQuantitySumArr = $quantitySumArr = $monthArr = [];

        if (!$saleInfo->isEmpty()) {
            foreach ($saleInfo as $info) {
                $saleDate = date('Y-m-d', strtotime($info->created_at));
                if (!empty($info->product_data)) {
                    $productArrList = json_decode($info->product_data, TRUE);
                    if (!empty($productArrList)) {
                        foreach ($productArrList as $pKey => $pInfo) {
                            $grossQuantitySumArr[$saleDate] = $grossQuantitySumArr[$saleDate] ?? 0;
                            $grossQuantitySumArr[$saleDate] += $pInfo['quantity'];
                        }
                    }
                }
            }
        }

        for ($j = $startDay; $j <= $endDay; $j->modify('+1 day')) {
            $day = $j->format("Y-m-d");
            $month = $j->format("Y-m");

            $quantitySumArr[$month] = !empty($quantitySumArr[$month]) ? $quantitySumArr[$month] : 0;
            $quantitySumArr[$month] += !empty($grossQuantitySumArr[$day]) ? $grossQuantitySumArr[$day] : 0;

            $monthArr[$month] = $j->format("F Y");
        }
//
//        echo '<pre>';
//        print_r($grossQuantitySumArr);
//        print_r($quantitySumArr);
//        exit;
        return view('admin.sales.salesReport', compact('monthArr','quantitySumArr'));
    }

}
