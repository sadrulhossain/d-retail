@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.EDIT_BUYER')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target,['route' => array('customer.update', $target->id), 'method' => 'POST','class' => 'form-horizontal', 'id' => 'customerEditForm']) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {!! Form::hidden('id', $target->id) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="form-section title-section bold">@lang('label.BUYER_INFORMATION')</h3>
                        <div class="col-md-offset-1 col-md-7">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control']) !!} 
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4" for="email">@lang('label.EMAIL') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::text('email', null, ['id'=> 'email', 'class' => 'form-control']) !!} 
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4" for="phone">@lang('label.PHONE') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::text('phone', null, ['id'=> 'phone', 'class' => 'form-control']) !!} 
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="countryId">@lang('label.COUNTRY') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::select('country_id', $countryList, null, ['class' => 'form-control js-source-states', 'id' => 'countryId']) !!}
                                    <span class="text-danger">{{ $errors->first('country_id') }}</span>
                                </div>
                            </div>
                            <div class="form-group" id="division">
                                <label class="control-label col-md-4" for="divisionId">@lang('label.DIVISION') :</label>
                                <div class="col-md-8" id="showDivision">
                                    {!! Form::select('division_id', $divisionList, null, ['class' => 'form-control js-source-states', 'id' => 'divisionId']) !!}
                                    <span class="text-danger">{{ $errors->first('division_id') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4" for="code">@lang('label.CODE') :</label>
                                <div class="col-md-8">
                                    {!! Form::text('code', null, ['id'=> 'code', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                                    <span class="text-danger">{{ $errors->first('code') }}</span>
                                </div>
                            </div>
                            
                            
                            <div class="form-group">
                                <label class="control-label col-md-4" for="gMapEmbedCode">@lang('label.GMAP_EMBED_CODE') :</label>
                                <div class="col-md-8">
                                    {{ Form::textarea('gmap_embed_code', null, ['id'=> 'gMapEmbedCode', 'class' => 'form-control','size' => '30x5','autocomplete' => 'off','placeholder' => __('label.GMAP_EMBED_CODE_PLACEHOLDER')]) }}
                                    <span class="text-danger">{{ $errors->first('gmap_embed_code') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                                <div class="col-md-8">
                                    {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], null, ['class' => 'form-control js-source-states-2', 'id' => 'status']) !!}
                                    <span class="text-danger">{{ $errors->first('status') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- START:: Contact Person Data -->
                    
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="submit" id="submitEditBuyer">
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
<script type="text/javascript">
    $(document).ready(function () {

        //country wise division
        $(document).on('change', '#countryId', function () {
            var countryId = $(this).val();
            if(countryId == '18'){
                $("#division").show(100);
            }else{
                $("#division").hide(100);
            }
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: '{{URL::to("buyer/getDivision/")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    country_id: countryId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showDivision').html(res.html);
                    $('.js-source-states').select2();
                    App.unblockUI();
                }

            });
        });

    });
</script>
@stop