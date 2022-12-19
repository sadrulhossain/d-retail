@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-th-list"></i>@lang('label.INVOICE')
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => '#','id'=>'previewForm','class' => 'form-horizontal')) !!}
            {!! Form::hidden('order_id', $id) !!} 
            {!! Form::hidden('customer_id', $order->customer_id) !!} 
            {!! Form::hidden('courier_id', $order->courier_id) !!} 
            <div class="row">
                <div class="col-md-4">
                    <span class="bold">@lang('label.CUSTOMER'): </span>{!! $order->customer_name !!}
                </div>
                <div class="col-md-4">
                    <span class="bold">@lang('label.EMAIL'): </span>{!! $order->email !!}
                </div>
                <div class="col-md-4">
                    <span class="bold">@lang('label.PHONE'): </span>{!! $order->phone !!}
                </div>
                <div class="col-md-4">
                    <span class="bold">@lang('label.ADDRESS'): </span>{!! $order->shipping_address !!}
                </div>
            </div>
            <div class="row margin-top-20">

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-5" for="search">@lang('label.INVOICE_NUMBER') :<span class="text-danger"> *</span></label>
                        <div class="col-md-7">
                            {!! Form::text('invoice_number',  !empty($invoiceInfo->invoice_number) ? $invoiceInfo->invoice_number : Request::get('invoice_number'), ['class' => 'form-control tooltips', 'title' => 'Invoice Number', 'placeholder' => 'Invoice Number', 'autocomplete'=>'off']) !!} 

                            <span class="text-danger">{{ $errors->first('invoice_number') }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-5" for="fromDate">@lang('label.DATE') :<span class="text-danger"> *</span></label>
                        <div class="col-md-7">
                            <div class="input-group date datepicker2">
                                {!! Form::text('date', !empty($invoiceInfo->date) ? Helper::formatDate($invoiceInfo->date) : Request::get('date'), ['id'=> 'date', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="date">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>

                                <span class="text-danger">{{ $errors->first('date') }}</span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12 margin-top-10">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="vcenter text-center">@lang('label.SL_NO')</th>
                                    <th class="vcenter">@lang('label.PRODUCT_NAME')</th>
                                    <th class="vcenter text-center">@lang('label.UNIT_PRICE')</th>
                                    <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                    <th class="vcenter text-center">@lang('label.AMOUNT')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if (!empty($orderDetailInfo))
                                <?php
                                $sl = $subTotal = 0;
                                ?>
                                @foreach($orderDetailInfo as $order)
                                
                                <?php $subTotal += (!empty($order->total_price) ? $order->total_price : 0);?>
                                <tr>
                                    <td class="vcenter text-center">{{ ++$sl }}</td>
                                    <td class="vcenter">{!! !empty($order->name) ? $order->name : '' !!}</td>
                                    <td class="vcenter text-center">{!! !empty($order->unit_price) ? Helper::numberFormat2Digit($order->unit_price) : '0.00' !!}&nbsp;@lang('label.TK')</td>
                                    <td class="vcenter text-center">{!! !empty($order->quantity) ? $order->quantity : '' !!}</td>
                                    <td class="vcenter text-right">{!! !empty($order->total_price) ? Helper::numberFormat2Digit($order->total_price) : '0.00' !!}&nbsp;@lang('label.TK')</td>
                                </tr>
                                @endforeach
                                @endif

                                <tr>
                                    <td class="vcenter text-right bold" colspan="4">@lang('label.SUBTOTAL')</td>
                                    <td class="vcenter text-right bold">{!! !empty($subTotal) ? Helper::numberFormat2Digit($subTotal) : '0.00' !!}&nbsp;@lang('label.TK')</td>
                                    {!! Form::hidden('sub_total', $subTotal, ['id' => 'subTotal']) !!}
                                </tr>

                                <tr>
                                    <td class="vcenter text-right bold" colspan="4">@lang('label.VAT')</td>
                                    <td class="vcenter text-right bold">
                                        {!! !empty($order->vat) ? Helper::numberFormat2Digit($order->vat) : '0.00' !!}&nbsp;@lang('label.TK')
                                        {!! Form::hidden('vat', $order->vat, ['id' => 'vat']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="vcenter text-right bold" colspan="4">@lang('label.DELIVERY_CHARGE')</td>
                                    <td class="vcenter text-right bold">
                                        {!! !empty($order->delivery_charge) ? Helper::numberFormat2Digit($order->delivery_charge) : '0.00' !!}&nbsp;@lang('label.TK')
                                        {!! Form::hidden('delivery_charge', $order->delivery_charge, ['id' => 'deliveryCharge']) !!}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="vcenter text-right bold" colspan="4">@lang('label.NET_PAYABLE_AMOUNT')</td>
                                    <td class="vcenter text-right bold">
                                        <span class="net-payable">{!! !empty($order->paying_amount) ? Helper::numberFormat2Digit($order->paying_amount) : '0.00' !!}</span>&nbsp;@lang('label.TK')
                                    </td>
                                    {!! Form::hidden('net_payable', $order->paying_amount, ['id' => 'netPayable']) !!}
                                </tr>
                                <tr>
                                    <td class="vcenter text-right bold">@lang('label.IN_WORDS')</td>
                                    <td class="vcenter bold" colspan="4">
                                        <span class="net-payable uppercase italic">{!! !empty($order->paying_amount) ? Helper::numberToWord($order->paying_amount) : Helper::numberToWord(0.00) !!}</span>&nbsp;@lang('label.TK')
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>

                </div>

                <div class="col-md-10 margin-top-10">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="specialNote">@lang('label.SPECIAL_NOTE') :</label>
                        <div class="col-md-8">
                            {{ Form::textarea('special_note', !empty($invoiceInfo->special_note) ? $invoiceInfo->special_note : null, ['id'=> 'specialNote', 'class' => 'form-control','size' => '30x5','autocomplete' => 'off']) }}
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-circle green" href="#previewModal" type="button" data-toggle="modal" id="submitPreview">
                            <i class="fa fa-check"></i> @lang('label.SAVE_AND_PREVIEW')
                        </button>
                        <a href="{{ URL::to('/admin/processingOrder') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>

            </div>


            {!! Form::close() !!}
            <!-- End Filter -->

        </div>
    </div>
</div>
<!-- Modal start -->
<div class="modal fade" id="previewModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showPreviewModal">
        </div>
    </div>
</div>

<script>
    $(function () {
        $(document).on("keyup", "#vat", function () {
            var vat = $(this).val();
            var subTotal = $('#subTotal').val();
            var netPayable = Number(vat) + Number(subTotal);
            netPayable = parseFloat(netPayable).toFixed(2);
            $('span.net-payable').text(netPayable.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#netPayable').val(netPayable);
        });

        //preview submit form function
        $(document).on("click", "#submitPreview", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            // Serialize the form data
            var formData = new FormData($('#previewForm')[0]);
            $.ajax({
                url: "{{ URL::to('/admin/processingOrder/invoiceGenerate') }}",
                type: "POST",
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $("#showPreviewModal").html('');
                },
                success: function (res) {
                    $("#showPreviewModal").html(res.html);
                    $(".js-source-states").select2({dropdownParent: $('#showPreviewModal'), width: '100%'});
//                    $(".js-source-states").select2({});
                    $('.tooltips').tooltip({container: 'body'});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }

                    $("#showPreviewModal").html('');
                    App.unblockUI();
                }
            }); //ajax

        });
        //endof preview form


        //invoice save submit form function
        $(document).on("click", "#submitInvoiceSave", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            // Serialize the form data
            var formData = new FormData($('#previewForm')[0]);
            $.ajax({
                url: "{{ URL::to('/admin/processingOrder/storeInvoice') }}",
                type: "POST",
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $('#submitInvoiceSave').prop('disabled', true);
                },
                success: function (res) {
                    toastr.success(res.message, res.heading, options);
                    setTimeout(
                            window.location.replace('{{ URL::to("/admin/processingOrder")}}'
                                    ), 1000);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    $('#submitInvoiceSave').prop('disabled', false);
                    App.unblockUI();
                }
            }); //ajax

        });
        //endof invoce save form
    });
</script>
@stop
