@extends('layouts.default.master')
@section('data_count')
<!-- BEGIN CONTENT BODY -->
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-home"></i>@lang('label.EDIT_MENU')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['route' => array('menu.update', $target->id), 'method' => 'PATCH', 'files'=> true, 'class' => 'form-horizontal'] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">


                        <div class="form-group">
                            <label class="control-label col-md-3" for="title">@lang('label.TITLE') :</label>
                            <div class="col-md-5">
                                {!! Form::text('title', null, ['id'=> 'title', 'class' => 'form-control','autocomplete'=>'off']) !!}
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="url">@lang('label.URL') :<span class="text-danger"> *</span></label>
                            <div class="col-md-5">
                                {!! Form::text('url', $target->url, ['id'=> 'url', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                <span class="text-danger">{{ $errors->first('url') }}</span>
                            </div>
                        </div>

                        @if($target->for_logged_in_users == '1')
                        <div class="form-group">
                            <label class="control-label col-md-3 mt-checkbox" for="forLoggedInUsers">@lang('label.FOR_LOGGED_IN_USERS') :</label>
                            <div class="col-md-5 checkbox-center md-checkbox has-success">
                                <input type="hidden" name="for_logged_in_users" value="0">
                                {!! Form::checkbox('for_logged_in_users',1,true, ['id' => 'loginStatus', 'class'=> 'md-check']) !!}
                                <label for="forLoggedInUsers">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                    ( @lang('label.PUT_TICK_FOR_LOGGED_IN_USERS') )
                                </label>
                            </div>
                        </div>
                        @else
                        <div class="form-group">
                            <label class="control-label col-md-3 mt-checkbox" for="forLoggedInUsers">@lang('label.FOR_LOGGED_IN_USERS') :</label>
                            <div class="col-md-5 checkbox-center md-checkbox has-success">
                                <input type="hidden" name="for_logged_in_users" value="0">
                                {!! Form::checkbox('for_logged_in_users',1,false, ['id' => 'forLoggedInUsers', 'class'=> 'md-check']) !!}
                                <label for="forLoggedInUsers">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                    ( @lang('label.PUT_TICK_FOR_LOGGED_IN_USERS') )
                                </label>
                            </div>
                        </div>
                        @endif

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
                        <a href="{{ URL::to('/admin/menu'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>



@stop