@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-th-list"></i>@lang('label.DELIVERED_ORDER_LIST')
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => '/admin/deliveredOrder/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="fromDate">@lang('label.FROM_DATE') :</label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                {!! Form::text('from_date', Request::get('from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="fromDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="toDate">@lang('label.TO_DATE') </label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                {!! Form::text('to_date', Request::get('to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="toDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="orderNo">@lang('label.ORDER_NO')</label>
                        <div class="col-md-8">
                            {!! Form::select('order_no', $orderNoList, Request::get('order_no'), ['class' => 'form-control js-source-states','id'=>'orderNo']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="orderNo">@lang('label.RETAILER'):</label>
                        <div class="col-md-8">
                            {!! Form::select('retailer_id', $retailerList, Request::get('retailer_id'), ['class' => 'form-control js-source-states','id'=>'retailerId']) !!}
                        </div>
                    </div>
                </div>
                @if(Auth::user()->group_id != 14)
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="srId">@lang('label.SR'):</label>
                        <div class="col-md-8">
                            {!! Form::select('sr_id', $srList, Request::get('sr_id'), ['class' => 'form-control js-source-states','id'=>'srId']) !!}
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-md-4 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.FILTER')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->


            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.ORDER_NO')</th>
                            <th class="vcenter">@lang('label.RETAILER')</th>
                            <th class="vcenter">@lang('label.SR')</th>
                            <th class="vcenter">@lang('label.PRODUCT')</th>
                            <th class="vcenter">@lang('label.BRAND')</th>
                            <th class="vcenter">@lang('label.SKU')</th>
                            <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                            <th class="vcenter text-center">@lang('label.PRICE')</th>
                            <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                            <th class="vcenter text-center">@lang('label.TOTAL_PAYING_AMOUNT')</th>
                            <th class="text-center vcenter">@lang('label.CREATION_DATE')</th>
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
                            @if(!empty($userAccessArr[57][5]))
                            <th class="text-center vcenter">@lang('label.DELIVERY_DETAILS')</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $data)
                        <tr>
                            <?php
                            $order = !empty($data->order_id) && !empty($orderArr[$data->order_id]) ? $orderArr[$data->order_id] : 0;
                            ?>
                            <td class="text-center vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{!! ++$sl !!}</td>
                            <td class="vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{{ $order['order_no'] }}</td>
                            <td class="vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{{ $order['retailer_name'] }}</td>
                            <td class="vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{{ $order['user_name'] }}</td>

                            @if(!empty($order['products']))
                            <?php $i = 0; ?>
                            @foreach($order['products'] as $detailsId => $details)
                            <?php
                            if ($i > 0) {
                                echo '<tr>';
                            }
                            ?>
                            <td class="vcenter"> {{$details['product_name']}} </td>
                            <td class="vcenter"> {{$details['brand_name']}} </td>
                            <td class="vcenter"> {{$details['sku']}} </td>
                            <td class="vcenter text-right"> {{$details['quantity']}} </td>
                            <td class="vcenter text-right"> {{$details['unit_price']}}&nbsp;@lang('label.TK') </td>
                            <?php
                            $text = 'red-intense';
                            if (!empty($details['quantity']) && !empty($details['available_quantity'])) {
                                if ($details['quantity'] <= $details['available_quantity']) {
                                    $text = 'green-steel';
                                }
                            }
                            ?>
                            <td class="vcenter text-right"> {{$details['total_price']}}&nbsp;@lang('label.TK') </td>

                            @if($i == 0)
                            <td class="vcenter text-right" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}"> {{$order['grand_total']}}&nbsp;@lang('label.TK') </td>

                            <td class="text-center vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">
                                {{ !empty($order['created_at']) ? Helper::formatDate($order['created_at']) : '' }}
                            </td>
                            <td class="text-center vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">

                                @if($order['status'] == '2')
                                <span class="label label-sm label-blue-soft">@lang('label.PROCESSING')</span>
                                @elseif($order['status'] == '3')
                                <span class="label label-sm label-purple-sharp">@lang('label.PLACED_IN_DELIVERY')</span>
                                @elseif($order['status'] == '4')
                                <span class="label label-sm label-yellow">@lang('label.RETURNED')</span>
                                @elseif($order['status'] == '5')
                                <span class="label label-sm label-green-sharp">@lang('label.DELIVERED')</span>
                                @endif
                            </td>
                            @if(!empty($userAccessArr[57][5]))
                            <td class="td-actions text-center vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">
                                
                                @if(!empty($deliveryArr[$order['order_id']]))
                                @foreach($deliveryArr[$order['order_id']] as $deliveryId => $delivery)
                                <button  type="button" class="btn btn-xs green-seagreen btn-circle btn-rounded tooltips vcenter delivery-details" 
                                         href="#deliveryDetails"  data-toggle="modal" data-orderId ="{{$delivery['order_id']}}" 
                                         data-deliveryId="{{$delivery['delivery_id']}}" data-html="true" 
                                         title="
                                         <div class='text-left'>
                                         @lang('label.BL_NO'): &nbsp;{!! $delivery['bl_no'] !!}<br/>
                                         @lang('label.PAYMENT_STATUS'): &nbsp;{!! $delivery['payment_status'] !!}<br/>
                                         @lang('label.PAYMENT_MODE'): &nbsp;{!! $delivery['payment_mode'] !!}<br/>
                                         @lang('label.CLICK_TO_SEE_DETAILS')
                                         </div>
                                         " 
                                         >
                                    <i class="fa fa-truck"></i>
                                </button>
                                @endforeach
                                @else
                                <button type="button" class="btn btn-xs cursor-default btn-circle btn-rounded-flat red-soft tooltips vcenter" title="@lang('label.NO_SHIPMENT_YET')">
                                    <i class="fa fa-minus"></i>
                                </button>                                
                                @endif

                                @endif

                            </td>
                            @endif

                            <?php
                            if ($i < (sizeof($order['products']) - 1)) {
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
            @include('layouts.paginator')
        </div>

    </div>
</div>
<div class="modal fade" id="modalProductReturn" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showProductReturn">

        </div>
    </div>
</div>
<!--view stock and demand modal-->
<div class="modal fade" id="modalViewStockDemand" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showViewStockDemand">
        </div>
    </div>
</div>

<!-- START:: Show Order Information Form-->
<div class="modal fade" id="deliveryDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showDeliveryDetails">
        </div>
    </div>
</div>
<!-- END:: Show Order Information Form -->

<script type="text/javascript">

    $(document).on('click', '.product-return', function () {

        var orderId = $(this).attr("data-id");
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        $.ajax({
            url: "{{URL::to('admin/orderPlacedInDelivery/getProductReturn')}}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                order_id: orderId
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#showProductReturn').html(res.html);
                App.unblockUI();
            },
        });
    });

    $(document).on("click", "#submitReturnSave", function () {
        swal({
            title: "Are you sure?",
            text: "@lang('label.DO_YOU_WANT_TO_CONTINUE_IT')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('label.YES_CONTINUE_IT')",
            closeOnConfirm: true,
            closeOnCancel: true,
        }, function (isConfirm) {
            if (isConfirm) {
                var options = {
                    closeButton: true,
                    debug: false,
                    positionClass: "toast-bottom-right",
                    onclick: null,
                };

                var formData = new FormData($("#productReturnForm")[0]);
                $.ajax({
                    url: "{{ URL::to('/admin/orderPlacedInDelivery/setProductReturn')}}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    beforeSend: function () {
                        App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
                        setTimeout(window.location.replace('{{ URL::to("/admin/orderPlacedInDelivery")}}'), 1000);
                        App.unblockUI();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        if (jqXhr.status == 400) {
                            var errorsHtml = '';
                            var errors = jqXhr.responseJSON.message;
                            $.each(errors, function (key, value) {
                                errorsHtml += '<li>' + value[0] + '</li>';
                            });
                            toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                        } else if (jqXhr.status == 401) {
                            toastr.error(jqXhr.responseJSON.message, '', options);
                        } else {
                            toastr.error('Error', "@lang('label.SOMETHING_WENT_WRONG')", options);
                        }
                        App.unblockUI();
                    }
                }); //ajax
            }
        });
    });


    $(document).on("click", ".mark-as-delivered", function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null
        };
        var id = $(this).attr("data-id");
        var flag = $(this).attr("data-flag");
        if (id) {
            $.ajax({
                url: "{{URL::to('admin/orderPlacedInDelivery/confirmDelivery')}}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id,
                    status: flag,
                },
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
        }
    });

    $(document).on("click", ".view-stock-demand", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        $.ajax({
            url: "{{ URL::to('/admin/orderPlacedInDelivery/viewStockDemand')}}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id
            },
            beforeSend: function () {
                $("#showViewStockDemand").html('');
                App.blockUI({
                    boxed: true
                });
            },
            success: function (res) {
                $("#showViewStockDemand").html(res.html);
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                App.unblockUI();
            }
        }); //ajax
    });

    // START:: Delivery Modal
    $(document).on('click', ".delivery-details", function (e) {
        e.preventDefault();
        var orderId = $(this).attr("data-orderId");
        var deliveryId = $(this).attr("data-deliveryId");
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        $.ajax({
            url: "{{ URL::to('admin/deliveredOrder/getDeliveryDetails')}}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json', // what to expect back from the PHP script, if anything
            data: {
                delivery_id: deliveryId,
                order_id: orderId,
            },
            beforeSend: function () {
                $('#showDeliveryDetails').html();
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#showDeliveryDetails').html(res.html)
//                    setTimeout(window.location.replace('{{ URL::to("/admin/processingOrder")}}'), 1000);
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                if (jqXhr.status == 400) {
                    var errorsHtml = '';
                    var errors = jqXhr.responseJSON.message;
                    $.each(errors, function (key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                } else if (jqXhr.status == 401) {
                    toastr.error(jqXhr.responseJSON.message, '', options);
                } else {
                    toastr.error('Error', "@lang('label.SOMETHING_WENT_WRONG')", options);
                }
                $('#confirmDeliveryLoading').html('');
                App.unblockUI();
            }

        }); //ajax
    });
    // END:: Delivery Modal
</script>

@stop
