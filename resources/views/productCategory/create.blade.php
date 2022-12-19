@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.CREATE_PRODUCT_CATEGORY')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '','class' => 'form-horizontal','enctype' => 'multipart/form-data','id'=>"productCategoryForm")) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-8">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="parentId">@lang('label.PARENT_CATEGORY') :</label>
                            <div class="col-md-8">
                                {!! Form::select('parent_id', array('0' => __('label.SELECT_CATEGORY_OPT')) + $parentArr, null, ['class' => 'form-control js-source-states', 'id' => 'parentId']) !!}
                                <span class="text-danger">{{ $errors->first('parent_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name',null, ['id'=> 'name', 'class' => 'form-control','autocomplete' => 'off']) !!}
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="code">@lang('label.CODE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('code',null, ['id'=> 'code', 'class' => 'form-control','autocomplete' => 'off']) !!}
                                <span class="text-danger">{{ $errors->first('code') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="fullDescription">@lang('label.DESCRIPTION') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::textarea('description', null, ['id'=> 'fullDescription', 'class' => 'form-control full-name-text-area','cols'=>'20','rows' => '8']) !!}
                                <span class="text-danger">{{ $errors->first('description') }}</span>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="order">@lang('label.ORDER') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('order', $orderList, null, ['class' => 'form-control js-source-states', 'id' => 'order']) !!}
                                <span class="text-danger">{{ $errors->first('order') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control', 'id' => 'status']) !!}
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="highlighted">@lang('label.HIGHLIGHTED_FOR_HOME_PAGE') :</label>
                            <div class="col-md-8 checkbox-center md-checkbox has-success">
                                {!! Form::checkbox('highlighted',1,null, ['id' => 'highlighted', 'class'=> 'md-check']) !!}
                                <label for="highlighted">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                <span class="text-success">@lang('label.PUT_TICK_TO_HIGHLIGHT_FOR_HOME_PAGE')</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="CategoryImage">@lang('label.PHOTO') :</label>
                            <div class="col-md-8">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 150px; height: 120px;"></div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="width: 150px; height: 120px;"> </div>
                                    <div>
                                        <span class="btn red btn-outline btn-file">
                                            <span class="fileinput-new"> @lang('label.SELECT_IMAGE') </span>
                                            <span class="fileinput-exists"> @lang('label.CHANGE') </span>
                                            {!! Form::file('image',['id'=> 'CategoryImage']) !!}

                                        </span>
                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> @lang('label.REMOVE') </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green btn-submit" >
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/admin/productCategory'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        $('#description').summernote({
            placeholder: 'Product Category Description',
            tabsize: 2,
            height: 100,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
        });
        $(document).on("click", ".btn-submit", function (e) {
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: "@lang('label.DO_YOU_WANT_TO_CONTINUE_IT')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('label.YES_CONTINUE_IT')",
                closeOnConfirm: true,
                closeOnCancel: true,
            }, function (isConfirm) {
                if (isConfirm) {

                    var options = {
                        closeButton: true,
                        debug: false,
                        positionClass: "toast-bottom-right",
                        onclick: null,
                    };

                    var formData = new FormData($("#productCategoryForm")[0]);
                    $.ajax({
                        url: "{{ URL::to('/admin/productCategory') }}",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function () {
                            App.blockUI({
                                boxed: true
                            });
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            App.unblockUI();
                            setTimeout(() => {
                                window.location.replace('{{ URL::to("admin/productCategory")}}')
                            }, 2000);
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value[0] +
                                            '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading,
                                        options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '',
                                        options);
                            } else {
                                toastr.error('Error', 'Something went wrong',
                                        options);
                            }
                            App.unblockUI();
                        }
                    }); //ajax
                }
            });
        });

    });
</script>
@stop
