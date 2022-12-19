<?php

namespace App\Http\Controllers;

use Validator;
use Session;
use Redirect;
use App\Warehouse;
use App\WhToLocalWhManager;
use App\TmToWarehouse;
use App\Product;
use App\ProductSKUCode;
use App\ProductCheckInDetails;
use App\ProductCheckInMaster;
use App\ProductAdjustmentMaster;
use App\ProductAdjustmentDetails;
use App\ProductReturn;
use App\ProductReturnDetails;
use App\ProductWhReturn;
use App\ProductWhReturnDetails;
use App\ProductTransferMaster;
use App\ProductTransferDetails;
use App\CompanyInformation;
use Helper;
use DB;
use Auth;
use Common;
use PDF;
use Input;
use Illuminate\Http\Request;

class CentralStockLedgerReportController extends Controller {

    //Stock Summary Report
    public function index(Request $request) {


        $fromDate = $toDate = '';
        $totalAmount = 0;
        $targetArr = $productCheckInInfo = $ledgerArr = $previousBalance = [];
        $totalBalance = $totalTransfer = $totalReturn = $totalDamage = $totalCheckIn = $balanceArr = [];


        if ($request->generate == 'true') {
            $fromDate = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) : '';
            $toDate = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) : '';

            //Start :: Check in
            //current checkin
            $productCheckInInfo = ProductCheckInDetails::join('product_checkin_master', 'product_checkin_master.id', 'product_checkin_details.master_id')
                    ->join('product', 'product.id', '=', 'product_checkin_details.product_id')
                    ->join('product_sku_code', 'product_sku_code.id', '=', 'product_checkin_details.sku_id')
                    ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                    ->join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                    ->whereBetween('product_checkin_master.checkin_date', [$fromDate, $toDate])
                    ->select('product.name as product', 'brand.name as brand', 'product_sku_code.sku as sku_code'
                            , 'product_checkin_master.checkin_date as date', 'product_checkin_master.ref_no'
                            , 'product_checkin_master.challan_no', 'product_checkin_details.quantity'
                            , 'product_checkin_details.rate', 'product_checkin_details.amount'
                            , 'product_unit.name as unit')
                    ->orderBy('product_checkin_master.checkin_date', 'desc')
                    ->get();
            $i = 0;
            if (!$productCheckInInfo->isEmpty()) {
                foreach ($productCheckInInfo as $ckInfo) {
                    $ledgerArr[$ckInfo->date][$i]['1'] = $ckInfo->toArray();
                    $ledgerArr[$ckInfo->date][$i]['1']['type'] = __('label.PRODEUCT_CHECKED_IN');
                    $i++;
                }
            }

            //previous checkin
            $prevCheckInInfo = ProductCheckInDetails::join('product_checkin_master', 'product_checkin_master.id', 'product_checkin_details.master_id')
                    ->where('product_checkin_master.checkin_date', '<', $fromDate)
                    ->select(DB::raw("SUM(product_checkin_details.quantity) as total_quantity")
                            , DB::raw("SUM(product_checkin_details.amount) as total_amount"))
                    ->first();

            $previousCheckIn['quantity'] = !empty($prevCheckInInfo->total_quantity) ? $prevCheckInInfo->total_quantity : 0;
            $previousCheckIn['amount'] = !empty($prevCheckInInfo->total_amount) ? $prevCheckInInfo->total_amount : 0;
            //End :: Check in
            //Start :: damage adjustment
            //current damage
            $productDamageInfo = ProductAdjustmentDetails::join('product_adjustment_master', 'product_adjustment_master.id', 'product_adjustment_details.master_id')
                    ->join('product_sku_code', 'product_sku_code.id', '=', 'product_adjustment_details.sku_id')
                    ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                    ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                    ->join('product_unit', 'product_unit.id', '=', 'product.product_unit_id');
            if (!empty($request->product_id)) {
                $productDamageInfo = $productDamageInfo->where('product_sku_code.product_id', $request->product_id);
            }
            $productDamageInfo = $productDamageInfo->whereBetween('product_adjustment_master.adjustment_date', [$fromDate, $toDate])
                    ->select('product.name as product', 'brand.name as brand', 'product_sku_code.sku as sku_code'
                            , 'product_adjustment_master.adjustment_date as date', 'product_adjustment_master.reference_no as ref_no'
                            , 'product_adjustment_details.quantity', 'product_sku_code.selling_price as rate'
                            , DB::raw("(product_sku_code.selling_price*product_adjustment_details.quantity) as amount")
                            , 'product_unit.name as unit')
                    ->orderBy('product_adjustment_master.adjustment_date', 'desc')
                    ->get();
            if (!$productDamageInfo->isEmpty()) {
                foreach ($productDamageInfo as $dmInfo) {
                    $ledgerArr[$dmInfo->date][$i]['2'] = $dmInfo->toArray();
                    $ledgerArr[$dmInfo->date][$i]['2']['type'] = __('label.PRODUCT_DAMMAGED');
                    $i++;
                }
            }

            //prevoius damage
            $prevDamageInfo = ProductAdjustmentDetails::join('product_adjustment_master', 'product_adjustment_master.id', 'product_adjustment_details.master_id')
                    ->join('product_sku_code', 'product_sku_code.id', '=', 'product_adjustment_details.sku_id')
                    ->where('product_adjustment_master.adjustment_date', '<', $fromDate)
                    ->select(DB::raw("SUM(product_adjustment_details.quantity) as total_quantity")
                            , DB::raw("SUM(product_sku_code.selling_price*product_adjustment_details.quantity) as total_amount"))
                    ->first();

            $previousDamage['quantity'] = !empty($prevDamageInfo->total_quantity) ? $prevDamageInfo->total_quantity : 0;
            $previousDamage['amount'] = !empty($prevDamageInfo->total_amount) ? $prevDamageInfo->total_amount : 0;
            //End :: damage adjustment
            //Start :: Stock Transfer
            //current transfer
            $stockTransferInfo = ProductTransferDetails::join('product_transfer_master', 'product_transfer_master.id', 'product_transfer_details.master_id')
                    ->join('product_sku_code', 'product_sku_code.id', '=', 'product_transfer_details.sku_id')
                    ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                    ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                    ->join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                    ->join('warehouse', 'warehouse.id', '=', 'product_transfer_master.warehouse_id')
                    ->whereBetween('product_transfer_master.transfer_date', [$fromDate, $toDate])
                    ->select('product.name as product', 'brand.name as brand', 'product_sku_code.sku as sku_code'
                            , 'product_transfer_master.transfer_date as date', 'product_transfer_master.reference_no as ref_no'
                            , 'product_transfer_details.quantity', 'product_sku_code.selling_price as rate'
                            , DB::raw("(product_sku_code.selling_price * product_transfer_details.quantity) as amount")
                            , 'product_transfer_master.remarks', 'product_unit.name as unit', 'warehouse.name as warehouse')
                    ->orderBy('product_transfer_master.transfer_date', 'desc')
                    ->get();
            
            if (!$stockTransferInfo->isEmpty()) {
                foreach ($stockTransferInfo as $strInfo) {
                    $ledgerArr[$strInfo->date][$i]['3'] = $strInfo->toArray();
                    $ledgerArr[$strInfo->date][$i]['3']['type'] = __('label.STOCK_TRANSFERRED_TO_WH', ['wh' => $strInfo->warehouse]);
                    $i++;
                }
            }

            //previous transfer
            $prevTransferInfo = ProductTransferDetails::join('product_transfer_master', 'product_transfer_master.id', 'product_transfer_details.master_id')
                    ->join('product_sku_code', 'product_sku_code.id', '=', 'product_transfer_details.sku_id')
                    ->where('product_transfer_master.transfer_date', '<', $fromDate)
                    ->select(DB::raw("SUM(product_transfer_details.quantity) as total_quantity")
                            , DB::raw("SUM(product_sku_code.selling_price * product_transfer_details.quantity) as total_amount"))
                    ->first();

            $previousTransfer['quantity'] = !empty($prevTransferInfo->total_quantity) ? $prevTransferInfo->total_quantity : 0;
            $previousTransfer['amount'] = !empty($prevTransferInfo->total_amount) ? $prevTransferInfo->total_amount : 0;
            //End :: Stock Transfer
            //Start :: Stock Return
            //current return
            $stockReturnInfo = ProductWhReturnDetails::join('product_wh_return', 'product_wh_return.id', 'product_wh_return_details.product_wh_return_id')
                    ->join('product_sku_code', 'product_sku_code.id', '=', 'product_wh_return_details.sku_id')
                    ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                    ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                    ->join('warehouse', 'warehouse.id', '=', 'product_wh_return.warehouse_id')
                    ->join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                    ->whereBetween('product_wh_return.return_date', [$fromDate, $toDate])
                    ->select('product.name as product', 'brand.name as brand', 'product_sku_code.sku as sku_code'
                            , 'product_wh_return.return_date as date', 'product_wh_return.reference_no as ref_no'
                            , 'product_wh_return_details.quantity', 'product_sku_code.selling_price as rate'
                            , DB::raw("(product_sku_code.selling_price * product_wh_return_details.quantity) as amount")
                            , 'product_wh_return.remarks', 'product_unit.name as unit', 'warehouse.name as warehouse')
                    ->orderBy('product_wh_return.return_date', 'desc')
                    ->get();
            
            if (!$stockReturnInfo->isEmpty()) {
                foreach ($stockReturnInfo as $srtInfo) {
                    $ledgerArr[$srtInfo->date][$i]['4'] = $srtInfo->toArray();
                    $ledgerArr[$srtInfo->date][$i]['4']['type'] = __('label.STOCK_RETURNED_FROM_WH', ['wh' => $srtInfo->warehouse]);
                    $i++;
                }
            }

            //previous return
            $prevStockReturnInfo = ProductWhReturnDetails::join('product_wh_return', 'product_wh_return.id', 'product_wh_return_details.product_wh_return_id')
                    ->join('product_sku_code', 'product_sku_code.id', '=', 'product_wh_return_details.sku_id')
                    ->where('product_wh_return.return_date', '<', $fromDate)
                    ->select(DB::raw("SUM(product_wh_return_details.quantity) as total_quantity")
                            , DB::raw("SUM(product_sku_code.selling_price * product_wh_return_details.quantity) as total_amount"))
                    ->first();

            $previousStockReturn['quantity'] = !empty($prevStockReturnInfo->total_quantity) ? $prevStockReturnInfo->total_quantity : 0;
            $previousStockReturn['amount'] = !empty($prevStockReturnInfo->total_amount) ? $prevStockReturnInfo->total_amount : 0;
            //End :: Stock Return

            krsort($ledgerArr);

            $previousBalance['quantity'] = $previousCheckIn['quantity'] + $previousStockReturn['quantity'] - ($previousDamage['quantity'] + $previousTransfer['quantity']);
            $previousBalance['amount'] = $previousCheckIn['amount'] + $previousStockReturn['amount'] - ($previousDamage['amount'] + $previousTransfer['amount']);

            if (!empty($ledgerArr)) {
                $previousBalance['quantity'] = !empty($previousBalance['quantity']) ? $previousBalance['quantity'] : 0;
                $previousBalance['amount'] = !empty($previousBalance['amount']) ? $previousBalance['amount'] : 0;
                $balance['quantity'] = $previousBalance['quantity'];
                $balance['amount'] = $previousBalance['amount'];

                foreach ($ledgerArr as $date => $ledgerInfo) {
                    foreach ($ledgerInfo as $index => $indexInfo) {
                        foreach ($indexInfo as $type => $info) {
                            $checkIn['quantity'] = $checkIn['amount'] = 0;
                            $damage['quantity'] = $damage['amount'] = 0;
                            $return['quantity'] = $return['amount'] = 0;
                            $transfer['quantity'] = $transfer['amount'] = 0;

                            if ($type == '1') {
                                $checkIn['quantity'] = !empty($info['quantity']) ? $info['quantity'] : 0;
                                $checkIn['amount'] = !empty($info['amount']) ? $info['amount'] : 0;
                            } elseif ($type == '2') {
                                $damage['quantity'] = !empty($info['quantity']) ? $info['quantity'] : 0;
                                $damage['amount'] = !empty($info['amount']) ? $info['amount'] : 0;
                            } elseif ($type == '3') {
                                $transfer['quantity'] = !empty($info['quantity']) ? $info['quantity'] : 0;
                                $transfer['amount'] = !empty($info['amount']) ? $info['amount'] : 0;
                            } elseif ($type == '4') {
                                $return['quantity'] = !empty($info['quantity']) ? $info['quantity'] : 0;
                                $return['amount'] = !empty($info['amount']) ? $info['amount'] : 0;
                            }

                            $balance['quantity'] = $balance['quantity'] + $checkIn['quantity'] + $return['quantity'] - ($damage['quantity'] + $transfer['quantity']);
                            $balance['amount'] = $balance['amount'] + $checkIn['amount'] + $return['amount'] - ($damage['amount'] + $transfer['amount']);

                            $balanceArr[$date][$index][$type] = $balance;

                            $totalCheckIn['quantity'] = !empty($totalCheckIn['quantity']) ? $totalCheckIn['quantity'] : 0;
                            $totalCheckIn['quantity'] += $checkIn['quantity'];
                            $totalCheckIn['amount'] = !empty($totalCheckIn['amount']) ? $totalCheckIn['amount'] : 0;
                            $totalCheckIn['amount'] += $checkIn['amount'];

                            $totalDamage['quantity'] = !empty($totalDamage['quantity']) ? $totalDamage['quantity'] : 0;
                            $totalDamage['quantity'] += $damage['quantity'];
                            $totalDamage['amount'] = !empty($totalDamage['amount']) ? $totalDamage['amount'] : 0;
                            $totalDamage['amount'] += $damage['amount'];

                            $totalReturn['quantity'] = !empty($totalReturn['quantity']) ? $totalReturn['quantity'] : 0;
                            $totalReturn['quantity'] += $return['quantity'];
                            $totalReturn['amount'] = !empty($totalReturn['amount']) ? $totalReturn['amount'] : 0;
                            $totalReturn['amount'] += $return['amount'];

                            $totalTransfer['quantity'] = !empty($totalTransfer['quantity']) ? $totalTransfer['quantity'] : 0;
                            $totalTransfer['quantity'] += $transfer['quantity'];
                            $totalTransfer['amount'] = !empty($totalTransfer['amount']) ? $totalTransfer['amount'] : 0;
                            $totalTransfer['amount'] += $transfer['amount'];

                            $totalBalance['quantity'] = !empty($totalBalance['quantity']) ? $totalBalance['quantity'] : 0;
                            $totalBalance['quantity'] = $previousBalance['quantity'] + $totalCheckIn['quantity'] + $totalReturn['quantity'] - ($totalDamage['quantity'] + $totalTransfer['quantity']);
                            $totalBalance['amount'] = !empty($totalBalance['amount']) ? $totalBalance['amount'] : 0;
                            $totalBalance['amount'] = $previousBalance['amount'] + $totalCheckIn['amount'] + $totalReturn['amount'] - ($totalDamage['amount'] + $totalTransfer['amount']);
                        }
                    }
                }
            }
        }

        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }
        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[118][6])) {
                return redirect('dashboard');
            }
            return view('report.centralStockLedger.print.index')->with(compact('request', 'targetArr', 'totalAmount'
                                    , 'konitaInfo', 'phoneNumber', 'fromDate', 'toDate', 'totalBalance'
                                    , 'totalTransfer', 'totalReturn', 'totalDamage', 'totalCheckIn', 'balanceArr'
                                    , 'ledgerArr', 'previousBalance'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[118][9])) {
                return redirect('/dashboard');
            }
            $pdf = PDF::loadView('report.centralStockLedger.print.index', compact('request', 'targetArr', 'totalAmount'
                                    , 'konitaInfo', 'phoneNumber', 'fromDate', 'toDate', 'totalBalance'
                                    , 'totalTransfer', 'totalReturn', 'totalDamage', 'totalCheckIn', 'balanceArr'
                                    , 'ledgerArr', 'previousBalance'))
                    ->setPaper('a3', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('central_stock_ledger_report.pdf');
//            return $pdf->stream();
        } else {
            return view('report.centralStockLedger.index')->with(compact('request', 'targetArr', 'totalAmount'
                                    , 'konitaInfo', 'phoneNumber', 'fromDate', 'toDate', 'totalBalance', 'totalTransfer'
                                    , 'totalReturn', 'totalDamage', 'totalCheckIn', 'balanceArr', 'ledgerArr'
                                    , 'previousBalance'));
        }
    }

    public function filter(Request $request) {
        $messages = [];
        $rules = [
            'from_date' => 'required',
            'to_date' => 'required',
        ];

        $messages = [
            'from_date.required' => __('label.THE_FROM_DATE_FIELD_IS_REQUIRED'),
            'to_date.required' => __('label.THE_TO_DATE_FIELD_IS_REQUIRED'),
        ];
        $url = 'from_date=' . $request->from_date . '&to_date=' . $request->to_date;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('admin/centralStockLedgerReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }


        return Redirect::to('admin/centralStockLedgerReport?generate=true&' . $url);
    }

}
