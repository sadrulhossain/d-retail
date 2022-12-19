<!--transfer-->
@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.EDIT_NEW_ORDER')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form','class' => 'form-horizontal', 'id' => 'submitForm')) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="warehouse">@lang('label.WAREHOUSE'):</label>
                            <div class="col-md-8">
                                <div class="control-label pull-left"> <strong> {{$orderDetails->warehouse_name ?? ''}} </strong></div>
                                {!! Form::hidden('warehouse_id', $orderDetails->warehouse_id ?? '', ['id' => 'warehouseId']) !!}
                            </div>
                        </div>
                    </div>
                    <?php $fullName = Auth::user()->first_name . " " . Auth::user()->last_name ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="sr">@lang('label.SR'):</label>
                            <div class="col-md-8">
                                <div class="control-label pull-left"> <strong> {!! !empty($fullName) ? $fullName : '' !!} </strong></div>
                                {!! Form::hidden('sr_id', Auth::user()->id ?? '', ['id' => 'srId']) !!}
                                {!! Form::hidden('order_id', $orderDetails->order_id ?? '', ['id' => 'orderId']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="creationDate">@lang('label.DATE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-9">
                                <div class="input-group date datepicker2">
                                    {!! Form::text('creation_date', !empty($orderDetails->created_at)?Helper::formatDate($orderDetails->created_at):null, ['id'=> 'creationDate', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
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
                                {!! Form::select('retailer_id', $retailerList, !empty($orderDetails->retailer_id) ? $orderDetails->retailer_id : null, ['class' => 'form-control js-source-states', 'id' => 'retailer']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="paymentCollection">@lang('label.PAYMENT') :</label>
                            <div class="md-radio-inline">
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
                    </div>
                </div>

            </div>
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <p><b><u>@lang('label.PRODUCT_LIST'):</u></b></p>
                        <table class="table table-bordered table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="vcenter" width="15%">
                                        <?php
                                        ?>
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
                            <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                            <th class="vcenter text-center">@lang('label.PRICE')</th>
                            <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php $sl = 0; @endphp

                                @foreach($targetArr as $target)
                                <?php
                                ?>
                                <tr>
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="vcenter">
                                        <div class="md-checkbox has-success tooltips" >
                                            {!! Form::checkbox('sku['.$target->id.']',$target->id, 1, ['id' => 'sku_'.$target->id, 'data-id'=>$target->id, 'class'=> 'md-check sku']) !!}
                                            <label for="sku_{!! $target->id !!}">
                                                <span class="inc"></span>
                                                <span class="check mark-caheck tooltips" title=""></span>
                                                <span class="box mark-caheck tooltips" title=""></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="vcenter">{!! $target->sku!!}</td>
                                    <td class="text-center vcenter width-100">
                                        {!! Form::text('product_quantity['.$target->id.']', !empty($target->quantity) ? $target->quantity : null, ['id'=> 'productQuantity_'.$target->id, 'data-id' => $target->id, 'class' => 'form-control width-inherit text-right integer-decimal-only product-quantity']) !!}
                                        {!! Form::hidden('product_id['.$target->id.']', $target->product_id ?? '', ['id' => 'productId']) !!}
                                    </td>
                                    <td class="text-center vcenter width-150">
                                        <div class="input-group bootstrap-touchspin width-inherit">
                                            {!! Form::text('product_price['.$target->id.']', $target->unit_price, ['id'=> 'productPrice_'.$target->id, 'data-id' => $target->id, 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per product-price', 'readonly']) !!}
                                            <span class="input-group-addon bootstrap-touchspin-postfix bold">&#2547;</span>
                                        </div>
                                    </td>
                                    <td class="text-center vcenter width-150">
                                        <div class="input-group bootstrap-touchspin width-inherit">
                                            {!! Form::text('product_total_price['.$target->id.']', !empty($target->total_price) ? $target->total_price : null, ['id'=> 'productTotalPrice_'.$target->id, 'data-id' => $target->id, 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per product-total-price', 'readonly']) !!}
                                            <span class="input-group-addon bootstrap-touchspin-postfix bold">&#2547;</span>
                                        </div>
                                    </td>
                                </tr>

                                @endforeach
                                <tr>
                                    <td class="text-right vcenter width-150" colspan="5">
                                        <strong>@lang('label.GRAND_TOTAL')</strong>
                                    </td>
                                    <td class="text-right vcenter width-150">
                                        <div class="input-group bootstrap-touchspin width-inherit">
                                            {!! Form::text('grand_total_price', !empty($target->grand_total) ? $target->grand_total : null, ['id'=> 'grandTotalPrice', 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per grand-total-price', 'readonly']) !!}
                                            <span class="input-group-addon bootstrap-touchspin-postfix bold">&#2547;</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--<input type="hidden" id="editRowId" value="">-->
                    <!--<input type="hidden" id="total" value="">-->
                </div>
            </div>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green button-submit" id="subBtn"  type="button">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
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

        //load total price
        $(document).on('keyup', '.product-quantity', function () {
            var dataId = $(this).attr('data-id');
            var productPrice = $("#productPrice_" + dataId).val();
            var quantity = $('#productQuantity_' + dataId).val();
            if (quantity == '') {
                quantity = 0;
            }
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
        });

        $(".sku").on("click", function () {
            var dataId = $(this).attr('data-id');
            if (this.checked) {
                $("#productQuantity_" + dataId).removeAttr('disabled');
            } else {
                $("#productQuantity_" + dataId).attr('disabled', 'disabled');
            }

            if ($('.sku:checked').length == $('.sku').length) {
                $('.all-sku').prop("checked", true);
            } else {
                $('.all-sku').prop("checked", false);
            }
        });

        $(".all-sku").click(function () {
            if (this.checked) {
                $(".product-quantity").removeAttr('disabled');
            } else {
                $(".product-quantity").attr('disabled', 'disabled');
            }
            if ($(this).prop('checked')) {
                $('.sku').prop("checked", true);
            } else {
                $('.sku').prop("checked", false);
            }
        });
        if ($('.sku:checked').length == $('.sku').length) {
            $('.all-sku').prop("checked", true);
        } else {
            $('.all-sku').prop("checked", false);
        }

        $(document).on('click', '.button-submit', function (e) {
            e.preventDefault();
            var form_data = new FormData($('#submitForm')[0]);
//            var orderId = $('#orderId').val();
//            alert(orderId);
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null
            };
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
                        url: "{{URL::to('admin/pendingOrder/updateOrder')}}",
                        type: "POST",
                        datatype: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function (res) {
                            toastr.success('@lang("label.NEW_ORDER_UPDATED_SUCCESSFULLY")', res, options);
                            window.location.replace('{{URL::to("admin/pendingOrder")}}');
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

    });
</script>
@stop

