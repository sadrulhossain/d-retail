@extends('layouts.default.master')
@section('data_count')
<!-- BEGIN CONTENT BODY -->
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-road"></i>@lang('label.EDIT_FOOTER_MENU')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['route' => array('footerMenu.update', $target->id), 'method' => 'PATCH', 'files'=> true, 'class' => 'form-horizontal'] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">


                        <div class="form-group">
                            <label class="control-label col-md-3" for="title">@lang('label.TITLE') :</label>
                            <div class="col-md-5">
                                {!! Form::text('title', $target->title, ['id'=> 'title', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-3" for="postContent">@lang('label.CONTENT') :</label>
                                    <div class="col-md-9">
                                        {!! Form::textarea('content', $target->content, ['id'=> 'postContent', 'class' => 'form-control summernote']) !!}
                                        <span class="text-danger">{{ $errors->first('content') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-3" for="order_id">@lang('label.ORDER') :<span class="text-danger"> *</span></label>
                            <div class="col-md-5">
                                {!! Form::select('order_id', $orderList, $target->order, ['id'=> 'order_id', 'class' => 'form-control js-source-states','autocomplete'=>'off']) !!}
                                <span class="text-danger">{{ $errors->first('order_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="statusId">@lang('label.STATUS') :</label>
                            <div class="col-md-5">
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
                        <a href="{{ URL::to('/admin/footerMenu'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
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

</script>
@stop