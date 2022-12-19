@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-industry"></i>@lang('label.RELATE_SUPPLIER_TO_PRODUCT')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'supplierToProductRelateForm')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="supplierId">@lang('label.SUPPLIER') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('supplier_id', $supplierArr, Request::get('supplier_id'), ['class' => 'form-control js-source-states', 'id' => 'supplierId']) !!}
                                <span class="text-danger">{{ $errors->first('supplier_id') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br/>
                        <div id="showProducts">
                            @if(!empty(Request::get('supplier_id')))
                            <div class="row margin-bottom-10">
                                <div class="col-md-12">
                                    <span class="label label-success" >@lang('label.TOTAL_NUM_OF_PRODUCTS'): {!! !empty($productArr)?count($productArr):0 !!}</span>
                                    @if(!empty($userAccessArr[94][5]))
                                    <button class='label label-primary tooltips' href="#modalRelatedProduct" id="relateProduct" data-toggle="modal" title="@lang('label.SHOW_RELATED_PRODUCTS')">
                                        @lang('label.PRODUCT_RELATED_TO_THIS_SUPPLIER'): {!! !empty($productRelateToSupplier)?count($productRelateToSupplier):0 !!}&nbsp; <i class="fa fa-search-plus"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover relation-view">
                                            <thead>
                                                <tr class="active">
                                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                                    @if(!empty($productArr))
                                                    <?php
                                                    $allCheckDisabled = '';
                                                    if (!empty($dependentAttributeArr[$request->get('product_id')])) {
                                                        $allCheckDisabled = 'disabled';
                                                    }
                                                    ?>
                                                    <th class="vcenter">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-product-check', $allCheckDisabled]) !!}
                                                            <label for="checkAll">
                                                                <span class="inc"></span>
                                                                <span class="check mark-caheck"></span>
                                                                <span class="box mark-caheck"></span>
                                                            </label>
                                                            &nbsp;&nbsp;<span>@lang('label.CHECK_ALL')</span>
                                                        </div>
                                                    </th>
                                                    @endif

                                                    <th class="vcenter">@lang('label.PRODUCT_NAME')</th>
                                                    <th class="vcenter">@lang('label.PRODUCT_ASSIGNED_TO_OTHER_SUPPLIERS')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($productArr))
                                                <?php $sl = 0; ?>
                                                @foreach($productArr as $product)
                                                <?php
                                                //check and show previous value
                                                $checked = '';
                                                if (!empty($productRelateToSupplier) && array_key_exists($product['id'], $productRelateToSupplier)) {
                                                    $checked = 'checked';
                                                }

                                                $productDisabled = $productTooltips = '';
                                                $checkCondition = 0;
                                                if (!empty($inactiveProductArr) && in_array($product['id'], $inactiveProductArr)) {
                                                    if ($checked == 'checked') {
                                                        $checkCondition = 1;
                                                    }
                                                    $productDisabled = 'disabled';
                                                    $productTooltips = __('label.INACTIVE');
                                                }
                                                ?>
                                                <tr>
                                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                                    <td class="vcenter">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('product['.$product['id'].']', $product['id'], $checked, ['id' => $product['id'], 'data-id'=> $product['id'],'class'=> 'md-check product-check', $productDisabled]) !!}
                                                            <label for="{!! $product['id'] !!}">
                                                                <span class="inc tooltips" data-placement="right" title="{{ $productTooltips }}"></span>
                                                                <span class="check mark-caheck tooltips" data-placement="right" title="{{ $productTooltips }}"></span>
                                                                <span class="box mark-caheck tooltips" data-placement="right" title="{{ $productTooltips }}"></span>
                                                            </label>
                                                        </div>
                                                        @if($checkCondition == '1')
                                                        {!! Form::hidden('product['.$product['id'].']', $product['id']) !!}
                                                        @endif
                                                    </td>

                                                    <td class="vcenter">{!! $product['name'] ?? '' !!}</td>

                                                    <td>
                                                        <button class='label btn-primary tooltips' href="#modalRelatedSupplier" id="relateSupplier" data-id="{{ $product['id'] }}" data-toggle="modal" title="@lang('label.SHOW_RELATED_SUPPLIERS')">
                                                            <span class="badge badge-primary" >
                                                                {{ !empty($productWiseSupplierArr[$product['id']]) ? sizeof($productWiseSupplierArr[$product['id']]) : 0 }}
                                                            </span>
                                                        </button>
                                                    </td>


                                                </tr>
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td class="vcenter text-danger" colspan="20">@lang('label.NO_ATTRIBUTE_FOUND')</td>
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
                                        @if(!empty($productArr))
                                        @if(!empty($userAccessArr[94][7]))
                                        <button class="btn btn-circle green btn-submit" id="" type="button">
                                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                                        </button>
                                        @endif
                                        @if(!empty($userAccessArr[94][1]))
                                        <a href="{{ URL::to('/admin/supplierToProduct') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<!-- Modal start -->
<div class="modal fade" id="modalRelatedProduct" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showRelatedProduct">
        </div>
    </div>
</div>

<div class="modal fade" id="modalRelatedSupplier" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showRelatedSupplier">
        </div>
    </div>
</div>


<!-- Modal end-->
<script type="text/javascript">
    $(function () {
//        $('.tooltips').tooltip();
<?php if (!empty($supplierArr)) { ?>
            $('.relation-view').dataTable({
                "language": {
                    "search": "Search Keywords : ",
                },
                "paging": true,
                "info": true,
                "order": false
            });
<?php } ?>

        $(".product-check").on("click", function () {
            if ($('.product-check:checked').length == $('.product-check').length) {
                $('.all-product-check').prop("checked", true);
            } else {
                $('.all-product-check').prop("checked", false);
            }
        });
        $(".all-product-check").click(function () {
            if ($(this).prop('checked')) {
                $('.product-check').prop("checked", true);
            } else {
                $('.product-check').prop("checked", false);
            }
        });
        if ($('.product-check:checked').length == $('.product-check').length) {
            $('.all-product-check').prop("checked", true);
        } else {
            $('.all-product-check').prop("checked", false);
        }

        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };


        $(document).on('change', '#supplierId', function () {
            var supplierId = $('#supplierId').val();

            if (supplierId == '0') {
                $('#showProducts').html('');
                return false;
            }
            $.ajax({
                url: '{{URL::to("admin/supplierToProduct/getProductsToRelate")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    supplier_id: supplierId,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showProducts').html(res.html);
                    App.unblockUI();
                }, error: function (jqXhr, ajaxOptions, thrownError) {
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
                    App.unblockUI();
                }
            });
        });

        $(document).on("click", "#relateProduct", function (e) {
            e.preventDefault();
            var supplierId = $("#supplierId").val();
            $.ajax({
                url: "{{ URL::to('/admin/supplierToProduct/getRelatedProducts')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    supplier_id: supplierId
                },
                beforeSend: function () {
                    $("#showRelatedProduct").html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $("#showRelatedProduct").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });


        $(document).on("click", "#relateSupplier", function (e) {
            e.preventDefault();
            var productId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/admin/supplierToProduct/getRelatedSuppliers')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId
                },
                beforeSend: function () {
                    $("#showRelatedSupplier").html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $("#showRelatedSupplier").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        //insert sales person to buyer
        $(document).on("click", ".btn-submit", function (e) {
            e.preventDefault();
            var oTable = $('.relation-view').dataTable();
            var x = oTable.$('input,select,textarea').serializeArray();
            $.each(x, function (i, field) {
                $("#supplierToProductRelateForm").append(
                        $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', field.name)
                        .val(field.value));
            });
            swal({
                title: 'Are you sure?',
                text: "You can not undo this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true},
                    function (isConfirm) {
                        if (isConfirm) {
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
                            var form_data = new FormData($('#supplierToProductRelateForm')[0]);
                            $.ajax({
                                url: "{{URL::to('admin/supplierToProduct/relateSupplierToProduct')}}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    var supplierId = $('#supplierId').val();
                                    location = "supplierToProduct?supplier_id=" + supplierId;
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
                                }
                            });
                        }
                    });
        });

    });
</script>
@stop