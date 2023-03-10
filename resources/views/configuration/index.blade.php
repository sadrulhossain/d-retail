@extends('layouts.default.master')
@section('data_count')
<?php
$v3 = 'z' . uniqid();
?>
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.CONFIGURATION')
            </div>
        </div>
        <div class="portlet-body form">
            <div class="row">
                <div class="col-md-12">
                    <div class="tabbable-line">
                        <ul class="nav nav-tabs ">
                            <li class="active">
                                <a href="#tab_15_1" data-toggle="tab"> @lang('label.COMPANY_INFORMATION') </a>
                            </li>
                            <li >
                                <a href="#tab_15_2" data-toggle="tab"> @lang('label.SIGNATORY_INFORMATION') </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- START:: company info tab -->
                            <div class="tab-pane active" id="tab_15_1">
                                <div class="portlet-body form">
                                    {!! Form::open(array('group' => 'form', 'url' => '#', 'class' => 'form-horizontal','files' => true,'id'=>'companyInfoFormData')) !!}
                                    {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
                                    {{csrf_field()}}
                                    @if(empty($companyInfo))
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-6">

                                                    <div class="form-group">
                                                        <label class="control-label col-md-4" for="companyName">@lang('label.COMPANY_NAME') :<span class="text-danger"> *</span></label>
                                                        <div class="col-md-8">
                                                            {!! Form::text('name', __('label.KONITA_TRADE_INTERNATIONAL'), ['id'=> 'companyName','class' => 'form-control','autocomplete' => 'off']) !!}
                                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4" for="address">@lang('label.ADDRESS') :<span class="text-danger"> *</span></label>
                                                        <div class="col-md-8">
                                                            {!! Form::textarea('address',  __('label.KONITA_ADDRESS'), ['id'=> 'address', 'class' => 'form-control address','autocomplete' => 'off']) !!}
                                                            <span class="text-danger">{{ $errors->first('address') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4" for="gEmbed">@lang('label.GMAP_EMBED_CODE') :<span class="text-danger"> *</span></label>
                                                        <div class="col-md-8">
                                                            {!! Form::textarea('google_emed', null, ['id'=> 'gEmbed', 'class' => 'form-control','size' => '30x5', 'autocomplete' => 'off','placeholder' => __('label.GMAP_EMBED_CODE_PLACEHOLDER')]) !!}
                                                            <span class="text-danger">{{ $errors->first('address') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4" for="hotline">@lang('label.HOTLINE') :<span class="text-danger"> *</span></label>
                                                        <div class="col-md-8">
                                                            {!! Form::text('hotline', null, ['id'=> 'hotline','class' => 'form-control','autocomplete' => 'off']) !!}
                                                            <span class="text-danger">{{ $errors->first('hotline') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4" for="vat">@lang('label.VAT') :<span class="text-danger"> *</span></label>
                                                        <div class="col-md-8">
                                                            <div class="input-group bootstrap-touchspin width-inherit">
                                                                {!! Form::text('vat', null, ['id'=> 'vat','class' => 'form-control integer-decimal-only text-input-width-100-per text-right','autocomplete' => 'off']) !!}
                                                                <span class="input-group-addon bootstrap-touchspin-postfix bold">%</span>
                                                            </div>

                                                            <div class="md-checkbox vcenter module-check margin-top-10">
                                                                {!! Form::checkbox('include_vat', null, false, ['id' => 'headOffice', 'class'=> 'md-check']) !!}
                                                                <label for="headOffice">
                                                                    <span class="inc"></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span>
                                                                </label>
                                                                <span class="vcenter">@lang('label.INCLUDE_VAT')</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <?php $v3 = 'a' . uniqid() ?>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4" for="phone_number_{{$v3}}">@lang('label.PHONE_NUMBER') :</label>
                                                        <div class="col-md-6">
                                                            {!! Form::text('phone_number['.$v3.']',null, ['id'=> 'phone_number_'.$v3, 'class' => 'form-control', 'autocomplete' => 'off']) !!}
                                                            <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <button class="btn btn-inline green-haze add-phone-number  tooltips"  data-placement="right" title="@lang('label.ADD_NEW_PHONE_NUMBER')" type="button">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div id="addPhoneNumberRow"></div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-4" for="email">@lang('label.EMAIL') :<span class="text-danger"> *</span></label>
                                                        <div class="col-md-8">
                                                            {!! Form::email('email', null, ['id'=> 'email', 'class' => 'form-control','autocomplete' => 'off']) !!}
                                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4" for="website">@lang('label.WEBSITE') :</label>
                                                        <div class="col-md-8">
                                                            {!! Form::text('website', null, ['id'=> 'website', 'class' => 'form-control','autocomplete' => 'off']) !!}
                                                            <span class="text-danger">{{ $errors->first('website') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">

                                                        <div class="col-xs-12 col-sm-12 col-lg-12 col-md-12 map-view width-full">

                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-4 col-md-8">
                                                    <button class="btn green btn-submit" id="saveCompanyInfo" type="button">
                                                        <i class="fa fa-check"></i> @lang('label.SUBMIT')
                                                    </button>
                                                    <a href="{{ URL::to('/product'.Helper::queryPageStr($qpArr)) }}" class="btn btn-outline grey-salsa">@lang('label.CANCEL')</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="companyName">@lang('label.COMPANY_NAME') :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-8">
                                                        {!! Form::text('name', $companyInfo->name, ['id'=> 'companyName','class' => 'form-control','autocomplete' => 'off']) !!}
                                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="companyLogo">@lang('label.COMPANY_LOGO') :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-8">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-new thumbnail" {{ (empty($companyInfo->company_logo)) ? 'style="width: 100px; height: 100px;"' : 'style="max-width: 100px; max-height: 100px; line-height: 10px ;"' }}>
                                                                @if(!empty($companyInfo->company_logo))
                                                                <img src="{{URL::to('/')}}/public/frontend/assets/images/{{$companyInfo->company_logo}}" alt="">
                                                                @else
                                                                <img src="{{URL::to('/')}}/public/img/no_image.png" alt="">
                                                                @endif
                                                            </div>
                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 100px; max-height: 100px;"> </div>
                                                            <div>
                                                                <span class="btn default btn-file">
                                                                    <span class="fileinput-new"> {{ empty($companyInfo->company_logo) ? __('label.SELECT_LOGO') : __('label.CHANGE')  }} </span>
                                                                    <span class="fileinput-exists"> @lang('label.CHANGE') </span>
                                                                    {!! Form::file('company_logo',['id'=> 'companyLogo']) !!}
                                                                </span>
                                                                <span class="help-block text-danger">{{ $errors->first('company_logo') }}</span>
                                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> @lang('label.REMOVE') </a>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix margin-top-10">
                                                            <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.COMPANY_INFO_IMAGE_FOR_IMAGE_DESCRIPTION')
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="address">@lang('label.ADDRESS') :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-8">
                                                        {!! Form::textarea('address', $companyInfo->address, ['id'=> 'address', 'class' => 'form-control','autocomplete' => 'off']) !!}
                                                        <span class="text-danger">{{ $errors->first('address') }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="gEmbed">@lang('label.GMAP_EMBED_CODE') :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-8">
                                                        {!! Form::textarea('google_emed',  $companyInfo->google_emed, ['id'=> 'gEmbed', 'class' => 'form-control','size' => '30x5', 'autocomplete' => 'off','placeholder' => __('label.GMAP_EMBED_CODE_PLACEHOLDER')]) !!}
                                                        <span class="text-danger">{{ $errors->first('address') }}</span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="hotline">@lang('label.HOTLINE') :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-8">
                                                        {!! Form::text('hotline', $companyInfo->hotline, ['id'=> 'hotline','class' => 'form-control','autocomplete' => 'off']) !!}
                                                        <span class="text-danger">{{ $errors->first('hotline') }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="vat">@lang('label.VAT') :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-8">
                                                        <div class="input-group bootstrap-touchspin width-inherit">
                                                            {!! Form::text('vat', $companyInfo->vat, ['id'=> 'vat','class' => 'form-control integer-decimal-only text-input-width-100-per text-right','autocomplete' => 'off']) !!}
                                                            <span class="input-group-addon bootstrap-touchspin-postfix bold">%</span>
                                                        </div>

                                                        <?php
                                                        $checked = '';
                                                        if (!empty($companyInfo->include_vat) && ($companyInfo->include_vat == '1')) {
                                                            $checked = 'checked';
                                                        }
                                                        ?>
                                                        <div class="md-checkbox vcenter module-check margin-top-10">
                                                            {!! Form::checkbox('include_vat', null, $checked, ['id' => 'headOffice', 'class'=> 'md-check']) !!}
                                                            <label for="headOffice">
                                                                <span class="inc"></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span>
                                                            </label>
                                                            <span class="vcenter">@lang('label.INCLUDE_VAT')</span>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <?php
                                                $v3 = 'a' . uniqid();
                                                $i = 1;
                                                $jsonDecodedPhoneNumber = [];
                                                $jsonDecodedPhoneNumber = json_decode($companyInfo->phone_number, true);
                                                ?>
                                                @foreach($jsonDecodedPhoneNumber as $item)
                                                @if($i == '1')
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="phone_number_{{$v3}}">@lang('label.PHONE_NUMBER') :</label>
                                                    <div class="col-md-6">
                                                        {!! Form::text('phone_number['.$v3.']',$item, ['id'=> 'phone_number_'.$v3, 'class' => 'form-control', 'autocomplete' => 'off']) !!}
                                                        <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                                                    </div>

                                                    <div class="col-md-1">
                                                        <button class="btn btn-inline green-haze add-phone-number  tooltips"  data-placement="right" title="@lang('label.ADD_NEW_PHONE_NUMBER')" type="button">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="phone_number_{{$v3}}"></label>
                                                    <div class="col-md-6">
                                                        {!! Form::number('phone_number['.$v3.']',$item, ['id'=> 'phone_number_'.$v3, 'class' => 'form-control', 'autocomplete' => 'off']) !!}
                                                        <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button class="btn btn-inline btn-danger remove-phone-number-row  tooltips"  title="Remove" type="button">
                                                            <i class="fa fa-remove"></i>
                                                        </button>
                                                    </div>

                                                </div>
                                                @endif
                                                <?php
                                                $i++;
                                                $v3++;
                                                ?>
                                                @endforeach
                                                <div id="addPhoneNumberRow"></div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="email">@lang('label.EMAIL') :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-8">
                                                        {!! Form::email('email', $companyInfo->email, ['id'=> 'email', 'class' => 'form-control','autocomplete' => 'off']) !!}
                                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="website">@lang('label.WEBSITE') :</label>
                                                    <div class="col-md-8">
                                                        {!! Form::text('website', $companyInfo->website, ['id'=> 'website', 'class' => 'form-control','autocomplete' => 'off']) !!}
                                                        <span class="text-danger">{{ $errors->first('website') }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-12 col-sm-12 col-lg-12 col-md-12 map-view width-full">
                                                        <iframe src=" {!! !empty($companyInfo->google_emed)?$companyInfo->google_emed:'' !!}" width="100%" height="220" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-12 text-center">
                                                <button class="btn green btn-submit" id="saveCompanyInfo" type="button">
                                                    <i class="fa fa-check"></i> @lang('label.SUBMIT')
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row margin-top-10">
                                        <div class="col-md-12">
                                            <div class="col-md-12 table-responsive">
                                                <table class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr class="center">
                                                            <th class="vcenter">@lang('label.COMPANY_NAME')</th>
                                                            <th class="vcenter text-center">@lang('label.ADDRESS')</th>
                                                            <th class="vcenter text-center">@lang('label.PHONE_NUMBER')</th>
                                                            <th class="vcenter text-center">@lang('label.HOTLINE')</th>
                                                            <th class="text-center vcenter">@lang('label.EMAIL')</th>
                                                            <th class="text-center vcenter">@lang('label.WEBSITE')</th>
                                                            <th class="text-center vcenter">@lang('label.VAT')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($companyInfo))
                                                        <tr>
                                                            <td class="vcenter"> {{ $companyInfo->name }}</td>
                                                            <td class="vcenter text-center">{!! $companyInfo->address !!} </td>

                                                            <td class="vcenter text-center">
                                                                @foreach($jsonDecodedPhoneNumber as $item)
                                                                {{ $item }}
                                                                @endforeach
                                                            </td>
                                                            <td class="vcenter text-center">{{ $companyInfo->hotline }} </td>
                                                            <td class="vcenter text-center">{{ $companyInfo->email }} </td>
                                                            <td class="vcenter text-center">{{ $companyInfo->website }} </td>
                                                            <td class="vcenter text-center">{{ $companyInfo->vat }} </td>

                                                            </td>
                                                        </tr>
                                                        @else
                                                        <tr>
                                                            <td colspan="8">@lang('label.NO_COMPANY_INFORMATION_FOUND')</td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    {!! Form::close() !!}
                                </div>
                            </div>
                            <!-- EOF:: company info tab -->
                            <!-- Start:: signatory info tab -->
                            <div class="tab-pane" id="tab_15_2">
                                <div class="portlet-body form">
                                    {!! Form::open(array('group' => 'form', 'url' => '#','files' => true,'class' => 'form-horizontal','id' => 'signatoryInfoFormData')) !!}
                                    {{csrf_field()}}
                                    <?php $colMd = '2' ?>
                                    @if(empty($target))
                                    <?php $colMd = '4' ?>
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-offset-1 col-md-7">
                                                <div class="form-group">
                                                    <label class="control-label col-md-5" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-7">
                                                        {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control','autocomplete'=>'off']) !!}
                                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5" for="designation">@lang('label.DESIGNATION') :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-7">

                                                        {!! Form::text('designation', null, ['id'=> 'designation', 'class' => 'form-control','autocomplete'=>'off']) !!}
                                                        <span class="text-danger">{{ $errors->first('designation') }}</span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-5" for="seal">@lang('label.SEAL') :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-7">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;">

                                                                <img src="{{URL::to('/')}}/public/img/no_image.png" alt="">
                                                            </div>
                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 100px; max-height: 100px;"> </div>
                                                            <div>
                                                                <span class="btn default btn-file">
                                                                    <span class="fileinput-new"> @lang('label.SELECT_IMAGE') </span>
                                                                    <span class="fileinput-exists"> @lang('label.CHANGE') </span>
                                                                    {!! Form::file('seal',['id'=> 'seal']) !!}
                                                                </span>
                                                                <span class="help-block text-danger">{{ $errors->first('seal') }}</span>
                                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> @lang('label.REMOVE') </a>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix margin-top-10">
                                                            <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.SIGNATORY_INFO_IMAGE_FOR_IMAGE_DESCRIPTION')
                                                        </div>
                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                    </div>

                                    @else
                                    <div class="row ">
                                        <div class="col-md-7">
                                            {!! Form::hidden('id',$target->id) !!}
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-offset-1 col-md-7">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                                                            <div class="col-md-7">
                                                                {!! Form::text('name', $target->name, ['id'=> 'name', 'class' => 'form-control','autocomplete'=>'off']) !!}
                                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5" for="designation">@lang('label.DESIGNATION') :<span class="text-danger"> *</span></label>
                                                            <div class="col-md-7">
                                                                {!! Form::text('designation', $target->designation, ['id'=> 'designation', 'class' => 'form-control','autocomplete'=>'off']) !!}
                                                                <span class="text-danger">{{ $errors->first('designation') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5" for="seal">@lang('label.SEAL') :&nbsp;&nbsp;</label>
                                                            <div class="col-md-7">
                                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;">
                                                                        @if(!empty($target->seal))
                                                                        <img src="{{URL::to('/')}}/public/img/signatoryInfo/{{$target->seal}}" alt="{{ $target->name}}"/>
                                                                        @else
                                                                        <img src="{{URL::to('/')}}/public/img/no_image.png" alt="">
                                                                        @endif
                                                                    </div>
                                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 100px; max-height: 100px;"> </div>
                                                                    <div>
                                                                        <span class="btn default btn-file">
                                                                            <span class="fileinput-new"> @lang('label.SELECT_IMAGE') </span>
                                                                            <span class="fileinput-exists"> @lang('label.CHANGE') </span>
                                                                            {!! Form::file('seal',['id'=> 'seal']) !!}
                                                                        </span>
                                                                        <span class="help-block text-danger">{{ $errors->first('seal') }}</span>
                                                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> @lang('label.REMOVE') </a>
                                                                    </div>
                                                                </div>
                                                                <div class="clearfix margin-top-10">
                                                                    <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.SIGNATORY_INFO_IMAGE_FOR_IMAGE_DESCRIPTION')
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 ">
                                            <div class="table-responsive form-actions">
                                                <table class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr class="center">
                                                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                                            <th class="vcenter">@lang('label.NAME')</th>
                                                            <th class="vcenter">@lang('label.DESIGNATION')</th>
                                                            <th class="text-center vcenter">@lang('label.IMAGE')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!$targetArr->isEmpty())
                                                        <?php
                                                        $sl = 0;
                                                        ?>
                                                        @foreach($targetArr as $target)
                                                        <tr>
                                                            <td class="text-center vcenter"> {{ ++$sl }}</td>
                                                            <td class="vcenter"> {{ $target->name }}</td>
                                                            <td class="vcenter">{{ $target->designation }} </td>
                                                            <td class="text-center vcenter"> <img src="{{'public/img/signatoryInfo/'.$target->seal }}" style="width:50px; height: 50px;">

                                                            </td>


                                                        </tr>
                                                        @endforeach
                                                        @else
                                                        <tr>
                                                            <td colspan="8">@lang('label.NO_KONITA_BANK_ACCOUNT_FOUND')</td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-{{ $colMd }} col-md-8">
                                                @if(!empty($userAccessArr[8][2]))
                                                <button class="btn btn-circle green" id="signatoryInfoSubmit" type="submit">
                                                    <i class="fa fa-check"></i> @lang('label.SUBMIT')
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                            <!-- EOF:: signatory info tab -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('keyup', '#gEmbed', function () {
            var map = $(this).val();
            $('.map-view').html('<iframe src="' + map + '" width="100%" height="220" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>');
        });


        $('#address').summernote({
            placeholder: 'Address',
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

        $(document).on("click", ".add-bank-account", function () {
            $.ajax({
                url: "{{ route('configuration.index') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#newBankAccount").prepend(res.html);
                    $(".tooltips").tooltip();
                },
            });
        });


        //function for save signatory info data
        $(document).on("click", "#signatoryInfoSubmit", function (e) {
            e.preventDefault();
            // Serialize the form data
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: "@lang('label.DO_YOU_WANT_TO_SUBMIT')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('label.SUBMIT')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    var formData = new FormData($('#signatoryInfoFormData')[0]);
                    $.ajax({
                        url: "{{ route('configuration.store') }}",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            // similar behavior as an HTTP redirect
                            setTimeout(window.location.replace('{{ route("configuration.index")}}'), 3000);
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
                            $("#submitSupplier").prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }

            });
        });//EOF- save signatory inof data

        //add multiple phone number
        $(document).on("click", ".add-phone-number", function () {
            $.ajax({
                url: "{{ URL::to('admin/configuration/addPhoneNumber')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                },
                success: function (res) {
                    $("#addPhoneNumberRow").prepend(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //After Click to Save company information
        $(document).on("click", "#saveCompanyInfo", function (e) {
            e.preventDefault();
            var formData = new FormData($('#companyInfoFormData')[0]);
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
                        url: "{{ URL::to('admin/configuration/saveCompanyInfo')}}",
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            setTimeout(() => {
                                location.reload();
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

                            App.unblockUI();
                        }
                    });
                }
            });
        });

        //remove  row
        $('.remove-phone-number-row').on('click', function () {
            $(this).parent().parent().remove();
            return false;
        });

    });
</script>
@stop
