@extends('layouts.default.master')
@section('data_count')
<!-- BEGIN CONTENT BODY -->
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-home"></i>@lang('label.EDIT_SPECIALITY')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['route' => array('speciality.update', $target->id), 'method' => 'PATCH', 'files'=> true, 'class' => 'form-horizontal'] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">


                        
                        <div class="form-group">
                            <label class="control-label col-md-3" for="icon">@lang('label.ICON') :<span class="text-danger"> *</span></label>
                            <div class="col-md-5">
                                {!! Form::select('icon', $iconList, $target->icon, ['class' => 'form-control js-source-states', 'id'=> 'icon']) !!} 
                                <span class="text-danger">{{ $errors->first('icon') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="title">@lang('label.TITLE') :</label>
                            <div class="col-md-5">
                                {!! Form::text('title', null, ['id'=> 'title', 'class' => 'form-control','autocomplete'=>'off']) !!}
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            </div>
                        </div>

                       <div class="form-group">
                            <label class="control-label col-md-3" for="subtitle">@lang('label.SUBTITLE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-5">
                                {!! Form::text('subtitle', null, ['id'=> 'subtitle', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                <span class="text-danger">{{ $errors->first('subtitle') }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3" for="orderId">@lang('label.ORDER') :<span class="text-danger"> *</span></label>
                            <div class="col-md-5">
                                {!! Form::select('order_id', $orderList, $target->order, ['id'=> 'orderId', 'class' => 'form-control js-source-states','autocomplete'=>'off']) !!}
                                <span class="text-danger">{{ $errors->first('order_id') }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3" for="status">@lang('label.STATUS') :<span class="text-danger"> *</span></label>
                            <div class="col-md-5">
                                {!! Form::select('status_id', array('1' => 'Active', '0' => 'Inactive'), Request::old('status_id'), ['class' => 'form-control', 'id' => 'status']) !!}
                                
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="submit">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/admin/speciality'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>



@stop