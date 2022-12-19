<?php

namespace App\Http\Controllers;

use Validator;
use App\Retailer;
use App\Invoice;
use App\InvoiceDetails;
use App\WhToLocalWhManager;
use App\TmToWarehouse;
use App\WarehouseToRetailer;
use App\SrToRetailer;
use App\Receive;
use App\CompanyInformation;
use Session;
use Redirect;
use Auth;
use Common;
use Input;
use Helper;
use File;
use Response;
use DB;
use PDF;
use Illuminate\Http\Request;

class PaymentDueByRetailerReportController extends Controller {

    public function index(Request $request) {
        $qpArr = $request->all();
        //retailer list
		$whList = [];
		if (Auth::user()->group_id == 12) {
            $whList = WhToLocalWhManager::where('lwm_id', Auth::user()->id)->pluck('warehouse_id', 'warehouse_id')->toArray();
        } elseif (Auth::user()->group_id == 15) {
            $whList = TmToWarehouse::where('tm_id', Auth::user()->id)->pluck('warehouse_id', 'warehouse_id')->toArray();
        }
		
		$rtlArr = WarehouseToRetailer::whereIn('warehouse_id', $whList)->pluck('retailer_id', 'retailer_id')->toArray();
		if (Auth::user()->group_id == 14) {
			$rtlArr = SrToRetailer::where('sr_id', Auth::user()->id)->pluck('retailer_id', 'retailer_id')->toArray();
		}

        $retailerList = Retailer::orderBy('order', 'asc')->where('status', '1')
                ->where('approval_status', '1');
		if (in_array(Auth::user()->group_id, [12, 14, 15])) {
			$retailerList = $retailerList->whereIn('id', $rtlArr);
		}
        $retailerList = $retailerList->pluck('name', 'id')
                ->toArray();
        $receivedArr = $invoiceArr = [];

        //konita info
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }
        //end :: konita info
        
        if ($request->generate == 'true') {
			
			
            $fromDate = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) . ' 00:00:00' : '';
            $toDate = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) . ' 23:59:59' : '';

            $receivedArr = Receive::join('retailer', 'retailer.id', 'receive.retailer_id');

            $invoiceArr = Invoice::join('retailer', 'retailer.id', 'invoice.retailer_id');
			

            if (!empty($fromDate) && !empty($toDate)) {
                $receivedArr = $receivedArr->whereBetween('receive.created_at', [$fromDate, $toDate]);
                $invoiceArr = $invoiceArr->whereBetween('invoice.updated_at', [$fromDate, $toDate]);
            }
            elseif(!empty($fromDate) && empty($toDate)){
                $receivedArr = $receivedArr->where('receive.created_at', '>=',$fromDate);
                $invoiceArr = $invoiceArr->where('invoice.updated_at', '>=',$fromDate);
            }
            elseif(empty($fromDate) && !empty($toDate)){
                $receivedArr = $receivedArr->where('receive.created_at', '<=',$toDate);
                $invoiceArr = $invoiceArr->where('invoice.updated_at', '<=',$toDate);
            }
            $receivedArr = $receivedArr->select(DB::raw('SUM(receive.collection_amount) as total_invoice'), 'receive.retailer_id')
                    ->groupBy('receive.retailer_id')
                    ->pluck('total_invoice', 'receive.retailer_id')->toArray();

            $invoiceArr = $invoiceArr->select(DB::raw('SUM(invoice.net_receivable) as total_received'), 'invoice.retailer_id')
                    ->groupBy('invoice.retailer_id')
                    ->pluck('total_received', 'invoice.retailer_id')->toArray();
            
        }

//        echo '<pre>';print_r($receivedArr); exit;
        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[140][6])) {
                return redirect('dashboard');
            }
            return view('report.paymentDueByRetailer.print.index')->with(compact('retailerList', 'qpArr', 'invoiceArr', 'receivedArr'
                                    , 'request', 'konitaInfo', 'phoneNumber'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[140][9])) {
                return redirect('dashboard');
            }
            $pdf = PDF::loadView('report.paymentDueByRetailer.print.index', compact('retailerList', 'qpArr', 'invoiceArr', 'receivedArr'
                                    , 'request', 'konitaInfo', 'phoneNumber'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('PaymentDueReportByRetailerDistributor.pdf');
//            return $pdf->stream();
        } else {
            return view('report.paymentDueByRetailer.index')->with(compact('qpArr', 'request', 'retailerList', 'invoiceArr', 'receivedArr'));
        }
    }

    public function filter(Request $request) {

        $url = '&from_date=' . $request->from_date
                . '&to_date=' . $request->to_date;

        return Redirect::to('admin/paymentDueByRetailerReport?generate=true&' . $url);
    }

}
