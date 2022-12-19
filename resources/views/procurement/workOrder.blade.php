@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-th-list"></i>@lang('label.WORK_ORDER')
            </div>
        </div>

        <div class="portlet-body">

            {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'submit_form')) !!}
            {{csrf_field()}}

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="reference">@lang('label.REFERENCE'):</label>
                            <div class="col-md-8">
                                {!! Form::text('reference',$reference,['id' => 'refNo','class' => 'form-control','readonly']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="supplier">@lang('label.SUPPLIER'):<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                {!! Form::select('supplier',  $supplierArr, Request::get('supplier'), ['class' => 'form-control js-source-states','id'=>'supplier']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="issueDate">@lang('label.ISSUE_DATE') :<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <div class="input-group date datepicker2">
                                    {!! Form::text('issue_date',!empty($issueDate)? Helper::formatDate($issueDate) : '' ,['id'=> 'issueDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!}
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="issueDate">
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
                </div>
                <div class="row margin-top-20">
                    <div class="col-md-12">
                        <div class="table-responsive webkit-scrollbar">
                            <table class="table table-bordered table-hover" id="dataTable">
                                <thead>
                                    <tr>
                                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                        <th class="vcenter text-center width-120">
                                            <div class="md-checkbox has-success tooltips" title="@lang('label.SELECT_ALL')">
                                                {!! Form::checkbox('check_all',1,false,['id' => 'checkedAll','class'=> 'md-check']) !!}
                                                <label for="checkedAll">
                                                    <span></span>
                                                    <span class="check mark-caheck"></span>
                                                    <span class="box mark-caheck"></span>
                                                </label>
                                                <span class="bold">@lang('label.CHECK_ALL')</span>
                                            </div>
                                        </th>

                                        <th class="vcenter">@lang('label.SKU')</th>
                                        <th class="text-center vcenter">@lang('label.PROCUREMENT_QUANTITY')</th>
                                        <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                        <th class="text-center vcenter">@lang('label.UNIT_PRICE')</th>
                                        <th class="text-center vcenter">@lang('label.TOTAL_PRICE')</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!$skuList->isEmpty())
                                    <?php
                                    $sl = $grandTotalPrice = 0;
                                    ?>
                                    @foreach($skuList as $sku)

                                    <?php
                                    $checked = '';
                                    $disabled = 'disabled';
                                    $class = 'sku-check';
                                    $readonly = 'readonly';
                                    $grandTotalPrice += (!empty($procurementArr[$sku->sku_id]['unit_price']) ? $procurementArr[$sku->sku_id]['unit_price'] : 0.00) * (!empty($procurementArr[$sku->sku_id]['quantity']) ? $procurementArr[$sku->sku_id]['quantity'] : 0.00);
                                    ?>
                                    <tr>
                                        <td class="text-center vcenter">{{ ++$sl }}</td>
                                        <td class="vcenter text-center">
                                            <div class="md-checkbox has-success tooltips" title="<?php ?>">
                                                {!! Form::checkbox('sku['.$sku->sku_id.']', $sku->sku_id,$checked,['id' => $sku->sku_id, 'data-id' => $sku->sku_id, 'class'=> 'md-check '. $class ]) !!}
                                                <label for="{{ $sku->sku_id }}">
                                                    <span class="inc"></span>
                                                    <span class="check mark-caheck"></span>
                                                    <span class="box mark-caheck"></span>

                                                </label>
                                            </div>
                                        </td>

                                        {!! Form::hidden('sku_name['.$sku->sku_id.']', $sku->sku ?? '') !!}
                                        {!! Form::hidden('procurement_master_id', $id ?? '') !!}
                                        <td class="vcenter">{{ $sku->sku }}</td>
                                        <td class="text-right vcenter width-150 qty-{{$sku->sku_id}}">{!! !empty($procurementArr[$sku->sku_id]['quantity']) ? Helper::numberFormat2Digit($procurementArr[$sku->sku_id]['quantity']) : '0.00' !!}</td>
                                        <td class="vcenter width-150">
                                            <div class="input-group bootstrap-touchspin width-14">
                                                {!! Form::text('quantity['.$sku->sku_id.']', null, ['data-id'=>$sku->sku_id  ,'id'=> 'quantity_'.$sku->sku_id, 'class' => 'form-control integer-decimal-only text-input-width-100-per text-right quantity sku sku-'.$sku->sku_id, 'autocomplete' => 'off', $disabled])!!}
                                            </div>
                                        </td>
                                        <td class="vcenter width-150">
                                            <div class="input-group bootstrap-touchspin width-140">

                                                {!! Form::text('unit_price['.$sku->sku_id.']', !empty($procurementArr[$sku->sku_id]['unit_price']) ? $procurementArr[$sku->sku_id]['unit_price'] : '0.00', ['data-id'=>$sku->sku_id  ,'id'=> 'unitPrice_'.$sku->sku_id, 'class' => 'form-control integer-decimal-only text-input-width-100-per text-right unit-price sku sku-'.$sku->sku_id, 'autocomplete' => 'off','readonly',  $disabled])!!}
                                                <span class="input-group-addon bootstrap-touchspin-prefix bold">@lang('label.TK')</span>
                                            </div>
                                        </td>
                                        <td class="vcenter width-150">
                                            <div class="input-group bootstrap-touchspin width-140">
                                                {!! Form::text('total_price['.$sku->sku_id.']', null, ['data-id'=>$sku->sku_id  ,'id'=> 'totalPrice_'.$sku->sku_id, 'class' => 'form-control integer-decimal-only text-input-width-100-per text-right total-price sku sku-'.$sku->sku_id, 'autocomplete' => 'off','readonly',  $disabled])!!}
                                                <span class="input-group-addon bootstrap-touchspin-prefix bold">@lang('label.TK')</span>
                                            </div>
                                        </td>

                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td class=" vcenter text-right" colspan="6">@lang('label.GRAND_TOTAL')</td>
                                        <td class=" vcenter width-150">
                                            <div class="input-group bootstrap-touchspin width-140">
                                                {!! Form::text('grand_total', null,['id'=> 'grandTotal', 'class' => 'form-control integer-decimal-only text-input-width-100-per text-right grand-total', 'autocomplete' => 'off',$readonly])!!}
                                                <span class="input-group-addon bootstrap-touchspin-prefix bold">@lang('label.TK')</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td colspan="8" class="vcenter">@lang('label.NO_WORKORDER_FOUND')</td>
                                    </tr>
                                    @endif
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green button-submit" id="submitButton" type="button">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>

        <!-- END BORDERED TABLE PORTLET-->
    </div>


</div>

<script type="text/javascript">
    $(function () {
<?php
if (!$skuList->isEmpty()) {
    ?>
            $("#checkedAll").change(function () {
                if (this.checked) {
                    $('.sku-check').prop('checked', true);
                    $('.sku').prop('disabled', false);
                } else {
                    $('.sku-check').prop('checked', false);
                    $('.sku').prop('disabled', true);
                }
            });
            $('.sku-check').change(function () {
                var id = $(this).attr("data-id");
                if (this.checked) {
                    $('.sku-' + id).prop('disabled', false);
                } else {
                    $('.sku-' + id).prop('disabled', true);
                }

                allCheck();

            });

            //load total price
            $(document).on('keyup click input paste', '.quantity', function () {
                var dataId = $(this).attr('data-id');
                $(this).val($(`.qty-${dataId}`).text());
                var unitPrice = $("#unitPrice_" + dataId).val();
                var totalPriceVal = $("#totalPrice_" + dataId).val();
                var quantity = $('#quantity_' + dataId).val();
                var grandTotalPriceVal = $('#grandTotal').val();

                if (quantity == '') {
                    quantity = 0;
                }

                if (isNaN(grandTotalPriceVal) || (grandTotalPriceVal == '')) {
                    grandTotalPriceVal = 0;
                }

                if (totalPriceVal == '' || isNaN(totalPriceVal)) {
                    totalPriceVal = 0;
                }

                var totalPrice = unitPrice * quantity;
                var grandTotalPrice = 0;

                $('#totalPrice_' + dataId).val(parseFloat(totalPrice).toFixed(2));
                $(".total-price").each(function () {
                    var price = $(this).val();
                    if (price == '') {
                        price = 0;
                    }
                    grandTotalPrice = Number(grandTotalPrice) + Number(price);

                });

                $('#grandTotal').val(parseFloat(grandTotalPrice).toFixed(2));
                return false;
            });
    <?php
}
?>

        function allCheck() {
            if ($('.sku-check:checked').length == $('.sku-check').length) {
                $('#checkedAll')[0].checked = true; //change 'check all' checked status to true
            } else {
                $('#checkedAll')[0].checked = false;
            }
        }
        ;


        $(document).on("click", ".button-submit", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            if(!$("#supplier").val() || $("#supplier").val() == "0" ){
                toastr.error( 'Please! Select supplier.',"Validation Error", options);
                return false;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            // Serialize the form data
            var formData = new FormData($('#submit_form')[0]);
            swal({
                title: "Are you sure to submit ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Approve It",
                closeOnConfirm: true,
                closeOnCancel: true,
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{URL::to('admin/procurementList/workOrderInsert')}}",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function () {
                            $('.button-submit').prop('disabled', true);
                            App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            toastr.success(res.data, 'Work order has been generated successfully', options);
                            setTimeout(() => {
                                window.location.replace('{{URL::to("admin/procurementList")}}');
                            }, 2000);
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
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            $('.button-submit').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });

    });

</script>

@stop
