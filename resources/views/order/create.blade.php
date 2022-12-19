<!--transfer-->
@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.CREATE_NEW_ORDER')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form','class' => 'form-horizontal', 'id' => 'submitForm')) !!}

            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="warehouse">@lang('label.WAREHOUSE'):</label>
                            <div class="col-md-8">
                                <div class="control-label pull-left"> <strong> {{$warehouse->warehouse_name ?? ''}} </strong></div>
                                {!! Form::hidden('warehouse_id', $warehouse->warehouse_id ?? '', ['id' => 'warehouseId']) !!}
                            </div>
                        </div>
                    </div>
                    <?php $fullName = Auth::user()->first_name . " " . Auth::user()->last_name ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="sr">@lang('label.SR'):</label>
                            <div class="col-md-8">
                                <div class="control-label pull-left"> <strong> {!! !empty($fullName) ? $fullName : '' !!} </strong></div>
                                {!! Form::hidden('sr_id', Auth::user()->id ?? '', ['id' => 'srId']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="creationDate">@lang('label.DATE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-9">
                                <div class="input-group date datepicker2">
                                    {!! Form::text('creation_date', !empty($creationDate)?Helper::formatDate($creationDate):null, ['id'=> 'creationDate', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="creationDate">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-danger">{{ $errors->first('creation_date') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-left control-label col-md-4">@lang('label.RETAILER') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('retailer', $retailerList, null, ['class' => 'form-control js-source-states', 'id' => 'retailer']) !!}
                            </div>
                        </div>
                    </div>
                    <!--                    <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label col-md-4" for="paymentCollection">@lang('label.PAYMENT_COLLECTION') :</label>
                                                <div class="md-radio-inline col-md-8">
                                                    <div class="md-radio">
                                                        <input type="radio" name="payment_collection" id="paymentCollection" value="0" checked>
                                                        <label for="paymentCollection">
                                                            <span class="inc"></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span>
                                                        </label>
                                                        <span class="bold">@lang('label.CASH')</span>
                                                    </div>
                                                    <div class="md-radio">
                                                        <input type="radio" name="payment_collection" id="paymentCollection2" value="1">
                                                        <label for="paymentCollection2">
                                                            <span class="inc"></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span>
                                                        </label>
                                                        <span class="bold">@lang('label.CREDIT')</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->
                </div>


            </div>
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <p><b><u>@lang('label.PRODUCT_IMFORMATION'):</u></b></p>
                        <table class="table table-bordered table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="vcenter" width="15%">

                                        <div class="md-checkbox has-success">
                                            {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'all-sku md-check']) !!}
                                            <label for="checkAll">
                                                <span class="inc"></span>
                                                <span class="check mark-caheck"></span>
                                                <span class="box mark-caheck"></span>
                                            </label>&nbsp;&nbsp;
                                            <span class="bold">@lang('label.CHECK_ALL')</span>
                                        </div>
                                    </th>
                                    <th class="vcenter">@lang('label.PRODUCT_SKU')</th>
                                    <th class="vcenter text-center">@lang('label.CUSTOMER_DEMAND')</th>
                                    <th class="vcenter text-center">@lang('label.STOCK')</th>
                                    <th class="vcenter text-center">@lang('label.AVAILABLE_QTY')</th>
                                    <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                    <th class="vcenter text-center">@lang('label.PRICE')</th>
                                    <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 0;  @endphp
                                @if(!empty($targetArr))
                                @foreach($targetArr as $target)
                                <?php
                                $freezeStockArr = Common::getFreezeStock($target->warehouse_id);
                                ?>
                                <!--{{json_encode($target)}}-->
                                <!--{{json_encode($warehouse)}}-->
                                <tr>
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="vcenter">
                                        <div class="md-checkbox has-success tooltips" >
                                            {!! Form::checkbox('sku['.$target->id.']',$target->id, 0, ['id' => 'sku_'.$target->id, 'data-id'=>$target->id, 'class'=> 'md-check sku']) !!}
                                            <label for="sku_{!! $target->id !!}">
                                                <span class="inc"></span>
                                                <span class="check mark-caheck tooltips" title=""></span>
                                                <span class="box mark-caheck tooltips" title=""></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="vcenter">{!! $target->sku!!}</td>
                                    <td class="text-center vcenter width-80">
                                        {!! Form::text('customer_demand['.$target->id.']', null, ['id'=> 'customerDemand_'.$target->id, 'data-id' => $target->id, 'class' => 'form-control width-inherit text-right integer-decimal-only customer-demand','disabled']) !!}
                                    </td>
                                    <td class="text-center vcenter width-80">
                                        <span class="text-primary">{{!empty($target->available_quantity) ? number_format($target->available_quantity, 0) : 0}}</span>
                                        {!! Form::hidden('available_qty['.$target->id.']', $target->available_quantity ?? '', ['id' => 'availableQty_'.$target->id,'data-id'=> $target->id]) !!}
                                    </td>
                                    <td class="text-center vcenter width-80">
                                        <span class="text-primary">
                                            {{ isset($freezeStockArr[$target['sku_id']]) 
                                            ? (int)$target->available_quantity - $freezeStockArr[$target->id] 
                                            : (int)$target->available_quantity
                                            }}

                                        </span>

                                    </td>
                                    <td class="text-center vcenter width-80">
                                        {!! Form::text('product_quantity['.$target->id.']', null, ['id'=> 'productQuantity_'.$target->id, 'data-id' => $target->id, 'class' => 'form-control width-inherit text-right integer-decimal-only product-quantity','disabled']) !!}
                                        {!! Form::hidden('product_id['.$target->id.']', $target->product_id ?? '', ['id' => 'productId']) !!}
                                    </td>

                                    <td class="text-center vcenter width-150">
                                        <div class="input-group bootstrap-touchspin width-inherit">
                                            {!! Form::text('product_price['.$target->id.']', $target->selling_price, ['id'=> 'productPrice_'.$target->id, 'data-id' => $target->id, 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per product-price', 'readonly']) !!}
                                            <span class="input-group-addon bootstrap-touchspin-postfix bold">&nbsp;@lang('label.TK')</span>
                                        </div>
                                    </td>
                                    <td class="text-center vcenter width-150">
                                        <div class="input-group bootstrap-touchspin width-inherit">
                                            {!! Form::text('product_total_price['.$target->id.']', null, ['id'=> 'productTotalPrice_'.$target->id, 'data-id' => $target->id, 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per product-total-price', 'readonly']) !!}
                                            <span class="input-group-addon bootstrap-touchspin-postfix bold">&nbsp;@lang('label.TK')</span>
                                        </div>
                                    </td>


                                </tr>
                                @endforeach
                                @else
                                <tr class="info">
                                    <td class="vcenter bold" colspan="9">@lang('label.NO_PRODUCT_FOUND')</td>
                                    <!--<td class="text-right vcenter bold">{!! !empty($totalAmount) ? Helper::numberFormat2Digit($totalAmount) : '0.00' !!}&nbsp;</td>-->
                                </tr>
                                @endif
                                @if(!empty($targetArr))
                                <tr>
                                    <td class="text-right vcenter width-150" colspan="8">
                                        <strong>@lang('label.GRAND_TOTAL')</strong>
                                    </td>
                                    <td class="text-right vcenter width-150">
                                        <div class="input-group bootstrap-touchspin width-inherit">
                                            {!! Form::text('grand_total_price', null, ['id'=> 'grandTotalPrice', 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per grand-total-price', 'readonly']) !!}
                                            <span class="input-group-addon bootstrap-touchspin-postfix bold">&nbsp;@lang('label.TK')</span>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="row">
                            <label class="form-label-stripped col-md-1" for="note">@lang('label.NOTE_'): </label>
                            <div class="form-group">
                                <div class="col-md-10">
                                    {!! Form::textarea('note', null, ['id'=> 'note', 'class' => 'form-control','rows'=>3,'placeholder'=>__('label.ORDER_NOTE'),'maxlength' => 255]) !!}
                                    <span class="text-danger">{{ $errors->first('note') }}</span>
                                </div>
                            </div>
                            <span class=" col-md-11 text-right">

                            </span>
                        </div>
                    </div>
                    <!--<input type="hidden" id="editRowId" value="">-->
                    <!--<input type="hidden" id="total" value="">-->
                </div>
            </div>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button {!!empty($targetArr) ? "disabled": ""!!} class="btn btn-circle green button-submit" id="subBtn"  type="button">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/admin/order'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <!-- END BORDERED TABLE PORTLET-->
</div>

<script>

    $(document).ready(function () {


        $("#subBtn").prop('disabled', true);

        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null
        };

        if ($('.sku:checked').length == $('.sku').length) {
            $('.all-sku').prop("checked", true);
        } else {
            $('.all-sku').prop("checked", false);
        }
        //load total price
        $(document).on('keyup input change', '.product-quantity', function () {
            var dataId = $(this).attr('data-id');
            var productPrice = $("#productPrice_" + dataId).val();
            var productTotalPriceVal = $("#productTotalPrice_" + dataId).val();
            var quantity = parseFloat($('#productQuantity_' + dataId).val());
            var availableQty = parseFloat($('#availableQty_' + dataId).val());
            var customerDemand = parseFloat($('#customerDemand_' + dataId).val());
            var grandTotalPriceVal = parseFloat($('#grandTotalPrice').val());

            if (quantity == '') {
                quantity = 0;
                $("#subBtn").prop('disabled', true);
            }

            if (isNaN(grandTotalPriceVal) || (grandTotalPriceVal == '')) {
                grandTotalPriceVal = 0;
                $("#subBtn").prop('disabled', true);
            }

            if (productTotalPriceVal == '' || !productTotalPriceVal) {
                productTotalPriceVal = 0;
                $("#subBtn").prop('disabled', true);
            }
            $("#subBtn").prop('disabled', false);



            if (quantity > customerDemand) {
                swal({
                    title: `Customer Demand: ${customerDemand} is less Product Quantity: ${quantity}`,
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Close",
                    closeOnConfirm: true,
                    closeOnCancel: true,
                }, function (isConfirmed) {
                    if (isConfirmed) {
                        $('#productQuantity_' + dataId).val('');
                    }
                });
            } else if (quantity > availableQty) {
                swal({
                    title: `Product Quantity: ${quantity} is greater than Available quantity: ${availableQty} `,
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Close",
                    closeOnConfirm: true,
                    closeOnCancel: true,
                }, function (isConfirmed) {
                    if (isConfirmed) {
                        $('#productQuantity_' + dataId).val('');
                    }
                });
            } else {
                var totalPrice = productPrice * quantity;
                var grandTotalPrice = 0;

                $('#productTotalPrice_' + dataId).val(parseFloat(totalPrice).toFixed(2));
                $(".product-total-price").each(function () {
                    var price = $(this).val();
                    if (price == '') {
                        price = 0;
                    }
                    grandTotalPrice = Number(grandTotalPrice) + Number(price);

                });

                $('#grandTotalPrice').val(parseFloat(grandTotalPrice).toFixed(2));
                return false;
            }
        });

        $(document).on("click", ".sku", function () {
//           
            var dataId = $(this).attr('data-id');
            if (this.checked) {
                $("#productQuantity_" + dataId).removeAttr('disabled');
                $("#customerDemand_" + dataId).removeAttr('disabled');
            } else {
                $("#productQuantity_" + dataId).attr('disabled', 'disabled');
                $("#customerDemand_" + dataId).attr('disabled', 'disabled');
            }

            if ($('.sku:checked').length == $('.sku').length) {
                $('.all-sku').prop("checked", true);
            } else {
                $('.all-sku').prop("checked", false);
            }
        });

        $(document).on('click', ".all-sku", function () {
            if (this.checked) {
                $(".product-quantity").removeAttr('disabled');
                $(".customer-demand").removeAttr('disabled');
            } else {
                $(".product-quantity").attr('disabled', 'disabled');
                $(".customer-demand").attr('disabled', 'disabled');
            }
            if ($(this).prop('checked')) {
                $('.sku').prop("checked", true);
            } else {
                $('.sku').prop("checked", false);
            }
        });

        $(document).on('click', '.button-submit', function (e) {
            e.preventDefault();
            let retailer = $("#retailer").val();
            let skuLen = $('.sku:checked').length;
            if (!retailer || retailer == "0") {
                toastr.error("Retailer/Distributor is required.", "Error", options);
                return false;
            }
            if (skuLen <= 0) {

                toastr.error("Please Select at least one SKU.", "Error", options);
                return false;
            }

            var form_data = new FormData($('#submitForm')[0]);

            swal({
                title: 'Are you sure?',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{URL::to('admin/order/saveNewOrder')}}",
                        type: "POST",
                        datatype: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function (res) {
                            toastr.success('@lang("label.NEW_ORDER_SAVED_SUCCESSFULLY")', '@lang("label.SUCCESS")', options);
//                            $("#eventId").trigger('change');
                            setTimeout(() => {
                                window.location.replace('{{URL::to("admin/order")}}');
                            }, 1000);
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
                            App.unblockUI();
                        }
                    });
                }

            });

        });

        // Retailer / Distributor wise price 
        $(document).on("change", "#retailer", function () {
            var retailer_id = $(this).val();
            if (retailer_id == "0") {
                $("#subBtn").prop('disabled', true);
                return false;
            }

//           

            $.ajax({
                url: "{{URL::to('admin/order/retailerWisePrice')}}",
                type: "POST",
                datatype: 'json',
                data: {retailer_id: retailer_id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
//                    $(".confirm-order").prop('disabled', true);
                    App.blockUI({boxed: true});
                },
                success: function (res) {
//                    $("#dataTable").empty();
//                    $("#subBtn").prop('disabled', false);
                    if (res.table) {
                        $("#dataTable").html(res.table);
                    }
                    App.unblockUI();

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
                    App.unblockUI();
                }
            });
        });
        // Retailer / Distributor wise price 

    });
</script>
@stop

