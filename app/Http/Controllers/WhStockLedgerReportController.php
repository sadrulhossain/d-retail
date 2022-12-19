<?php

namespace App\Http\Controllers;

use Validator;
use Session;
use Redirect;
use App\Warehouse;
use App\WhToLocalWhManager;
use App\Product;
use App\ProductWhReturn;
use App\ProductWhReturnDetails;
use App\ProductReturn;
use App\ProductReturnDetails;
use App\ProductTransferMaster;
use App\ProductTransferDetails;
use App\Delivery;
use App\DeliveryDetails;
use App\CompanyInformation;
use Helper;
use DB;
use Auth;
use Common;
use PDF;
use Input;
use Illuminate\Http\Request;

class WhStockLedgerReportController extends Controller {

    //Stock Summary Report
    public function index(Request $request) {
        $whList = [];
        if (in_array(Auth::user()->group_id, [1, 11])) {
            $whList = ['0' => __('label.SELECT_WAREHOUSE_OPT')] + Warehouse::where('allowed_for_central_warehouse', '0')
                            ->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        } elseif (in_array(Auth::user()->group_id, [12])) {
            $whList = WhToLocalWhManager::join('warehouse', 'warehouse.id', 'wh_to_local_wh_manager.warehouse_id')
                            ->where('warehouse.allowed_for_central_warehouse', '0')
                            ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id)
                            ->pluck('warehouse.name', 'warehouse.id')->toArray();
        }



        $fromDate = $toDate = '';
        $totalAmount = 0;
        $targetArr = $stockTransferInfo = $ledgerArr = $previousBalance = [];
        $totalBalance = $totalDelivery = $totalReturn = $totalStockReturn = $totalTransfer = $balanceArr = [];


        if ($request->generate == 'true') {
            $fromDate = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) : '';
            $toDate = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) : '';

            //Start :: Stock Transfer
            //current transfer
            $stockTransferInfo = ProductTransferDetails::join('product_transfer_master', 'product_transfer_master.id', 'product_transfer_details.master_id')
                    ->join('product_sku_code', 'product_sku_code.id', '=', 'product_transfer_details.sku_id')
                    ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                    ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                    ->leftJoin('warehouse', 'warehouse.id', '=', 'product_transfer_master.tr_warehouse_id')
                    ->join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                    ->where('product_transfer_master.warehouse_id', $request->wh_id)
                    ->whereBetween('product_transfer_master.transfer_date', [$fromDate, $toDate])
                    ->select('product.name as product', 'brand.name as brand', 'product_sku_code.sku as sku_code'
                            , 'product_transfer_master.transfer_date as date', 'product_transfer_master.reference_no'
                            , 'product_transfer_details.quantity', 'product_sku_code.selling_price as rate', 'product_transfer_master.tr_warehouse_id'
                            , DB::raw("(product_sku_code.selling_price * product_transfer_details.quantity) as amount")
                            , 'product_transfer_master.remarks', 'product_unit.name as unit', 'warehouse.name as tr_warehouse')
                    ->orderBy('product_transfer_master.transfer_date', 'desc')
                    ->get();
            $i = 0;
            if (!$stockTransferInfo->isEmpty()) {
                foreach ($stockTransferInfo as $strInfo) {
                    $warehouseName = $strInfo->tr_warehouse ?? __('label.CENTRAL_WAREHOUSE');
                    $ledgerArr[$strInfo->date][$i]['1'] = $strInfo->toArray();
                    $ledgerArr[$strInfo->date][$i]['1']['type'] = __('label.STOCK_TRANSFERRED_FROM_WH', ['warehouseName' => $warehouseName]);
                    $i++;
                }
            }

            //previous transfer
            $prevTransferInfo = ProductTransferDetails::join('product_transfer_master', 'product_transfer_master.id', 'product_transfer_details.master_id')
                    ->join('product_sku_code', 'product_sku_code.id', '=', 'product_transfer_details.sku_id')
                    ->where('product_transfer_master.warehouse_id', $request->wh_id)
                    ->where('product_transfer_master.transfer_date', '<', $fromDate)
                    ->select(DB::raw("SUM(product_transfer_details.quantity) as total_quantity")
                            , DB::raw("SUM(product_sku_code.selling_price * product_transfer_details.quantity) as total_amount"))
                    ->first();

            $previousTransfer['quantity'] = !empty($prevTransferInfo->total_quantity) ? $prevTransferInfo->total_quantity : 0;
            $previousTransfer['amount'] = !empty($prevTransferInfo->total_amount) ? $prevTransferInfo->total_amount : 0;
            //End :: Stock Transfer
            //Start :: Stock Transfer to local
            //current transfer
            $stockTransferLocalInfo = ProductTransferDetails::join('product_transfer_master', 'product_transfer_master.id', 'product_transfer_details.master_id')
                    ->join('product_sku_code', 'product_sku_code.id', '=', 'product_transfer_details.sku_id')
                    ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                    ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                    ->leftJoin('warehouse', 'warehouse.id', '=', 'product_transfer_master.tr_warehouse_id')
                    ->join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                    ->where('product_transfer_master.tr_warehouse_id', $request->wh_id)
                    ->whereBetween('product_transfer_master.transfer_date', [$fromDate, $toDate])
                    ->select('product.name as product', 'brand.name as brand', 'product_sku_code.sku as sku_code'
                            , 'product_transfer_master.transfer_date as date', 'product_transfer_master.reference_no'
                            , 'product_transfer_details.quantity', 'product_sku_code.selling_price as rate', 'product_transfer_master.tr_warehouse_id'
                            , DB::raw("(product_sku_code.selling_price * product_transfer_details.quantity) as amount")
                            , 'product_transfer_master.remarks', 'product_unit.name as unit', 'warehouse.name as tr_warehouse')
                    ->orderBy('product_transfer_master.transfer_date', 'desc')
                    ->get();
            $i = 0;
            if (!$stockTransferLocalInfo->isEmpty()) {
                foreach ($stockTransferLocalInfo as $strLclInfo) {
                    $warehouseName = $strLclInfo->tr_warehouse ?? '';
                    $ledgerArr[$strLclInfo->date][$i]['5'] = $strLclInfo->toArray();
                    $ledgerArr[$strLclInfo->date][$i]['5']['type'] = __('label.STOCK_TRANSFERRED_TO_WH', ['warehouseName' => $warehouseName]);
                    $i++;
                }
            }

            //previous transfer
            $prevTransferLocalInfo = ProductTransferDetails::join('product_transfer_master', 'product_transfer_master.id', 'product_transfer_details.master_id')
                    ->join('product_sku_code', 'product_sku_code.id', '=', 'product_transfer_details.sku_id')
                    ->where('product_transfer_master.warehouse_id', $request->wh_id)
                    ->where('product_transfer_master.transfer_date', '<', $fromDate)
                    ->select(DB::raw("SUM(product_transfer_details.quantity) as total_quantity")
                            , DB::raw("SUM(product_sku_code.selling_price * product_transfer_details.quantity) as total_amount"))
                    ->first();

            $previousTransfer['quantity'] = !empty($prevTransferLocalInfo->total_quantity) ? $prevTransferLocalInfo->total_quantity : 0;
            $previousTransfer['amount'] = !empty($prevTransferLocalInfo->total_amount) ? $prevTransferLocalInfo->total_amount : 0;
            //End :: Stock Transfer to local
            //Start :: Stock Return
            //current return
            $stockReturnInfo = ProductWhReturnDetails::join('product_wh_return', 'product_wh_return.id', 'product_wh_return_details.product_wh_return_id')
                    ->join('product_sku_code', 'product_sku_code.id', '=', 'product_wh_return_details.sku_id')
                    ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                    ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                    ->join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                    ->where('product_wh_return.warehouse_id', $request->wh_id)
                    ->whereBetween('product_wh_return.return_date', [$fromDate, $toDate])
                    ->select('product.name as product', 'brand.name as brand', 'product_sku_code.sku as sku_code'
                            , 'product_wh_return.return_date as date', 'product_wh_return.reference_no'
                            , 'product_wh_return_details.quantity', 'product_sku_code.selling_price as rate'
                            , DB::raw("(product_sku_code.selling_price * product_wh_return_details.quantity) as amount")
                            , 'product_wh_return.remarks', 'product_unit.name as unit')
                    ->orderBy('product_wh_return.return_date', 'desc')
                    ->get();
            
            if (!$stockReturnInfo->isEmpty()) {
                foreach ($stockReturnInfo as $srtInfo) {
                    $ledgerArr[$srtInfo->date][$i]['2'] = $srtInfo->toArray();
                    $ledgerArr[$srtInfo->date][$i]['2']['type'] = __('label.STOCK_RETURNED_TO_CENTRAL_WH');
                    $i++;
                }
            }

            //previous return
            $prevStockReturnInfo = ProductWhReturnDetails::join('product_wh_return', 'product_wh_return.id', 'product_wh_return_details.product_wh_return_id')
                    ->join('product_sku_code', 'product_sku_code.id', '=', 'product_wh_return_details.sku_id')
                    ->where('product_wh_return.warehouse_id', $request->wh_id)
                    ->where('product_wh_return.return_date', '<', $fromDate)
                    ->select(DB::raw("SUM(product_wh_return_details.quantity) as total_quantity")
                            , DB::raw("SUM(product_sku_code.selling_price * product_wh_return_details.quantity) as total_amount"))
                    ->first();

            $previousStockReturn['quantity'] = !empty($prevStockReturnInfo->total_quantity) ? $prevStockReturnInfo->total_quantity : 0;
            $previousStockReturn['amount'] = !empty($prevStockReturnInfo->total_amount) ? $prevStockReturnInfo->total_amount : 0;
            //End :: Stock Return
            //Start :: Order Return
            //current return
            $fromDateTime = !empty($fromDate) ? $fromDate . ' 00:00:00' : '';
            $toDateTime = !empty($toDate) ? $toDate : ' 23:59:59';
            $productReturnInfo = ProductReturnDetails::join('product_return', 'product_return.id', 'product_return_details.return_id')
                    ->join('product', 'product.id', '=', 'product_return_details.product_id')
                    ->join('order', 'order.id', '=', 'product_return.order_id')
                    ->join('product_sku_code', 'product_sku_code.id', '=', 'product_return_details.sku_id')
                    ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                    ->join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                    ->where('order.warehouse_id', $request->wh_id)
                    ->whereBetween('product_return.updated_at', [$fromDateTime, $toDateTime])
                    ->select('product.name as product', 'brand.name as brand', 'product_sku_code.sku as sku_code'
                            , 'product_return.updated_at as date', 'order.order_no', 'product_return_details.quantity'
                            , 'product_return_details.unit_price as rate', 'product_return_details.total_price as amount'
                            , 'product_unit.name as unit')
                    ->orderBy('product_return.updated_at', 'desc')
                    ->get();

            if (!$productReturnInfo->isEmpty()) {
                foreach ($productReturnInfo as $rtInfo) {
                    $date = date("Y-m-d", strtotime($rtInfo->date));
                    $ledgerArr[$date][$i]['3'] = $rtInfo->toArray();
                    $ledgerArr[$date][$i]['3']['type'] = __('label.ORDER_RETURNED_TO_WH');
                    $i++;
                }
            }

            //previous return
            $prevReturnInfo = ProductReturnDetails::join('product_return', 'product_return.id', 'product_return_details.return_id')
                    ->join('order', 'order.id', '=', 'product_return.order_id')
                    ->where('order.warehouse_id', $request->wh_id)
                    ->where('product_return.updated_at', '<', $fromDateTime)
                    ->select(DB::raw("SUM(product_return_details.quantity) as total_quantity")
                            , DB::raw("SUM(product_return_details.total_price) as total_amount"))
                    ->first();

            $previousReturn['quantity'] = !empty($prevReturnInfo->total_quantity) ? $prevReturnInfo->total_quantity : 0;
            $previousReturn['amount'] = !empty($prevReturnInfo->total_amount) ? $prevReturnInfo->total_amount : 0;
            //End :: Order Return
            //Start :: Order Delivery
            //current delivery
            $productDeliveryInfo = DeliveryDetails::join('delivery', 'delivery.id', 'delivery_details.delivery_id')
                    ->join('product', 'product.id', '=', 'delivery_details.product_id')
                    ->join('order', 'order.id', '=', 'delivery.order_id')
                    ->join('product_sku_code', 'product_sku_code.id', '=', 'delivery_details.sku_id')
                    ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                    ->join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                    ->where('order.warehouse_id', $request->wh_id)
                    ->whereBetween('delivery.updated_at', [$fromDateTime, $toDateTime])
                    ->select('product.name as product', 'brand.name as brand', 'product_sku_code.sku as sku_code'
                            , 'delivery.updated_at as date', 'order.order_no', 'delivery_details.quantity'
                            , 'delivery_details.unit_price as rate', 'delivery_details.total_price as amount'
                            , 'product_unit.name as unit')
                    ->orderBy('delivery.updated_at', 'desc')
                    ->get();

            if (!$productDeliveryInfo->isEmpty()) {
                foreach ($productDeliveryInfo as $dlInfo) {
                    $date = date("Y-m-d", strtotime($dlInfo->date));
                    $ledgerArr[$date][$i]['4'] = $dlInfo->toArray();
                    $ledgerArr[$date][$i]['4']['type'] = __('label.ORDER_DELIVERED_FROM_WH');
                    $i++;
                }
            }

            //previous delivery
            $prevDeliveryInfo = DeliveryDetails::join('delivery', 'delivery.id', 'delivery_details.delivery_id')
                    ->join('order', 'order.id', '=', 'delivery.order_id')
                    ->where('order.warehouse_id', $request->wh_id)
                    ->where('delivery.updated_at', '<', $fromDateTime)
                    ->select(DB::raw("SUM(delivery_details.quantity) as total_quantity")
                            , DB::raw("SUM(delivery_details.total_price) as total_amount"))
                    ->first();

            $previousDelivery['quantity'] = !empty($prevDeliveryInfo->total_quantity) ? $prevDeliveryInfo->total_quantity : 0;
            $previousDelivery['amount'] = !empty($prevDeliveryInfo->total_amount) ? $prevDeliveryInfo->total_amount : 0;
            //End :: Order Delivery

            krsort($ledgerArr);

            $previousBalance['quantity'] = $previousTransfer['quantity'] + $previousReturn['quantity'] - ($previousStockReturn['quantity'] + $previousDelivery['quantity']);
            $previousBalance['amount'] = $previousTransfer['amount'] + $previousReturn['amount'] - ($previousStockReturn['amount'] + $previousDelivery['amount']);

            if (!empty($ledgerArr)) {
                $previousBalance['quantity'] = !empty($previousBalance['quantity']) ? $previousBalance['quantity'] : 0;
                $previousBalance['amount'] = !empty($previousBalance['amount']) ? $previousBalance['amount'] : 0;
                $balance['quantity'] = $previousBalance['quantity'];
                $balance['amount'] = $previousBalance['amount'];

                foreach ($ledgerArr as $date => $ledgerInfo) {
                    foreach ($ledgerInfo as $index => $indexInfo) {
                        foreach ($indexInfo as $type => $info) {
                            $stockTransfer['quantity'] = $stockTransfer['amount'] = 0;
                            $stockReturn['quantity'] = $stockReturn['amount'] = 0;
                            $return['quantity'] = $return['amount'] = 0;
                            $delivery['quantity'] = $delivery['amount'] = 0;
                            $stockTransferLocal['quantity'] = $stockTransferLocal['amount'] = 0;

                            if ($type == '1') {
                                $stockTransfer['quantity'] = !empty($info['quantity']) ? $info['quantity'] : 0;
                                $stockTransfer['amount'] = !empty($info['amount']) ? $info['amount'] : 0;
                            } elseif ($type == '2') {
                                $stockReturn['quantity'] = !empty($info['quantity']) ? $info['quantity'] : 0;
                                $stockReturn['amount'] = !empty($info['amount']) ? $info['amount'] : 0;
                            } elseif ($type == '3') {
                                $return['quantity'] = !empty($info['quantity']) ? $info['quantity'] : 0;
                                $return['amount'] = !empty($info['amount']) ? $info['amount'] : 0;
                            } elseif ($type == '4') {
                                $delivery['quantity'] = !empty($info['quantity']) ? $info['quantity'] : 0;
                                $delivery['amount'] = !empty($info['amount']) ? $info['amount'] : 0;
                            } elseif ($type == '5') {
                                $stockTransferLocal['quantity'] = !empty($info['quantity']) ? $info['quantity'] : 0;
                                $stockTransferLocal['amount'] = !empty($info['amount']) ? $info['amount'] : 0;
                            }

                            $balance['quantity'] = $balance['quantity'] + $stockTransfer['quantity'] + $return['quantity'] - ($stockReturn['quantity'] + $delivery['quantity'] + $stockTransferLocal['quantity']);
                            $balance['amount'] = $balance['amount'] + $stockTransfer['amount'] + $return['amount'] - ($stockReturn['amount'] + $delivery['amount'] + $stockTransferLocal['amount']);

                            $balanceArr[$date][$index][$type] = $balance;

                            $totalTransfer['quantity'] = !empty($totalTransfer['quantity']) ? $totalTransfer['quantity'] : 0;
                            $totalTransfer['quantity'] += $stockTransfer['quantity'];
                            $totalTransfer['amount'] = !empty($totalTransfer['amount']) ? $totalTransfer['amount'] : 0;
                            $totalTransfer['amount'] += $stockTransfer['amount'];

                            $totalStockReturn['quantity'] = !empty($totalStockReturn['quantity']) ? $totalStockReturn['quantity'] : 0;
                            $totalStockReturn['quantity'] += ($stockReturn['quantity'] + $stockTransferLocal['quantity']);
                            $totalStockReturn['amount'] = !empty($totalStockReturn['amount']) ? $totalStockReturn['amount'] : 0;
                            $totalStockReturn['amount'] += ($stockReturn['amount'] + $stockTransferLocal['amount']);

                            $totalReturn['quantity'] = !empty($totalReturn['quantity']) ? $totalReturn['quantity'] : 0;
                            $totalReturn['quantity'] += $return['quantity'];
                            $totalReturn['amount'] = !empty($totalReturn['amount']) ? $totalReturn['amount'] : 0;
                            $totalReturn['amount'] += $return['amount'];

                            $totalDelivery['quantity'] = !empty($totalDelivery['quantity']) ? $totalDelivery['quantity'] : 0;
                            $totalDelivery['quantity'] += $delivery['quantity'];
                            $totalDelivery['amount'] = !empty($totalDelivery['amount']) ? $totalDelivery['amount'] : 0;
                            $totalDelivery['amount'] += $delivery['amount'];

                            $totalBalance['quantity'] = !empty($totalBalance['quantity']) ? $totalBalance['quantity'] : 0;
                            $totalBalance['quantity'] = $previousBalance['quantity'] + $totalTransfer['quantity'] + $totalReturn['quantity'] - ($totalStockReturn['quantity'] + $totalDelivery['quantity']);
                            $totalBalance['amount'] = !empty($totalBalance['amount']) ? $totalBalance['amount'] : 0;
                            $totalBalance['amount'] = $previousBalance['amount'] + $totalTransfer['amount'] + $totalReturn['amount'] - ($totalStockReturn['amount'] + $totalDelivery['amount']);
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
            if (empty($userAccessArr[110][6])) {
                return redirect('dashboard');
            }
            return view('report.whStockLedger.print.index')->with(compact('request', 'targetArr', 'totalAmount'
                                    , 'whList', 'konitaInfo', 'phoneNumber', 'fromDate', 'toDate', 'totalBalance'
                                    , 'totalDelivery', 'totalReturn', 'totalStockReturn', 'totalTransfer', 'balanceArr'
                                    , 'ledgerArr', 'previousBalance'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[110][9])) {
                return redirect('/dashboard');
            }
            $pdf = PDF::loadView('report.whStockLedger.print.index', compact('request', 'targetArr', 'totalAmount'
                                    , 'whList', 'konitaInfo', 'phoneNumber', 'fromDate', 'toDate', 'totalBalance'
                                    , 'totalDelivery', 'totalReturn', 'totalStockReturn', 'totalTransfer', 'balanceArr'
                                    , 'ledgerArr', 'previousBalance'))
                    ->setPaper('a3', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('wh_stock_ledger_report.pdf');
//            return $pdf->stream();
        } else {
            return view('report.whStockLedger.index')->with(compact('request', 'targetArr', 'totalAmount', 'whList'
                                    , 'konitaInfo', 'phoneNumber', 'fromDate', 'toDate', 'totalBalance', 'totalDelivery'
                                    , 'totalReturn', 'totalStockReturn', 'totalTransfer', 'balanceArr', 'ledgerArr'
                                    , 'previousBalance'));
        }
    }

    public function filter(Request $request) {
        $messages = [];
        $rules = [
            'wh_id' => 'required|not_in:0',
            'from_date' => 'required',
            'to_date' => 'required',
        ];

        $messages = [
            'wh_id.not_in' => __('label.THE_WAREHOUSE_FIELD_IS_REQUIRED'),
            'from_date.required' => __('label.THE_FROM_DATE_FIELD_IS_REQUIRED'),
            'to_date.required' => __('label.THE_TO_DATE_FIELD_IS_REQUIRED'),
        ];
        $url = 'wh_id=' . $request->wh_id . '&from_date=' . $request->from_date . '&to_date=' . $request->to_date;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('admin/whStockLedgerReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }


        return Redirect::to('admin/whStockLedgerReport?generate=true&' . $url);
    }

}
