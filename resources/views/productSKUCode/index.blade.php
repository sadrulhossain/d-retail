@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-industry"></i>@lang('label.PRODUCT_SKU_CODE')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'productSKUCodeForm')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="productId">@lang('label.PRODUCT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('product_id', $productArr, Request::get('product_id'), ['class' => 'form-control js-source-states', 'id' => 'productId']) !!}
                                @if(sizeof($productArr) <= 1)
                                <span class="text-danger">{!! __('label.NO_CM_IS_ASSIGNED_TO_THIS_COURSE') !!}</span>
                                @endif
                            </div>
                        </div>
                        <div id="showCategoryBrand">

                        </div>

                    </div>
                </div>


            </div>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">

                        @if(!empty($userAccessArr[96][2]))
                        <button class="btn btn-circle green button-submit" type="button" id="buttonSubmit" >
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        @endif
                        @if(!empty($userAccessArr[96][1]))
                        <a href="{{ URL::to('/admin/productSKUCode') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                        @endif

                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        
    </div>
</div>

<!-- Modal start -->
<div class="modal fade" id="modalRelatedAttribute" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showAssignedSKUCodes">
        </div>
    </div>
</div>
<!-- Modal end-->
<script type="text/javascript">
    $(document).ready(function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        $(document).on('change', '#productId', function () {
            var productId = $('#productId').val();
            if (productId == '0') {
                $('#showCategoryBrand').html('');
                return false;
            }
            $.ajax({
                url: '{{URL::to("admin/productSKUCode/getCategoryBrand")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showCategoryBrand').html(res.html);
                    var brand = $('#brandName').val();
                    var pCode = $('#code').val();
                    var category = $('#categoryName').val();
                    var sku = pCode + '-' + brand + '-' + category + '-';
                    $('#skuCode').val(sku);
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

        $(document).on('click', '#buttonSubmit', function (e) {
            e.preventDefault();
            var form_data = new FormData($('#productSKUCodeForm')[0]);
            swal({
                title: 'Are you sure?',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{URL::to('admin/productSKUCode/relateProductToSKUCode')}}",
                        type: "POST",
                        datatype: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function (res) {
                            toastr.success(res, "@lang('label.SKU_CREATED_SUCCESSFULLY')", options);
                            //App.blockUI({ boxed: false });
                            //setTimeout(location.reload.bind(location), 1000);
                            $( "#productId" ).trigger( "change" );
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
                                //toastr.error(jqXhr.responseJSON.message, '', options);
                                var errors = jqXhr.responseJSON.message;
                                var errorsHtml = '';
                                if (typeof (errors) === 'object') {
                                    $.each(errors, function (key, value) {
                                        errorsHtml += '<li>' + value + '</li>';
                                    });
                                    toastr.error(errorsHtml, '', options);
                                } else {
                                    toastr.error(jqXhr.responseJSON.message, '', options);
                                }
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            App.unblockUI();
                        }
                    });
                }
            });
        });
        
        
        
        $(document).on("click", "#assignedSKU", function (e) {
            e.preventDefault();
            var productId = $("#productId").val();
            $.ajax({
                url: "{{ URL::to('/admin/productSKUCode/getAssignedSKUCodes')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId
                },
                beforeSend: function () {
                    $("#showAssignedSKUCodes").html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $("#showAssignedSKUCodes").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });
        
    });
</script>
@stop