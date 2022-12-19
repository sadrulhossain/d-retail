<?php

namespace App\Http\Controllers;

use Validator;
use App\Retailer;
use App\Invoice;
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

class RetailerLedgerReportController extends Controller {

    public function index(Request $request) {
        $qpArr = $request->all();

        //retailer list
        $retailerIdArr = Invoice::join('retailer', 'retailer.id', 'invoice.retailer_id')
                ->leftJoin('receive', 'receive.retailer_id', '=', 'invoice.retailer_id');
        if (in_array(Auth::user()->group_id, [1, 11])) {
            $retailerIdArr = $retailerIdArr->pluck('retailer.id', 'retailer.id')->toArray();
        } elseif (in_array(Auth::user()->group_id, [12])) {
            $retailerIdArr = $retailerIdArr->join('order', 'order.id', 'invoice.order_id')
                            ->join('wh_to_local_wh_manager', 'wh_to_local_wh_manager.warehouse_id', 'order.warehouse_id')
                            ->join('warehouse_to_retailer', 'warehouse_to_retailer.warehouse_id', 'wh_to_local_wh_manager.warehouse_id')
                            ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id)
                            ->pluck('retailer.id', 'retailer.id')->toArray();
        } else {
            $retailerIdArr = [];
        }

        $retailerArr = Retailer::whereIn('id', $retailerIdArr)->where('approval_status', '1');

        $retailerCodeArr = $retailerArr->pluck('code', 'id')->toArray();
        $retailerArr = $retailerArr->pluck('name', 'id')->toArray();

        $retailerList = [0 => __('label.SELECT_RETAILER_OPT')] + $retailerArr;
        //end :: retailer list
//      
        $invoiceNoList = Invoice::pluck('invoice_no', 'id')->toArray();

        //konita info
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }
        //end :: konita info

        $ledgerArr = [];
        $balanceArr = [];
        $previousBalance = 0;
        $totalBilled = 0;
        $totalReceived = 0;
        $totalBalance = 0;

        $retailerCode = '';

        if ($request->generate == 'true') {
            $retailerId = $request->retailer_id;
            $retailerCode = '-' . $retailerCodeArr[$retailerId];
            $fromDate = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) . ' 00:00:00' : '';
            $toDate = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) . ' 23:59:59' : '';

            //billed info
            $billedInfo = Invoice::select('id', 'invoice_no', 'net_receivable', 'updated_at')
                    ->where('retailer_id', $retailerId);

            if (!empty($fromDate) && !empty($toDate)) {
                $billedInfo = $billedInfo->whereBetween('updated_at', [$fromDate, $toDate]);
            }

            $billedInfo = $billedInfo->get();

            if (!$billedInfo->isEmpty()) {
                foreach ($billedInfo as $bill) {
                    $ledgerArr[$bill->updated_at][$bill->id]['billed'] = $bill->net_receivable;
                }
            }
            //end :: billed info
            //received info
            $receivedInfo = Receive::select(DB::raw('SUM(collection_amount) as net_received'), 'invoice_id', 'created_at')
                    ->groupBy('created_at', 'invoice_id')
                    ->where('retailer_id', $retailerId);

            if (!empty($fromDate) && !empty($toDate)) {
                $receivedInfo = $receivedInfo->whereBetween('created_at', [$fromDate, $toDate]);
            }

            $receivedInfo = $receivedInfo->get();

            if (!$receivedInfo->isEmpty()) {
                foreach ($receivedInfo as $receive) {
                    $ledgerArr[$receive->created_at][$receive->invoice_id]['received'] = $receive->net_received;
                }
            }
            ksort($ledgerArr);

            //end :: received info
            //previous balance set
            if (!empty($fromDate)) {
                $previousBilledInfo = Invoice::select(DB::raw('SUM(net_receivable) as total_billed'))
                        ->where('retailer_id', $retailerId)->where('updated_at', '<', $fromDate)
                        ->first();
                $previousReceivedInfo = Receive::select(DB::raw('SUM(collection_amount) as total_received'))
                        ->where('retailer_id', $retailerId)->where('created_at', '<', $fromDate)
                        ->first();

                $previousBilled = !empty($previousBilledInfo->total_billed) ? $previousBilledInfo->total_billed : 0;
                $previousReceived = !empty($previousReceivedInfo->total_received) ? $previousReceivedInfo->total_received : 0;
                $previousBalance = $previousBilled - $previousReceived;
            }

            //end :: previous balance set
            //balance sheet
            if (!empty($ledgerArr)) {
                $balance = $previousBalance;
                foreach ($ledgerArr as $dateTime => $invoiceList) {
                    foreach ($invoiceList as $invoiceId => $amount) {
                        $billed = !empty($amount['billed']) ? $amount['billed'] : 0;
                        $received = !empty($amount['received']) ? $amount['received'] : 0;
                        $balance = $balance + $billed - $received;
                        $balanceArr[$dateTime][$invoiceId] = $balance;
                        $totalBilled += $billed;
                        $totalReceived += $received;
                        $totalBalance = $previousBalance + $totalBilled - $totalReceived;
                    }
                }
            }

            //end :: balance sheet
        }

        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[111][6])) {
                return redirect('dashboard');
            }
            return view('report.retailerLedger.print.index')->with(compact('retailerList', 'qpArr', 'invoiceNoList'
                                    , 'ledgerArr', 'previousBalance', 'balanceArr', 'totalBalance'
                                    , 'totalBilled', 'totalReceived', 'request', 'konitaInfo'
                                    , 'phoneNumber'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[111][9])) {
                return redirect('dashboard');
            }
            $pdf = PDF::loadView('report.retailerLedger.print.index', compact('retailerList', 'qpArr', 'invoiceNoList'
                                    , 'ledgerArr', 'previousBalance', 'balanceArr', 'totalBalance'
                                    , 'totalBilled', 'totalReceived', 'request', 'konitaInfo'
                                    , 'phoneNumber'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('Ledger-' . $retailerCode . '-' . date('YmdHis') . '.pdf');
//            return $pdf->stream();
        } else {
            return view('report.retailerLedger.index')->with(compact('retailerList', 'qpArr', 'invoiceNoList'
                                    , 'ledgerArr', 'previousBalance', 'balanceArr', 'totalBalance'
                                    , 'totalBilled', 'totalReceived', 'request', 'konitaInfo'
                                    , 'phoneNumber'));
        }
    }

    public function filter(Request $request) {
        
        $messages = ['retailer_id.not_in'=>'Retailer field is invalid'];
        $rules = [
            'retailer_id' => 'required|not_in:0',
        ];

        if (!empty($request->from_date)) {
            $rules['to_date'] = 'required';
            $messages['to_date.required'] = __('label.THE_TO_DATE_FIELD_IS_REQUIRED');
        }
        if (!empty($request->to_date)) {
            $rules['from_date'] = 'required';
            $messages['from_date.required'] = __('label.THE_FROM_DATE_FIELD_IS_REQUIRED');
        }

        $url = 'retailer_id=' . $request->retailer_id . '&from_date=' . $request->from_date
                . '&to_date=' . $request->to_date;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('admin/retailerLedgerReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }

        return Redirect::to('admin/retailerLedgerReport?generate=true&' . $url);
    }

}
