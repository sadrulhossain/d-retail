@extends('layouts.default.master')
@section('data_count')
<!-- BEGIN CONTENT BODY -->
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-road"></i>@lang('label.CREATE_NEWS')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => 'admin/newsAndEvents', 'files'=> true, 'class' => 'form-horizontal')) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="title">@lang('label.TITLE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-6">
                                {!! Form::text('title', null, ['id'=> 'title', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            </div>
                        </div>
                        <div class="form-group last">
                            <label class="control-label col-md-3">@lang('label.FEATURED_IMAGE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-6">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                        <img src="{{URL::to('/')}}/public/img/no-image.png" alt=""> </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                    <div>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new"> Select image </span>
                                            <span class="fileinput-exists"> Change </span>
                                            <input type="file" name="featured_image" id="featuredImage"> </span>
                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                        <div class=""><span class="text-danger">{{ $errors->first('featured_image') }}</span></div>
                                    </div>
                                </div>
                                <div class="clearfix margin-top-10">
                                    <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.USER_IMAGE_FOR_IMAGE_DESCRIPTION')
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-3" for="postContent">@lang('label.CONTENT') :</label>
                                    <div class="col-md-9">
                                        {!! Form::textarea('content', null, ['id'=> 'postContent', 'class' => 'form-control summernote']) !!}
                                        <span class="text-danger">{{ $errors->first('content') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="location">@lang('label.LOCATION') :</label>
                            <div class="col-md-6">
                                {!! Form::text('location', null, ['id'=> 'location', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                <span class="text-danger">{{ $errors->first('location') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">{{trans('label.PUBLISH_DATE')}} :</label>
                            <div class="col-md-6">
                                <div class="input-group date datetime-picker">
                                    {!! Form::text('publish_date', null,['id'=> 'publishDate', 'class' => 'form-control','placeholder' => __('label.ENTER_PUBLISH_DATE')]) !!}
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="publishDate">
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
                            <label class="col-md-3 control-label">{{trans('label.PUBLISH_DURATION')}} :</label>
                            <div class="col-md-3">
                                <div class="input-group date datepicker2">
                                    {!! Form::text('from_date', null,['id'=> 'fromDate', 'class' => 'form-control','placeholder' => __('label.ENTER_FROM_DATE')]) !!}
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="fromDate">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                
                            </div>
                            <div class="col-md-3">
                                <div class="input-group date datepicker2">
                                    {!! Form::text('to_date', null,['id'=> 'toDate', 'class' => 'form-control','placeholder' => __('label.ENTER_TO_DATE')]) !!}
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="toDate">
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
                            <label class="control-label col-md-3" for="order_id">@lang('label.ORDER') :<span class="text-danger"> *</span></label>
                            <div class="col-md-6">
                                {!! Form::select('order_id', $orderList, $lastOrderNumber, ['id'=> 'order_id', 'class' => 'form-control js-source-states','autocomplete'=>'off']) !!}
                                <span class="text-danger">{{ $errors->first('order_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="statusId">@lang('label.STATUS') :</label>
                            <div class="col-md-6">
                                {!! Form::select('status_id', ['1' => __('label.ACTIVE'), '0' => __('label.INACTIVE')], '1', ['class' => 'form-control', 'id' => 'statusId']) !!}
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="submit" name="submit">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('admin/newsAndEvents'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>


<link href="{{asset('public/assets/global/plugins/bootstrap-summernote/summernote.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('public/assets/global/plugins/bootstrap-summernote/summernote.min.js')}}" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function () {
    $('.summernote').summernote({
        height: 200
    });
    
    $('button.reset-date').click(function () {
        var remove = $(this).attr('remove');
        $('#' + remove).val('');
    });
    
    $('.datetime-picker').datetimepicker({
        format: 'dd MM yyyy hh:ii',
        autoclose: true,
        todayHighlight: true,
    });
    $('.datepicker2').datepicker({
        format: 'dd MM yyyy',
        autoclose: true,
        todayHighlight: true,
    });
});
$(document).on("change", '#featuredImage', function (e) {
    readURL(this);
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#prvImg').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}

</script>
@stop