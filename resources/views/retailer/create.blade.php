@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.CREATE_RETAILER')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'retailerCreateForm')) !!}
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
                        <div class="form-group">
                            <label class="control-label col-md-4" for="code">@lang('label.CODE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('code', null, ['id'=> 'code', 'class' => 'form-control']) !!}
                                <span class="text-danger">{{ $errors->first('code') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="type">@lang('label.TYPE') :</label>
                            <div class="col-md-8">
                                {!! Form::select('type', $typeList, $target->type ?? null, ['class' => 'form-control js-source-states', 'id' => 'type']) !!}
                                <span class="text-danger">{{ $errors->first('type') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="clusterId">@lang('label.CLUSTER') :</label>
                            <div class="col-md-8">
                                {!! Form::select('cluster_id', $clusterList, $target->cluster_id ?? null, ['class' => 'form-control js-source-states', 'id' => 'clusterId']) !!}
                                <span class="text-danger">{{ $errors->first('cluster_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="zoneId">@lang('label.ZONE') :</label>
                            <div class="col-md-8" id="zoneListDiv">
                                {!! Form::select('zone_id', $zoneList, $target->zone_id ?? null, ['class' => 'form-control js-source-states', 'id' => 'zoneId']) !!}
                                <span class="text-danger">{{ $errors->first('zone_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="longitude">@lang('label.LONGITUDE') :</label>
                            <div class="col-md-8">
                                {!! Form::text('longitude', null, ['id'=> 'longitude', 'class' => 'form-control']) !!}
                                <span class="text-danger">{{ $errors->first('longitude') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="latitude">@lang('label.LATITUDE') :</label>
                            <div class="col-md-8">
                                {!! Form::text('latitude', null, ['id'=> 'latitude', 'class' => 'form-control']) !!}
                                <span class="text-danger">{{ $errors->first('latitude') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="address">@lang('label.ADDRESS') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::textarea('address', null, array('id'=> 'address', 'class' => 'form-control', 'size' => '30x5')) !!}
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

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="logo">@lang('label.LOGO') :</label>
                            <div class="col-md-9">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">

                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                    <div>
                                        <span class="btn red btn-outline btn-file">
                                            <span class="fileinput-new"> @lang('label.SELECT_IMAGE') </span>
                                            <span class="fileinput-exists"> @lang('label.CHANGE') </span>
                                            {!! Form::file('logo',['id'=> 'logo']) !!}
                                        </span>
                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                    </div>
                                </div>
                                <div class="clearfix margin-top-10">
                                    <span class="label label-success">@lang('label.NOTE')</span>&nbsp;<span class="text-danger bold">@lang('label.SUPPLIER_IMAGE_FOR_IMAGE_DESCRIPTION')</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- START:: Contact Person Data -->
                    <div class="col-md-12">
                        <h3 class="form-section title-section bold">@lang('label.CONTACT_PERSON')</h3>
                        <div class="form-body">
                            <div class="form-group">
                                <div class="col-md-1">
                                    <button  type="button" class="btn purple-soft add-contact-person tooltips" title="@lang('label.CLICK_HERE_TO_ADD_MORE_CONTACT_PERSON')">
                                        @lang('label.ADD_CONTACT_PERSON')&nbsp; <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                                <div class="" id="newContactPerson"> </div>
                                <?php
                                $v3 = 'z' . uniqid();
                                ?>
                                <div class="col-md-12 contact-person-div">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4 contact-div">
                                                    {!! Form::text('contact_name['.$v3.']', null, ['id'=> 'contactName'.$v3,'class' => 'focus-input']) !!}
                                                    <label class="floating-label" id="spanName_{{$v3}}">@lang('label.NAME') <span class="text-danger"> *</span></label>
                                                </div>
                                                <div class="col-md-4 contact-div">
                                                    {!! Form::text('contact_phone['.$v3.']', null, ['id'=> 'contactPhone'.$v3,'class' => 'integer-only focus-input']) !!}
                                                    <label class="floating-label" id="spanPhone_{{$v3}}">@lang('label.PHONE') <span class="text-danger"> *</span></label>
                                                </div>
                                                <div class="col-md-4 contact-div">
                                                    {!! Form::textarea('remarks['.$v3.']', null, ['id'=> 'remarks'.$v3, 'class' => 'focus-input', 'autocomplete' => 'off', 'size' => '40x1' ]) !!}
                                                    <label class="floating-label" id="spanRemarks_{{$v3}}">@lang('label.REMARKS')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END:: Contact Person Data -->

                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-8">
                            <button class="btn btn-circle green" type="button" id="submitRetailer">
                                <i class="fa fa-check"></i> @lang('label.SUBMIT')
                            </button>
                            <a href="{{ URL::to('/admin/retailer'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
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
        $(document).on("click", ".add-contact-person", function () {
            $.ajax({
                url: "{{ route('retailer.createContactPerson') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#newContactPerson").prepend(res.html);
                    $(".tooltips").tooltip();
                }
            });

        });

        //Function for Save Retailer Data
        $(document).on("click", "#submitRetailer", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            // Serialize the form data
            var formData = new FormData($('#retailerCreateForm')[0]);
            swal({
                title: 'Are you sure?',
                text: "You can not undo this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ route('retailer.store') }}",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function () {
                            $('#submitRetailer').prop('disabled', true);
                            toastr.info("Loading...", "Please Wait.", options);
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            // similar behavior as an HTTP redirect
                            function explode() {
                                window.location.replace('{{ route("retailer.index")}}');
                            }
                            setTimeout(explode, 2000);
                            $('#submitRetailer').prop('disabled', false);

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
                            $('#submitRetailer').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });
        $(document).on("change","#clusterId",function(e){
           var clusterId = $(this).val();
           
           $.ajax({
                url: "{{ URL::to('admin/retailer/getZone')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    clusterId: clusterId
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#zoneListDiv").html(res.html);
                    $('.js-source-states').select2();
                    App.unblockUI();
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
                    App.unblockUI();
                }
            }); //ajax end
        });



    });
</script>
@stop
