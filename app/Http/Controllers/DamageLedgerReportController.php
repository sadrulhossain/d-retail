<?php

namespace App\Http\Controllers;

use Validator;
use Session;
use Redirect;
use App\Product;
use App\Supplier;
use App\ProductSKUCode;
use App\ProductCheckInDetails;
use App\ProductCheckInMaster;
use App\ProductAdjustmentMaster;
use App\ProductAdjustmentDetails;
use App\ProductReturn;
use App\ProductReturnDetails;
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

class DamageLedgerReportController extends Controller {

    //Stock Summary Report
    public function index(Request $request) {
        $productList = ['0' => __('label.ALL_PRODUCTS')] + ProductSKUCode::join('product', 'product.id', 'product_sku_code.product_id')
                        ->pluck('product.name', 'product.id')->toArray();

        $fromDate = $toDate = '';
        $totalAmount = 0;
        $targetArr = $productCheckInInfo = $ledgerArr = $previousBalance = [];
        $totalBalance = $totalDelivery = $totalReturn = $totalDamage = $totalCheckIn = $balanceArr = [];


        if ($request->generate == 'true') {
            $fromDate = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) : '';
            $toDate = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) : '';
            //check in
//            $productCheckInInfo = ProductCheckInDetails::join('product_checkin_master', 'product_checkin_master.id', 'product_checkin_details.master_id')
//                    ->join('product', 'product.id', '=', 'product_checkin_details.product_id')
//                    ->join('product_sku_code', 'product_sku_code.id', '=', 'product_checkin_details.sku_id')
//                    ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
//                    ->join('product_unit', 'product_unit.id', '=', 'product.product_unit_id');
//            if (!empty($request->product_id)) {
//                $productCheckInInfo = $productCheckInInfo->where('product_checkin_details.product_id', $request->product_id);
//            }
//            $productCheckInInfo = $productCheckInInfo->whereBetween('product_checkin_master.checkin_date', [$fromDate, $toDate])
//                    ->select('product.name as product', 'brand.name as brand', 'product_sku_code.sku as sku_code'
//                            , 'product_checkin_master.checkin_date as date', 'product_checkin_master.ref_no'
//                            , 'product_checkin_master.challan_no', 'product_checkin_details.quantity'
//                            , 'product_checkin_details.rate', 'product_checkin_details.amount'
//                            , 'product_unit.name as unit')
//                    ->orderBy('product_checkin_master.checkin_date', 'desc')
//                    ->get();
            $i = 0;
//            if (!$productCheckInInfo->isEmpty()) {
//                foreach ($productCheckInInfo as $ckInfo) {
//                    $ledgerArr[$ckInfo->date][$i]['1'] = $ckInfo->toArray();
//                    $ledgerArr[$ckInfo->date][$i]['1']['type'] = __('label.PRODEUCT_CHECKED_IN');
//                    $i++;
//                }
//            }
//
//            $prevCheckInInfo = ProductCheckInDetails::join('product_checkin_master', 'product_checkin_master.id', 'product_checkin_details.master_id');
//            if (!empty($request->product_id)) {
//                $prevCheckInInfo = $prevCheckInInfo->where('product_checkin_details.product_id', $request->product_id);
//            }
//            $prevCheckInInfo = $prevCheckInInfo->where('product_checkin_master.checkin_date', '<', $fromDate)
//                    ->select(DB::raw("SUM(product_checkin_details.quantity) as total_quantity")
//                            , DB::raw("SUM(product_checkin_details.amount) as total_amount"))
//                    ->first();
//
//            $previousCheckIn['quantity'] = !empty($prevCheckInInfo->total_quantity) ? $prevCheckInInfo->total_quantity : 0;
//            $previousCheckIn['amount'] = !empty($prevCheckInInfo->total_amount) ? $prevCheckInInfo->total_amount : 0;



            //damage
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

            $prevDamageInfo = ProductAdjustmentDetails::join('product_adjustment_master', 'product_adjustment_master.id', 'product_adjustment_details.master_id')
                    ->join('product_sku_code', 'product_sku_code.id', '=', 'product_adjustment_details.sku_id');
            if (!empty($request->product_id)) {
                $prevDamageInfo = $prevDamageInfo->where('product_sku_code.product_id', $request->product_id);
            }
            $prevDamageInfo = $prevDamageInfo->where('product_adjustment_master.adjustment_date', '<', $fromDate)
                    ->select(DB::raw("SUM(product_adjustment_details.quantity) as total_quantity")
                            , DB::raw("SUM(product_sku_code.selling_price*product_adjustment_details.quantity) as total_amount"))
                    ->first();

            $previousDamage['quantity'] = !empty($prevDamageInfo->total_quantity) ? $prevDamageInfo->total_quantity : 0;
            $previousDamage['amount'] = !empty($prevDamageInfo->total_amount) ? $prevDamageInfo->total_amount : 0;



            //return
//            $fromDateTime = !empty($fromDate) ? $fromDate . ' 00:00:00' : '';
//            $toDateTime = !empty($toDate) ? $toDate : ' 23:59:59';
//            $productReturnInfo = ProductReturnDetails::join('product_return', 'product_return.id', 'product_return_details.return_id')
//                    ->join('product', 'product.id', '=', 'product_return_details.product_id')
//                    ->join('order', 'order.id', '=', 'product_return.order_id')
//                    ->join('product_sku_code', 'product_sku_code.id', '=', 'product_return_details.sku_id')
//                    ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
//                    ->join('product_unit', 'product_unit.id', '=', 'product.product_unit_id');
//            if (!empty($request->product_id)) {
//                $productReturnInfo = $productReturnInfo->where('product_return_details.product_id', $request->product_id);
//            }
//            $productReturnInfo = $productReturnInfo->whereBetween('product_return.updated_at', [$fromDateTime, $toDateTime])
//                    ->select('product.name as product', 'brand.name as brand', 'product_sku_code.sku as sku_code'
//                            , 'product_return.updated_at as date', 'order.order_no', 'product_return_details.quantity'
//                            , 'product_return_details.unit_price as rate', 'product_return_details.total_price as amount'
//                            , 'product_unit.name as unit')
//                    ->orderBy('product_return.updated_at', 'desc')
//                    ->get();
//
//            if (!$productReturnInfo->isEmpty()) {
//                foreach ($productReturnInfo as $rtInfo) {
//                    $date = date("Y-m-d", strtotime($rtInfo->date));
//                    $ledgerArr[$date][$i]['3'] = $rtInfo->toArray();
//                    $ledgerArr[$date][$i]['3']['type'] = __('label.PRODUCT_RETURNED');
//                    $i++;
//                }
//            }
//
//            $prevReturnInfo = ProductReturnDetails::join('product_return', 'product_return.id', 'product_return_details.return_id');
//            if (!empty($request->product_id)) {
//                $prevReturnInfo = $prevReturnInfo->where('product_return_details.product_id', $request->product_id);
//            }
//            $prevReturnInfo = $prevReturnInfo->where('product_return.updated_at', '<', $fromDateTime)
//                    ->select(DB::raw("SUM(product_return_details.quantity) as total_quantity")
//                            , DB::raw("SUM(product_return_details.total_price) as total_amount"))
//                    ->first();
//
//            $previousReturn['quantity'] = !empty($prevReturnInfo->total_quantity) ? $prevReturnInfo->total_quantity : 0;
//            $previousReturn['amount'] = !empty($prevReturnInfo->total_amount) ? $prevReturnInfo->total_amount : 0;
//
//
//
//            //order
//            $productDeliveryInfo = DeliveryDetails::join('delivery', 'delivery.id', 'delivery_details.delivery_id')
//                    ->join('product', 'product.id', '=', 'delivery_details.product_id')
//                    ->join('order', 'order.id', '=', 'delivery.order_id')
//                    ->join('product_sku_code', 'product_sku_code.id', '=', 'delivery_details.sku_id')
//                    ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
//                    ->join('product_unit', 'product_unit.id', '=', 'product.product_unit_id');
//            if (!empty($request->product_id)) {
//                $productDeliveryInfo = $productDeliveryInfo->where('delivery_details.product_id', $request->product_id);
//            }
//            $productDeliveryInfo = $productDeliveryInfo->whereBetween('delivery.updated_at', [$fromDateTime, $toDateTime])
//                    ->select('product.name as product', 'brand.name as brand', 'product_sku_code.sku as sku_code'
//                            , 'delivery.updated_at as date', 'order.order_no', 'delivery_details.quantity'
//                            , 'delivery_details.unit_price as rate', 'delivery_details.total_price as amount'
//                            , 'product_unit.name as unit')
//                    ->orderBy('delivery.updated_at', 'desc')
//                    ->get();
//
//            if (!$productDeliveryInfo->isEmpty()) {
//                foreach ($productDeliveryInfo as $dlInfo) {
//                    $date = date("Y-m-d", strtotime($dlInfo->date));
//                    $ledgerArr[$date][$i]['4'] = $dlInfo->toArray();
//                    $ledgerArr[$date][$i]['4']['type'] = __('label.CUSTOMER_ORDER');
//                    $i++;
//                }
//            }
//
//            $prevDeliveryInfo = DeliveryDetails::join('delivery', 'delivery.id', 'delivery_details.delivery_id');
//            if (!empty($request->product_id)) {
//                $prevDeliveryInfo = $prevDeliveryInfo->where('delivery_details.product_id', $request->product_id);
//            }
//            $prevDeliveryInfo = $prevDeliveryInfo->where('delivery.updated_at', '<', $fromDateTime)
//                    ->select(DB::raw("SUM(delivery_details.quantity) as total_quantity")
//                            , DB::raw("SUM(delivery_details.total_price) as total_amount"))
//                    ->first();
//
//            $previousDelivery['quantity'] = !empty($prevDeliveryInfo->total_quantity) ? $prevDeliveryInfo->total_quantity : 0;
//            $previousDelivery['amount'] = !empty($prevDeliveryInfo->total_amount) ? $prevDeliveryInfo->total_amount : 0;
//

            krsort($ledgerArr);

            $previousBalance['quantity'] = $previousDamage['quantity'];
            $previousBalance['amount'] = $previousDamage['amount'];

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
                            $delivery['quantity'] = $delivery['amount'] = 0;

                            if ($type == '1') {
                                $checkIn['quantity'] = !empty($info['quantity']) ? $info['quantity'] : 0;
                                $checkIn['amount'] = !empty($info['amount']) ? $info['amount'] : 0;
                            } elseif ($type == '2') {
                                $damage['quantity'] = !empty($info['quantity']) ? $info['quantity'] : 0;
                                $damage['amount'] = !empty($info['amount']) ? $info['amount'] : 0;
                            } elseif ($type == '3') {
                                $return['quantity'] = !empty($info['quantity']) ? $info['quantity'] : 0;
                                $return['amount'] = !empty($info['amount']) ? $info['amount'] : 0;
                            } elseif ($type == '4') {
                                $delivery['quantity'] = !empty($info['quantity']) ? $info['quantity'] : 0;
                                $delivery['amount'] = !empty($info['amount']) ? $info['amount'] : 0;
                            }

                            $balance['quantity'] = $balance['quantity'] + $checkIn['quantity'] + $return['quantity'] + ($damage['quantity'] + $delivery['quantity']);
                            $balance['amount'] = $balance['amount'] + $checkIn['amount'] + $return['amount'] + ($damage['amount'] + $delivery['amount']);

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

                            $totalDelivery['quantity'] = !empty($totalDelivery['quantity']) ? $totalDelivery['quantity'] : 0;
                            $totalDelivery['quantity'] += $delivery['quantity'];
                            $totalDelivery['amount'] = !empty($totalDelivery['amount']) ? $totalDelivery['amount'] : 0;
                            $totalDelivery['amount'] += $delivery['amount'];

                            $totalBalance['quantity'] = !empty($totalBalance['quantity']) ? $totalBalance['quantity'] : 0;
                            $totalBalance['quantity'] = $previousBalance['quantity'] + $totalCheckIn['quantity'] + $totalReturn['quantity'] + ($totalDamage['quantity'] + $totalDelivery['quantity']);
                            $totalBalance['amount'] = !empty($totalBalance['amount']) ? $totalBalance['amount'] : 0;
                            $totalBalance['amount'] = $previousBalance['amount'] + $totalCheckIn['amount'] + $totalReturn['amount'] + ($totalDamage['amount'] + $totalDelivery['amount']);
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
            if (empty($userAccessArr[108][6])) {
                return redirect('dashboard');
            }
            return view('report.damageLedger.print.index')->with(compact('request', 'targetArr', 'totalAmount'
                                    , 'productList', 'konitaInfo', 'phoneNumber', 'fromDate', 'toDate', 'totalBalance'
                                    , 'totalDelivery', 'totalReturn', 'totalDamage', 'totalCheckIn', 'balanceArr'
                                    , 'ledgerArr', 'previousBalance'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[108][9])) {
                return redirect('/dashboard');
            }
            $pdf = PDF::loadView('report.damageLedger.print.index', compact('request', 'targetArr', 'totalAmount'
                                    , 'productList', 'konitaInfo', 'phoneNumber', 'fromDate', 'toDate', 'totalBalance'
                                    , 'totalDelivery', 'totalReturn', 'totalDamage', 'totalCheckIn', 'balanceArr'
                                    , 'ledgerArr', 'previousBalance'))
                    ->setPaper('a3', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('damage_ledger_report.pdf');
//            return $pdf->stream();
        } else {
            return view('report.damageLedger.index')->with(compact('request', 'targetArr', 'totalAmount', 'productList'
                                    , 'konitaInfo', 'phoneNumber', 'fromDate', 'toDate', 'totalBalance', 'totalDelivery'
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
        $url = 'product_id=' . $request->product_id . '&from_date=' . $request->from_date . '&to_date=' . $request->to_date;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('admin/damageLedgerReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }


        return Redirect::to('admin/damageLedgerReport?generate=true&' . $url);
    }

}
