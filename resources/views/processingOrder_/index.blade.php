@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-th-list"></i>@lang('label.PENDING_ORDER_LIST')
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => '/admin/processingOrder/filter','class' => 'form-horizontal')) !!}
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
                            <th class="vcenter text-center">@lang('label.STOCK')</th>
                            <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                            <th class="vcenter text-center">@lang('label.TOTAL_PAYING_AMOUNT')</th>
                            <th class="text-center vcenter">@lang('label.CREATION_DATE')</th>
                            <th class="text-center vcenter">@lang('label.ACTION')</th>
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
                            <td class="vcenter text-right text-{{$text}}"> 
                                {{ !empty($details['available_quantity']) ? number_format($details['available_quantity'], 0) : 0 }} 
                            </td>
                            <td class="vcenter text-right"> {{$details['total_price']}}&nbsp;@lang('label.TK') </td>

                            @if($i == 0)
                            <td class="vcenter text-right" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}"> {{$order['grand_total']}}&nbsp;@lang('label.TK') </td>

                            <td class="text-center vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">
                                {{ !empty($order['created_at']) ? Helper::formatDate($order['created_at']) : '' }}
                            </td>
                            <td class="td-actions text-center vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">
                                <div class="width-inherit">
                                    @if(!empty($userAccessArr[103][5]))
                                    <button class="btn btn-xs yellow-mint tooltips vcenter view-order-details" data-id="{!! $order['order_id'] !!}" href="#modalViewStockDemand"  data-toggle="modal" title="@lang('label.VIEW_STOCK_DEMAND')">
                                        <i class="fa fa-file-text-o"></i>
                                    </button>
                                    @endif
                                    @if(in_array(Auth::user()->group_id, [12]))
                                    @if(!empty($userAccessArr[103][16]))
                                    <!-- <a class="btn btn-xs purple-soft tooltips vcenter" href="{{ URL::to('admin/processingOrder/' . $order['order_id'] . '/getSetDelivery') }}"  title="@lang('label.SET_DELIVERY')">
                                        <i class="fa fa-cart-plus"></i>
                                    </a>-->
                                    <button class="btn btn-xs purple-soft tooltips vcenter delivery-information" data-id="{!! $order['order_id'] !!}" href="#modalDeliveryInformation"  data-toggle="modal"  title="@lang('label.SET_DELIVERY')">
                                        <i class="fa fa-cart-plus"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[103][13]))
                                    <button class="btn btn-xs tooltips vcenter red-soft cancel-order" data-id="{!! $order['order_id'] !!}" data-flag='8' data-placement="top" data-rel="tooltip" title="@lang('label.CANCEL_ORDER')">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                    @endif
                                    @endif
                                </div>

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
                            <td colspan="18" class="vcenter">@lang('label.NO_PROCESSING_ORDER_FOUND')</td>
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
<div class="modal fade" id="modalDeliveryInformation" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showDeliveryInformation"></div>
    </div>
</div>
<!-- END:: Show Order Information Form -->

<script type="text/javascript">

    $(document).ready(function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null
        };

        $(document).on('click', '.product-return', function () {

            var orderId = $(this).attr("data-id");
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "{{URL::to('admin/processingOrder/getProductReturn')}}",
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
                        url: "{{ URL::to('/admin/processingOrder/setProductReturn')}}",
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
                            setTimeout(window.location.replace('{{ URL::to("/admin/processingOrder")}}'), 1000);
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

        $(document).on("click", ".cancel-order", function () {
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null
            };
            var id = $(this).attr("data-id");
            var flag = $(this).attr("data-flag");
            swal({
                title: "Are you sure, you want cancel this order?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, cancel It",
                closeOnConfirm: true,
                closeOnCancel: true,
            }, function (isConfirm) {
                if (isConfirm) {
                    if (id) {
                        $.ajax({
                            url: "{{URL::to('admin/processingOrder/cancel')}}",
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
                    url: "{{URL::to('admin/processingOrder/confirmDelivery')}}",
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

        $(document).on("click", ".view-order-details", function (e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/admin/processingOrder/viewStockDemand')}}",
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

        // *************** START:: Show Delivery Information Modal ***************//
        $(document).on("click", ".delivery-information", function (e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/admin/processingOrder/getSetDelivery')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id
                },
                beforeSend: function () {
                    $("#showDeliveryInformation").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showDeliveryInformation").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax


        });
        // *************** END:: Show Delivery Information Modal ***************//

        // ***************** START:: Show Payment Information ******************//

        $(document).on("click", "#confirmProceed", function (e) {
            e.preventDefault();
            var bilNo = $(".bl-no").val();
            $("#confirmProceed").prop('disabled', true);

            if (typeof bilNo === 'undefined' || bilNo === '') {
                toastr.error("Please, Enter Delivery Challan No", "Error", options);
                $("#confirmProceed").prop('disabled', false);
                return false;
            }

            var deliveryFromData = new FormData($("#setDeliveryForm")[0]);

            $.ajax({
                url: "{{ URL::to('admin/showPaymentInfo')}}",
                type: "POST",
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: deliveryFromData,
                beforeSend: function () {
                    $("#showDeliveryInformation").html('<strong>@lang("label.LOADING_PLEASE_WAIT")</strong>');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $("#showDeliveryInformation").fadeIn('slow');
                    $("#showDeliveryInformation").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    App.unblockUI();
                }
            }); //ajax
        });

        $(document).on("click", "#confirmPayment", function (e) {
            e.preventDefault();
            var orderId = $(this).attr("data-id");
            var paymentMode = $('input[name=payment_mode]:checked').attr('data-val');
            var deliveryFromData = new FormData($("#setInformationForm")[0]);
            if (typeof paymentMode === 'undefined' || paymentMode === '') {
                toastr.error("Please, Select Payment Mode", "Error", options);
                return false;
            }
            $.ajax({
                url: "{{ URL::to('admin/confirmDelivery')}}",
                type: "POST",
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: deliveryFromData,
                beforeSend: function () {
                    $("#showDeliveryInformation").html('<strong>@lang("label.LOADING_PLEASE_WAIT")</strong>');
                    /*App.blockUI({
                     boxed: true
                     });*/
                },
                success: function (res) {
                    $("#showDeliveryInformation").fadeIn('slow');
                    $("#showDeliveryInformation").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax


        });

        $(document).on("click", "#confirmDelivery", function () {
            $("#confirmDelivery").prop("disabled", true);
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

                    var formData = new FormData($("#saveDeliveryForm")[0]);
                    $.ajax({
                        url: "{{ URL::to('/admin/processingOrder/saveSetDelivery')}}",
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
                            $('#confirmDeliveryLoading').append('<strong>@lang("label.ORDER_STATUS_UPDATE_IN_PROGRESS")</strong>');
                            App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            setTimeout(window.location.replace('{{ URL::to("/admin/processingOrder")}}'), 1000);
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
                            $("#confirmDelivery").prop("disabled", false);
                            $('#confirmDeliveryLoading').html('');
                            App.unblockUI();
                        }

                    }); //ajax
                } else {
                    $("#confirmDelivery").prop("disabled", false);
                }
            });
        });
    });


    // ***************** END:: Show Payment Information ******************//
</script>

@stop
