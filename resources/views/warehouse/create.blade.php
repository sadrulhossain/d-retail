@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.CREATE_WAREHOUSE')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => 'admin/warehouse/store','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>

                        <div class="form-group" id="cwh">
                            <label class="control-label col-md-4" for="allowedForCwh">@lang('label.ALLOWED_FOR_CWH') :</label>
                            <div class="col-md-8 checkbox-center md-checkbox has-success">
                                {!! Form::checkbox('allowed_for_central_warehouse',1,null, ['id'=> 'allowedForCwh', 'class' => 'make-switch allowed-for-cwh','data-on-text'=> "Yes",'data-off-text'=>"No"]) !!}
                                <!--<span class="text-success">Put tick to mark as allowed</span>-->
                            </div>
                        </div>

                        <div id="">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="divisionId">@lang('label.DIVISION') :</label>
                                <div class="col-md-8" id="showDivision">
                                    {!! Form::select('division_id', $divisionList, null, ['class' => 'form-control js-source-states', 'id' => 'divisionId']) !!}
                                    <span class="text-danger">{{ $errors->first('division_id') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="districtId">@lang('label.DISTRICT') :</label>
                                <div class="col-md-8" id="showDistrict">
                                    {!! Form::select('district_id', $districtList, null, ['class' => 'form-control js-source-states', 'id' => 'districtId']) !!}
                                    <span class="text-danger">{{ $errors->first('district_id') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="thanaId">@lang('label.THANA') :</label>
                                <div class="col-md-8" id="showThana">
                                    {!! Form::select('thana_id', $thanaList, null, ['class' => 'form-control js-source-states', 'id' => 'thanaId']) !!}
                                    <span class="text-danger">{{ $errors->first('thana_id') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="address">@lang('label.ADDRESS') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::textarea('address', null, array('id'=> 'address', 'class' => 'form-control', 'rows' => '50')) !!}
                                <span class="text-danger">{{ $errors->first('address') }}</span>
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

                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="submit">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/admin/warehouse'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>

<link href="{{asset('public/css/website/summernote.min.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('public/js/website/summernote.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('#address').summernote();

    $('.allowed-for-cwh').on('switchChange.bootstrapSwitch', function () {
        if ($(this).prop("checked") == true) {
            $('#hideForCentralWarehouse').hide();
        } else {
            $('#hideForCentralWarehouse').show();
        }
    });

    //division wise district
    $(document).on('change', '#divisionId', function () {
        var divisionId = $(this).val();
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        $.ajax({
            url: '{{URL::to("admin/warehouse/getDistrictToCreate/")}}',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                division_id: divisionId
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#showDistrict').html(res.html);
                $('.js-source-states').select2();
                App.unblockUI();
            }

        });
    });

    //district wise thana
    $(document).on('change', '#districtId', function () {
        var districtId = $(this).val();
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        $.ajax({
            url: '{{URL::to("admin/warehouse/getThanaToCreate/")}}',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                district_id: districtId
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#showThana').html(res.html);
                $('.js-source-states').select2();
                App.unblockUI();
            }

        });
    });

    //START:: Ajax for Allow User as CRM Leader
    $("#allowedForCwh").bootstrapSwitch({
        offColor: 'danger'
    });

    $('#allowedForCwh').on('switchChange.bootstrapSwitch', function () {
        if ($(this).prop("checked") == true) {
            $.ajax({
                url: "{{ route('warehouse.getCheckCwh') }}",
                type: "POST",
                data: {
                    val: '1',
                },
                dataType: 'json', // what to expect back from the PHP script, if anything
                headers: {
                    'X-CSRF-TOKEN': $('meta[nameDO_YOU_WANT_TO_CHANGE_IT="csrf-token"]').attr('content')
                },
                success: function (res) {
                    swal({
                        title: "'" + res['name'] + "' @lang('label.IS_ALREADY_ADDED_AS_CENTRAL_WAREHOUSE')",
                        text: "@lang('label.DO_YOU_WANT_TO_CHANGE_IT')",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "@lang('label.YES_CHANGE_IT')",
                        closeOnConfirm: true,
                        closeOnCancel: true,
                    }, function (isConfirm) {
                        if (isConfirm) {
                            return true;
                        } else {
                            $("#allowedForCwh").bootstrapSwitch('state', false);
                        }
                    });
                },
            });
        }

    });
//END:: Ajax for Allow User as CRM Leader
});


</script>
@stop