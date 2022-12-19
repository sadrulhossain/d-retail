@extends('layouts.default.master')
@section('data_count')
<!-- BEGIN CONTENT BODY -->
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-road"></i>@lang('label.EDIT_ADVERTISEMENT')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['route' => array('advertisement.update', $target->id), 'method' => 'PATCH', 'files'=> true, 'class' => 'form-horizontal'] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="form-group" style="display: none">
                        <label class="control-label col-md-3" for="caption">@lang('label.CAPTION') :</label>
                        <div class="col-md-4">
                            {!! Form::text('caption', null, ['id'=> 'caption', 'class' => 'form-control','autocomplete'=>'off']) !!}
                            <span class="text-danger">{{ $errors->first('caption') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">@lang('label.IMAGE_FOR_ADVERTISEMENT') :<span class="text-danger"> *</span></label>
                        <div class="col-md-9">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                    @if(!empty($target->img_d_x))
                                    <img src="{{asset('public/uploads/content/advertisement').'/'.$target->img_d_x }}" alt=""/>
                                    @else
                                    <img src="{{URL::to('/')}}/public/img/no-image.png" alt=""/>
                                    @endif
                                </div>   
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                <div>
                                    <span class="btn default btn-file">
                                        <span class="fileinput-new"> Select image </span>
                                        <span class="fileinput-exists"> Change </span>
                                        <input type="file" name="advertisement_image"> </span>
                                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                    <div class=""><span class="text-danger">{{ $errors->first('advertisement_image') }}</span></div>
                                </div>
                            </div>
                            <div class="clearfix margin-top-10">
                                <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.ACCEPTED_IMAGE_FORMATE_jpg_png_jpeg_gif')
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3" for="title">@lang('label.URL') :</label>
                        <div class="col-md-6">
                            {!! Form::text('url', null, ['id'=> 'url', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                            <span class="text-danger">{{ $errors->first('title') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3" for="show_advertise">@lang('label.SHOW_ADVERTISE') :<span class="text-danger"> *</span></label>
                        <div class="col-md-5">
                            {!! Form::select('show_advertise', $showAddArr, null, ['id'=> 'show_advertise', 'class' => 'form-control js-source-states','autocomplete'=>'off']) !!}
                            <span class="text-danger">{{ $errors->first('order_id') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3" for="orderId">@lang('label.ORDER') :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            {!! Form::select('order_id', $orderList, $target->order, ['id'=> 'orderId', 'class' => 'form-control js-source-states','autocomplete'=>'off']) !!}
                            <span class="text-danger">{{ $errors->first('order_id') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3" for="email">@lang('label.STATUS') :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            {!! Form::select('status_id', array('1' => 'Active', '0' => 'Inactive'), Request::old('status_id'), ['class' => 'form-control', 'id' => 'userStatus']) !!}

                            <span class="text-danger">{{ $errors->first('email') }}</span>
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
                        <a href="{{ URL::to('/admin/advertisement'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
@stop