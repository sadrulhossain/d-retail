
<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        @if(!empty($userAccessArr[103][6]))
        <a class="btn green-haze pull-right tooltips vcenter margin-left-right-5" target="_blank" href="{{ URL::to('admin/processingOrder/' . $request->delivery_id . '/printInvoice') }}" title="" data-original-title="Click here to Print">
            <i class="fa fa-print"></i>&nbsp;@lang('label.PRINT_INVOICE')</a>
        @endif
        <h4 class="modal-title text-center">
            {{ __('label.DELIVERY_DETAILS') }}
        </h4>
    </div>
    <div class="modal-body">

        <!--BASIC ORDER INFORMATION-->
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.BASIC_ORDER_INFO')</strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless">
                            <tr >
                                <td class="bold" width="50%">@lang('label.ORDER_NO')</td>
                                <td width="50%">{!! !empty($order->order_no)?$order->order_no: __('label.N_A') !!}</td>
                            </tr>
                            <tr >
                                <td class="bold" width="50%">@lang('label.RETAILER')</td>
                                <td width="50%">{!! !empty($order->retailer_name)?$order->retailer_name:__('label.N_A') !!}</td>
                            </tr>
                            <tr >
                                <td class="bold" width="50%">@lang('label.SR')</td>
                                <td width="50%">{!! !empty($order->sr)?$order->sr:__('label.N_A') !!}</td>
                            </tr>

                        </table>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless">

                            <tr>
                                <td class="bold" width="50%">@lang('label.CREATION_DATE')</td>
                                <td width="50%">
                                    {!! !empty($order->created_at)?Helper::formatDate($order->created_at):__('label.N_A') !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%">@lang('label.STATUS')</td>
                                <td width="50%">
                                    @if($order->status == '0')
                                    <span class="label label-sm label-primary">@lang('label.PENDING')</span>
                                    @elseif($order->status == '5')
                                    <span class="label label-sm label-info">@lang('label.DELIVERED')</span>
                                    @endif
                                </td>
                            </tr>         
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--END OF BASIC ORDER INFORMATION-->

        <!--DELIVERY INFORMATION-->
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.DELIVERY_INFO')</strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless">
                            <tr >
                                <td class="bold" width="50%">@lang('label.BL_NO')</td>
                                <td width="50%">{!! !empty($delivery->bl_no)?$delivery->bl_no:__('label.N_A') !!}</td>
                            </tr>
                            <tr >
                                <td class="bold" width="50%">@lang('label.EXPRESS_TRACKING_NO')</td>
                                <td width="50%">{!! !empty($delivery->express_tracking_no)?$delivery->express_tracking_no:__('label.N_A') !!}</td>
                            </tr>
                            <tr >
                                <td class="bold" width="50%">@lang('label.CONTAINER_NO')</td>
                                <td width="50%">{!! !empty($delivery->container_no)?$delivery->container_no:__('label.N_A') !!}</td>
                            </tr>

                        </table>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless">

                            <tr>
                                <td class="bold" width="50%">@lang('label.BL_DATE')</td>
                                <td width="50%">
                                    {!! !empty($delivery->bl_date)?Helper::formatDate($delivery->bl_date):__('label.N_A') !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%">@lang('label.PAYMENT_STATUS')</td>
                                <td width="50%">
                                    @if($delivery->payment_status == '0')
                                    <span class="label label-sm label-blue-steel">@lang('label.UNPAID')</span>
                                    @elseif($delivery->payment_status == '1')
                                    <span class="label label-sm label-green-steel">@lang('label.PAID')</span>
                                    @endif
                                </td>
                            </tr>         
                            <tr>
                                <td class="bold" width="50%">@lang('label.PAYMENT_MODE')</td>
                                <td width="50%">
                                    @if($delivery->payment_mode == '1')
                                    <span class="label label-sm label-green-sharp">{{$paymentModeList[$delivery->payment_mode]}}</span>
                                    @elseif($delivery->payment_mode == '2')
                                    <span class="label label-sm label-purple-sharp">{{$paymentModeList[$delivery->payment_mode]}}</span>
                                    @elseif($delivery->payment_mode == '3')
                                    <span class="label label-sm label-yellow-casablanca">{{$paymentModeList[$delivery->payment_mode]}}</span>
                                    @endif
                                </td>
                            </tr>         
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--END OF DELIVERY INFORMATION-->

        <!--product details-->
        @if(!$deliveryInfo->isEmpty())
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.PRODUCT_INFO')</strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 margin-top-20">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="active">
                                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                        <th class="vcenter">@lang('label.PRODUCT')</th>
                                        <th class="vcenter">@lang('label.SKU')</th>
                                        <th class="vcenter text-center">@lang('label.CUSTOMER_DEMAND')</th>
                                        <th class="vcenter text-center">@lang('label.AVAILABLE_QTY')</th>
                                        <th class="vcenter text-center">@lang('label.ORDER_QTY')</th>
                                        <th class="vcenter text-center">@lang('label.DELIVERED_QUANTITY')</th>
                                        <th class="vcenter text-center">@lang('label.DUE_QTY')</th>
                                        <th class="vcenter text-center">@lang('label.CHALLAN_QUANTITY')</th>
                                        <th class="text-center vcenter">@lang('label.PRICE')</th>
                                        <th class="text-center vcenter">@lang('label.TOTAL_PRICE')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $countItem = 0; ?>
                                    @foreach($deliveryInfo as $item)
                                    <tr>
                                        <?php
                                        $orderQty = $item->order_qty ?? 0;
                                        $delQty = !empty($item->sku_id) && !empty($deliveryDataArr[$item->sku_id]) ? $deliveryDataArr[$item->sku_id] : 0;
                                        $due = $orderQty - $delQty;

                                        $price = !empty($item->unit_price) ? Helper::numberFormat2Digit($item->unit_price) : '--';
                                        $priceText = !empty($item->unit_price) ? 'right' : 'center';
                                        $totalPrice = !empty($item->total_price) ? Helper::numberFormat2Digit($item->total_price) : '--';
                                        $totalPriceText = !empty($item->total_price) ? 'right' : 'center';
                                        ?>
                                        <td class="text-center vcenter">{!! ++$countItem !!}</td>
                                        <td class="vcenter">{!! $item->product_name ?? '' !!}</td>
                                        <td class="vcenter">{!! $item->sku ?? '' !!}</td>
                                        <td class="vcenter text-center">{!! !empty($item->customer_demand) ? (int) $item->customer_demand : '--' !!}</td>
                                        <td class="vcenter text-center">{!! !empty($item->available_quantity) ? (int) $item->available_quantity : '--' !!}</td>
                                        <td class="vcenter text-center">{!! !empty($item->order_qty) ? (int) $item->order_qty : '--' !!}</td>
                                        <td class="vcenter text-center">{!! !empty($item->sku_id) && !empty($deliveryDataArr[$item->sku_id]) ? (int) $deliveryDataArr[$item->sku_id] : '--' !!}</td>
                                        <td class="vcenter text-center">{!! !empty($due) ? (int) $due : '0' !!}</td>
                                        <td class="vcenter text-center">{!! !empty($item->quantity) ? (int) $item->quantity : '--' !!}</td>
                                        <td class="vcenter text-{{$priceText}}">{!! $price !!}</td>
                                        <td class="vcenter text-{{$totalPriceText}}">{!! $totalPrice !!}</td>
                                    </tr>
                                    @endforeach
                                    <?php
                                    $grandPrice = !empty($delivery->paying_amount) ? Helper::numberFormat2Digit($delivery->paying_amount) : '--';
                                    $grandPriceText = !empty($delivery->paying_amount) ? 'right' : 'center';
                                    ?>
                                    <tr>
                                        <td class=" vcenter bold text-right" colspan="10">@lang('label.GRAND_TOTAL')</td>
                                        <td class="vcenter bold text-{{$grandPriceText}}">{!! $grandPrice !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!--END OF PRODUCT DETAILS-->
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips"
                title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')
        </button>
    </div>
</div>

<script src="{{ asset('public/js/custom.js') }}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
});
$("#hasBankAccountSwitch").bootstrapSwitch({
    offColor: 'danger'

});


</script>
