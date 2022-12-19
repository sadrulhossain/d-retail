@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.PRODUCT_CHECK_IN')
            </div>
            <div class="actions">
                <a class="btn btn-default btn-sm create-new tooltips" title="@lang('label.CURRENT_SYSTEM_TIME')">
                    <b>   {{ $purchaseTime }} </b>
                </a>
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'submit_form')) !!}
            {{csrf_field()}}

            <div class="form-body">
                <div class="row">
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="challanNo">@lang('label.CHALLAN_NO'):<span class="text-danger"> *</span></label>
                            {!! Form::text('challan_no', null, ['id' => 'challanNo','class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="refNo">@lang('label.REFERENCE_NO'):</label>
                            {!! Form::text('ref_no',$referenceNo,['id' => 'refNo','class' => 'form-control','readonly']) !!}
                        </div>
                    </div>
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="checkInDate">@lang('label.CHECK_IN_DATE'):</label>
                            <div class="input-group date datepicker2">
                                {!! Form::text('checkin_date', !empty($checkinDate)? Helper::formatDate($checkinDate) : '' , ['id'=> 'checkInDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off', 'style' => 'min-width:150px']) !!}
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="checkInDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger">{{ $errors->first('check_in_date') }}</span>

                        </div>
                    </div>
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="shippingDate">@lang('label.SHIPPING_DATE'):</label>
                            <div class="input-group date datepicker2">
                                {!! Form::text('shipping_date', '' , ['id'=> 'shippingDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off', 'style' => 'min-width:150px']) !!}
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="shippingDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger">{{ $errors->first('shipping_date') }}</span>

                        </div>
                    </div>
                    <div class="form margin-top-10">
                        <div class="col-md-3">
                            <label class="control-label" for="productContainerId">@lang('label.CONTAINER_TYPE'):</label>
                            {!! Form::select('product_container_id', $productContainerList, null, ['class' => 'form-control js-source-states','id'=>'productContainerId']) !!}                            
                            <span class="text-danger">{{ $errors->first('product_container_id') }}</span>

                        </div>
                    </div>
                    <div class="form margin-top-10">
                        <div class="col-md-3">
                            <label class="control-label" for="workOrderRef">@lang('label.WORK_ORDER_REFERENCE'):</label>
                            {!! Form::select('work_order_ref_id', $workOrderReference, null, ['class' => 'form-control js-source-states','id'=>'workOrderRef']) !!}                            
                            <span class="text-danger">{{ $errors->first('work_order_ref') }}</span>

                        </div>
                    </div>

                </div>

                <div class="row margin-top-10 border-top-1-ash">

                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="sku">@lang('label.PRODUCT_SKU_CODE'):<span class="text-danger"> *</span></label>
                            {!! Form::select('sku', $productSkuArr, null, ['class' => 'form-control js-source-states', 'id' => 'sku']) !!}
                            <div id="displayProductHints"></div>
                        </div>
                    </div>

                    <div id='showProductBrand'>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="brand">@lang('label.PRODUCT'):</label>
                                {!! Form::text('product',null,['id' => 'product','class' => 'form-control','readonly']) !!}
                                {!! Form::hidden('product_id',null,['id' => 'productId']) !!}
                            </div>
                        </div>

                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="brand">@lang('label.BRAND'):</label>
                                {!! Form::text('brand',null,['id' => 'brand','class' => 'form-control','readonly']) !!}
                                {!! Form::hidden('brand_id',null,['id' => 'brandId']) !!}
                            </div>
                        </div>

                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="supplierId">@lang('label.SUPPLIER'):<span class="text-danger"> *</span></label>
                                {!! Form::select('supplier_id', $supplierArr, null, ['class' => 'form-control js-source-states', 'id' => 'supplierId']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div id='showSupplierAddress'>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="supplierAddress">@lang('label.SUPPLIER_ADDRESS'):</label>
                                {!! Form::text('address',null,['id' => 'address','class' => 'form-control','readonly']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="quantity">@lang('label.QUANTITY'):<span class="text-danger"> *</span></label>
                            {!! Form::text('quantity',null, ['id'=> 'quantity', 'class' => 'form-control  integer-only qty','autocomplete' => 'off']) !!}
                            <div id="displayQtyDetails"></div>
                        </div>
                    </div>

                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="rate">@lang('label.RATE'):<span class="text-danger"> *</span></label>
                            {!! Form::text('rate',null, ['id' => 'rate','class' => 'form-control  interger-decimal-only rate','autocomplete' => 'off']) !!}
                            <div id="displaySellingPrice">
                            </div>
                        </div>
                    </div>

                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="amount">@lang('label.AMOUNT'):<span class="text-danger"> *</span></label>
                            {!! Form::text('amount',null,['id' => 'amount','class' => 'form-control  interger-decimal-only','readonly']) !!}
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="form">
                        <div class="col-md-12 text-right margin-top-26">
                            <span class="btn green tooltips" type="button" id="addItem"  title="Add Item">
                                <i class="fa fa-plus text-white"></i>&nbsp;<span>@lang('label.ADD_ITEM')</span>
                            </span>
                            <span class="btn green display-none tooltips" data-count ="" data-sku-id ="" type="button" id="updateItem"  title="Update Item">
                                <i class="fa fa-plus text-white"></i>&nbsp;<span>@lang('label.UPDATE_ITEM')</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <p><b><u>@lang('label.PURCHASED_ITEM_LIST'):</u></b></p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="vcenter">@lang('label.PRODUCT')</th>
                                    <th class="vcenter">@lang('label.SKU')</th>
                                    <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                    <th class="text-right vcenter">@lang('label.RATE')</th>
                                    <th class="text-right vcenter">@lang('label.AMOUNT')</th>
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
                    <input type="hidden" id="total" value="">
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
<script type="text/javascript">

    $(document).ready(function () {



        $(document).on("change", '#sku', function () {
            var skuId = $("#sku").val();
            $('#address').val('');
            $('#quantity').val('');
            $('.rate').val('');
            $('#amount').val('');
            $("#displayProductHints").text('');
            $("#displayQtyDetails").text('');
            $("#displaySellingPrice").text('');

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
                url: "{{URL::to('admin/productCheckIn/getProductBrand')}}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sku_id: skuId,
                },
                success: function (res) {
                    $('#showProductBrand').html(res.html);
                    $('#rate').val(res.rate);
                    $('#displaySellingPrice').html(`<div id="pInform" class="text-success">Hints : Selling price ${res.selling_price} per pcs</div>`);
                    $('.js-source-states').select2();
                    $("#displayProductHints").html('<div id="pInform" class="text-success">Hints : ' + res.quantity + ' ' + res.unit_name + ' available</div>');

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



        $(document).on("change", '#supplierId', function () {
            var supplierId = $("#supplierId").val();
            //alert(productId);return false;
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            $.ajax({
                url: "{{URL::to('admin/productCheckIn/getSupplierAddress')}}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    supplier_id: supplierId,
                },
                success: function (res) {
                    $('#showSupplierAddress').html(res.html);
                    $('.js-source-states').select2();
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

//        $('#supplierId').trigger('change');


        var count = 1;
        $('#addItem').on("click", function () {
            $('.edit-show').attr("disabled", false);
            $('.rem-show').attr("disabled", false);

            var sku = $('#sku').val();
            var supplierId = $('#supplierId').val();
            var productId = $('#productId').val();
            var brandId = $('#brandId').val();
            var quantity1 = $('#quantity').val();
            var rate1 = $('#rate').val();
            var amount = $('#amount').val();
            var challanNo = $('#challanNo').val();
            var productContainerId = $('#productContainerId').val();
            var countNumber = count++;

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            if (productContainerId == '0') {
                document.getElementById("productContainerId").focus();
                toastr.error("Please select container type", "Error", options);
                return false;
            }
            if (sku == '0') {
                toastr.error("Please select  Product SKU", "Error", options);
                return false;
            }

            if (productId == '0') {
                toastr.error("Please select  Product", "Error", options);
                return false;
            }
            if (brandId == '0') {
                toastr.error("Please select Brand", "Error", options);
                return false;
            }
            if (supplierId == '0') {
                toastr.error("Please select  Supplier", "Error", options);
                return false;
            }


            if (quantity1 == '') {
                toastr.error("Please insert  quantity", "Error", options);
                return false;
            }

            if (rate1 == '') {
                toastr.error("Please insert  rate", "Error", options);
                return false;
            }

            if (challanNo == '') {
                toastr.error("Please insert  Challan No", "Error", options);
                return false;
            }


            var prevItemVal = $("#prevItem_" + sku + "_" + productId + "_" + brandId + "_" + supplierId).val();

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
                url: "{{ URL::to('admin/productCheckIn/purchaseNew')}}",
                type: "POST",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sku: sku,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                }
            }).done(function (result) {

                $("#hideNodata").css({"display": "none"});
                var rowCount = $('tbody#itemRows tr').length;

                row = '<tr id="rowId_' + sku + '_' + countNumber + '" class="item-list">\n\
                <td>\n\<input type="hidden" name="add_btn" value="1">\n\
                    <input type="hidden" id="editFlag_' + sku + '_' + countNumber + '"  value="">\n\
                    <input type="hidden" id="supplierId_' + sku + '_' + countNumber + '" name="supplier_id[' + sku + ']" value="' + supplierId + '">\n\
                    <input type="hidden" id="product_' + sku + '_' + countNumber + '" name="product[' + sku + ']"  value="' + productId + '">\n\
                    <input type="hidden" id="brand_' + sku + '_' + countNumber + '" name="brand[' + sku + ']"  value="' + brandId + '">\n\
                    <input type="hidden" id="quantity_' + sku + '_' + countNumber + '"  name="quantity[' + sku + ']"  value="' + parseFloat(quantity1).toFixed(0) + '">\n\
                    <input type="hidden" id="rate_' + sku + '_' + countNumber + '"  name="rate[' + sku + ']"  value="' + parseFloat(rate1).toFixed(2) + '">\n\
                    <input type="hidden" id="amount_' + sku + '_' + countNumber + '"  name="amount[' + sku + ']" class="item-amount"  value="' + amount + '">\n\
                   <input type="hidden" id="sku_' + sku + '_' + countNumber + '" name="sku[' + sku + ']"  value="' + result.productSku + '">\n\
                    <input type="hidden" id="prevItem_' + sku + '_' + productId + '_' + brandId + '_' + supplierId + '" name="prev_item[' + sku + '][' + productId + '][' + brandId + '][' + supplierId + ']"  value="1">\n\
                    ' + result.productName + '</td>\n\
                <td>' + result.productSku + '</td>\n\
                <td class="text-center">' + parseInt(quantity1) + ' ' + result.productUnit + '</td>\n\
                <td class="text-right">' + parseFloat(rate1).toFixed(2) + '</td>\n\
                <td class="text-right">' + amount + '</td>\n\
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
                $('#productId').focus();
                $('#submitButton').attr("disabled", false);

                App.unblockUI();
            });
        });
        $('#updateItem').click(function () {
            var updateSku = $(this).attr('data-sku-id');
            var updateCount = $(this).attr('data-count');

            $('#editBtn' + updateSku + '_' + updateCount).attr('disabled', false);
            $('#deleteBtn' + updateSku + '_' + updateCount).attr('disabled', false);

            var sku = $('#sku').val();
            var supplierId = $('#supplierId').val();
            var productId = $('#productId').val();
            var brandId = $('#brandId').val();
            var quantity1 = $('#quantity').val();
            var rate1 = $('#rate').val();
            var amount = $('#amount').val();
            var challanNo = $('#challanNo').val();
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

            if (productId == '0') {
                toastr.error("Please select  Product", "Error", options);
                return false;
            }
            if (brandId == '0') {
                toastr.error("Please select  Brand", "Error", options);
                return false;
            }
            if (supplierId == '0') {
                toastr.error("Please select  Supplier", "Error", options);
                return false;
            }


            if (quantity1 == '') {
                toastr.error("Please insert  quantity", "Error", options);
                return false;
            }

            if (rate1 == '') {
                toastr.error("Please insert  rate", "Error", options);
                return false;
            }

            if (challanNo == '') {
                toastr.error("Please insert  Challan No", "Error", options);
                return false;
            }

            var prevItemVal = $("#prevItem_" + sku + "_" + productId + "_" + brandId + "_" + supplierId).val();
            var prevItemlength = $("#prevItem_" + sku + "_" + productId + "_" + brandId + "_" + supplierId).length;
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
                $("#editRowId").remove();
            }



            $.ajax({
                url: "{{ URL::to('admin/productCheckIn/purchaseNew')}}",
                type: "POST",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sku: sku,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                }
            }).done(function (result) {

                $(".edit-sku").prop('disabled', false);
                $(".rem-sku").prop('disabled', false);

                $("#hideNodata").css({"display": "none"});
                var rowCount = $('tbody#itemRows tr').length;

                row = '<tr id="rowId_' + sku + '_' + countNumber + '" class="item-list">\n\
                <td>\n\<input type="hidden" name="add_btn" value="1">\n\
                    <input type="hidden" id="editFlag_' + sku + '_' + countNumber + '"  value="">\n\
                    <input type="hidden" id="supplierId_' + sku + '_' + countNumber + '" name="supplier_id[' + sku + ']" value="' + supplierId + '">\n\
                    <input type="hidden" id="product_' + sku + '_' + countNumber + '" name="product[' + sku + ']"  value="' + productId + '">\n\
                    <input type="hidden" id="brand_' + sku + '_' + countNumber + '" name="brand[' + sku + ']"  value="' + brandId + '">\n\
                    <input type="hidden" id="quantity_' + sku + '_' + countNumber + '"  name="quantity[' + sku + ']"  value="' + parseFloat(quantity1).toFixed(0) + '">\n\
                    <input type="hidden" id="rate_' + sku + '_' + countNumber + '"  name="rate[' + sku + ']"  value="' + parseFloat(rate1).toFixed(2) + '">\n\
                    <input type="hidden" id="amount_' + sku + '_' + countNumber + '"  name="amount[' + sku + ']" class="item-amount"  value="' + amount + '">\n\
                   <input type="hidden" id="sku_' + sku + '_' + countNumber + '" name="sku[' + sku + ']"  value="' + result.productSku + '">\n\
                    <input type="hidden" id="prevItem_' + sku + '_' + productId + '_' + brandId + '_' + supplierId + '" name="prev_item[' + sku + '][' + productId + '][' + brandId + '][' + supplierId + ']"  value="1">\n\
                    ' + result.productName + '</td>\n\
                <td>' + result.productSku + '</td>\n\
                <td class="text-center">' + parseInt(quantity1) + ' ' + result.productUnit + '</td>\n\
                <td class="text-right">' + parseFloat(rate1).toFixed(2) + '</td>\n\
                <td class="text-right">' + amount + '</td>\n\
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
                $('#productId').focus();
                $('#submitButton').attr("disabled", false);
                $('#addItem').removeClass('display-none');
                $('#updateItem').attr('data-count', '');
                $('#updateItem').attr('data-sku-id', '');
                $('#updateItem').addClass('display-none');


                App.unblockUI();
            });
        });


        $(document).on('keyup', '#quantity,#rate', function () {
            getAmount();
        });

        function getAmount() {
            var quantity1 = $('#quantity').val();
            var rate1 = $('#rate').val();

            var unitPricex = getParsedAmount(rate1);
            var quantity = getParsedAmount(quantity1);

            var totalPricex = (unitPricex) * (quantity);
            var amount = parseFloat(totalPricex).toFixed(2);

            $('#amount').val(amount);
        }

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

//        edit item

        $(document).on('click', '.edit-sku', function () {
            var sku = $(this).attr('sku');
            var countNumber = $(this).attr('countNumber');

            $('#addItem').addClass('display-none');
            $('#updateItem').removeClass('display-none');
            $('#updateItem').attr('data-count', countNumber);
            $('#updateItem').attr('data-sku-id', sku);

            var quantity1 = $('#quantity_' + sku + '_' + countNumber).val();
            var rate1 = $('#rate_' + sku + '_' + countNumber).val();
            var amount1 = $('#amount_' + sku + '_' + countNumber).val();
            var supplierId = $('#supplierId_' + sku + '_' + countNumber).val();
            var address = $('#address_' + sku + '_' + countNumber).val();
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

            $.ajax({
                url: "{{URL::to('admin/productCheckIn/getProductBrand')}}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sku_id: sku,
                },
                success: function (res) {
                    $('#showProductBrand').html(res.html);
                    $('#supplierId').val(supplierId).select2();
                    $("#displayProductHints").html('<div id="pInform" class="text-success">Hints : ' + parseFloat(res.quantity).toFixed(2) + '</span>&nbsp;&nbsp;' + res.unit_name + ' available</div>');
                    $("#displaySellingPrice").html('<div id="pInform" class="text-success">Selling Price : ' + res.selling_price + ' &#2547;</div>');

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

            $.ajax({
                url: "{{URL::to('admin/productCheckIn/getSupplierAddress')}}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    supplier_id: supplierId,
                },
                success: function (res) {
                    $('#showSupplierAddress').html(res.html);
                    $('.js-source-states').select2();
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

            $('#quantity').val(quantity);
            $('#rate').val(rate);
            $('#amount').val(amount);
            $('#sku').val(sku).select2();
            $("#editRowId").val(sku + '_' + countNumber);

            $('#editBtn' + sku + '_' + countNumber).attr('disabled', true);
            $('#deleteBtn' + sku + '_' + countNumber).attr('disabled', true);


            if (editRowId != '') {
                $('#editBtn' + editRowId).prop('disabled', true);
                $('#deleteBtn' + editRowId).prop('disabled', true);
            }
        });

        /*
         *
         * Parse a Number to Float. If "NaN", convert to "0.00"
         * @param {Varchar} InitVal
         * @returns {Float Number}
         *
         */
        function getParsedAmount(InitVal) {
            var OutVal = parseFloat(InitVal);
            if (isNaN(OutVal)) {
                OutVal = 0.00;
            }
            return OutVal;
        }



        //save-data for checkin
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
                        url: "{{URL::to('/admin/productCheckIn/purchaseProduct')}}",
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
                            toastr.success(res.data, 'Product CheckedIn Successfully', options);
                            setTimeout(() => {
                                window.location.replace('{{URL::to("admin/productCheckInList")}}');
                            },2000);
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

    //Remove Item
//    function removeItem(sku, countNumber) {
//        alert("Hello!");
//        $('#rowId_' + sku + '_' + countNumber).remove();
//
//        var rowCount = $('tbody#itemRows tr').length;
//        if (rowCount == 2) {
//            $('tr#netTotalRow').remove();
//            $('#hideNodata').show();
//        }
//
//        var netTotal = 0;
//        $(".item-amount").each(function () {
//            netTotal += parseFloat($(this).val());
//        });
//
//        $('#netTotal').text(netTotal);
//        $('#submitButton').attr("disabled", false);
//
//    }

    //edit item
//    function editProduct(editId, countNumber) {
//        var quantity1 = $('#quantity_' + editId + '_' + countNumber).val();
//        var rate1 = $('#rate_' + editId + '_' + countNumber).val();
//        var amount1 = $('#amount_' + editId + '_' + countNumber).val();
//        var lotNumber = $('#lotNumber_' + editId + '_' + countNumber).val();
//        var sku = $('#sku_' + editId + '_' + countNumber).val();
//        var supplierId = $('#supplierId_' + editId + '_' + countNumber).val();
//        var address = $('#address_' + editId + '_' + countNumber).val();
//        var editRowId = $('#editRowId').val();
//        var quantity = parseFloat(quantity1).toFixed(2);
//        var rate = parseFloat(rate1).toFixed(2);
//        var amount = parseFloat(amount1).toFixed(2);
//
//        $('#quantity').val(quantity);
//        $('#rate').val(rate);
//        $('#amount').val(amount);
//        $('#lotNumber').val(lotNumber);
//        $('#sku').val(sku).select2();
//        $('#supplierId').val(supplierId).select2();
//        $('#address').val(address).select2();
//        $("#editRowId").val(editId + '_' + countNumber);
//
//        $('#editBtn' + editId + '_' + countNumber).attr('disabled', true);
//        $('#deleteBtn' + editId + '_' + countNumber).attr('disabled', true);
//        //alert(editRowId);return false;
//
//        if (editRowId != '') {
//            $('#editBtn' + editRowId).prop('disabled', true);
//            $('#deleteBtn' + editRowId).prop('disabled', true);
//        }
//    }

    $(document).on("blur", '#quantity,.rate', function () {
        var quantity = $('#quantity').val();
        var rate = $(".rate").val();
        var sku = $('#sku').val();
        if (sku == '0') {
            swal({
                title: '@lang("label.PLEASE_SELECT_PRODUCT")',
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Ok',
                closeOnConfirm: true,
            }, function (isConfirm) {
                $("#quantity").val('');
                $(".rate").val('');
                $('#pInform').text('');
                return false;
            });
        } else if (quantity == 0 || rate == 0) {
//            $("#quantity").val('');
//            $(".rate").val('');
            return false;
        } else {

        }
    });

    $(document).on('keyup', '.qty', function () {
        var quantity = $('#quantity').val();
        if (quantity != '') {
            validateNumberInput(quantity); // Check Qty 6 digits after decimal point
            var totalQty = parseFloat(quantity);
            var detailsText = unitConvert(totalQty);
            $('#displayQtyDetails').html('<div id="pInform" class="text-danger">' + detailsText + '</div>');
        } else {
            $('#displayQtyDetails').html('');
        }
    });



    $(document).on('keyup', '.rate', function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        var rate = $('#rate').val();
        var skuId = $('#sku').val();

        if (skuId == '0') {
            swal({
                title: '@lang("label.PLEASE_SELECT_PRODUCT")',
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Ok',
                closeOnConfirm: true,
            }, function (isConfirm) {
                $(".rate").val('');
                return false;
            });
        }


        if (rate != '' && skuId != '0') {
            $.ajax({
                url: "{{URL::to('admin/productCheckIn/getProductBrand')}}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sku_id: skuId
                },
                success: function (res) {
                    $("#displaySellingPrice").html('<div id="pInform" class="text-success">Selling Price : ' + res.selling_price + ' &#2547;</div>');
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
        } else {

            $('#displaySellingPrice').html('');
        }
    });


    function validateNumberInput(totalQty) {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        //Find out the position of "." at Quantity
        var totalQtyPointPos = totalQty.toString().indexOf(".");
        if (totalQtyPointPos != -1) {
            var totalQtyArr = totalQty.toString().split(".");
            var kgAmnt = totalQtyArr[0];
            var gmAmntStr = totalQtyArr[1];
            var gmAmntStrLen = gmAmntStr.length;
            if (gmAmntStrLen > 6) {
                var allowedQtyStr = kgAmnt + "." + gmAmntStr.substring(0, 6);
                $('#quantity').val(allowedQtyStr);
                toastr.error('Error', "@lang('label.MAX_DIGITS')", options);
                return false;
            }//EOF - if length
        }//EOF - if -1
    }//EOF - function

    function unitConvert(totalQty) {
        var totalQtyArr = totalQty.toString().split(".");
        var kgAmnt = totalQtyArr[0];
        var gmAmntStr = totalQtyArr[1];
        var kgFinalAmntStr = '';
        if (kgAmnt > 0) {
            kgFinalAmntStr = parseInt(kgAmnt) + " @lang('label.UNIT_PIS')";
        }

        //var lengthOfGm = gmAmntStr.length;//length of amount after decimal point
        //var zeroPadLength = (6 - (lengthOfGm)); //6 is fixed as 1KG is equal to 1000000 mg (0.000001 KG => 6 digit after decimal point)
        var pad = '000000';
        var totalAmntStr = (gmAmntStr + pad).substring(0, pad.length);
        var gmStr = totalAmntStr.substring(0, 3);//Subtract gram aamount
        var gmFinalAmntStr = "";
        if (gmStr > 0) {
            gmFinalAmntStr = parseInt(gmStr) + " @lang('label.GM')";
        }
        var miliGmStr = totalAmntStr.substring(3, 6);//Subtract miligram aamount
        var mgFinalAmntStr = "";
        if (miliGmStr > 0) {
            mgFinalAmntStr = parseInt(miliGmStr) + " @lang('label.MG')";
        }

        var text = kgFinalAmntStr + " " + gmFinalAmntStr + " " + mgFinalAmntStr;
        return text;
    }

</script>
@stop

