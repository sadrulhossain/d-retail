<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProductSKUCode;
use App\Product;
use App\ProductReturn;
use App\ProductAdjustmentMaster;
use App\OrderDetails;
use App\Order;
use App\ProductCheckInMaster;
use App\ProductCategory;
use App\WarehouseStore;
use App\WhToLocalWhManager;
use App\WarehouseToSr;
use App\Warehouse;
use App\SrToRetailer;
use App\Receive;
use PDF;
use DB;
use Carbon\Carbon;
use Common;
use Debugbar;
use Helper;
use Response;
use Validator;
use DateTime;

class DashboardController extends Controller {

    public function __construct() {
        //
    }

    public function index(Request $request) {
        if (in_array(Auth::user()->group_id, [9])) {
            return redirect('/');
        }
        //Start::for waiting for processing modal & to be placed in delivery modal
        $waitingForProcessing = Order::whereIn('order.status', ['2']);
        $tobePalcedInDelivery = Order::whereIn('order.status', ['3']);

        $todayBegin = date("Y-m-d") . ' 00:00:00';
        $todayEnd = date("Y-m-d") . ' 23:59:59';
        $todaysOrder = Order::whereBetween('created_at', [$todayBegin, $todayEnd])
                        ->where('sr_id', Auth::user()->id)->count();
        $totalRetailers = SrToRetailer::where('sr_id', Auth::user()->id)->count();

        if (Auth::user()->group_id == 12) {
            $waitingForProcessing = $waitingForProcessing->join('wh_to_local_wh_manager', function ($join) {
                $join->on('wh_to_local_wh_manager.warehouse_id', '=', 'order.warehouse_id')
                        ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id);
            });
            $tobePalcedInDelivery = $tobePalcedInDelivery->join('wh_to_local_wh_manager', function ($join) {
                $join->on('wh_to_local_wh_manager.warehouse_id', '=', 'order.warehouse_id')
                        ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id);
            });
        } elseif (Auth::user()->group_id == 14) {
            $waitingForProcessing = $waitingForProcessing->join('warehouse_to_sr', function ($join) {
                        $join->on('warehouse_to_sr.warehouse_id', '=', 'order.warehouse_id')
                        ->where('warehouse_to_sr.sr_id', Auth::user()->id);
                    })
                    ->where('order.sr_id', Auth::user()->id);
            $tobePalcedInDelivery = $tobePalcedInDelivery->join('warehouse_to_sr', function ($join) {
                        $join->on('warehouse_to_sr.warehouse_id', '=', 'order.warehouse_id')
                        ->where('warehouse_to_sr.sr_id', Auth::user()->id);
                    })
                    ->where('order.sr_id', Auth::user()->id);
        } elseif (Auth::user()->group_id == 15) {
            $waitingForProcessing = $waitingForProcessing->join('tm_to_warehouse', function ($join) {
                $join->on('tm_to_warehouse.warehouse_id', '=', 'order.warehouse_id')
                        ->where('tm_to_warehouse.tm_id', Auth::user()->id);
            });
            $tobePalcedInDelivery = $tobePalcedInDelivery->join('tm_to_warehouse', function ($join) {
                $join->on('tm_to_warehouse.warehouse_id', '=', 'order.warehouse_id')
                        ->where('tm_to_warehouse.tm_id', Auth::user()->id);
            });
        }
        $waitingForProcessing = $waitingForProcessing->count();
        $tobePalcedInDelivery = $tobePalcedInDelivery->count();

        // Monthly Expected profit
        $totalSell = Order::join('order_details', 'order_details.order_id', 'order.id')
                        ->whereMonth('order.updated_at', Carbon::now()->month)
                        ->select(DB::raw('SUM(order.grand_total) as total_amount'))->first();

        $totalBuy = ProductCheckInMaster::join('product_checkin_details', 'product_checkin_details.master_id', 'product_checkin_master.id')
                        ->whereMonth('product_checkin_master.checkin_date', Carbon::now()->month)
                        ->select(DB::raw('SUM(product_checkin_details.amount) as buy_total_amount'))->first();
        $expectedProfit = (!empty($totalSell->total_amount) ? $totalSell->total_amount : 0) - (!empty($totalBuy->buy_total_amount) ? $totalBuy->buy_total_amount : 0);
        $expectedProfit = !empty($expectedProfit) ? $expectedProfit : '0.00';
        // End:: Monthly Expected profit
        //Monthly  profit
        $totalDelivery = Order::join('order_details', 'order_details.order_id', 'order.id')
                        ->whereIn('order.status', ['3', '4', '5'])
                        ->whereMonth('order.updated_at', Carbon::now()->month)
                        ->select(DB::raw('SUM(order.grand_total) as total_amount'))->first();

        $return = ProductReturn::join('product_return_details', 'product_return_details.return_id', 'product_return.id')
                        ->whereMonth('product_return.updated_at', Carbon::now()->month)
                        ->select(DB::raw('SUM(product_return_details.total_price) as return_total_amount'))->first();

        $profit = (!empty($totalSell->total_amount) ? $totalSell->total_amount : 0) - (!empty($totalBuy->buy_total_amount) ? $totalBuy->buy_total_amount : 0) - (!empty($return->return_total_amount) ? $return->return_total_amount : 0);
        $profit = !empty($profit) ? $profit : '0.00';

        // echo '<pre>';        print_r($profit);exit;
        // End:: Monthly  profit
        //start::Low quantity modal
        if (in_array(Auth::user()->group_id, [12, 14, 15])) {
            $lowQuantityProductList = WarehouseStore::join('product_sku_code', 'product_sku_code.id', 'wh_store.sku_id')
                    ->join('product', 'product.id', 'product_sku_code.product_id');
            if (Auth::user()->group_id == 12) {
                $lowQuantityProductList = $lowQuantityProductList->join('wh_to_local_wh_manager', function ($join) {
                    $join->on('wh_to_local_wh_manager.warehouse_id', '=', 'wh_store.warehouse_id')
                            ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id);
                });
            } elseif (Auth::user()->group_id == 14) {
                $lowQuantityProductList = $lowQuantityProductList->join('warehouse_to_sr', function ($join) {
                    $join->on('warehouse_to_sr.warehouse_id', '=', 'wh_store.warehouse_id')
                            ->where('warehouse_to_sr.sr_id', Auth::user()->id);
                });
            } elseif (Auth::user()->group_id == 15) {
                $lowQuantityProductList = $lowQuantityProductList->join('tm_to_warehouse', function ($join) {
                    $join->on('tm_to_warehouse.warehouse_id', '=', 'wh_store.warehouse_id')
                            ->where('tm_to_warehouse.tm_id', Auth::user()->id);
                });
            }
            $lowQuantityProductList = $lowQuantityProductList->whereRaw('product_sku_code.available_quantity < product_sku_code.reorder_level')
                            ->where('product.status', '1')->count();
        } else {
            $lowQuantityProductList = ProductSKUCode::join('product', 'product.id', 'product_sku_code.product_id')
                            ->whereRaw('product_sku_code.available_quantity < product_sku_code.reorder_level')
                            ->where('product.status', '1')->count();
        }




        //End::Low quantity modal
        // Start :: Last 1 year Growth    And     Expense vs earning
        $toDayDate = date('Y-m-d');
        $oneYearAgoDate = date('Y-m-d', strtotime('-11 months'));
        $totalBuy2 = ProductCheckInMaster::join('product_checkin_details', 'product_checkin_details.master_id', 'product_checkin_master.id')
                ->select(DB::raw('SUM(product_checkin_details.amount) as buy_total_amount'), 'product_checkin_master.checkin_date')
                ->groupBy('product_checkin_master.checkin_date')
                ->whereBetween('product_checkin_master.checkin_date', [$oneYearAgoDate, $toDayDate])
                ->get();

        $totalDelivery2 = Order::join('order_details', 'order_details.order_id', 'order.id')
                ->whereIn('order.status', ['3', '4', '5'])
                ->select(DB::raw('SUM(order.grand_total) as total_amount'), DB::raw('DATE(order.updated_at) AS created'), 'order.updated_at')
                ->groupBy('order.updated_at')
                ->whereBetween('order.updated_at', [$oneYearAgoDate, $toDayDate])
                ->get();

        $return2 = ProductReturn::join('product_return_details', 'product_return_details.return_id', 'product_return.id')
                ->select(DB::raw('SUM(product_return_details.total_price) as return_total_amount'), DB::raw('DATE(product_return.updated_at) AS created'), 'product_return.updated_at')
                ->groupBy('product_return.updated_at')
                ->whereBetween('product_return.updated_at', [$oneYearAgoDate, $toDayDate])
                ->get();

        $lastOneYearProfitArr = [];
        if (!$totalBuy2->isEmpty()) {
            foreach ($totalBuy2 as $info) {
                $lastOneYearProfitArr['buy'][$info->checkin_date] = !empty($lastOneYearProfitArr['buy'][$info->checkin_date]) ? $lastOneYearProfitArr['buy'][$info->checkin_date] : 0;
                $lastOneYearProfitArr['buy'][$info->checkin_date] += $info->buy_total_amount;
            }
        }
        if (!$totalDelivery2->isEmpty()) {
            foreach ($totalDelivery2 as $info) {
                $lastOneYearProfitArr['delivery'][$info->created] = !empty($lastOneYearProfitArr['delivery'][$info->created]) ? $lastOneYearProfitArr['delivery'][$info->created] : 0;
                $lastOneYearProfitArr['delivery'][$info->created] += $info->total_amount;
            }
        }
        if (!$return2->isEmpty()) {
            foreach ($return2 as $info) {
                $lastOneYearProfitArr['return'][$info->created] = !empty($lastOneYearProfitArr['return'][$info->created]) ? $lastOneYearProfitArr['return'][$info->created] : 0;
                $lastOneYearProfitArr['return'][$info->created] += $info->return_total_amount;
            }
        }
        $beginDate = new DateTime($oneYearAgoDate);
        $endDate = new DateTime($toDayDate);
        $lastOneYearMonth = $lastOneYearEarning = $lastOneYearExpense = [];
        for ($j = $beginDate; $j <= $endDate; $j->modify('+1 day')) {
            $day = $j->format("Y-m-d");
            $month = $j->format("M Y");

            $lastOneYearMonth[$month] = !empty($lastOneYearMonth[$month]) ? $lastOneYearMonth[$month] : 0;
            $lastOneYearMonth[$month] += ((!empty($lastOneYearProfitArr['delivery'][$day]) ? $lastOneYearProfitArr['delivery'][$day] : 0) - (!empty($lastOneYearProfitArr['buy'][$day]) ? $lastOneYearProfitArr['buy'][$day] : 0) - (!empty($lastOneYearProfitArr['return'][$day]) ? $lastOneYearProfitArr['return'][$day] : 0));

            $lastOneYearExpense[$month] = !empty($lastOneYearExpense[$month]) ? $lastOneYearExpense[$month] : 0;
            $lastOneYearExpense[$month] += (!empty($lastOneYearProfitArr['buy'][$day]) ? $lastOneYearProfitArr['buy'][$day] : 0);

            $lastOneYearEarning[$month] = !empty($lastOneYearEarning[$month]) ? $lastOneYearEarning[$month] : 0;
            $lastOneYearEarning[$month] += ((!empty($lastOneYearProfitArr['delivery'][$day]) ? $lastOneYearProfitArr['delivery'][$day] : 0) - (!empty($lastOneYearProfitArr['return'][$day]) ? $lastOneYearProfitArr['return'][$day] : 0));
        }
        $maxProfit = max($lastOneYearMonth);
        $minProfit = min($lastOneYearMonth);
//        echo '<pre>';        print_r($lastOneYearExpense);
//        echo '<pre>';        print_r($lastOneYearEarning);exit;
        // End :: Last 1 year Growth     and   Expense vs earning
        // Start :: Category Wise Sale Graph
        $sixMonthsAgoDate = date('Y-m-d', strtotime('-5 months'));
        $totalBuy3 = ProductCheckInMaster::join('product_checkin_details', 'product_checkin_details.master_id', 'product_checkin_master.id')
                ->join('product', 'product.id', 'product_checkin_details.product_id')
                ->join('product_category', 'product_category.id', 'product.product_category_id')
                ->select(DB::raw('SUM(product_checkin_details.amount) as buy_total_amount')
                        , 'product_checkin_master.checkin_date', 'product_category.id as category_id')
                ->groupBy('product_checkin_master.checkin_date')
                ->groupBy('product_category.id')
                ->whereBetween('product_checkin_master.checkin_date', [$sixMonthsAgoDate, $toDayDate])
                ->get();

        $totalDelivery3 = Order::join('order_details', 'order_details.order_id', 'order.id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('product_category', 'product_category.id', 'product.product_category_id')
                ->whereIn('order.status', ['3', '4', '5'])
                ->select(DB::raw('SUM(order.grand_total) as total_amount')
                        , DB::raw('DATE(order.updated_at) AS created')
                        , 'order.updated_at', 'product_category.id as category_id')
                ->groupBy('order.updated_at')
                ->groupBy('product_category.id')
                ->whereBetween('order.updated_at', [$sixMonthsAgoDate, $toDayDate])
                ->get();

        $return3 = ProductReturn::join('product_return_details', 'product_return_details.return_id', 'product_return.id')
                ->join('product', 'product.id', 'product_return_details.product_id')
                ->join('product_category', 'product_category.id', 'product.product_category_id')
                ->select(DB::raw('SUM(product_return_details.total_price) as return_total_amount')
                        , DB::raw('DATE(product_return.updated_at) AS created')
                        , 'product_return.updated_at', 'product_category.id as category_id')
                ->groupBy('product_return.updated_at')
                ->groupBy('product_category.id')
                ->whereBetween('product_return.updated_at', [$sixMonthsAgoDate, $toDayDate])
                ->get();

        $categoryWiseProfitArr = [];
        if (!$totalBuy3->isEmpty()) {
            foreach ($totalBuy3 as $info) {
                $categoryWiseProfitArr['buy'][$info->checkin_date][$info->category_id] = !empty($categoryWiseProfitArr['buy'][$info->checkin_date][$info->category_id]) ? $categoryWiseProfitArr['buy'][$info->checkin_date][$info->category_id] : 0;
                $categoryWiseProfitArr['buy'][$info->checkin_date][$info->category_id] += $info->buy_total_amount;
            }
        }
        if (!$totalDelivery3->isEmpty()) {
            foreach ($totalDelivery3 as $info) {
                $categoryWiseProfitArr['delivery'][$info->created][$info->category_id] = !empty($categoryWiseProfitArr['delivery'][$info->created][$info->category_id]) ? $categoryWiseProfitArr['delivery'][$info->created][$info->category_id] : 0;
                $categoryWiseProfitArr['delivery'][$info->created][$info->category_id] += $info->total_amount;
            }
        }
        if (!$return3->isEmpty()) {
            foreach ($return3 as $info) {
                $categoryWiseProfitArr['return'][$info->created][$info->category_id] = !empty($categoryWiseProfitArr['return'][$info->created][$info->category_id]) ? $categoryWiseProfitArr['return'][$info->created][$info->category_id] : 0;
                $categoryWiseProfitArr['return'][$info->created][$info->category_id] += $info->return_total_amount;
            }
        }

        $categoryInfo = ProductCategory::select('id', 'name', 'parent_id')
                        ->where('status', 1)->get();

//        echo '<pre>';
//        print_r($categoryWiseProfitArr);
//        exit;

        $beginDate = new DateTime($sixMonthsAgoDate);
        $endDate = new DateTime($toDayDate);

        $lastSixMonths = [];
        $catI = 0;
        for ($j = $beginDate; $j <= $endDate; $j->modify('+1 day')) {
            $day = $j->format("Y-m-d");
            $month = $j->format("M Y");

            if (!$categoryInfo->isEmpty()) {
                foreach ($categoryInfo as $info) {
                    if ($info->parent_id == 0) {
                        $lastSixMonths[$month][$info->id] = !empty($lastSixMonths[$month][$info->id]) ? $lastSixMonths[$month][$info->id] : 0;
                        $lastSixMonths[$month][$info->id] += ((!empty($categoryWiseProfitArr['delivery'][$day][$info->id]) ? $categoryWiseProfitArr['delivery'][$day][$info->id] : 0) - (!empty($categoryWiseProfitArr['buy'][$day][$info->id]) ? $categoryWiseProfitArr['buy'][$day][$info->id] : 0) - (!empty($categoryWiseProfitArr['return'][$day][$info->id]) ? $categoryWiseProfitArr['return'][$day][$info->id] : 0));
                    } else {
                        $lastSixMonths[$month][$info->parent_id] = !empty($lastSixMonths[$month][$info->parent_id]) ? $lastSixMonths[$month][$info->parent_id] : 0;
                        $lastSixMonths[$month][$info->parent_id] += ((!empty($categoryWiseProfitArr['delivery'][$day][$info->id]) ? $categoryWiseProfitArr['delivery'][$day][$info->id] : 0) - (!empty($categoryWiseProfitArr['buy'][$day][$info->id]) ? $categoryWiseProfitArr['buy'][$day][$info->id] : 0) - (!empty($categoryWiseProfitArr['return'][$day][$info->id]) ? $categoryWiseProfitArr['return'][$day][$info->id] : 0));
                    }
                }
            }
        }

        $maxProfitArr = [];
        $i = 0;
        if (!empty($lastSixMonths)) {
            foreach ($lastSixMonths as $month => $categories) {
                foreach ($categories as $category => $profits) {
                    $maxProfitArr[$i] = $profits;
                    $i++;
                }
            }
        }
        $categoryWiseMaxProfit = max($maxProfitArr);
        $categoryWiseMinProfit = min($maxProfitArr);
        // End :: Category Wise Sale Graph
        // Start :: Top 10 Most Selling Products of the Year

        $totalDelivery = Order::join('order_details', 'order_details.order_id', 'order.id')
                ->whereIn('order.status', ['3', '4', '5']);
        if (in_array(Auth::user()->group_id, [12, 14, 15])) {
            $productList = WarehouseStore::join('product_sku_code', 'product_sku_code.id', 'wh_store.sku_id')
                    ->join('product', 'product.id', 'product_sku_code.product_id');

            if (Auth::user()->group_id == 12) {
                $totalDelivery = $totalDelivery->join('wh_to_local_wh_manager', function ($join) {
                    $join->on('wh_to_local_wh_manager.warehouse_id', '=', 'order.warehouse_id')
                            ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id);
                });
                $productList = $productList->join('wh_to_local_wh_manager', function ($join) {
                    $join->on('wh_to_local_wh_manager.warehouse_id', '=', 'wh_store.warehouse_id')
                            ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id);
                });
            } elseif (Auth::user()->group_id == 14) {
                $totalDelivery = $totalDelivery->join('warehouse_to_sr', function ($join) {
                            $join->on('warehouse_to_sr.warehouse_id', '=', 'order.warehouse_id')
                            ->where('warehouse_to_sr.sr_id', Auth::user()->id);
                        })
                        ->where('order.sr_id', Auth::user()->id);
                $productList = $productList->join('warehouse_to_sr', function ($join) {
                    $join->on('warehouse_to_sr.warehouse_id', '=', 'wh_store.warehouse_id')
                            ->where('warehouse_to_sr.sr_id', Auth::user()->id);
                });
            } elseif (Auth::user()->group_id == 15) {
                $totalDelivery = $totalDelivery->join('tm_to_warehouse', function ($join) {
                    $join->on('tm_to_warehouse.warehouse_id', '=', 'order.warehouse_id')
                            ->where('tm_to_warehouse.tm_id', Auth::user()->id);
                });
                $productList = $productList->join('tm_to_warehouse', function ($join) {
                    $join->on('tm_to_warehouse.warehouse_id', '=', 'wh_store.warehouse_id')
                            ->where('tm_to_warehouse.tm_id', Auth::user()->id);
                });
            }
            $productList = $productList->pluck('product.name', 'product.id')->toArray();
        } else {
            $productList = Product::pluck('product.name', 'product.id')->toArray();
        }
        $totalDelivery = $totalDelivery->select(DB::raw('SUM(order_details.quantity) as total_quantity'), DB::raw('SUM(order_details.total_price) as total_amount')
                        , 'order_details.product_id')
                ->groupBy('order_details.product_id')
                ->whereBetween('order.updated_at', [$oneYearAgoDate, $toDayDate]);

        $totalDeliveryQuantity = $totalDelivery->pluck('total_quantity', 'order_details.product_id')->toArray();
        $totalDeliveryAmount = $totalDelivery->pluck('total_amount', 'order_details.product_id')->toArray();

        $totalDeliveryQuantityArr = $totalDeliveryAmountArr = [];
        if (!empty($productList)) {
            foreach ($productList as $product => $name) {
                $totalDeliveryQuantityArr[$product] = !empty($totalDeliveryQuantity[$product]) ? $totalDeliveryQuantity[$product] : 0;
                $totalDeliveryAmountArr[$product] = !empty($totalDeliveryAmount[$product]) ? $totalDeliveryAmount[$product] : 0;
            }
            arsort($totalDeliveryQuantityArr);
            arsort($totalDeliveryAmountArr);
        }

        $totalSellQuantityArr = [];
        $productCount = 0;
        if (!empty($totalDeliveryQuantityArr)) {
            foreach ($totalDeliveryQuantityArr as $product => $quantity) {
                if ($productCount < 10) {
                    $totalSellQuantityArr[$product] = $quantity;
                }
                $productCount++;
            }
        }



        $totalSellAmountArr = [];
        $productCount = 0;
        if (!empty($totalDeliveryAmountArr)) {
            foreach ($totalDeliveryAmountArr as $product => $quantity) {
                if ($productCount < 10) {
                    $totalSellAmountArr[$product] = $quantity;
                }
                $productCount++;
            }
        }

//        echo '<pre>';        print_r($totalSellAmountArr);exit;
        // End :: Top 10 Most Selling Products of the Year
        // Start :: Top 10 Most Returned & Damaged Products of the Year
        $totalReturn = ProductReturn::join('product_return_details', 'product_return_details.return_id', 'product_return.id')
                        ->select(DB::raw('SUM(product_return_details.quantity) as return_total_quantity')
                                , 'product_return_details.product_id')
                        ->groupBy('product_return_details.product_id')
                        ->whereBetween('product_return.updated_at', [$oneYearAgoDate, $toDayDate])
                        ->pluck('return_total_quantity', 'product_return_details.product_id')->toArray();

        $totalReturnArr = [];
        if (!empty($productList)) {
            foreach ($productList as $product => $name) {
                $totalReturnArr[$product] = !empty($totalReturn[$product]) ? $totalReturn[$product] : 0;
            }
            arsort($totalReturnArr);
        }

        $totalReturnQuantityArr = [];
        $productCount = 0;
        if (!empty($totalReturnArr)) {
            foreach ($totalReturnArr as $product => $quantity) {
                if ($productCount < 10) {
                    $totalReturnQuantityArr[$product] = $quantity;
                }
                $productCount++;
            }
        }



        $totalDamage = ProductAdjustmentMaster::join('product_adjustment_details', 'product_adjustment_details.master_id', 'product_adjustment_master.id')
                        ->join('product_sku_code', 'product_sku_code.id', 'product_adjustment_details.sku_id')
                        ->select(DB::raw('SUM(product_adjustment_details.quantity) as adjusment_total_quantity')
                                , 'product_sku_code.product_id')
                        ->groupBy('product_sku_code.product_id')
                        ->whereBetween('product_adjustment_master.adjustment_date', [$oneYearAgoDate, $toDayDate])
                        ->pluck('adjusment_total_quantity', 'product_sku_code.product_id')->toArray();

        $totalDamageArr = [];
        if (!empty($productList)) {
            foreach ($productList as $product => $name) {
                $totalDamageArr[$product] = !empty($totalDamage[$product]) ? $totalDamage[$product] : 0;
            }
            arsort($totalDamageArr);
        }

        $totalDamegeQuantityArr = [];
        $productCount = 0;
        if (!empty($totalDamageArr)) {
            foreach ($totalDamageArr as $product => $quantity) {
                if ($productCount < 10) {
                    $totalDamegeQuantityArr[$product] = $quantity;
                }
                $productCount++;
            }
        }

        $warehouseWiseQuantityTodayArr = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                        ->select(DB::raw('SUM(order_details.quantity) as total_quantity'), 'order.warehouse_id')
                        ->groupBy('order.warehouse_id')
                        ->whereDate('created_at', '=', $toDayDate)
                        ->pluck('total_quantity', 'order.warehouse_id')->toArray();

        $paymentModeList = Common::getPaymentModeList();
        $pmModeWiseTodaysCollectionArr = Receive::join('delivery', 'delivery.id', 'receive.delivery_id')
                ->select(DB::raw('SUM(receive.collection_amount) as total_price'), 'delivery.payment_mode')
                ->groupBy('delivery.payment_mode')
                ->whereDate('receive.created_at', '=', $toDayDate)
                ->pluck('total_price', 'delivery.payment_mode')
                ->toArray();

//        echo "<pre>";
//        print_r($warehouseWisePriceTodayArr);
//        exit;

        $warehouseList = Warehouse::where('allowed_for_central_warehouse', '0')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();

        $todayTime = date("Y-m-d") . ' 23:59:59';
        $thirtyDaysAgoTime = date("Y-m-d", strtotime("-29 day")) . ' 00:00:00';

        $whWiseQtyArr = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                        ->select(DB::raw('SUM(order_details.quantity) as total_quantity'), 'order.warehouse_id')
                        ->groupBy('order.warehouse_id')
                        ->whereBetween('updated_at', [$thirtyDaysAgoTime, $todayTime])
                        ->where('order.status', '5')
                        ->pluck('total_quantity', 'order.warehouse_id')->toArray();
        $last30DaysWhWiseQtyArr = [];
        if (!empty($warehouseList)) {
            foreach ($warehouseList as $whId => $wh) {
                $last30DaysWhWiseQtyArr[$whId] = !empty($whWiseQtyArr[$whId]) ? $whWiseQtyArr[$whId] : 0;
            }
        }

//        echo "<pre>";
//        print_r($last30DaysWhWiseQtyArr);
//        exit;

        arsort($last30DaysWhWiseQtyArr);
        $last30DaysBestWhArr = [];
        $count = 0;
        if (!empty($last30DaysWhWiseQtyArr)) {
            foreach ($last30DaysWhWiseQtyArr as $whId => $quantity) {
                if ($count < 10) {
                    $last30DaysBestWhArr[$whId] = $quantity;
                }
                $count++;
            }
        }

        asort($last30DaysWhWiseQtyArr);
        $last30DaysWorstWhArr = [];
        $count = 0;
        if (!empty($last30DaysWhWiseQtyArr)) {
            foreach ($last30DaysWhWiseQtyArr as $whId => $quantity) {
                if ($count < 10) {
                    $last30DaysWorstWhArr[$whId] = $quantity;
                }
                $count++;
            }
        }

        $threeMnthsOrderByAmountForSrArr = $srArr = $threeMnthsOrderByQtyFrSrArr = [];

        if (Auth::user()->group_id == 12) {

            $srArr = WarehouseToSr::join('users', 'users.id', 'warehouse_to_sr.sr_id')
                    ->join('wh_to_local_wh_manager', function ($join) {
                        $join->on('wh_to_local_wh_manager.warehouse_id', 'warehouse_to_sr.warehouse_id')
                        ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id);
                    })
                    ->select('warehouse_to_sr.sr_id', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"));

            $srIdArr = $srArr->pluck('warehouse_to_sr.sr_id', 'warehouse_to_sr.sr_id')->toArray();
            $srArr = $srArr->pluck('full_name', 'warehouse_to_sr.sr_id')->toArray();

            $threeMnthsAgoDate = date('Y-m-d', strtotime('-2 months')) . ' 00:00:00';

            $threeMnthsOrderByQtyFrSrArr = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                            ->select(DB::raw('SUM(order_details.quantity) as total_quantity'), 'order.sr_id')
                            ->groupBy('order.sr_id')
                            ->whereBetween('updated_at', [$threeMnthsAgoDate, $todayTime])
                            ->where('order.status', '5')
                            ->whereIn('order.sr_id', $srIdArr)
                            ->pluck('total_quantity', 'order.sr_id')->toArray();

            arsort($threeMnthsOrderByQtyFrSrArr);

            $threeMnthsOrderByAmountForSrArr = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                            ->select(DB::raw('SUM(order_details.total_price) as total_amount'), 'order.sr_id')
                            ->groupBy('order.sr_id')
                            ->whereBetween('updated_at', [$threeMnthsAgoDate, $todayTime])
                            ->where('order.status', '5')
                            ->whereIn('order.sr_id', $srIdArr)
                            ->pluck('total_amount', 'order.sr_id')->toArray();

            arsort($threeMnthsOrderByAmountForSrArr);
        }

        $productArr = ProductSKUCode::join('product', 'product.id', 'product_sku_code.product_id');
        if (Auth::user()->group_id == 12) {
            $productArr = $productArr->join('wh_store', function ($join) {
                        $join->on('wh_store.sku_id', '=', 'product_sku_code.id');
                    })
                    ->join('wh_to_local_wh_manager', function ($join) {
                $join->on('wh_to_local_wh_manager.warehouse_id', 'wh_store.warehouse_id')
                ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id);
            });
        }

        $productArr = $productArr->orderBy('product.name', 'asc')
                        ->pluck('product.name', 'product.id')->toArray();

        $last30DaysSalesInfo = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->select(DB::raw('SUM(order_details.quantity) as total_quantity'), 'order_details.product_id'
                        , 'order.status')
                ->groupBy('order_details.product_id', 'order.status')
                ->whereIn('order.status', ['0', '5', '8'])
                ->whereBetween('updated_at', [$thirtyDaysAgoTime, $todayTime])
                ->whereIn('order_details.product_id', $productArr)
                ->get();

        $last30DaysSalesArr = [];
        if (!$last30DaysSalesInfo->isEmpty()) {
            foreach ($last30DaysSalesInfo as $sales) {
                if ($sales->status == 0) {
                    $last30DaysSalesArr['pending'][$sales->product_id] = !empty($sales->total_quantity) ? $sales->total_quantity : 0;
                } elseif ($sales->status == 5) {
                    $last30DaysSalesArr['delivered'][$sales->product_id] = !empty($sales->total_quantity) ? $sales->total_quantity : 0;
                } elseif ($sales->status == 8) {
                    $last30DaysSalesArr['cancelled'][$sales->product_id] = !empty($sales->total_quantity) ? $sales->total_quantity : 0;
                }
            }
        }

        //***************** Monthly Order State **************************
        $currentDay = date('Y-m-d');
        $currentMonth = date('Y-m-01');
        $thirtyDaysAgo = date('Y-m-d', strtotime('-29 days'));
        $beginDate = new DateTime($thirtyDaysAgo);
        $endDate = new DateTime($currentDay);

        $lastThirtyDaysOrderStateInfo = Order::join('retailer', 'retailer.id', 'order.retailer_id')
                ->whereIn('order.status', ['0', '1', '2', '3', '5', '6', '7'])
                ->whereBetween('order.created_at', [$thirtyDaysAgo, $currentDay])
                ->where('retailer.user_id', Auth::user()->id)
                ->select(DB::raw("COUNT(order.id) as total")
                        , 'order.created_at')
                ->groupBY('order.created_at')
                ->get();
        $lastThirtyDaysOrderStateArr = [];
        if (!$lastThirtyDaysOrderStateInfo->isEmpty()) {
            foreach ($lastThirtyDaysOrderStateInfo as $oState) {
                $date = !empty($oState->created_at) ? date('Y-m-d', strtotime($oState->created_at)) : '';
                $lastThirtyDaysOrderStateArr[$date] = !empty($lastThirtyDaysOrderStateArr[$date]) ? $lastThirtyDaysOrderStateArr[$date] : 0;
                $lastThirtyDaysOrderStateArr[$date] += (!empty($oState->total) ? $oState->total : 0);
            }
        }

        $monthlyOrderStateArr = [];
        for ($j = $beginDate; $j <= $endDate; $j->modify('+1 day')) {
            $day = $j->format("Y-m-d");
            //inquiry summary
            $monthlyOrderStateArr[$day] = !empty($lastThirtyDaysOrderStateArr[$day]) ? $lastThirtyDaysOrderStateArr[$day] : 0;
        }

//        echo '<pre>';
//        print_r($monthlyOrderStateArr);
//        exit;
        //***************** End :: Monthly Order State **************************


        $pendingOrderArr = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('brand', 'brand.id', 'product.brand_id')
                ->join('product_sku_code', 'product_sku_code.id', 'order_details.sku_id')
                ->join('users', 'users.id', 'order.sr_id')
                ->where('order.status', '0');

        if (Auth::user()->group_id == 12) {
            $warehouseId = WhToLocalWhManager::select('warehouse_id')
                    ->where('lwm_id', Auth::user()->id)
                    ->first();
            $pendingOrderArr = $pendingOrderArr->where('order.warehouse_id', $warehouseId->warehouse_id ?? 0);
        }
        if (in_array(Auth::user()->group_id, [18, 19])) {
            $pendingOrderArr = $pendingOrderArr->where('retailer.user_id', Auth::user()->id);
        }
        $pendingOrder = $pendingOrderArr->select('order_details.*', 'product_sku_code.sku', 'product.name as product_name', 'order.created_at'
                        , 'order.order_no as order_no', 'brand.name as brand_name', 'product.name as product_name', 'order.grand_total as paying_amount'
                        , 'retailer.name as retailer_name', 'product_sku_code.attribute', 'users.first_name as sr_name')->count();

//        print_r(count($pendingOrder));
//        exit;
//        


        return view('admin.dashboard')->with(compact('request', 'lastOneYearExpense', 'lastOneYearEarning'
                                , 'lowQuantityProductList', 'expectedProfit', 'profit', 'tobePalcedInDelivery'
                                , 'waitingForProcessing', 'lastOneYearMonth', 'maxProfit', 'categoryInfo', 'lastSixMonths'
                                , 'categoryWiseMaxProfit', 'categoryWiseMinProfit', 'totalSellQuantityArr', 'totalSellAmountArr'
                                , 'productList', 'totalReturnQuantityArr', 'totalDamegeQuantityArr', 'minProfit'
                                , 'todaysOrder', 'totalRetailers', 'warehouseWiseQuantityTodayArr', 'warehouseList'
                                , 'last30DaysBestWhArr', 'last30DaysWorstWhArr', 'pmModeWiseTodaysCollectionArr', 'last30DaysSalesArr', 'productArr'
                                , 'paymentModeList', 'threeMnthsOrderByQtyFrSrArr', 'srArr'
                                , 'threeMnthsOrderByAmountForSrArr', 'pendingOrder', 'monthlyOrderStateArr'));
    }

    public function getProductPricing(Request $request) {

        if (in_array(Auth::user()->group_id, [12, 14, 15])) {
            $productPricingList = WarehouseStore::join('product_sku_code', 'product_sku_code.id', 'wh_store.sku_id')
                    ->join('product', 'product.id', 'product_sku_code.product_id')
                    ->join('product_unit', 'product_unit.id', 'product.product_unit_id');
            if (Auth::user()->group_id == 12) {
                $productPricingList = $productPricingList->join('wh_to_local_wh_manager', function ($join) {
                    $join->on('wh_to_local_wh_manager.warehouse_id', '=', 'wh_store.warehouse_id')
                            ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id);
                });
            } elseif (Auth::user()->group_id == 14) {
                $productPricingList = $productPricingList->join('warehouse_to_sr', function ($join) {
                    $join->on('warehouse_to_sr.warehouse_id', '=', 'wh_store.warehouse_id')
                            ->where('warehouse_to_sr.sr_id', Auth::user()->id);
                });
            } elseif (Auth::user()->group_id == 15) {
                $productPricingList = $productPricingList->join('tm_to_warehouse', function ($join) {
                    $join->on('tm_to_warehouse.warehouse_id', '=', 'wh_store.warehouse_id')
                            ->where('tm_to_warehouse.tm_id', Auth::user()->id);
                });
            }
            $productPricingList = $productPricingList->select('product_sku_code.sku', 'product.name', 'product_sku_code.selling_price'
                                    , 'wh_store.quantity as available_quantity', 'product_unit.name as unit')
                            ->where('product.status', '1')->get();
        } else {
            $productPricingList = ProductSKUCode::join('product', 'product.id', 'product_sku_code.product_id')
                            ->join('product_unit', 'product_unit.id', 'product.product_unit_id')
                            ->select('product_sku_code.sku', 'product.name', 'product_sku_code.selling_price'
                                    , 'product_sku_code.available_quantity', 'product_unit.name as unit')
                            ->where('product.status', '1')->get();
        }

        $view = view('admin.modal.showProductPricing', compact('productPricingList'))->render();
        return response()->json(['html' => $view]);
    }

    public function getWaitingForProcessing(Request $request) {
        $order = Order::whereIn('order.status', ['2'])
                ->leftJoin('invoice', 'invoice.order_id', 'order.id')
                ->join('retailer', 'retailer.id', 'order.retailer_id');
        $orderDetailArr = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('product_sku_code', 'product_sku_code.product_id', 'product.id')
                ->join('brand', 'brand.id', 'product.brand_id')
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->whereIn('order.status', ['2']);

        if (Auth::user()->group_id == 12) {
            $order = $order->join('wh_to_local_wh_manager', function ($join) {
                $join->on('wh_to_local_wh_manager.warehouse_id', '=', 'order.warehouse_id')
                        ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id);
            });
            $orderDetailArr = $orderDetailArr->join('wh_to_local_wh_manager', function ($join) {
                $join->on('wh_to_local_wh_manager.warehouse_id', '=', 'order.warehouse_id')
                        ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id);
            });
        } elseif (Auth::user()->group_id == 14) {
            $order = $order->join('warehouse_to_sr', function ($join) {
                        $join->on('warehouse_to_sr.warehouse_id', '=', 'order.warehouse_id')
                        ->where('warehouse_to_sr.sr_id', Auth::user()->id);
                    })
                    ->where('order.sr_id', Auth::user()->id);
            $orderDetailArr = $orderDetailArr->join('warehouse_to_sr', function ($join) {
                        $join->on('warehouse_to_sr.warehouse_id', '=', 'order.warehouse_id')
                        ->where('warehouse_to_sr.sr_id', Auth::user()->id);
                    })
                    ->where('order.sr_id', Auth::user()->id);
        } elseif (Auth::user()->group_id == 15) {
            $order = $order->join('tm_to_warehouse', function ($join) {
                $join->on('tm_to_warehouse.warehouse_id', '=', 'order.warehouse_id')
                        ->where('tm_to_warehouse.tm_id', Auth::user()->id);
            });
            $orderDetailArr = $orderDetailArr->join('tm_to_warehouse', function ($join) {
                $join->on('tm_to_warehouse.warehouse_id', '=', 'order.warehouse_id')
                        ->where('tm_to_warehouse.tm_id', Auth::user()->id);
            });
        }


        $order = $order->select('order.id as order_id', 'order.status', 'order.grand_total as paying_amount', 'order.created_at'
                        , 'retailer.name as customer_name', 'invoice.id as invoice_id')
                ->get();

        $oderArr = [];
        if (!$order->isEmpty()) {
            foreach ($order as $item) {
                $oderArr[$item->order_id] = $item->toArray();
            }
        }



        $orderDetailArr = $orderDetailArr->select('order_details.*', 'product_sku_code.sku', 'brand.name as brand'
                        , 'retailer.name as customer')
                ->get();

        if (!$orderDetailArr->isEmpty()) {
            foreach ($orderDetailArr as $item) {
                $oderArr[$item->order_id]['products'][$item->id] = $item->toArray();
            }
        }



        $view = view('admin.modal.showWaitingForProcessing', compact('oderArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getPlacedInDelivery(Request $request) {

        $order2 = Order::whereIn('order.status', ['3'])
                ->leftJoin('invoice', 'invoice.order_id', 'order.id')
                ->join('retailer', 'retailer.id', 'order.retailer_id');

        $orderDetailArr2 = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('product_sku_code', 'product_sku_code.product_id', 'product.id')
                ->join('brand', 'brand.id', 'product.brand_id')
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->whereIn('order.status', ['3']);

        if (Auth::user()->group_id == 12) {
            $order2 = $order2->join('wh_to_local_wh_manager', function ($join) {
                $join->on('wh_to_local_wh_manager.warehouse_id', '=', 'order.warehouse_id')
                        ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id);
            });
            $orderDetailArr2 = $orderDetailArr2->join('wh_to_local_wh_manager', function ($join) {
                $join->on('wh_to_local_wh_manager.warehouse_id', '=', 'order.warehouse_id')
                        ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id);
            });
        } elseif (Auth::user()->group_id == 14) {
            $order2 = $order2->join('warehouse_to_sr', function ($join) {
                        $join->on('warehouse_to_sr.warehouse_id', '=', 'order.warehouse_id')
                        ->where('warehouse_to_sr.sr_id', Auth::user()->id);
                    })
                    ->where('order.sr_id', Auth::user()->id);
            $orderDetailArr2 = $orderDetailArr2->join('warehouse_to_sr', function ($join) {
                        $join->on('warehouse_to_sr.warehouse_id', '=', 'order.warehouse_id')
                        ->where('warehouse_to_sr.sr_id', Auth::user()->id);
                    })
                    ->where('order.sr_id', Auth::user()->id);
        } elseif (Auth::user()->group_id == 15) {
            $order2 = $order2->join('tm_to_warehouse', function ($join) {
                $join->on('tm_to_warehouse.warehouse_id', '=', 'order.warehouse_id')
                        ->where('tm_to_warehouse.tm_id', Auth::user()->id);
            });
            $orderDetailArr2 = $orderDetailArr2->join('tm_to_warehouse', function ($join) {
                $join->on('tm_to_warehouse.warehouse_id', '=', 'order.warehouse_id')
                        ->where('tm_to_warehouse.tm_id', Auth::user()->id);
            });
        }


        $order2 = $order2->select('order.id as order_id', 'order.status', 'order.grand_total as paying_amount', 'order.created_at'
                        , 'retailer.name as customer_name', 'invoice.id as invoice_id')
                ->get();

        $oderArr2 = [];
        if (!$order2->isEmpty()) {
            foreach ($order2 as $item) {
                $oderArr2[$item->order_id] = $item->toArray();
            }
        }



        $orderDetailArr2 = $orderDetailArr2->select('order_details.*', 'product_sku_code.sku', 'brand.name as brand'
                        , 'retailer.name as customer')
                ->get();
        if (!$orderDetailArr2->isEmpty()) {
            foreach ($orderDetailArr2 as $item) {
                $oderArr2[$item->order_id]['products'][$item->id] = $item->toArray();
            }
        }

        $view = view('admin.modal.showPlacedInDelivery', compact('oderArr2'))->render();
        return response()->json(['html' => $view]);
    }

    public function getPendingOrder(Request $request) {
        if (Auth::user()->group_id == 12) {
            $whList = WhToLocalWhManager::where('lwm_id', Auth::user()->id)->pluck('warehouse_id', 'warehouse_id')->toArray();
        }

        $targetArr = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('brand', 'brand.id', 'product.brand_id')
                ->join('product_sku_code', 'product_sku_code.id', 'order_details.sku_id')
                ->join('users', 'users.id', 'order.sr_id')
                ->where('order.status', '0');

        if (Auth::user()->group_id == 12) {
            $warehouseId = WhToLocalWhManager::select('warehouse_id')
                    ->where('lwm_id', Auth::user()->id)
                    ->first();
            $targetArr = $targetArr->where('order.warehouse_id', $warehouseId->warehouse_id);
        }
        if (in_array(Auth::user()->group_id, [18, 19])) {
            $targetArr = $targetArr->where('retailer.user_id', Auth::user()->id);
        }
        $targetArr = $targetArr->select('order_details.*', 'product_sku_code.sku', 'product.name as product_name', 'order.created_at'
                                , 'order.order_no as order_no', 'brand.name as brand_name', 'product.name as product_name', 'order.grand_total as paying_amount'
                                , 'retailer.name as retailer_name', 'product_sku_code.attribute', 'users.first_name as sr_name')
                        ->get()->toArray();

        $view = view('admin.modal.showPendingOrder', compact('targetArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getTodaysOrder(Request $request) {
        $todayBegin = date("Y-m-d") . ' 00:00:00';
        $todayEnd = date("Y-m-d") . ' 23:59:59';

        $totalRetailers = SrToRetailer::where('sr_id', Auth::user()->id)->count();

        $order2 = Order::join('retailer', 'retailer.id', 'order.retailer_id')
                ->whereBetween('order.created_at', [$todayBegin, $todayEnd])
                ->where('order.sr_id', Auth::user()->id)
                ->select('order.id as order_id', 'order.status', 'order.grand_total as paying_amount', 'order.created_at'
                        , 'retailer.name as customer_name')
                ->get();

        $orderDetailArr2 = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('product_sku_code', 'product_sku_code.product_id', 'product.id')
                ->join('brand', 'brand.id', 'product.brand_id')
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->whereBetween('order.created_at', [$todayBegin, $todayEnd])
                ->where('order.sr_id', Auth::user()->id)
                ->select('order_details.*', 'product_sku_code.sku', 'brand.name as brand'
                        , 'retailer.name as customer')
                ->get();

        $oderArr2 = [];
        if (!$order2->isEmpty()) {
            foreach ($order2 as $item) {
                $oderArr2[$item->order_id] = $item->toArray();
            }
        }



        if (!$orderDetailArr2->isEmpty()) {
            foreach ($orderDetailArr2 as $item) {
                $oderArr2[$item->order_id]['products'][$item->id] = $item->toArray();
            }
        }



        $view = view('admin.modal.showTodaysOrder', compact('oderArr2'))->render();
        return response()->json(['html' => $view]);
    }

    public function getTotalRetailer(Request $request) {

        $retailerDataArr = SrToRetailer::join('retailer', 'retailer.id', 'sr_to_retailer.retailer_id')
                ->where('sr_to_retailer.sr_id', Auth::user()->id)
                ->select('retailer.name', 'retailer.code', 'retailer.logo', 'retailer.address')
                ->get();

        $view = view('admin.modal.showTotalRetailer', compact('retailerDataArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getLowQuantitySKU(Request $request) {

        if (in_array(Auth::user()->group_id, [12, 14, 15])) {
            $lowQuantityProductList = WarehouseStore::join('product_sku_code', 'product_sku_code.id', 'wh_store.sku_id')
                    ->join('product', 'product.id', 'product_sku_code.product_id')
                    ->join('product_unit', 'product_unit.id', 'product.product_unit_id');
            if (Auth::user()->group_id == 12) {
                $lowQuantityProductList = $lowQuantityProductList->join('wh_to_local_wh_manager', function ($join) {
                    $join->on('wh_to_local_wh_manager.warehouse_id', '=', 'wh_store.warehouse_id')
                            ->where('wh_to_local_wh_manager.lwm_id', Auth::user()->id);
                });
            } elseif (Auth::user()->group_id == 14) {
                $lowQuantityProductList = $lowQuantityProductList->join('warehouse_to_sr', function ($join) {
                    $join->on('warehouse_to_sr.warehouse_id', '=', 'wh_store.warehouse_id')
                            ->where('warehouse_to_sr.sr_id', Auth::user()->id);
                });
            } elseif (Auth::user()->group_id == 15) {
                $lowQuantityProductList = $lowQuantityProductList->join('tm_to_warehouse', function ($join) {
                    $join->on('tm_to_warehouse.warehouse_id', '=', 'wh_store.warehouse_id')
                            ->where('tm_to_warehouse.tm_id', Auth::user()->id);
                });
            }
            $lowQuantityProductList = $lowQuantityProductList->whereRaw('product_sku_code.available_quantity < product_sku_code.reorder_level')
                    ->where('product.status', '1')
                    ->select('product_sku_code.sku', 'product.name', 'product_sku_code.available_quantity'
                            , 'product_sku_code.reorder_level', 'product_unit.name as unit')
                    ->get();
        } else {
            $lowQuantityProductList = ProductSKUCode::join('product', 'product.id', 'product_sku_code.product_id')
                    ->join('product_unit', 'product_unit.id', 'product.product_unit_id')
                    ->whereRaw('product_sku_code.available_quantity < product_sku_code.reorder_level')
                    ->where('product.status', '1')
                    ->select('product_sku_code.sku', 'product.name', 'product_sku_code.available_quantity'
                            , 'product_sku_code.reorder_level', 'product_unit.name as unit')
                    ->get();
        }



//        $lowQuantityProductList = ProductSKUCode::join('product', 'product.id', 'product_sku_code.product_id')
//                ->join('product_unit', 'product_unit.id', 'product.product_unit_id')
//                ->whereRaw('product_sku_code.available_quantity < product_sku_code.reorder_level')
//                ->where('product.status', '1')
//                ->select('product_sku_code.sku', 'product.name', 'product_sku_code.available_quantity'
//                        , 'product_sku_code.reorder_level', 'product_unit.name as unit')
//                ->get();

        $view = view('admin.modal.showLowQuanSKU', compact('lowQuantityProductList'))->render();
        return response()->json(['html' => $view]);
    }

    public function getMonthlyOrderState(Request $request) {
        $dateIndex = $request->date_index;
        if ($dateIndex < 0) {
            exit;
        }
        $date = date('Y-m-d', strtotime('-' . (29 - $dateIndex) . ' days'));
        $orderInfo = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->join('product', 'product.id', '=', 'order_details.product_id')
                ->whereIn('order.status', ['0', '1', '2', '3', '5', '6', '7'])
//                ->where('order.created_at', $date)
                ->where('retailer.user_id', Auth::user()->id)
                ->select('order.order_no','retailer.name','product.name'
                        ,'order_details.quantity','order_details.unit_price'
                        ,'order_details.total_price'
                )->get();
//        echo '<pre>';        print_r($orderInfo); exit;

        $view = view('admin.others.showMonthlyOrderState', compact('request','orderInfo','date' ))->render();
        return response()->json(['html' => $view]);
    }

}
