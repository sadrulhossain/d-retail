<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i><?php echo app('translator')->get('label.PRODUCT_TRANSFER'); ?>
            </div>
            <div class="actions">
                <a class="btn btn-default btn-sm create-new tooltips" title="<?php echo app('translator')->get('label.CURRENT_SYSTEM_TIME'); ?>">
                    <b>   <?php echo e($transferTime); ?> </b>
                </a>
            </div>
        </div>
        <div class="portlet-body form">
            <?php echo Form::open(array('group' => 'form','class' => 'form-horizontal','id' => 'submit_form')); ?>

            <?php echo e(csrf_field()); ?>

            <div class="form-body">
                <div class="row">
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="voucherNo"><?php echo app('translator')->get('label.REFERENCE_NO'); ?>:</label>
                            <?php echo Form::text('reference_no',$referenceNo,['id' => 'referenceNo','class' => 'form-control','readonly']); ?>

                        </div>
                    </div>

                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="transferFrom"><?php echo app('translator')->get('label.TRANSFER_FROM'); ?>:<span class="text-danger"> *</span></label>
                            <?php if(in_array(Auth::user()->group_id, [1, 11])): ?>

                            <?php echo Form::text('tr_warehouse','Central Warehouse',['id' => 'transferFrom','class' => 'form-control','readonly']); ?>

                            <?php echo Form::hidden('tr_warehouse_id', 0); ?>


                            <?php elseif(Auth::user()->group_id == 12): ?>
                            <?php echo Form::text('tr_warehouse',$warehouse->wh ?? '',['id' => 'transferFrom','class' => 'form-control','readonly']); ?>

                            <?php echo Form::hidden('tr_warehouse_id', $warehouse->wh_id ?? 0); ?>

                            <?php endif; ?>
                            <!--<div id="displayProductHints"></div>-->
                        </div>
                    </div>
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="warehouse"><?php echo app('translator')->get('label.TRANSFER_TO'); ?>:<span class="text-danger"> *</span></label>
                            <?php echo Form::select('warehouse_id', $warehouseList, null, ['class' => 'form-control js-source-states', 'id' => 'warehouse']); ?>

                            <!--<div id="displayProductHints"></div>-->
                        </div>
                    </div>

                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="remarks"><?php echo app('translator')->get('label.REMARKS'); ?>:</label>
                            <?php echo Form::textarea('remarks',null,['id' => 'remarks','class' => 'form-control','cols'=> '50','rows' => '2']); ?>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form">
                            <label class="control-label"><?php echo app('translator')->get('label.DATE'); ?> :</label>
                            <?php echo Form::text('transfer_date_text',Helper::formatDate($transferDate) , ['id'=> 'transferDateText', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly']); ?>

                            <?php echo Form::hidden('transfer_date',$transferDate , ['id'=> 'transferDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly']); ?>

                        </div>
                    </div>


                </div>

                <div class="row">
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="sku"><?php echo app('translator')->get('label.PRODUCT_SKU_CODE'); ?>:<span class="text-danger"> *</span></label>
                            <?php echo Form::select('sku', $productSkuArr, null, ['class' => 'form-control js-source-states', 'id' => 'sku']); ?>

                            <div id="displayProductHints"></div>
                        </div>
                    </div>

                    <div id='showProductBrand'>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="product"><?php echo app('translator')->get('label.PRODUCT'); ?>:</label>
                                <?php echo Form::text('product', null,['id' => 'product','class' => 'form-control','readonly']); ?>

                                <?php echo Form::hidden('product_id', null,['id' => 'productId','class' => 'form-control']); ?>

                            </div>
                        </div>
                    </div>
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="quantity"><?php echo app('translator')->get('label.QUANTITY'); ?>:<span class="text-danger"> *</span></label>
                            <?php echo Form::text('quantity',null, ['id'=> 'quantity', 'class' => 'form-control  interger-decimal-only qty','autocomplete' => 'off']); ?>

                            <div id="displayQtyDetails"></div>
                        </div>
                    </div>




                    <div class="form">
                        <div class="col-md-3 margin-top-25">
                            <label class="control-label">&nbsp;</label>
                            <span class="btn green tooltips" type="button" id="addItem"  title="Add Item">
                                <i class="fa fa-plus text-white"></i>&nbsp;<span><?php echo app('translator')->get('label.ADD_ITEM'); ?></span>
                            </span>
                            <span class="btn green display-none tooltips" data-count ="" data-sku-id ="" type="button" id="updateItem"  title="Update Item">
                                <i class="fa fa-plus text-white"></i>&nbsp;<span><?php echo app('translator')->get('label.UPDATE_ITEM'); ?></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <p><b><u><?php echo app('translator')->get('label.TRANSFERRED_PRODUCT_LIST'); ?>:</u></b></p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="vcenter"><?php echo app('translator')->get('label.PRODUCT'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.SKU'); ?></th>
                                    <th class="text-center vcenter"><?php echo app('translator')->get('label.QUANTITY'); ?></th>
                                    <th class="text-center vcenter"><?php echo app('translator')->get('label.UNIT'); ?></th>
                                    <th class="text-center vcenter"><?php echo app('translator')->get('label.ACTION'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="itemRows">
                                <tr id="netTotalTr">
                                </tr>
                                <tr id="hideNodata">
                                    <td colspan="7"><?php echo app('translator')->get('label.NO_DATA_SELECT_YET'); ?></td>
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
                        <button class="btn btn-circle green button-submit" id="subBtn"  type="button" disabled>
                            <i class="fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
                        </button>
                        <a href="" class="btn btn-circle btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

        </div>
    </div>
    <!-- END BORDERED TABLE PORTLET-->
</div>

<script>
    $(document).ready(function () {

        $(document).on("keyup", '#quantity', function () {
            var quantity = $(this).val();

            var avQuantity = $('#avQuantity').text();
//            console.log(avQuantity);
            if (avQuantity == '') {
                swal({
                    title: '<?php echo app('translator')->get("label.PLEASE_SELECT_PRODUCT_BEFORE_QUANTITY"); ?>',
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Ok',
                    closeOnConfirm: true,
                }, function (isConfirm) {
                    $("#quantity").val('');
                    $('#pInform').text('');
                    return false;
                });
            } else if (quantity == 0) {
                $("#quantity").val('');
                return false;
            } else if (parseFloat(quantity) > parseFloat(avQuantity)) {
                swal({
                    title: '<?php echo app('translator')->get("label.QUANTITY_CANNOT_BE_GREATER_THAN_AVAILABLE_PRODUCT"); ?>',
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Ok',
                    closeOnConfirm: true,
                }, function (isConfirm) {
                    $("#quantity").val('');
                    $("#displayQtyDetails").text("");
                    return false;
                });
            } else {

            }
        });

        $(document).on("change", '#sku', function () {
            var skuId = $("#sku").val();
            $("#quantity").val('');
            $("#displayQtyDetails").text("");
            if (skuId == '0') {
                return false;
            }

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            $.ajax({
                url: "<?php echo e(URL::to('admin/stockTransfer/getProductName')); ?>",
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
                    $('.js-source-states').select2();
                    $("#displayProductHints").html('<div id="pInform" class="text-success">Hints : <span id="avQuantity">' + res.quantity + '</span>&nbsp;&nbsp;' + res.unit_name + ' available</div>');

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

        var count = 1;
        $('#addItem').click(function () {
            var sku = $('#sku').val();
            var warehouse = $('#warehouse').val();
            var quantity = $('#quantity').val();
            var countNumber = count++;
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            if (warehouse == '0') {
                toastr.error("Please select  Warehouse", "Error", options);
                return false;
            }

            if (sku == '0') {
                toastr.error("Please select  Product SKU", "Error", options);
                return false;
            }

            if (quantity == '') {
                toastr.error('Please, provide product\'s quantity !', "Error", options);
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
//                var prevItemTotalPrice = parseFloat($('#totalPrice_' + editRow).val());
                $('#rowId_' + editRow).remove();
                $("#editRowId").remove();
            }

//            if (isNaN(prevItemTotalPrice)) {
//                var prevItemTotalPrice = 0;
//            }


            $.ajax({
                url: "<?php echo e(URL::to('admin/stockTransfer/purchaseNew')); ?>",
                type: "POST",
                dataType: 'json',
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sku: sku,
                    quantity: quantity,
                    type: 3
                },

                success: function (result) {
                    $("#hideNodata").css({"display": "none"});

                    var row = '<tr id="rowId_' + sku + '_' + countNumber + '">\n\
                <td>\n\<input type="hidden" name="add_btn" value="1">\n\
               <input type="hidden" id="editFlag_' + sku + '_' + countNumber + '"  value="">\n\
                    <input type="hidden" id="quantity_' + sku + '_' + countNumber + '" name="quantity[' + sku + ']"  value="' + parseFloat(quantity).toFixed(0) + '">\n\
                    <input type="hidden" id="sku_' + sku + '_' + countNumber + '" name="product_sku[' + sku + ']"  value="' + result.productSku + '">\n\
                    <input type="hidden" id="prevItem_' + sku + '" name="prev_item[' + sku + ']"  value="1">\n\
                    ' + result.productName + '</td>\n\
                <td>' + result.productSku + '</td>\n\
                <td class="text-center">' + parseInt(quantity) + '</td><td class="text-center">' + result.productUnit + '\n\
               <td class="text-center">\n\
                    <button type="button" sku="' + sku + '" countNumber="' + countNumber + '" class="btn btn-xs btn-primary tooltips vcenter edit-sku" id="editBtn' + sku + '_' + countNumber + '" title="Edit Product" style="cursor:pointer" ><i class="fa fa-edit text-white"></i></button>\n\
                    <button type="button" sku="' + sku + '" countNumber="' + countNumber + '" class="btn btn-xs btn-danger tooltips vcenter rem-sku" id="deleteBtn' + sku + '_' + countNumber + '"  title="Remove Product" style="cursor:pointer"><i class="fa fa-trash text-white"></i></button>\n\
                </td>\n\
                </tr>';

                    $("#netTotalTr").before(row);
                    $('#subBtn').attr("disabled", false);
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
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    App.unblockUI();
                }

            });
        });
        $('#updateItem').click(function () {

            var updateSku = $(this).attr('data-sku-id');
            var updateCount = $(this).attr('data-count');

            $('#editBtn' + updateSku + '_' + updateCount).attr('disabled', false);
            $('#deleteBtn' + updateSku + '_' + updateCount).attr('disabled', false);

            var sku = $('#sku').val();
            var warehouse = $('#warehouse').val();
            var quantity = $('#quantity').val();
            var countNumber = count++;
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            if (warehouse == '0') {
                toastr.error("Please select  Warehouse", "Error", options);
                return false;
            }
            if (sku == '0') {
                toastr.error("Please select  Product SKU", "Error", options);
                return false;
            }

            if (quantity == '') {
                toastr.error('Please, provide product\'s quantity !', "Error", options);
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
//                var prevItemTotalPrice = parseFloat($('#totalPrice_' + editRow).val());
                $('#rowId_' + editRow).remove();
            }

//            if (isNaN(prevItemTotalPrice)) {
//                var prevItemTotalPrice = 0;
//            }


            $.ajax({
                url: "<?php echo e(URL::to('admin/stockTransfer/purchaseNew')); ?>",
                type: "POST",
                dataType: 'json',
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sku: sku,
                    quantity: quantity,
                    type: 3
                },

                success: function (result) {
                    $("#hideNodata").css({"display": "none"});
//                var netTotal = parseFloat($('#netTotal').val());
//                if (editRow != '') {
//                    var netTotal = netTotal - prevItemTotalPrice;
//                }


                    var row = '<tr id="rowId_' + sku + '_' + countNumber + '">\n\
                <td>\n\<input type="hidden" name="add_btn" value="1">\n\
               <input type="hidden" id="editFlag_' + sku + '_' + countNumber + '"  value="">\n\
                    <input type="hidden" id="quantity_' + sku + '_' + countNumber + '" name="quantity[' + sku + ']"  value="' + parseFloat(quantity).toFixed(0) + '">\n\
                    <input type="hidden" id="sku_' + sku + '_' + countNumber + '" name="product_sku[' + sku + ']"  value="' + result.productSku + '">\n\
                    <input type="hidden" id="prevItem_' + sku + '" name="prev_item[' + sku + ']"  value="1">\n\
                    ' + result.productName + '</td>\n\
                <td>' + result.productSku + '</td>\n\
                <td class="text-center">' + parseInt(quantity) + '</td><td class="text-center">' + result.productUnit + '\n\
               <td class="text-center">\n\
                    <button type="button" sku="' + sku + '" countNumber="' + countNumber + '" class="btn btn-xs btn-primary tooltips vcenter edit-sku" id="editBtn' + sku + '_' + countNumber + '" title="Edit Product" style="cursor:pointer" ><i class="fa fa-edit text-white"></i></button>\n\
                    <button type="button" sku="' + sku + '" countNumber="' + countNumber + '" class="btn btn-xs btn-danger tooltips vcenter rem-sku" id="deleteBtn' + sku + '_' + countNumber + '"  title="Remove Product" style="cursor:pointer"><i class="fa fa-trash text-white"></i></button>\n\
                </td>\n\
                </tr>';

                    $("#netTotalTr").before(row);
                    $('#subBtn').attr("disabled", false);
                    $('#addItem').removeClass('display-none');
                    $('#updateItem').attr('data-count', '');
                    $('#updateItem').attr('data-sku-id', '');
                    $('#updateItem').addClass('display-none');
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
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    App.unblockUI();
                }

            });
        });

    });


    //remove item


    $(document).on('click', '.rem-sku', function () {
        var sku = $(this).attr('sku');
        var countNumber = $(this).attr('countNumber');

        $('#rowId_' + sku + '_' + countNumber).remove();

        var rowCount = $('tbody#itemRows tr').length;
        if (rowCount == 2) {
            $('tr#netTotalRow').remove();
            $('#hideNodata').show();
            $('#subBtn').prop("disabled", true);
        }

        var netTotal = 0;
        $(".item-amount").each(function () {
            netTotal += parseFloat($(this).val());
        });

        $('#netTotal').text(netTotal);
    });

    $(document).on('click', '.edit-sku', function () {
        var sku = $(this).attr('sku');
        var countNumber = $(this).attr('countNumber');

        $('#addItem').addClass('display-none');
        $('#updateItem').removeClass('display-none');
        $('#updateItem').attr('data-count', countNumber);
        $('#updateItem').attr('data-sku-id', sku);

        var quantity = $('#quantity_' + sku + '_' + countNumber).val();
        var editRowId = $('#editRowId').val();

        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };

        $.ajax({
            url: "<?php echo e(URL::to('admin/stockTransfer/getProductName')); ?>",
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
                $('.js-source-states').select2();
                $("#displayProductHints").html('<div id="pInform" class="text-success">Hints : <span id="avQuantity">' + res.quantity + '</span>&nbsp;&nbsp;' + res.unit_name + ' available</div>');
                var quantity = $('#quantity').val();
                if (quantity != '') {
                    validateNumberInput(quantity); // Check Qty 6 digits after decimal point
                    var totalQty = parseFloat(quantity);
                    var detailsText = unitConvert(totalQty);
                    $('#displayQtyDetails').html('<div id="pInform" class="text-danger">' + detailsText + '</div>');
                } else {
                    $('#displayQtyDetails').html('');
                }
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
        $('#sku').val(sku).select2();
        $("#editRowId").val(sku + '_' + countNumber);

        $('#editBtn' + sku + '_' + countNumber).attr('disabled', true);
        $('#deleteBtn' + sku + '_' + countNumber).attr('disabled', true);

        if (editRowId != '') {
            $('#editBtn' + editRowId).prop('disabled', true);
            $('#deleteBtn' + editRowId).prop('disabled', true);
        }
    });

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
            title: "Are you sure, you want to submit ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Submit It",
            closeOnConfirm: true,
            closeOnCancel: true,
        }, function (isConfirm) {
            if (isConfirm) {
                $('#subBtn').attr('disabled', true);
                $.ajax({
                    url: "<?php echo e(URL::to('admin/stockTransfer/transferProduct')); ?>",
                    type: "POST",
                    dataType: 'json', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    beforeSend: function () {
                        $('.button-submit').prop('disabled', true);
                        toastr.info("Loading...", "Please Wait.", options);
                    },
                    success: function (res) {
                        toastr.success('Product Transferred Successfully', 'Success', options);
                        // similar behavior as an HTTP redirect
                        function explode() {
<?php if (!empty($userAccessArr[46][1])) { ?>
                                window.location.replace('<?php echo e(URL::to("admin/stockTransferList")); ?>');
<?php } else { ?>
                                window.location.replace('<?php echo e(URL::to("admin/stockTransfer")); ?>');
<?php } ?>

                        }
                        setTimeout(explode, 2000);

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
                            toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, options);
                        } else {
                            toastr.error('Error', 'Something went wrong', options);
                        }
                        $('.button-submit').prop('disabled', false);
                    }
                });
            }
        });
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
                toastr.error('Error', "<?php echo app('translator')->get('label.MAX_DIGITS'); ?>", options);
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
            kgFinalAmntStr = parseInt(kgAmnt) + " <?php echo app('translator')->get('label.UNIT_PIS'); ?>";
        }

        //var lengthOfGm = gmAmntStr.length;//length of amount after decimal point
        //var zeroPadLength = (6 - (lengthOfGm)); //6 is fixed as 1KG is equal to 1000000 mg (0.000001 KG => 6 digit after decimal point)
        var pad = '000000';
        var totalAmntStr = (gmAmntStr + pad).substring(0, pad.length);
        var gmStr = totalAmntStr.substring(0, 3);//Subtract gram aamount
        var gmFinalAmntStr = "";
        if (gmStr > 0) {
            gmFinalAmntStr = parseInt(gmStr) + " <?php echo app('translator')->get('label.GM'); ?>";
        }
        var miliGmStr = totalAmntStr.substring(3, 6);//Subtract miligram aamount
        var mgFinalAmntStr = "";
        if (miliGmStr > 0) {
            mgFinalAmntStr = parseInt(miliGmStr) + " <?php echo app('translator')->get('label.MG'); ?>";
        }

        var text = kgFinalAmntStr + " " + gmFinalAmntStr + " " + mgFinalAmntStr;
        return text;
    }

</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/productTransfer/transfer.blade.php ENDPATH**/ ?>