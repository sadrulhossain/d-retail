@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-th-list"></i>@lang('label.PROCUREMENT')
            </div>
        </div>

        <div class="portlet-body">
            {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'submit_form')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="reference">@lang('label.REFERENCE'):<span class="text-danger"> *</span></label>
                            {!! Form::text('reference',!empty($referenceNo)? $referenceNo : null, ['id'=> 'reference', 'class' => 'form-control  integer-only ','autocomplete' => 'off']) !!}
                            <div id="displayQtyDetails"></div>
                        </div>
                    </div>
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="reqDate">@lang('label.REQ_DATE'):</label>
                            <div class="input-group date datepicker2">
                                {!! Form::text('req_date', !empty($reqDate)? Helper::formatDate($reqDate) : '' , ['id'=> 'reqDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off', 'style' => 'min-width:150px']) !!}
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="reqDate">
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
                <div class="row margin-top-10 border-top-1-ash">
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="sku">@lang('label.PRODUCT_SKU'):<span class="text-danger"> *</span></label>
                            {!! Form::select('sku', $productSkuArr, null, ['class' => 'form-control js-source-states', 'id' => 'sku']) !!}
                            <div id="displayQtyDetails"></div>
                        </div>
                    </div>
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="unitPrice">@lang('label.UNIT_PRICE'):<span class="text-danger"> *</span></label>
                            <div class="input-group bootstrap-touchspin ">
                                {!! Form::text('unit_price',null, ['id'=> 'unitPrice', 'class' => 'form-control  integer-only','autocomplete' => 'off', 'readonly']) !!}
                                <span class="input-group-addon bootstrap-touchspin-prefix bold">@lang('label.TK')</span>
                            </div>
                        </div>
                    </div>
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="quantity">@lang('label.QUANTITY'):<span class="text-danger"> *</span></label>
                            <div class="input-group bootstrap-touchspin width-140">
                                {!! Form::text('quantity',null, ['id'=> 'quantity', 'class' => 'form-control  integer-only','autocomplete' => 'off']) !!}
                                <span class="input-group-addon bootstrap-touchspin-prefix bold">pcs</span>
                            </div>
                        </div>
                    </div>
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="totalPrice">@lang('label.TOTAL_PRICE'):<span class="text-danger"> *</span></label>
                            <div class="input-group bootstrap-touchspin">
                                {!! Form::text('total_price',null, ['id'=> 'totalPrice', 'class' => 'form-control  integer-only','autocomplete' => 'off', 'readonly']) !!}
                                <span class="input-group-addon bootstrap-touchspin-prefix bold">@lang('label.TK')</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form">
                        <div class="col-md-3 margin-top-25">
                            <span type="button" id="addItem" class="btn btn-md green btn-inline filter-submit margin-bottom-20">
                                <i class="fa fa-plus text-white"></i> @lang('label.ADD_ITEM')
                            </span>
                            <span type="button" id="updateItem" class="btn btn-md green btn-inline filter-submit margin-bottom-20 display-none" data-count ="" data-sku-id ="" title="@lang('label.UPDATE_ITEM')">
                                <i class="fa fa-plus text-white"></i> @lang('label.UPDATE_ITEM')
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p><b><u>@lang('label.PROCUREMENT_LIST'):</u></b></p>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="vcenter">@lang('label.PRODUCT')</th>
                                        <th class="vcenter">@lang('label.SKU')</th>
                                        <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                        <th class="text-right vcenter">@lang('label.UNIT_PRICE')</th>
                                        <th class="text-right vcenter">@lang('label.TOTAL_PRICE')</th>
                                        <th class="text-center vcenter">@lang('label.ACTION')</th>
                                    </tr>
                                </thead>
                                <tbody id="itemRows">
                                    <tr id="hideNodata">
                                        <td colspan="7">@lang('label.NO_DATA_SELECT_YET')</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" id="editRowId" value="">
                        <input type="hidden" id="total" value="" name="total">
                    </div>
                </div>

                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-8">
                            <button class="btn btn-circle green button-submit" id="submitButton" type="button" disabled>
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

    <!--view stock and demand modal-->
    <div class="modal fade" id="modalViewStockDemand" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div id="showViewStockDemand">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {

        $(document).on("change", '#sku', function () {
            var skuId = $("#sku").val();

            $("#quantity").val('');
            $("#totalPrice").val('');
            $('#unitPrice').val("");
            if (skuId == '0') {
                return false;
            }

            //alert(productId);return false;
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            $.ajax({
                url: "{{URL::to('admin/procurement/getProcurementUnitPrice')}}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sku_id: skuId,
                },
                success: function (res) {
                   $('#unitPrice').val(res.unitPrice);

                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        toastr.error(errors, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                }
            });
        });
        $(document).on('keyup', '#quantity,#unitPrice', function () {
            getAmount();
        });

        function getAmount() {
            var quantity1 = $('#quantity').val();
            var rate1 = $('#unitPrice').val();


            var unitPricex = getParsedAmount(rate1);
            var quantity = getParsedAmount(quantity1);

            var totalPricex = (unitPricex) * (quantity);
            var amount = parseFloat(totalPricex).toFixed(2);


            $('#totalPrice').val(amount);
        }

        var count = 1;
        $('#addItem').on("click", function () {

            var sku = $('#sku').val();
            var quantity = $('#quantity').val();
            var rate1 = $('#unitPrice').val();

            var amount = $('#totalPrice').val();
            var countNumber = count++;



            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };


            if (sku == '0') {
                toastr.error("Please select  Product SKU", "Error", options);
                return false;
            }
            if (quantity == '') {
                toastr.error("Please insert  quantity", "Error", options);
                return false;
            }


            var prevItemVal = $("#prevItem_" + sku).val();

            if (typeof prevItemVal !== 'undefined') {
                toastr.error("This item has already been added", "Error", options);
                return false;
            }

            //when i edit one row then delete previous row
            var editRow = $("#editRowId").val();
            if (editRow != '') {
                $('#rowId_' + editRow).remove();
            }

            $.ajax({
                url: "{{ URL::to('admin/procurement/getProcurement')}}",
                type: "POST",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sku: sku,
                    rate: rate1,
                    quantity: quantity,
                    totalPrice: amount,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                }
            }).done(function (result) {

                $("#hideNodata").css({"display": "none"});
                var rowCount = $('tbody#itemRows tr').length;

                var row = '<tr id="rowId_' + sku + '_' + countNumber + '" class="item-list">\n\
                <td>\n\<input type="hidden" name="add_btn" value="1">\n\
                    <input type="hidden" id="editFlag_' + sku + '_' + countNumber + '"  value="">\n\
                    <input type="hidden" id="quantity_' + sku + '_' + countNumber + '"  name="quantity[' + sku + ']"  value="' + parseFloat(quantity).toFixed(0) + '">\n\
                    <input type="hidden" id="unitPrice_' + sku + '_' + countNumber + '"  name="rate[' + sku + ']"  value="' + parseFloat(rate1).toFixed(2) + '">\n\
                    <input type="hidden" id="totalPrice_' + sku + '_' + countNumber + '"  name="amount[' + sku + ']" class="item-amount"  value="' + parseFloat(amount).toFixed(2) + '">\n\
                   <input type="hidden" id="sku_' + sku + '_' + countNumber + '" name="sku[' + sku + ']"  value="' + result.productSku + '">\n\
                    <input type="hidden" id="prevItem_' + sku + '" name="prev_item[' + sku + ']"  value="1">\n\
                    ' + result.productName + '</td>\n\
                <td class="text-center">' + result.productSku + '</td>\n\
                <td class="text-right">' + parseFloat(quantity).toFixed(2) + '</td>\n\
                <td class="text-right">' + result.productUnit + '</td>\n\
                <td class="text-right">' + result.totalPrice + '</td>\n\
                <td class="text-center">\n\
                    <button type="button" sku="' + sku + '" countNumber="' + countNumber + '" class="btn btn-xs btn-primary tooltips vcenter edit-sku" id="editBtn' + sku + '_' + countNumber + '" title="Edit Product" ><i class="fa fa-edit text-white"></i></button>\n\
                    <button type="button" sku="' + sku + '" countNumber="' + countNumber + '" class="btn btn-xs btn-danger tooltips vcenter rem-sku" id="deleteBtn' + sku + '_' + countNumber + '"  title="Remove Item"><i class="fa fa-trash text-white"></i></button>\n\
                </td></tr>';

                // get total amount

                if (rowCount == 1) {
                    row += '<tr id="netTotalRow">\n\
                    <td colspan="4" class="text-right">Total</td>\n\
                    <td id="netTotal" class="text-right interger-decimal-only"></td>\n\
                    <td></td>\n\
                    </tr>';
                    $('#itemRows').append(row);
                } else {
                    $('#itemRows tr:last').before(row);
                }


                var netTotal = 0;
                $(".item-amount").each(function () {
                    netTotal += parseFloat($(this).val());
                });

                $('#netTotal').text(netTotal.toFixed(2));
                $('#total').val(netTotal);

                $('#submitButton').attr("disabled", false);
                $('#addItem').removeClass('display-none');

                App.unblockUI();
            });
        });
        function getParsedAmount(InitVal) {
            var OutVal = parseFloat(InitVal);
            if (isNaN(OutVal)) {
                OutVal = 0.00;
            }
            return OutVal;
        }
        ;

        $(document).on('click', '.edit-sku', function () {
            var sku = $(this).attr('sku');
            var countNumber = $(this).attr('countNumber');

            $('#addItem').addClass('display-none');
            $('#updateItem').removeClass('display-none');
            $('#updateItem').attr('data-count', countNumber);
            $('#updateItem').attr('data-sku-id', sku);

            var quantity1 = $('#quantity_' + sku + '_' + countNumber).val();
            var rate1 = $('#unitPrice_' + sku + '_' + countNumber).val();
            var amount1 = $('#totalPrice_' + sku + '_' + countNumber).val();
            var editRowId = $('#editRowId').val();

            var quantity = parseInt(quantity1);
            var rate = parseFloat(rate1).toFixed(2);
            var amount = parseFloat(amount1).toFixed(2);


            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            $('#quantity').val(quantity);
            $('#unitPrice').val(rate);
            $('#totalPrice').val(amount);

            $('#sku').val(sku).select2();
            $("#editRowId").val(sku + '_' + countNumber);

            $('#editBtn' + sku + '_' + countNumber).attr('disabled', true);
            $('#deleteBtn' + sku + '_' + countNumber).attr('disabled', true);


            if (editRowId != '') {
                $('#editBtn' + editRowId).prop('disabled', true);
                $('#deleteBtn' + editRowId).prop('disabled', true);
            }

        });
        $('#updateItem').click(function () {
            var updateSku = $(this).attr('data-sku-id');
            var updateCount = $(this).attr('data-count');


            $('#editBtn' + updateSku + '_' + updateCount).attr('disabled', false);
            $('#deleteBtn' + updateSku + '_' + updateCount).attr('disabled', false);

            var sku = $('#sku').val();
            var quantity = $('#quantity').val();
            var rate1 = $('#unitPrice').val();
            var productId = $('#productId').val();
            var amount = $('#totalPrice').val();
            var countNumber = updateCount;

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            if (sku == '0') {
                toastr.error("Please select  Product SKU", "Error", options);
                return false;
            }
            if (quantity == '') {
                toastr.error("Please insert  quantity", "Error", options);
                return false;
            }



            var prevItemVal = $("#prevItem_" + sku).val();
            var prevItemlength = $("#prevItem_" + sku).length;
            if (typeof prevItemVal !== 'undefined') {
                if ((prevItemlength >= 1 && updateSku != sku) || (prevItemlength > 1 && updateSku == sku)) {
                    toastr.error("This item has already been added", "Error", options);
                    $('.edit-show').attr("disabled", false);
                    $('.rem-show').attr("disabled", false);

                    return false;
                }
            }

            //when i edit one row then delete previous row
            var editRow = $("#editRowId").val();


            if (editRow != '') {
                $('#rowId_' + editRow).remove();

            }
            $("#editRowId").val('');



            $.ajax({
                url: "{{ URL::to('admin/procurement/getProcurement')}}",
                type: "POST",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sku: sku,
                    rate: rate1,
                    quantity: quantity,
                    totalPrice: amount,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                }
            }).done(function (result) {

                $(".edit-sku").prop('disabled', false);
                $(".rem-sku").prop('disabled', false);

                $("#hideNodata").css({"display": "none"});
                var rowCount = $('tbody#itemRows tr').length;

                var row = '<tr id="rowId_' + sku + '_' + countNumber + '" class="item-list">\n\
                <td>\n\<input type="hidden" name="add_btn" value="1">\n\
                    <input type="hidden" id="editFlag_' + sku + '_' + countNumber + '"  value="">\n\
                    <input type="hidden" id="quantity_' + sku + '_' + countNumber + '"  name="quantity[' + sku + ']"  value="' + parseFloat(quantity).toFixed(0) + '">\n\
                    <input type="hidden" id="unitPrice_' + sku + '_' + countNumber + '"  name="rate[' + sku + ']"  value="' + parseFloat(rate1).toFixed(2) + '">\n\
                    <input type="hidden" id="totalPrice_' + sku + '_' + countNumber + '"  name="amount[' + sku + ']" class="item-amount"  value="' + parseFloat(amount).toFixed(2) + '">\n\
                   <input type="hidden" id="sku_' + sku + '_' + countNumber + '" name="sku[' + sku + ']"  value="' + result.productSku + '">\n\
                    <input type="hidden" id="prevItem_' + sku + '" name="prev_item[' + sku + ']"  value="1">\n\
                    ' + result.productName + '</td>\n\
                <td class="text-center">' + result.productSku + '</td>\n\
                <td class="text-right">' + parseFloat(quantity).toFixed(2) + '</td>\n\
                <td class="text-right">' + result.productUnit + '</td>\n\
                <td class="text-right">' + result.totalPrice + '</td>\n\
                <td class="text-center">\n\
                    <button type="button" sku="' + sku + '" countNumber="' + countNumber + '" class="btn btn-xs btn-primary tooltips vcenter edit-sku" id="editBtn' + sku + '_' + countNumber + '" title="Edit Product" ><i class="fa fa-edit text-white"></i></button>\n\
                    <button type="button" sku="' + sku + '" countNumber="' + countNumber + '" class="btn btn-xs btn-danger tooltips vcenter rem-sku" id="deleteBtn' + sku + '_' + countNumber + '"  title="Remove Item"><i class="fa fa-trash text-white"></i></button>\n\
                </td></tr>';


                // get total amount

                if (rowCount == 1) {
                    row += '<tr id="netTotalRow">\n\
                    <td colspan="4" class="text-right">Total</td>\n\
                    <td id="netTotal" class="text-right interger-decimal-only"></td>\n\
                    <td></td>\n\
                    </tr>';
                    $('#itemRows').append(row);
                } else {
                    $('#itemRows tr:last').before(row);
                }

                var netTotal = 0;
                $(".item-amount").each(function () {
                    netTotal += parseFloat($(this).val());
                });

                $('#netTotal').text(netTotal.toFixed(2));
                $('#submitButton').attr("disabled", false);
                $('#addItem').removeClass('display-none');
                $('#updateItem').attr('data-count', '');
                $('#updateItem').attr('data-sku-id', '');
                $('#updateItem').addClass('display-none');

                App.unblockUI();

            });
        });

        $(document).on('click', '.rem-sku', function () {
            var sku = $(this).attr('sku');
            var countNumber = $(this).attr('countNumber');


            $('#rowId_' + sku + '_' + countNumber).remove();

            var rowCount = $('tbody#itemRows tr').length;
            if (rowCount == 2) {
                $('tr#netTotalRow').remove();
                $('#hideNodata').show();
                $('#submitButton').prop("disabled", true);
            }

            var netTotal = 0;
            $(".item-amount").each(function () {
                netTotal += parseFloat($(this).val());
            });

            $('#netTotal').text(netTotal);

        });

        $(document).on("click", ".button-submit", function (e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

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
                        url: "{{URL::to('admin/procurement/store')}}",
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
                            toastr.success(res.data, 'Procurement has been generated successfully', options);
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
