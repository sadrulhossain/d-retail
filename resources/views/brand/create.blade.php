@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>@lang('label.CREATE_BRAND')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'files' => true,'id'=>'brandCreateForm')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}

            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name',null, ['id'=> 'name', 'class' => 'form-control','placeholder'=>'Name']) !!} 
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="origin">@lang('label.ORIGIN') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('origin', $originArr, null, ['class' => 'form-control js-source-states', 'id' => 'origin']) !!}
                                <span class="text-danger">{{ $errors->first('origin') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="brandCode">@lang('label.BRAND_CODE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('brand_code', null, ['id'=> 'brandCode', 'class' => 'form-control','autocomplete' => 'off','placeholder'=>'Code']) !!} 
                                <span class="text-danger">{{ $errors->first('brand_code') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="manufacturedProduct">@lang('label.MANUFACTURED_PRODUCT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('manufactured_product[]', $productArr, null, ['class' => 'form-control mt-multiselect btn btn-default', 
                                'id' => 'manufacturedProduct', 'multiple' => 'multiple', 'data-width' => '100%']) !!}
                                <span class="text-danger">{{ $errors->first('manufactured_product_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="description">@lang('label.DESCRIPTION') :</label>
                            <div class="col-md-8">
                                {{ Form::textarea('description', null, ['id'=> 'description', 'class' => 'form-control','size' => '30x5']) }}
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control', 'id' => 'status']) !!}
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>
                    </div>



                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="logo">@lang('label.LOGO') :</label>
                            <div class="col-md-8">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;">
                                        <img src="{{URL::to('/')}}/public/img/no_image.png" alt=""> 
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 100px; max-height: 100px;"> </div>
                                    <div>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new"> @lang('label.SELECT_IMAGE') </span>
                                            <span class="fileinput-exists"> @lang('label.CHANGE') </span>
                                            {!! Form::file('logo',['id'=> 'logo']) !!}
                                        </span>
                                        <span class="help-block text-danger">{{ $errors->first('logo') }}</span>
                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> @lang('label.REMOVE') </a>
                                    </div>
                                </div>
                                <div class="clearfix margin-top-10">
                                    <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.BRAND_IMAGE_FOR_IMAGE_DESCRIPTION')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green btn-submit" type="button"id="createBrand">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href=        "{{ URL::to('/admin/brand'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
<script type="text/javascript">

    $(function () {
        var productAllSelected = false;
        $('#manufacturedProduct').multiselect({
            numberDisplayed: 0,
            includeSelectAllOption: true,
            buttonWidth: '100%',
            maxHeight: 250,
            nonSelectedText: "@lang('label.SELECT_PRODUCTS')",
//        enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            onSelectAll: function () {
                productAllSelected = true;
            },
            onChange: function () {
                productAllSelected = false;
            }
        });
    });

    $(function () {

        $('.md-check').click(function () {
            var certificateId = $(this).data('id');
            if ($(this).prop('checked')) {
                $('#certificateFile_' + certificateId).prop('disabled', false);
            } else {
                $('#certificateFile_' + certificateId).prop('disabled', true);
            }

        });
        //Save Brand using Certicate Wise Attachment 
        $(document).on("click", "#createBrand", function (e) {
            e.preventDefault();
            var formData = new FormData($('#brandCreateForm')[0]);

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes,Confirm',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ route('brand.store')}}",
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $('#createBrand').prop('disabled', true);
                            App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            App.unblockUI();
                            setTimeout(() => {
                                location.replace("{{ URL::to('admin/brand')}}");
                            }, 2000);

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
                            $('#createBrand').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });


    });
</script>
@stop