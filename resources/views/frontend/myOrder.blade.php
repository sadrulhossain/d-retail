@extends('frontend.layouts.default.master')
@section('content')

<div class="container">

    <div class="wrap-breadcrumb">
        <ul>
            <li class="item-link"><a href="#" class="link">@lang('label.HOME')</a></li>
            <li class="item-link"><span>@lang('label.MY_ORDER')</span></li>
        </ul>
    </div>
    <div class="wishlist-box font-size-14 style-1">
        <h3 class="title-box">@lang('label.ORDER_LIST')</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="tabbable-line">
                    <ul class="nav nav-pills ">
                        <li class="active bg-blue-soft" id="onGoingOrder">
                            <a class="bold tab-color" href="#tab_1" id="onGoingOrderBtn" data-toggle="tab"> @lang('label.ON_GOING_ORDER') ({{ !empty($onGoingTargetArr['count']) ? $onGoingTargetArr['count'] : 0  }}) </a>
                        </li>
                        <li class="bg-blue-soft" id="returnedOrder">
                            <a class="bold tab-color" href="#tab_2" id="returnedOrderBtn" data-toggle="tab"> @lang('label.RETURNED_ORDER') ({{ !empty($returnedTargetArr['count']) ? $returnedTargetArr['count'] : 0 }})</a>
                        </li>
                        <li class="bg-blue-soft" id="cancelledOrder">
                            <a class="bold tab-color" href="#tab_3" id="cancelledOrderBtn" data-toggle="tab"> @lang('label.CANCELLED_ORDER') ({{ !empty($cancelledTargetArr['count']) ? $cancelledTargetArr['count'] : 0 }})</a>
                        </li>
                        <li class="bg-blue-soft" id="deliveredOrder">
                            <a class="bold tab-color" href="#tab_4" id="deliveredOrderBtn" data-toggle="tab"> @lang('label.DELIVERED_ORDER') ({{ !empty($deliveredTargetArr['count']) ? $deliveredTargetArr['count'] : 0 }})</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class=" main-content-area">
                                <div class="row margin-top-10">
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 table-responsive">
                                        <div class="max-height-500 webkit-scrollbar wrap-iten-in-cart">
                                            <table class="table table-striped table-bordered products-cart">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                                        <th class="vcenter">@lang('label.ORDER_NO')</th>
                                                        <th class="vcenter">@lang('label.SHIPPING_ADDRESS')</th>
                                                        <th class="vcenter">@lang('label.PAYMENT_TYPE')</th>
                                                        <th class="vcenter">@lang('label.PRODUCT')</th>
                                                        <th class="vcenter text-center">@lang('label.UNIT_PRICE')</th>
                                                        <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                                        <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                                                        <th class="vcenter text-center">@lang('label.VAT')</th>
                                                        <th class="vcenter text-center">@lang('label.TOTAL_PAYING_AMOUNT')</th>
                                                        <th class="text-center vcenter">@lang('label.DATE_OF_ORDER')</th>
                                                        <th class="text-center vcenter">@lang('label.STATUS')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!$onGoingOrderData->isEmpty())
                                                    <?php
                                                    $sl = 0;
                                                    ?>
                                                    @foreach($onGoingOrderData as $data)
                                                    <?php
                                                    $order = !empty($data->order_id) && !empty($onGoingTargetArr[$data->order_id]) ? $onGoingTargetArr[$data->order_id] : 0;
//                                                    echo "<pre>";
//                                                    print_r($onGoingTargetArr[$data->order_id]);
//                                                    exit;
                                                    ?>
                                                    <tr>
                                                        <td class="text-center vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">{!! ++$sl !!}</td>
                                                        <td class="vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">{{ $order['order_no'] }}</td>
                                                        <td class="vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">{{ $order['shipping_address'] }}</td>
                                                        <td class="vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            @if(!empty($order['payment_type']))
                                                            @if($order['payment_type'] == '1')
                                                            @lang('label.CASH_ON_DELIVERY')
                                                            @endif
                                                            @else
                                                            @lang('label.N_A')
                                                            @endif
                                                        </td>
                                                        @if(!empty($order['item']))
                                                        <?php $i = 0; ?>
                                                        @foreach($order['item'] as $skuId => $item)
                                                        <?php
                                                        if ($i > 0) {
                                                            echo '<tr>';
                                                        }
                                                        ?>
                                                        <td class="vcenter"> {{$item['product']}} </td>
                                                        <td class="vcenter text-right"> {{$item['unit_price']}}&nbsp;@lang('label.TK') </td>
                                                        <td class="vcenter text-right"> {{$item['quantity']}} </td>
                                                        <td class="vcenter text-right"> {{$item['total_price']}}&nbsp;@lang('label.TK') </td>

                                                        @if($i == 0)
                                                        <td class="text-right vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            {{ !empty($order['vat']) ? $order['vat'] : '0.00' }}&nbsp;@lang('label.TK')
                                                        </td>
                                                        <td class="text-right vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            {{ $order['paying_amount'] }}&nbsp;@lang('label.TK')
                                                        </td>
                                                        <td class="text-center vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            {{ !empty($order['created_at']) ? Helper::formatDate($order['created_at']) : '' }}
                                                        </td>
                                                        <td class="text-center vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">

                                                            @if($order['status'] == '0')
                                                            <span class="label label-sm label-blue-hoki">@lang('label.PENDING')</span>
                                                            @elseif($order['status'] == '1')
                                                            <span class="label label-sm label-green-steel">@lang('label.CONFIRMED')</span>
                                                            @elseif($order['status'] == '2')
                                                            <span class="label label-sm label-blue-soft">@lang('label.PROCESSING')</span>
                                                            @elseif($order['status'] == '3')
                                                            <span class="label label-sm label-purple-sharp">@lang('label.PLACED_IN_DELIVERY')</span>
                                                            @endif
                                                        </td>
                                                        @endif

                                                        <?php
                                                        if ($i < ($order['rowspan'] - 1)) {
                                                            echo '</tr>';
                                                        }
                                                        $i++;
                                                        ?>
                                                        @endforeach
                                                        @endif
                                                    </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="18" class="vcenter">@lang('label.NO_ORDER_FOUND')</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div><!--end main content area-->
                        </div>
                        <div class="tab-pane" id="tab_2">
                            <div class=" main-content-area">
                                <div class="row margin-top-10">
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 table-responsive">
                                        <div class="max-height-500 webkit-scrollbar wrap-iten-in-cart">
                                            <table class="table table-striped table-bordered products-cart">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                                        <th class="vcenter">@lang('label.ORDER_NO')</th>
                                                        <th class="vcenter">@lang('label.SHIPPING_ADDRESS')</th>
                                                        <th class="vcenter">@lang('label.PAYMENT_TYPE')</th>
                                                        <th class="vcenter">@lang('label.PRODUCT')</th>
                                                        <th class="vcenter text-center">@lang('label.UNIT_PRICE')</th>
                                                        <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                                        <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                                                        <th class="vcenter text-center">@lang('label.VAT')</th>
                                                        <th class="vcenter text-center">@lang('label.TOTAL_PAYING_AMOUNT')</th>
                                                        <th class="text-center vcenter">@lang('label.DATE_OF_ORDER')</th>
                                                        <th class="text-center vcenter">@lang('label.RETURN_DATE')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!$returnedOrderData->isEmpty())
                                                    <?php
                                                    $sl = 0;
                                                    ?>
                                                    @foreach($returnedOrderData as $data)
                                                    <?php
                                                    $order = !empty($data->order_id) && !empty($returnedTargetArr[$data->order_id]) ? $returnedTargetArr[$data->order_id] : 0;
                                                    ?>
                                                    <tr>
                                                        <td class="text-center vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">{!! ++$sl !!}</td>
                                                        <td class="vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">{{ $order['order_no'] }}</td>
                                                        <td class="vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">{{ $order['shipping_address'] }}</td>
                                                        <td class="vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            @if(!empty($order['payment_type']))
                                                            @if($order['payment_type'] == '1')
                                                            @lang('label.CASH_ON_DELIVERY')
                                                            @endif
                                                            @else
                                                            @lang('label.N_A')
                                                            @endif
                                                        </td>
                                                        @if(!empty($order['item']))
                                                        <?php $i = 0; ?>
                                                        @foreach($order['item'] as $skuId => $item)
                                                        <?php
                                                        if ($i > 0) {
                                                            echo '<tr>';
                                                        }
                                                        ?>
                                                        <td class="vcenter"> {{$item['product']}} </td>
                                                        <td class="vcenter text-right"> {{$item['unit_price']}}&nbsp;@lang('label.TK') </td>
                                                        <td class="vcenter text-right"> {{$item['quantity']}} </td>
                                                        <td class="vcenter text-right"> {{$item['total_price']}}&nbsp;@lang('label.TK') </td>

                                                        @if($i == 0)
                                                        <td class="text-right vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            {{ !empty($order['vat']) ? $order['vat'] : '0.00' }}&nbsp;@lang('label.TK')
                                                        </td>
                                                        <td class="text-right vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            {{ $order['paying_amount'] }}&nbsp;@lang('label.TK')
                                                        </td>
                                                        <td class="text-center vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            {{ !empty($order['created_at']) ? Helper::formatDate($order['created_at']) : '' }}
                                                        </td>
                                                        <td class="text-center vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            {{ !empty($order['updated_at']) ? Helper::formatDate($order['updated_at']) : '' }}
                                                        </td>
                                                        @endif

                                                        <?php
                                                        if ($i < ($order['rowspan'] - 1)) {
                                                            echo '</tr>';
                                                        }
                                                        $i++;
                                                        ?>
                                                        @endforeach
                                                        @endif
                                                    </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="18" class="vcenter">@lang('label.NO_ORDER_FOUND')</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane" id="tab_3">
                            <div class=" main-content-area">
                                <div class="row margin-top-10">
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 table-responsive">
                                        <div class="max-height-500 webkit-scrollbar wrap-iten-in-cart">
                                            <table class="table table-striped table-bordered products-cart">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                                        <th class="vcenter">@lang('label.ORDER_NO')</th>
                                                        <th class="vcenter">@lang('label.SHIPPING_ADDRESS')</th>
                                                        <th class="vcenter">@lang('label.PAYMENT_TYPE')</th>
                                                        <th class="vcenter">@lang('label.PRODUCT')</th>
                                                        <th class="vcenter text-center">@lang('label.UNIT_PRICE')</th>
                                                        <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                                        <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                                                        <th class="vcenter text-center">@lang('label.VAT')</th>
                                                        <th class="vcenter text-center">@lang('label.TOTAL_PAYING_AMOUNT')</th>
                                                        <th class="text-center vcenter">@lang('label.DATE_OF_ORDER')</th>
                                                        <th class="text-center vcenter">@lang('label.CANCELLATION_DATE')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!$cancelledOrderData->isEmpty())
                                                    <?php
                                                    $sl = 0;
                                                    ?>
                                                    @foreach($cancelledOrderData as $data)
                                                    <?php
                                                    $order = !empty($data->order_id) && !empty($cancelledTargetArr[$data->order_id]) ? $cancelledTargetArr[$data->order_id] : 0;
                                                    ?>
                                                    <tr>
                                                        <td class="text-center vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">{!! ++$sl !!}</td>
                                                        <td class="vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">{{ $order['order_no'] }}</td>
                                                        <td class="vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">{{ $order['shipping_address'] }}</td>
                                                        <td class="vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            @if(!empty($order['payment_type']))
                                                            @if($order['payment_type'] == '1')
                                                            @lang('label.CASH_ON_DELIVERY')
                                                            @endif
                                                            @else
                                                            @lang('label.N_A')
                                                            @endif
                                                        </td>
                                                        @if(!empty($order['item']))
                                                        <?php $i = 0; ?>
                                                        @foreach($order['item'] as $skuId => $item)
                                                        <?php
                                                        if ($i > 0) {
                                                            echo '<tr>';
                                                        }
                                                        ?>
                                                        <td class="vcenter"> {{$item['product']}} </td>
                                                        <td class="vcenter text-right"> {{$item['unit_price']}}&nbsp;@lang('label.TK') </td>
                                                        <td class="vcenter text-right"> {{$item['quantity']}} </td>
                                                        <td class="vcenter text-right"> {{$item['total_price']}}&nbsp;@lang('label.TK') </td>

                                                        @if($i == 0)
                                                        <td class="text-right vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            {{ !empty($order['vat']) ? $order['vat'] : '0.00' }}&nbsp;@lang('label.TK')
                                                        </td>
                                                        <td class="text-right vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            {{ $order['paying_amount'] }}&nbsp;@lang('label.TK')
                                                        </td>
                                                        <td class="text-center vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            {{ !empty($order['created_at']) ? Helper::formatDate($order['created_at']) : '' }}
                                                        </td>
                                                        <td class="text-center vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            {{ !empty($order['updated_at']) ? Helper::formatDate($order['updated_at']) : '' }}
                                                        </td>
                                                        @endif

                                                        <?php
                                                        if ($i < ($order['rowspan'] - 1)) {
                                                            echo '</tr>';
                                                        }
                                                        $i++;
                                                        ?>
                                                        @endforeach
                                                        @endif
                                                    </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="18" class="vcenter">@lang('label.NO_ORDER_FOUND')</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane" id="tab_4">
                            <div class=" main-content-area">
                                <div class="row margin-top-10">
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 table-responsive">
                                        <div class="max-height-500 webkit-scrollbar wrap-iten-in-cart">
                                            <table class="table table-striped table-bordered products-cart">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                                        <th class="vcenter">@lang('label.ORDER_NO')</th>
                                                        <th class="vcenter">@lang('label.SHIPPING_ADDRESS')</th>
                                                        <th class="vcenter">@lang('label.PAYMENT_TYPE')</th>
                                                        <th class="vcenter">@lang('label.PRODUCT')</th>
                                                        <th class="vcenter text-center">@lang('label.UNIT_PRICE')</th>
                                                        <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                                        <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                                                        <th class="vcenter text-center">@lang('label.VAT')</th>
                                                        <th class="vcenter text-center">@lang('label.TOTAL_PAYING_AMOUNT')</th>
                                                        <th class="text-center vcenter">@lang('label.DATE_OF_ORDER')</th>
                                                        <th class="text-center vcenter">@lang('label.DELIVERY_DATE')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!$deliveredOrderData->isEmpty())
                                                    <?php
                                                    $sl = 0;
                                                    ?>
                                                    @foreach($deliveredOrderData as $data)
                                                    <?php
                                                    $order = !empty($data->order_id) && !empty($deliveredTargetArr[$data->order_id]) ? $deliveredTargetArr[$data->order_id] : 0;
                                                    ?>
                                                    <tr>
                                                        <td class="text-center vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">{!! ++$sl !!}</td>
                                                        <td class="vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">{{ $order['order_no'] }}</td>
                                                        <td class="vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">{{ $order['shipping_address'] }}</td>
                                                        <td class="vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            @if(!empty($order['payment_type']))
                                                            @if($order['payment_type'] == '1')
                                                            @lang('label.CASH_ON_DELIVERY')
                                                            @endif
                                                            @else
                                                            @lang('label.N_A')
                                                            @endif
                                                        </td>
                                                        @if(!empty($order['item']))
                                                        <?php $i = 0; ?>
                                                        @foreach($order['item'] as $skuId => $item)
                                                        <?php
                                                        if ($i > 0) {
                                                            echo '<tr>';
                                                        }
                                                        ?>
                                                        <td class="vcenter"> {{$item['product']}} </td>
                                                        <td class="vcenter text-right"> {{$item['unit_price']}}&nbsp;@lang('label.TK') </td>
                                                        <td class="vcenter text-right"> {{$item['quantity']}} </td>
                                                        <td class="vcenter text-right"> {{$item['total_price']}}&nbsp;@lang('label.TK') </td>

                                                        @if($i == 0)
                                                        <td class="text-right vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            {{ !empty($order['vat']) ? $order['vat'] : '0.00' }}&nbsp;@lang('label.TK')
                                                        </td>
                                                        <td class="text-right vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            {{ $order['paying_amount'] }}&nbsp;@lang('label.TK')
                                                        </td>
                                                        <td class="text-center vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            {{ !empty($order['created_at']) ? Helper::formatDate($order['created_at']) : '' }}
                                                        </td>
                                                        <td class="text-center vcenter" rowspan="{{!empty($order['rowspan']) ? $order['rowspan'] : 1}}">
                                                            {{ !empty($order['updated_at']) ? Helper::formatDate($order['updated_at']) : '' }}
                                                        </td>
                                                        @endif

                                                        <?php
                                                        if ($i < ($order['rowspan'] - 1)) {
                                                            echo '</tr>';
                                                        }
                                                        $i++;
                                                        ?>
                                                        @endforeach
                                                        @endif
                                                    </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="18" class="vcenter">@lang('label.NO_ORDER_FOUND')</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div><!--end container-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script type="text/javascript">
$(document).ready(function () {
    $(document).on("click", ".remove-item", function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null
        };
        var id = $(this).data('id');
        if (id) {
            $.ajax({
                url: "{{URL::to('/wishlist/removeItem')}}/" + id,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    toastr.success(res.data, res.message, options);
                    location.reload();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                }
            });
        } else {
            alert('danger');
        }
    });
});
</script>
@stop
