@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.ORDER_INFORMATION')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal','files' => true,'id'=>'setDeliveryForm')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="col-md-12">
                    <div class="row">
                        {!! Form::hidden('order_id', $id) !!}
                        {!! Form::hidden('retailer_id', $orderNo->retailer_id) !!}
                        <div class="form-group">
                            <label class="col-md-4 text-align-end">@lang('label.ORDER_NO') :</label>
                            <div class="col-md-4">
                                <strong>{{ $orderNo->order_no ?? '' }}</strong>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="blNo">@lang('label.BL_NO') :<span class="text-danger"> *</span></label>
                            <div class="col-md-4">
                                {{ Form::text('bl_no', '', ['id'=> 'blNo', 'class' => 'form-control','size' => '30x2','autocomplete' => 'off']) }}
                                <span class="text-danger">{{ $errors->first('bl_no') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="blDate">@lang('label.BL_DATE') : <span class="text-danger"> *</span></label>
                            <div class="col-md-4">
                                <div class="input-group date datepicker2">
                                    {!! Form::text('bl_date', Helper::formatDate($deliveryDate) , ['id'=> 'blDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="date">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="expressTrackingNo">@lang('label.EXPRESS_TRACKING_NO') :</label>
                            <div class="col-md-4">
                                {{ Form::text('express_tracking_no', '', ['id'=> 'expressTrackingNo', 'class' => 'form-control','size' => '30x2','autocomplete' => 'off']) }}
                                <span class="text-danger">{{ $errors->first('express_tracking_no') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="containerNo">@lang('label.CONTAINER_NO') :</label>
                            <div class="col-md-4">
                                {{ Form::text('container_no', '', ['id'=> 'containerNo', 'class' => 'form-control','size' => '30x2','autocomplete' => 'off']) }}
                                <span class="text-danger">{{ $errors->first('container_no') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <!--<p><b><u>@lang('label.PRODUCT_IMFORMATION'):</u></b></p>-->
                        <table class="table table-bordered table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="vcenter">@lang('label.PRODUCT_SKU')</th>
                                    <th class="vcenter">@lang('label.CUSTOMER_DEMAND')</th>
                                    <th class="vcenter">@lang('label.AVAILABLE_QTY')</th>
                                    <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                    <th class="vcenter text-center">@lang('label.PRICE')</th>
                                    <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 0; @endphp

                                @foreach($orderDetailInfo as $target)
                                <?php
                                $grandTotal = !empty($target->grand_total) ? $target->grand_total : 0.00;
                                ?>
                                <tr>
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="vcenter">{{ $target->sku }}</td>
                                    <td class="vcenter">{{ $target->customer_demand }}</td>
                                    <td class="vcenter">{{ $target->available_quantity }}</td>
                                    <td class="text-center vcenter width-100">
                                        {!! Form::hidden('delivery['.$target->id.'][product_id]', $target->product_id ?? '', ['id' => 'productId']) !!}
                                        {!! Form::hidden('delivery['.$target->id.'][sku_id]', $target->sku_id ?? '', ['id' => 'skuId']) !!}
                                        {!! Form::hidden('delivery['.$target->id.'][warehouse_id]', $target->warehouse_id ?? '', ['id' => 'warehouseId']) !!}
                                        {!! Form::text('delivery['.$target->id.'][quantity]', $target->quantity, ['id'=> 'productQuantity_'.$target->id, 'class' => 'form-control width-inherit text-right integer-decimal-only product-quantity','readonly']) !!}
                                    </td>
                                    <td class="text-center vcenter width-150">
                                        <div class="input-group bootstrap-touchspin width-inherit">
                                            {!! Form::text('delivery['.$target->id.'][unit_price]', $target->unit_price, ['id'=> 'productPrice_'.$target->id, 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per product-price', 'readonly']) !!}
                                            <span class="input-group-addon bootstrap-touchspin-postfix bold">&#2547;</span>
                                        </div>
                                    </td>
                                    <td class="text-center vcenter width-150">
                                        <div class="input-group bootstrap-touchspin width-inherit">
                                            {!! Form::text('delivery['.$target->id.'][total_price]', $target->total_price, ['id'=> 'productTotalPrice_'.$target->id, 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per product-total-price', 'readonly']) !!}
                                            <span class="input-group-addon bootstrap-touchspin-postfix bold">&#2547;</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right vcenter width-150" colspan="6">
                                        <strong>@lang('label.GRAND_TOTAL')</strong>
                                    </td>
                                    <td class="text-right vcenter width-150">
                                        <div class="input-group bootstrap-touchspin width-inherit">
                                            {!! Form::text('grand_total_price', $grandTotal, ['id'=> 'grandTotalPrice', 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per grand-total-price', 'readonly']) !!}
                                            <span class="input-group-addon bootstrap-touchspin-postfix bold">&#2547;</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green button-submit" id="subBtn"  type="button">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{URL::to('admin/processingOrder')}}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {

        $(document).on("change", "#courierId", function () {
            var courierId = $("#courierId").val();

            $("#branch").prop("checked", false);
            $("#headOffice").prop("checked", false);

            $("#officeDetail").html('');
            if (courierId != 0) {
                $("#branchSelect").show(500);
            } else {
                $("#branchSelect").hide(500);
            }
        });

        $(document).on("click", "#headOffice", function () {
            var courierId = $("#courierId").val();
            $("#branch").prop("checked", false);

            if ($(this).prop('checked')) {
                $.ajax({
                    url: "{{ URL::to('/admin/processingOrder/getHeadOffice')}}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        courier_id: courierId
                    },
                    beforeSend: function () {
                        App.blockUI({boxed: true});
                        $("#officeDetail").html('');
                    },
                    success: function (res) {
                        $("#officeDetail").html(res.html);
                        App.unblockUI();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                        App.unblockUI();
                    }
                });//ajax
            } else {
                $("#officeDetail").html('');
            }

        });
        $(document).on("click", "#branch", function () {
            var courierId = $("#courierId").val();
            $("#headOffice").prop("checked", false);

            if ($(this).prop('checked')) {
                $.ajax({
                    url: "{{ URL::to('/admin/processingOrder/getBranch')}}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        courier_id: courierId
                    },
                    beforeSend: function () {
                        App.blockUI({boxed: true});
                        $("#officeDetail").html('');
                    },
                    success: function (res) {
                        $("#officeDetail").html(res.html);
                        $(".js-source-states").select2();
                        App.unblockUI();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                        App.unblockUI();
                    }
                });//ajax
            } else {
                $("#officeDetail").html('');
            }
        });

        $(document).on("change", "#branchId", function () {
            var courierId = $("#courierId").val();
            var branchId = $("#branchId").val();

            if (branchId != 0) {
                $.ajax({
                    url: "{{ URL::to('/admin/processingOrder/getBranchDetail')}}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        courier_id: courierId,
                        branch_id: branchId
                    },
                    beforeSend: function () {
                        App.blockUI({boxed: true});
                        $("#branchDetails").html('');
                    },
                    success: function (res) {
                        $("#branchDetails").html(res.html);
                        App.unblockUI();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                        App.unblockUI();
                    }
                });//ajax
            } else {
                $("#branchDetails").html('');
            }
        });


        $(document).on("click", ".button-submit", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            // Serialize the form data
            var formData = new FormData($('#setDeliveryForm')[0]);

            $.ajax({
                url: "{{URL::to('admin/processingOrder/saveSetDelivery')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $(".button-submit").prop('disabled', true);
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    toastr.success(res, '@lang("label.SET_DELIVERY_SUCCESSFULLY")', options);
                    setTimeout(
                            window.location.replace('{{ route("processingOrder.index")}}'
                                    ), 3000);
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
                    $(".button-submit").prop('disabled', false);
                    App.unblockUI();
                }
            });

        });


    });

</script>
@stop
