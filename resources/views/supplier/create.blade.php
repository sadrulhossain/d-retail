@extends('layouts.default.master')
@section('data_count')
<?php
$v3 = 'z' . uniqid();
?>
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.CREATE_SUPPLIER')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'supplierForm')) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <h3 class="form-section title-section bold">@lang('label.SUPPLIER_INFORMATION')</h3>
                    <div class="col-md-offset-1 col-md-7">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control','autocomplete' => 'off']) !!}
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="supplier_code">@lang('label.CODE') :</label>
                            <div class="col-md-8">
                                {!! Form::text('code', null, ['id'=> 'code', 'class' => 'form-control','autocomplete' => 'off']) !!}
                                <span class="text-danger">{{ $errors->first('code') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="address">@lang('label.ADDRESS') :</label>
                            <div class="col-md-8">
                                {{ Form::textarea('address', null, ['id'=> 'address', 'class' => 'form-control','size' => '30x5','autocomplete' => 'off']) }}
                                <span class="text-danger">{{ $errors->first('address') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control js-source-states-2', 'id' => 'status']) !!}
                                <span class="text-danger">{{ $errors->first('status') }}</span>
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

                                <?php
                                $v3 = 'z' . uniqid();
                                $v4 = 'z' . uniqid();
                                ?>
                                <div class="col-md-12 contact-person-div">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3 contact-div">
                                                    {!! Form::text('contact_name['.$v3.']', null, ['id'=> 'contactName'.$v3,'class' => 'focus-input']) !!}
                                                    <label class="floating-label" id="spanName_{{$v3}}">@lang('label.NAME') <span class="text-danger"> *</span></label>
                                                </div>
                                                <div class="col-md-3 contact-div">
                                                    {!! Form::select('designation_id['.$v3.']',$designationList, null, ['class' => 'form-control designation-id js-source-states', 'id' => 'designationId'.$v3,'data-width' => '100%']) !!}
                                                    <label class="floating-label" id="spanDeg_{{$v3}}">@lang('label.DESIGNATION') </label>
                                                </div>
                                                <div class="col-md-3 contact-div">
                                                    {!! Form::email('contact_email['.$v3.']', null, ['id'=> 'contactEmail'.$v3,'class' => 'focus-input']) !!}
                                                    <label class="floating-label" id="spanEmail_{{$v3}}">@lang('label.EMAIL') <span class="text-danger"> *</span></label>
                                                </div>
                                                <div class="col-md-3 contact-div">
                                                    {!! Form::textarea('special_note['.$v3.']', null, ['id'=> 'specialNote'.$v3, 'class' => 'focus-input', 'autocomplete' => 'off', 'size' => '40x1' ]) !!}
                                                    <label class="floating-label" id="spanNote_{{$v3}}">@lang('label.SPECIAL_NOTE')</label>
                                                </div>

                                            </div>
                                            <br/>
                                            <div class="row">
                                                <div class="col-md-3  contact-div">
                                                    {!! Form::text('contact_phone['.$v3.']['.$v4.']', null, ['id'=> 'contactPhone'.$v3,  'class' => ' focus-input']) !!}
                                                    <label class="floating-label" id="spanPhone_{{$v3}}">@lang('label.PHONE') <span class="text-danger"> *</span></label>
                                                </div>
                                                <div class="col-md-1 margin-top-10">
                                                    <button class="btn btn-inline green-haze add-phone-number tooltips" data-key="{{$v3}}" data-placement="right" title="@lang('label.ADD_NEW_PHONE_NUMBER')" type="button">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                                <div id="addPhoneNumberRow{{$v3}}"></div>

                                            </div>
                                            <br/>
                                        </div>
                                    </div>
                                </div>
                                <div class="" id="newContactPerson"> </div>
                            </div>
                        </div>
                        <!-- END:: Contact Person Data -->
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-8">
                            <button class="btn btn-circle green" type="button" id='submitSupplier'>
                                <i class="fa fa-check"></i> @lang('label.SUBMIT')
                            </button>
                            <a href="{{ URL::to('admin/supplier'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
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
                url: "{{ route('supplier.contactPersonToCreate') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#newContactPerson").prepend(res.html);
                    $(".tooltips").tooltip();
                },
            });

        });

        //Function for Save Supplier Data
        $(document).on("click", "#submitSupplier", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            // Serialize the form data
            var formData = new FormData($('#supplierForm')[0]);

            $.ajax({
                url: "{{ route('supplier.store') }}",
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
                    setTimeout(
                            window.location.replace('{{ route("supplier.index")}}'
                                    ), 7000);

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
            });

        });

        //add multiple phone number
        $(document).on("click", ".add-phone-number", function () {
            var key = $(this).attr("data-key");

            $.ajax({
                url: "{{ URL::to('admin/supplier/addPhoneNumber')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    key: key,
                },
                success: function (res) {
                    $("#addPhoneNumberRow"+key).prepend(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

    });
</script>
@stop
