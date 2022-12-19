<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\Supplier;
use App\User;
use App\SupplierToProduct;
use App\SalesPersonToProduct;
use App\ProductToBrand;
use App\Brand;
use App\Lead;
use App\InquiryDetails;
use App\Delivery;
use App\Invoice;
use App\InvoiceDetails;
use App\Receive;
use App\Retailer;
use Response;
use Auth;
use DB;
use Redirect;
use Session;
use Helper;
use Illuminate\Http\Request;

class ReceiveController extends Controller {

    public function create(Request $request) {
        $retailerArr = Invoice::join('retailer', 'retailer.id', '=', 'invoice.retailer_id')
                        ->where('invoice.payment_status', '0')
                        ->pluck('retailer.name', 'retailer.id')->toArray();
        $retailerList = array('0' => __('label.SELECT_RETAILER_OPT')) + $retailerArr;

        return view('receive.create')->with(compact('retailerList'));
    }

    //receive data against invoice
    public function getReceiveData(Request $request) {
        $receivedAmountHistoryArr = Receive::select('invoice_id', 'order_id', 'delivery_id', 'collection_amount')
                        ->where('retailer_id', $request->retailer_id)->get();

        $invoiceCollection = $blCollection = [];
        if (!$receivedAmountHistoryArr->isEmpty()) {
            foreach ($receivedAmountHistoryArr as $amount) {
                $invoiceCollection[$amount->invoice_id]['received'] = $invoiceCollection[$amount->invoice_id]['received'] ?? 0;
                $invoiceCollection[$amount->invoice_id]['received'] += $amount->collection_amount;


                $blCollection[$amount->invoice_id][$amount->order_id][$amount->delivery_id]['received'] = $blCollection[$amount->invoice_id][$amount->order_id][$amount->delivery_id]['received'] ?? 0;
                $blCollection[$amount->invoice_id][$amount->order_id][$amount->delivery_id]['received'] += $amount->collection_amount;
            }
        }

        //get invoice wise commission history of order
        $invoiceHistoryArr = InvoiceDetails::join('invoice', 'invoice.id', '=', 'invoice_details.invoice_id')
                ->join('order', 'order.id', '=', 'invoice.order_id')
                ->join('delivery', 'delivery.id', '=', 'invoice.delivery_id')
                ->select('invoice.invoice_no', 'invoice.net_receivable', 'order.order_no', 'delivery.bl_no', 'delivery.payment_mode'
                        , 'invoice_details.*', 'invoice.order_id', 'invoice.delivery_id', 'invoice.net_receivable')
                ->where('invoice.retailer_id', $request->retailer_id)
                ->where('invoice.payment_status', '0')
                ->get();

        $invoiceDetailsArr = $orderDetailsArr = $deliveryDetailsArr = [];
        $invoiceRowSpan = $orderRowSpan = $orderRowSpan2 = [];
        if (!$invoiceHistoryArr->isEmpty()) {
            foreach ($invoiceHistoryArr as $history) {
                $invoiceDetailsArr[$history->invoice_id]['invoice_no'] = $history->invoice_no;
                $invoiceDetailsArr[$history->invoice_id]['billed'] = $history->net_receivable;
                $invoiceDetailsArr[$history->invoice_id]['order_no'] = $history->order_no;
                $invoiceDetailsArr[$history->invoice_id]['payment_mode'] = $history->payment_mode;
                $invoiceDetailsArr[$history->invoice_id]['order_id'] = $history->order_id;
                $invoiceDetailsArr[$history->invoice_id]['delivery_id'] = $history->delivery_id;

                $invoiceCollection[$history->invoice_id]['due'] = $history->net_receivable - ($invoiceCollection[$history->invoice_id]['received'] ?? 0);


                $invoiceDetailsArr[$history->invoice_id]['bl_no'] = $history->bl_no;

                $invoiceRowSpan[$history->invoice_id] = 1;
            }
        }


        $view = view('receive.showReceiveData', compact('request', 'invoiceDetailsArr', 'orderDetailsArr'
                        , 'deliveryDetailsArr', 'invoiceRowSpan', 'orderRowSpan', 'invoiceCollection', 'blCollection'))->render();
        return response()->json(['html' => $view]);
    }

    //preview payment receive data
    public function previewReceiveData(Request $request) {
//        echo "<pre>";
//        print_r($request->all());
//        exit;

        //validation
        $rules = $message = [];
        $rules = [
            'retailer_id' => 'required|not_in:0'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $inv = [];

        if (count(array_filter($request->collection_amount)) != 0) {
            foreach ($request->collection_amount as $invoiceId => $collectionAmount) {
                if (!empty($collectionAmount) && $collectionAmount != 0) {
                    if ($request->payment_mode[$invoiceId] == 3) {
                        if ($request->transaction_id[$invoiceId] == '') {
                            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => [__('label.PLEASE_INSERT_TRANSACTION_ID')]), 400);
                        }
                    }
                }
            }
        }

        if (count(array_filter($request->collection_amount)) == 0) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => [__('label.PLEASE_INSERT_COLLECTION_AMOUNT_TO_ATLEAST_ONE_INVOICE')]), 400);
        } else {
            foreach ($request->collection_amount as $invoiceId => $collectionAmount) {
                if (!empty($collectionAmount) && empty($request->full_pay[$invoiceId])) {
                    $invoiceNo = $request->invoice_no[$invoiceId] ?? '';
                    $orderNo = $request->order_no[$invoiceId] ?? '';
                    $blNo = $request->bl_no[$invoiceId] ?? '';
                    $due = $request->invoice_due[$invoiceId] ?? 0.00;
                    if ($collectionAmount > $due) {
                        $message[$invoiceId] = __('label.COLLECTION_AMOUNT_OF_THIS_INVOICE_MUST_NOT_BE_GREATER_THAN_DUE_AMOUNT', ['invoice_no' => $invoiceNo]);
                    }
                }
            }

            if (!empty($message)) {
                return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $message), 400);
            }
        }

        $i = 0;
        $receive = $receiveList = [];
        if (count(array_filter($request->collection_amount)) != 0) {
            foreach ($request->collection_amount as $invoiceId => $collectionAmount) {
                if (!empty($collectionAmount) && $collectionAmount != 0) {

                    $receiveList[$invoiceId]['invoice_no'] = $request->invoice_no[$invoiceId];
                    $receiveList[$invoiceId]['order_no'] = $request->order_no[$invoiceId];

                    $receiveList[$invoiceId]['bl_no'] = $request->bl_no[$invoiceId];
                    $receiveList[$invoiceId]['transaction_id'] = $request->transaction_id[$invoiceId];
                    $receiveList[$invoiceId]['collection_amount'] = $collectionAmount;



                    $receive[$i]['retailer_id'] = $request->retailer_id;
                    $receive[$i]['invoice_id'] = $invoiceId;
                    $receive[$i]['order_id'] = $request->order_id[$invoiceId];
                    $receive[$i]['transaction_id'] = $request->transaction_id[$invoiceId];
                    $receive[$i]['delivery_id'] = $request->delivery_id[$invoiceId];
                    $receive[$i]['collection_amount'] = $collectionAmount;
                    $receive[$i]['created_at'] = date('Y-m-d H:i:s');
                    $receive[$i]['created_by'] = Auth::user()->id;
                    $i++;
                }
            }
        }
        
//        echo "<pre>";
//        print_r($receiveList);
//        exit;
        

        $retailer = Retailer::select('name')->where('id', $request->retailer_id)->first();

        $receive = json_encode($receive);

        $view = view('receive.showReceivePreview', compact('request', 'retailer', 'receive', 'receiveList'))->render();
        return response()->json(['html' => $view]);
    }

    public function setReceiveData(Request $request) {
        $receive = json_decode($request->receive, true);
        

        DB::beginTransaction();
        try {
            if (Receive::insert($receive)) {
                $receivedAmountHistoryArr = Receive::join('invoice', 'invoice.id', '=', 'receive.invoice_id')
                                ->select('receive.invoice_id', 'receive.order_id', 'receive.delivery_id'
                                        , 'receive.collection_amount', 'invoice.net_receivable')
                                ->where('receive.retailer_id', $request->retailer_id)->get();

                $invoiceCollection = $blCollection = $yes = [];
                if (!$receivedAmountHistoryArr->isEmpty()) {
                    foreach ($receivedAmountHistoryArr as $amount) {
                        $invoiceCollection[$amount->invoice_id]['received'] = $invoiceCollection[$amount->invoice_id]['received'] ?? 0;
                        $invoiceCollection[$amount->invoice_id]['received'] += $amount->collection_amount;
                        $invoiceCollection[$amount->invoice_id]['billed'] = $amount->net_receivable;
                        $received = Helper::numberFormat2Digit($invoiceCollection[$amount->invoice_id]['received']);
                        $billed = Helper::numberFormat2Digit($amount->net_receivable);
                        $invoiceCollection[$amount->invoice_id]['due'] = $amount->net_receivable - $invoiceCollection[$amount->invoice_id]['received'];
                        $due = $invoiceCollection[$amount->invoice_id]['due'];

                        if ($due < 0.01) {
//                        $yes[$amount->invoice_id] = 'yes';
                            Invoice::where('id', $amount->invoice_id)->update(['payment_status' => '1']);
                            Delivery::where('id', $amount->delivery_id)->update(['payment_status' => '1']);
                        }
                    }
                }
            }

            DB::commit();
            return Response::json(array('heading' => 'Success', 'message' => __('label.PAYMENT_RECEIVED_SUCCESSFULLY')), 201);
        } catch (Exception $ex) {
            DB::rollback();
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_RECEIVE_PAYMENT')), 401);
        }
    }

}
