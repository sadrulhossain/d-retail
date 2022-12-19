@extends('layouts.default.master')
@section('data_count')	
<?php
$v3 = 'z' . uniqid();
?>
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.CREATE_CUSTOMER')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(['route' => 'customer.store' , 'id' => 'buyerForm' ,'group' => 'form', 'class' => 'form-horizontal']) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <h3 class="form-section title-section bold">@lang('label.BUYER_INFORMATION')</h3>
                    <div class="col-md-offset-1 col-md-7">
                        
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="email">@lang('label.EMAIL') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('email', null, ['id'=> 'email', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="phone">@lang('label.PHONE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('phone', null, ['id'=> 'phone', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-8">
                            <button class="btn btn-circle green" type="submit" id='submitBuyer'>
                                <i class="fa fa-check"></i> @lang('label.SUBMIT')
                            </button>
                            <a href="{{ URL::to('/customer'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>	
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        


    });
</script>
@stop