<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\AclUserGroupToAccess;
use App\User;
use App\Department;
use App\Branch;
use App\Country;
use App\CourierService;
use App\ContactDesignation;
use App\Designation;
use App\OrderDetails;
use App\Order;
use App\Delivery;
use App\DeliveryDetails;
use App\ProductTransferMaster;
use App\ProductTransferDetails;
use App\Invoice;
use App\Division;
use App\District;
use App\Thana;
use App\ProductAttribute;
use App\ProductCategory;
use App\CompanyInformation;
use Illuminate\Http\Request;

class Common {

    private static $productCategoryArr = [];

    public static function userAccess() {
        //ACL ACCESS LIST
        $accessGroupArr = AclUserGroupToAccess::where('group_id', Auth::user()->group_id)
                        ->select('*')->get();

        $userAccessArr = [];
        if (!$accessGroupArr->isEmpty()) {
            foreach ($accessGroupArr as $item) {
                $userAccessArr[$item->module_id][$item->access_id] = $item->access_id;
            }
        }
        //ENDOF ACL ACCESS LIST
        return $userAccessArr;
    }

    public static function groupHasRoleAccess($groupId) {
        $accessGroupArr = AclUserGroupToAccess::where('group_id', $groupId)
                        ->select('*')->get();
        if ($groupId != 1 && $accessGroupArr->isEmpty()) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function getDivision(Request $request) {
        //country wise division
        $divisionArr = ['0' => __('label.SELECT_DIVISION_OPT')] + Division::where('country_id', $request->country_id)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $view = view('branch.showDivision', compact('divisionArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getDistrict(Request $request, $loadView) {
        //country wise division
        $districtArr = ['0' => __('label.SELECT_DISTRICT_OPT')] + District::where('division_id', $request->division_id)
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $view = view($loadView . '.showDistrict', compact('districtArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getThana(Request $request, $loadView) {
        //country wise division
        $thanaArr = ['0' => __('label.SELECT_THANA_OPT')] + Thana::where('district_id', $request->district_id)
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $view = view($loadView . '.showThana', compact('thanaArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function courierServiceContactPerson() {
        $designationList = array('' => __('label.SELECT_DESIGNATION_OPT')) + ContactDesignation::where('status', '1')->pluck('name', 'id')->toArray();
        $view = view('courierServiceContactPerson.showContactPerson', compact('designationList'))->render();
        return response()->json(['html' => $view]);
    }

    public static function newContactPerson() {
        $designationList = array('' => __('label.SELECT_DESIGNATION_OPT')) + ContactDesignation::where('status', '1')->pluck('name', 'id')->toArray();
        $view = view('supplierContactPerson.showContactPerson', compact('designationList'))->render();
        return response()->json(['html' => $view]);
    }

    public static function computePermutations(array $inputArray, array $template) {
        $permutations = [];
        $lastPass = count($template) === 1;

        foreach ($inputArray[$template[0]] as $firstPermutation) {
            if ($lastPass) {
                $permutations[] = [$firstPermutation];
            } else {
                foreach (Common::computePermutations($inputArray, array_slice($template, 1)) as $restPermutation) {
                    $permutations[] = [$firstPermutation, ...$restPermutation];
                }
            }
        }

        return $permutations;
    }

    public static function nameWiseSKUVariation($input) {
        $skuIdArr = [];
        $skuIdArr = explode(',', $input);
        if (!empty($skuIdArr)) {
            $i = 0;
            $skuCodeArr = [];
            foreach ($skuIdArr as $sku) {
                $ProductAttributeName = ProductAttribute::select('name')->where('id', $sku)->first();
                $skuCodeArr[$i] = $ProductAttributeName->name;
                $i++;
            }
        }
        $nameWiseSKU = implode('-', $skuCodeArr);
        return $nameWiseSKU;
    }

    public static function codeWiseSKUVariation($input) {
        $skuIdArr = [];
        $skuIdArr = explode(',', $input);
        if (!empty($skuIdArr)) {
            $i = 0;
            $skuCodeArr = [];
            foreach ($skuIdArr as $sku) {
                $ProductAttributeCode = ProductAttribute::select('product_attribute_code')->where('id', $sku)->first();
                $skuCodeArr[$i] = $ProductAttributeCode->product_attribute_code;
                $i++;
            }
        }
        $codeWiseSKU = implode('-', $skuCodeArr);
        return $codeWiseSKU;
    }

    public static function findParentCategory($parentId = null, $id = null) {
        $dataArr = ProductCategory::find($parentId);
        Common::$productCategoryArr[$id] = isset(Common::$productCategoryArr[$id]) ? Common::$productCategoryArr[$id] : '';
        if (!empty($dataArr['name'])) {
            Common::$productCategoryArr[$id] = $dataArr['name'] . ' &raquo; ' . Common::$productCategoryArr[$id];
        }

        if (!empty($dataArr['parent_id'])) {
            Common::findParentCategory($dataArr['parent_id'], $id);
        }

        //exclude last &raquo; sign
        Common::$productCategoryArr[$id] = trim(Common::$productCategoryArr[$id], ' &raquo; ');
        return true;
    }

    public static function getAllProductCategory() {
        Common::$productCategoryArr = [];
        $categoryArr = ProductCategory::where('status', 1)->orderBy('order', 'asc')->select('name', 'id', 'parent_id')->get();
        if (!$categoryArr->isEmpty()) {
            foreach ($categoryArr as $category) {
                Common::findParentCategory($category->parent_id, $category->id);
                Common::$productCategoryArr[$category->id] = trim(Common::$productCategoryArr[$category->id] . ' &raquo; ' . $category->name, ' &raquo; ');
            }
        }

        return Common::$productCategoryArr;
    }

    public static function getVat() {
        $companyInfo = CompanyInformation::select('vat', 'include_vat')->first();
        $vat = !empty($companyInfo->vat) && !empty($companyInfo->include_vat) ? $companyInfo->vat : 0;
        return $vat;
    }

    public static function getStockDemand(Request $request, $loadView) {
//        dd($request->id);
        $targetArr = [];
        $orderArr = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('product_sku_code', 'product_sku_code.id', 'order_details.sku_id')
                ->leftJoin('wh_store', function ($join) {
                    $join->on('wh_store.warehouse_id', '=', 'order.warehouse_id');
                    $join->on('wh_store.sku_id', '=', 'order_details.sku_id');
                })
                ->where('order.id', $request->id);
//        echo "<pre>";
//        print_r($orderArr);
//        exit;
        $productSKU = $orderArr->pluck('product_sku_code.id as sku_id', 'product_sku_code.id as sku_id')
                ->toArray();

        $orderArr = $orderArr->select('product_sku_code.sku', 'product_sku_code.id as sku_id', 'product.name as product', 'product.id as product_id'
                        , 'order_details.quantity', 'wh_store.quantity as available_quantity', 'product_sku_code.attribute')
                ->get();

        $attrList = ProductAttribute::where('status', '1')
                ->pluck('name', 'id')
                ->toArray();

//        echo "<pre>";
//        print_r($orderArr);
//        exit;


        if (!empty($orderArr)) {

            foreach ($orderArr as $order) {
                $attributeIdArr = !empty($order->attribute) ? explode(',', $order->attribute) : [];

                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $order->product .= (!empty($attrList[$attrId]) ? ' ' . $attrList[$attrId] : '');
                    }
                }
                $targetArr[$order->sku_id]['sku'] = $order->sku;
                $targetArr[$order->sku_id]['name'] = $order->product;
                $targetArr[$order->sku_id]['quantity_this_order'] = $order->quantity;
                $targetArr[$order->sku_id]['stock'] = $order->available_quantity;
            }
        }

        $orderDetailsArr = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->join('product_sku_code', 'product_sku_code.id', 'order_details.sku_id')
                ->whereIn('order.status', ['0', '1', '2'])
                ->whereIn('order_details.sku_id', $productSKU)
                ->select(DB::raw('SUM(order_details.quantity) as demand_quantity'), 'order_details.sku_id')
                ->groupBy('order_details.sku_id')
                ->get();
        if (!empty($orderDetailsArr)) {
            foreach ($orderDetailsArr as $order) {
                $targetArr[$order->sku_id]['demand'] = $order->demand_quantity;
            }
        }
//        echo '<pre>';        print_r($orderDetailsArr->toArray());exit;

        $view = view($loadView, compact('targetArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getDeliveryDetails(Request $request, $loadView) {
        $orderId = $request->order_id ?? 0;
        $deliveryId = $request->delivery_id ?? 0;

        $delivery = Delivery::where('id', $deliveryId)
                ->select('order_id', 'bl_no', 'bl_date', 'express_tracking_no', 'container_no'
                        , 'payment_status', 'payment_mode', 'paying_amount')
                ->first();
        $order = Invoice::join('order', 'order.id', 'invoice.order_id')
                ->where('order.id', $orderId)
                ->where('invoice.delivery_id', $deliveryId)
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->join('users', 'users.id', 'order.sr_id')
                ->select('order.id as order_id', 'order.grand_total', 'retailer.id as retailer_id', 'retailer.address'
                        , 'retailer.name as retailer_name', 'invoice.invoice_no', 'invoice.date as invoice_date'
                        , 'invoice.special_note', 'invoice.net_receivable'
                        , DB::raw("CONCAT(users.first_name,' ',users.last_name) AS sr")
                        , 'order.status', 'order.created_at', 'order.order_no')
                ->first();

        $deliveryInfo = DeliveryDetails::join('delivery', 'delivery.id', 'delivery_details.delivery_id')
                ->join('order', 'order.id', 'delivery.order_id')
                ->join('order_details', 'order_details.id', 'delivery_details.order_details_id')
                ->join('product', 'product.id', 'order_details.product_id')
                ->join('product_sku_code', 'product_sku_code.id', 'order_details.sku_id')
                ->leftJoin('wh_store', function ($join) {
                    $join->on('wh_store.warehouse_id', '=', 'order.warehouse_id');
                    $join->on('wh_store.sku_id', '=', 'order_details.sku_id');
                })
                ->join('brand', 'brand.id', 'product.brand_id')
                ->where('delivery.id', $deliveryId)
                ->select('delivery_details.*', 'product_sku_code.sku', 'product.name as product_name'
                        , 'wh_store.quantity as available_quantity', 'order_details.quantity as order_qty'
                        , 'order_details.customer_demand')
                ->get();

        $deliveryDataArr = DeliveryDetails::where('delivery_id', $deliveryId)
                        ->select(DB::raw("SUM(quantity) as total_qty"), 'sku_id')
                        ->groupBy('sku_id')
                        ->pluck('total_qty', 'sku_id')->toArray();
        $paymentModeList = Common::getPaymentModeList();

        $html = view($loadView, compact('request', 'deliveryInfo', 'delivery', 'order'
                        , 'paymentModeList', 'deliveryDataArr'))->render();
        return response(['html' => $html]);
    }

    public static function printInvoice(Request $request, $id, $loadView) {
        $delivery = Delivery::where('id', $id)->select('order_id')->first();
        $order = Invoice::join('order', 'order.id', 'invoice.order_id')
                ->where('order.id', $delivery->order_id ?? 0)
                ->where('invoice.delivery_id', $id)
                ->join('retailer', 'retailer.id', 'order.retailer_id')
                ->select('order.id as order_id', 'order.grand_total', 'retailer.id as retailer_id', 'retailer.address'
                        , 'retailer.name as retailer_name', 'invoice.invoice_no', 'invoice.date as invoice_date'
                        , 'invoice.special_note', 'invoice.net_receivable')
                ->first();

        $deliveryDetailInfo = DeliveryDetails::join('delivery', 'delivery.id', 'delivery_details.delivery_id')
                ->join('product', 'product.id', 'delivery_details.product_id')
                ->join('product_sku_code', 'product_sku_code.id', 'delivery_details.sku_id')
                ->where('delivery.id', $id)
                ->select('delivery.order_id', 'delivery_details.sku_id', 'product_sku_code.sku'
                        , 'product.name as product_name', 'product.id as product_id'
                        , 'delivery_details.unit_price', 'delivery_details.quantity', 'delivery_details.total_price'
                        , 'delivery.paying_amount', 'delivery_details.id as delivery_details_id')
                ->get();

        $companyInfo = CompanyInformation::first();
        $companyNumber = '';
        if (!empty($companyInfo)) {
            $phoneNumberDecode = json_decode($companyInfo->phone_number, true);
            $companyNumber = Helper::arrayTostring($phoneNumberDecode);
        }
        $imagePath = '/public/img/small_logo.png';

        return view($loadView)->with(compact('order', 'deliveryDetailInfo'
                                , 'companyInfo', 'companyNumber', 'imagePath'));
    }

    public static function retailerContactPerson() {
        $view = view('retailerContactPerson.showContactPerson')->render();
        return response()->json(['html' => $view]);
    }

    public static function generateOrderNo() {
        $orderDate = date('Y-m-d');
        $orderRefNo = Order::select(DB::raw('count(id) as total'))->where(DB::raw('substr(created_at, 1, 10)'), $orderDate)->first();
        // Generate unique order no.
        $unique = uniqid();
        $srId = Auth::user()->id;
        $uniqueId = strtoupper(substr($unique, -2));
        $dateTime = date("ym");
        $orderNoInfo = $orderRefNo->total + 1;
        $orderNo = 'PO' . $uniqueId . $srId . $dateTime . str_pad($orderNoInfo, 3, '0', STR_PAD_LEFT);

        return $orderNo;
    }

    public static function getDeliveryInformation(Request $request, $loadView) {

        $id = $request->id;
//        echo '<pre>';
//        print_r($request->all());

        $orderDetailInfo = OrderDetails::join('product', 'product.id', 'order_details.product_id')
                ->join('product_sku_code', 'product_sku_code.id', 'order_details.sku_id')
                ->join('order', 'order.id', 'order_details.order_id')
                ->leftJoin('wh_store', function ($join) {
                    $join->on('wh_store.warehouse_id', '=', 'order.warehouse_id');
                    $join->on('wh_store.sku_id', '=', 'order_details.sku_id');
                })
                ->where('order_details.order_id', $request->id)
                ->select('wh_store.quantity as available_quantity', 'product_sku_code.sku', 'product_sku_code.id as sku_id', 'order_details.quantity'
                        , 'order_details.unit_price', 'order_details.quantity', 'order_details.total_price', 'order_details.id'
                        , 'order.grand_total', 'order.warehouse_id', 'product.id as product_id', 'product.name as product_name'
                        , 'wh_store.quantity as available_quantity', 'order_details.customer_demand')
                ->get();

        $deliveryDetails = DeliveryDetails::join('delivery', 'delivery.id', '=', 'delivery_details.delivery_id')
                        ->where('delivery.order_id', $id)
                        ->select('delivery_details.sku_id', DB::raw('SUM(delivery_details.quantity) as delivered_qty'))
                        ->groupBy('delivery_details.sku_id')
                        ->pluck('delivered_qty', 'delivery_details.sku_id')->toArray();

//        $deliveryDetails2 = DeliveryDetails::join('delivery', 'delivery.id', '=', 'delivery_details.delivery_id')
//                ->where('delivery.order_id', $id)
//                ->select(DB::raw('SUM(delivery_details.quantity) as delivered_qty'))
//                ->groupBy('delivery.order_id')
//                ->first()->delivered_qty;
//        
//         $order = Order::join('order_details','order_details.order_id','order.id')
//                 ->where('order.id', $id)
//                 ->select(DB::raw('SUM(order_details.quantity) as ordered_qty'))
//                 ->groupBy('order_details.order_id')
//                 ->first()->ordered_qty;
//         
//        echo '<pre>';
//        print_r($deliveryDetails2);
//        echo '<pre>';
//        print_r($order);
//        return response()->json([$orderDetailInfo]);
        $orderNo = Order::where('order.id', $request->id)->select('order.order_no', 'order.retailer_id')->first();
        $deliveryDate = date('Y-m-d');

        $product = [];
        $i = 0;
        if (!$orderDetailInfo->isEmpty()) {
            foreach ($orderDetailInfo as $item) {
                if (($item->available_quantity) < ($item->quantity)) {
                    $product[$i] = $item->sku;
                    $i++;
                }
            }
        }
        $productList = implode(', ', $product);

        $view = view($loadView, compact('request', 'id', 'orderNo', 'orderDetailInfo', 'deliveryDate', 'deliveryDetails'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getPaymentInformation(Request $request, $loadView) {

        $orderDetailInfoData = !empty($request->all()) ? json_encode($request->all()) : '';
        $orderNo = Order::where('order.id', $request->order_id)->select('order.order_no', 'order.retailer_id')->first();
        $deliveryDate = date('Y-m-d');
        $view = view($loadView, compact('request', 'orderNo', 'orderDetailInfoData', 'deliveryDate'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getDeliveryConfirmInformation(Request $request, $loadView) {
        $orderDetailInfoData = !empty($request->all()) ? $request->all() : '';
        $orderNo = Order::where('order.id', $orderDetailInfoData['order_id'])->select('order.order_no', 'order.retailer_id')->first();
        $deliveryDate = date('Y-m-d');
        $view = view($loadView, compact('request', 'orderNo', 'orderDetailInfoData', 'deliveryDate'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getFreezeStock($warehouseId, $orderId = 0) {
        //pending stock transfer
        $pendingStock = ProductTransferDetails::join('product_transfer_master', 'product_transfer_master.id', 'product_transfer_details.master_id')
                ->where('product_transfer_master.tr_warehouse_id', $warehouseId)
                ->where('product_transfer_master.approval_status', '0')
                ->select('product_transfer_details.sku_id', DB::raw("SUM(product_transfer_details.quantity) as total_qty"))
                ->groupBy('product_transfer_details.sku_id');
        $pendingStockSku = $pendingStock->pluck('product_transfer_details.sku_id', 'product_transfer_details.sku_id')->toArray();
        $pendingStock = $pendingStock->pluck('total_qty', 'product_transfer_details.sku_id')->toArray();
//        return $pendingStock;
        //Pending order
        $pendingOrder = OrderDetails::join('order', 'order.id', 'order_details.order_id')
                ->select('order_details.sku_id', DB::raw("SUM(order_details.quantity) as total_qty"))
                ->groupBy('order_details.sku_id')
                ->where('order.warehouse_id', $warehouseId)
                ->whereIn('order.status', ['0', '2']);
        if (!empty($orderId)) {
            $pendingOrder = $pendingOrder->where('order_details.order_id', '<>', $orderId);
        }
        $pendingOrderSku = $pendingOrder->pluck('order_details.sku_id', 'order_details.sku_id')->toArray();
        $pendingOrder = $pendingOrder->pluck('total_qty', 'order_details.sku_id')->toArray();

        //partially delivered
        $partiallyDelivered = DeliveryDetails::join('order_details', 'order_details.id', 'delivery_details.order_details_id')
                ->join('order', 'order.id', 'order_details.order_id')
                ->select('delivery_details.sku_id', DB::raw("SUM(delivery_details.quantity) as total_qty"))
                ->groupBy('delivery_details.sku_id')
                ->where('order.warehouse_id', $warehouseId)
                ->where('order.status', '2');
        if (!empty($orderId)) {
            $partiallyDelivered = $partiallyDelivered->where('order_details.order_id', '<>', $orderId);
        }
        $partiallyDeliveredSku = $partiallyDelivered->pluck('delivery_details.sku_id', 'delivery_details.sku_id')->toArray();
        $partiallyDelivered = $partiallyDelivered->pluck('total_qty', 'delivery_details.sku_id')->toArray();

        $skuArr = !empty($pendingStockSku) ? $pendingStockSku : [];
        $skuArr += !empty($pendingOrderSku) ? $pendingOrderSku : [];
        $skuArr += !empty($partiallyDeliveredSku) ? $partiallyDeliveredSku : [];

        $freezeStockArr = [];
        if (!empty($skuArr)) {
            foreach ($skuArr as $skuId => $skuId) {
                $pendingStk = !empty($pendingStock[$skuId]) ? $pendingStock[$skuId] : 0;
                $pendingOrd = !empty($pendingOrder[$skuId]) ? $pendingOrder[$skuId] : 0;
                $prtDelv = !empty($partiallyDelivered[$skuId]) ? $partiallyDelivered[$skuId] : 0;
                $freezeStockArr[$skuId] = $pendingStk + $pendingOrd - $prtDelv;
            }
        }

        return $freezeStockArr;
    }

    public static function getDeliveredSku($OrderId) {

        return DeliveryDetails::join('delivery', 'delivery.id', '=', 'delivery_details.delivery_id')
                        ->where('delivery.order_id', $OrderId)
                        ->select('delivery_details.sku_id', DB::raw('SUM(delivery_details.quantity) as delivered_qty'))
                        ->groupBy('delivery_details.sku_id')
                        ->pluck('delivered_qty', 'delivery_details.sku_id')->toArray();
    }

    public static function getPaymentModeList() {
        $paymentModeList = [
            '1' => __('label.CASH'),
            '2' => __('label.CREDIT'),
            '3' => __('label.MFS'),
        ];
        return $paymentModeList;
    }

    public static function generateRandomString($length = 10): string {

        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    

}
