@extends('frontend.layouts.default.master')
@section('content')

<div class="container">

    <div class="wrap-breadcrumb">
        <ul>
            <li class="item-link"><a href="#" class="link">home</a></li>
            <li class="item-link"><span>Register</span></li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3 class="form-title text-center bold">@lang('label.CREATE_AN_ACCOUNT')</h3>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class=" main-content-area">
                <div class="wrap-login-item ">
                    <div class="register-form form-item ">
                        {!! Form::open(['id' => 'registerForm', 'group' => 'form', 'class' => 'form-horizontal']) !!}
                        {!! Form::hidden('go_to_payment', 1) !!}
                        {!! Form::hidden('this_route', 'register') !!}
                        {!! Form::hidden('order', $order) !!}
                        @csrf
                        <div class="row margin-left-right-mns-30">
                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                                <div class="form">
                                    <div class="col-md-12">
                                        <h3 class="form-title text-center">@lang('label.BASIC_INFORMATION')</h3>
                                    </div>
                                </div>
                                <div class="form">
                                    <div class="col-md-12">
                                        <label for="name">@lang('label.NAME')<span class="required">*</span></label>
                                        {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => 'Enter name']) !!}
                                        <span class="required">{{ $errors->first('name') }}</span>
                                    </div>
                                </div>
                                <div class="form">
                                    <div class="col-md-6">
                                        <label for="email">@lang('label.EMAIL')<span class="required">*</span></label>
                                        {!! Form::text('email', null, ['id' => 'email', 'class' => 'form-control', 'placeholder' => 'Enter Email']) !!}
                                        <span class="required">{{ $errors->first('email') }}</span>
                                    </div>
                                </div>
                                <div class="form">
                                    <div class="col-md-6">
                                        <label for="phone">@lang('label.PHONE')<span class="required">*</span></label>
                                        {!! Form::text('phone', null, ['id' => 'phone', 'class' => 'form-control', 'placeholder' => '01XXX-XX-XX-XX']) !!}
                                        <span class="required" id="phoneSpan"></span>
                                    </div>
                                </div>
                                <div class="form">
                                    <div class="col-md-6">
                                        <label for="type">@lang('label.TYPE') <span class="text-danger"> *</span></label>
                                        {!! Form::select('type', $typeList, null, ['class' => 'form-control js-source-states', 'id' => 'type']) !!}
                                        <span class="required">{{ $errors->first('type') }}</span>
                                    </div>
                                </div>
                                <div class="form">
                                    <div class="col-md-6">
                                        <label for="code">@lang('label.CODE') <span class="text-danger">*</span></label>
                                        {!! Form::text('code', null, ['id' => 'code', 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Enter code']) !!}
                                        <span class="required">{{ $errors->first('code') }}</span>
                                    </div>
                                </div>
                                <div class="form">
                                    <div class="col-md-12">
                                        <label for="latitude">@lang('label.ADDRESS')<span class="required">*</span></label>
                                        {!! Form::textarea('address', null, ['id' => 'address', 'class' => 'form-control', 'size' => '30x3']) !!}
                                        <span class="required">{{ $errors->first('address') }}</span>
                                    </div>

                                </div>

                                <div class="form">
                                    <div class="col-md-6">
                                        <label for="type">@lang('label.CLUSTER') </label>
                                        {!! Form::select('cluster_id', $clusterList, null, ['class' => 'form-control js-source-states', 'id' => 'clusterId']) !!}
                                        <span class="required">{{ $errors->first('cluster_id') }}</span>
                                    </div>
                                </div>
                                <div class="form">
                                    <div class="col-md-6">
                                        <label for="zone">@lang('label.ZONE') </label>
                                        <div id="zoneListDiv">
                                            {!! Form::select('zone_id', $zoneList, null, ['class' => 'form-control js-source-states', 'id' => 'zoneId']) !!}
                                        </div>
                                        <span class="required">{{ $errors->first('zone_id') }}</span>
                                    </div>
                                </div>


                                <!--                                <div class="form">
                                                                    <div class="col-md-6">
                                                                        <label for="longitude">@lang('label.LONGITUDE') </label>
                                                                        {!! Form::text('longitude', null, ['id'=> 'longitude', 'class' => 'form-control']) !!}
                                                                        <span class="required">{{ $errors->first('longitude') }}</sp                            an>
                                                                    </div>
                                                                </div>
                                                                <div class="form">
                                                                    <div class="col-md-6">
                                                                        <label for="latitude">@lang('label.LATITUDE')</label>
                                                                        {!! Form::text('latitude', null, ['id'=> 'latitude', 'class' => 'form-control']) !!}
                                                                        <span class="required">{{ $errors->first('latitude') }}</span>
                                                                    </div>
                                                                </div> -->



                            </div>


                            <!--ADDITIONAL_INFORMATION Start-->

                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                                <div class="form">
                                    <div class="col-md-12">
                                        <h3 class="form-title text-center">@lang('label.ADDITIONAL_INFORMATION')</h3>
                                    </div>
                                </div>
                                <div class="form">
                                    <div class="col-md-12">
                                        <label for="ownerName">@lang('label.OWNER_NAME') </label>
                                        {!! Form::text('owner_name', null, ['id' => 'ownerName', 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Enter Owner Name']) !!}
                                        <span class="required">{{ $errors->first('owner_name') }}</span>
                                    </div>
                                </div>

                                <div class="form">
                                    <div class="col-md-12">
                                        <label for="nidPassport">@lang('label.NID_PASSPORT') </label>
                                        {!! Form::text('nid_passport', null, ['id' => 'nidPassport', 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Enter ' . __('label.NID_PASSPORT')]) !!}
                                        <span class="required">{{ $errors->first('nid_passport') }}</span>
                                    </div>
                                </div>
                                <div class="form">
                                    <div class="col-md-6">
                                        <label for="infrastructureType">@lang('label.INFRASTRUCTURE_TYPE') </label>
                                        {!! Form::select('infrastructure_type', $infrastructureTypeList, null, ['class' => 'form-control js-source-states', 'id' => 'infrastructureType', 'style' => 'display:block']) !!}
                                        <span
                                            class="required">{{ $errors->first('infrastructure_type') }}</span>
                                    </div>
                                </div>
                                <div class="form">
                                    <div class="col-md-6">
                                        <label for="avgMonthlyTransection">@lang('label.AVG_TRANSACTION') </label>
                                        <div class="col-md-12">
                                            <div class="input-group bootstrap-touchspin width-full">
                                                {!! Form::text('avg_monthly_transaction_value', $target->avg_monthly_transaction_value ?? null, ['id' => 'avgMonthlyTransection', 'class' => 'form-control integer-decimal-only width-full text-right', 'placeholder' => 'Enter Amount']) !!}
                                                <span class="input-group-addon bootstrap-touchspin-postfix bold">
                                                    @lang('label.TK')</span>
                                            </div>
                                            <span
                                                class="text-danger">{{ $errors->first('avg_monthly_transaction_value') }}</span>
                                            <div class="margin-top-10">
                                                <span class="label label-success">@lang('label.NOTE')</span>&nbsp;<span
                                                    class="">@lang('label.AVG_MONTHLY_TRANSACTION_VALUE')</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form">
                                    <div class="col-md-4">
                                        <label for="divisionList">@lang('label.DIVISION') </label>
                                        {!! Form::select('division', $divisionList, $target->division ?? null, ['class' => 'form-control js-source-states', 'id' => 'divisionList']) !!}
                                        <span class="required">{{ $errors->first('division') }}</span>
                                    </div>
                                </div>

                                <div class="form">
                                    <div class="col-md-4">
                                        <label for="districtLIST">@lang('label.DISTRICT') </label>
                                        <div id="districtListDiv">
                                            {!! Form::select('district', $districtList, $target->district ?? null, ['class' => 'form-control js-source-states', 'id' => 'districtList']) !!}
                                        </div>
                                        <span class="required">{{ $errors->first('district') }}</span>
                                    </div>
                                </div>
                                <div class="form">
                                    <div class="col-md-4">
                                        <label for="thanaList">@lang('label.THANA') </label>
                                        <div id="thanaListDiv">
                                            {!! Form::select('thana', $thanaList, $target->thana ?? null, ['class' => 'form-control js-source-states', 'id' => 'thanaList']) !!}
                                        </div>
                                        <span class="required">{{ $errors->first('thana') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!--ADDITIONAL_INFORMATION END-->
                        </div>

                        <!--Login Information start-->
                        <div class="row margin-left-right-mns-30 margin-top-25">
                            <!--LOGIN_INFORMATION Start-->
                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                                <div class="form">
                                    <div class="col-md-12">
                                        <h3 class="form-title text-center">@lang('label.LOGIN_INFORMATION')</h3>
                                    </div>
                                </div>
                                <div class="form">
                                    <div class="col-md-12">
                                        <label for="username">@lang('label.USERNAME') <span class="text-danger">
                                                *</span></label>
                                        {!! Form::text('username', null, ['id' => 'username', 'class' => 'form-control', 'autocomplete' => 'false', 'placeholder' => 'Enter User Name']) !!}
                                        <span class="required">{{ $errors->first('username') }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="frm-reg-pass">@lang('label.PASSWORD')<span class="required">
                                                *</span></label>

                                        {!! Form::password('password', ['id' => 'password', 'class' => 'form-control', 'autocomplete' => 'false', 'placeholder' => 'Enter Password']) !!}

                                        <span class="required">{{ $errors->first('password') }}</span>
                                        <div class="clearfix margin-top-10">
                                            <span class="label label-danger">@lang('label.NOTE')</span>
                                            @lang('label.COMPLEX_PASSWORD_INSTRUCTION')
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="frm-reg-cfpass">@lang('label.CONFIRM_PASSWORD')</label><span
                                            class="required"> *</span>
                                        {!! Form::password('conf_password', ['id' => 'confPassword', 'class' => 'form-control', 'autocomplete' => 'false', 'placeholder' => 'Enter Confirm Password']) !!}
                                        <span class="required">{{ $errors->first('conf_password') }}</span>
                                    </div>
                                </div>
                                <div class="row text-center margin-top-25">
                                    <button class="btn btn-sign green-steel" type="submit" id='proccedButton'>
                                        @lang('label.CONFIRM_AND_PROCEED') <i class="fa fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                            <!--LOGIN_INFORMATION END-->

                            <!--LOGIN_INFORMATION Start-->
                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12" id ="verfityNumberDiv">


                            </div>
                            <!--LOGIN_INFORMATION END-->

                        </div>
                        <!--Login Information End-->
                        <div class="row text-center">
                            <button class="btn btn-sign" type="submit" id='submitRegistrionButton'>
                                <i class="fa fa-check"></i> @lang('label.SUBMIT')
                            </button>
                        </div>
                        <!-- half end -->

                        <!-- half start -->
                        <!-- half end -->
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <!--end main products area-->
        </div>
    </div>
    <!--end row-->

</div>
<!--end container-->
<script type="text/javascript">
    $(document).ready(function () {

        $("#registerForm").submit((e) => e.preventDefault());
        $("#submitRegistrionButton").hide();
//            var name = $("#name").val();
//            var type = $("#type").val();
//            var code = $("#code").val();
//            var cluster = $("#cluster").val();
//            var zone = $("#zone").val();
//            var address = $("#address").val();
//            $("#username").val('');
//            $("#password").val('');
//            $("#confPassword").val('');
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };

        $(document).on("change", "#clusterId", function () {
            var clusterId = $(this).val();

            $.ajax({
                url: "{{ URL::to('frontend/getZone') }}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    cluster_id: clusterId
                },
                success: function (res) {
                    $("#zoneId").html(res.html);
                    $('.js-source-states').select2();
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

                }
            });
            //ajax end
        });

        //GET Division List START
        $(document).on('change', '#divisionList', function (e) {
            e.preventDefault()
            var divisionId = $(this).val();
            // console.log(divisionId);return false;
            //ajax atart
            $.ajax({
                url: "{{ URL::to('frontend/getDistrict') }}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    divisionId: divisionId
                },
                //                beforeSend: function () {
                ////                    $.blockUI({ message: '<h1><img src="busy.gif" /> Just a moment...</h1>' });
                //                },
                success: function (res) {
                    $("#districtListDiv").html(res.html);
                    $("#thanaListDiv").html(res.html2);
                    $('.js-source-states').select2();

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

                }
            }); //ajax end
        });
        //GET Division List END

        //GET Thana List START
        $(document).on('change', '#districtList', function (e) {
            e.preventDefault()
            var thanaId = $(this).val();
            //ajax atart
            $.ajax({
                url: "{{ URL::to('frontend/getThana') }}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    thanaId: thanaId
                },
                // beforeSend: function() {
                //     $.blockUI({
                //         boxed: true
                //     });
                // },
                success: function (res) {
                    $("#thanaListDiv").html(res.html);
                    $('.js-source-states').select2();
                    $.unblockUI();
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
                    $.unblockUI();
                }
            }); //ajax end
        });
        //GET Thana List END
//        $(document).on("blur keyup keydown", "#phone", function (e) {
//            setTimeout(() => {
//                $("#phoneSpan").text('');
//                var phone = $(this).val();
//                var phoneRegex = new RegExp(/(^(\+88|0088)?(01){1}[3456789]{1}(\d){8})$/);
//                if (!phoneRegex.test(phone)) {
//                    $("#phoneSpan").text("Invalid phone number. Example: 01718565655");
//                    $("#phone").focus();
//                }
//            }, 1000);
//        });
        //GET Thana List END
        $(document).on("click", "#proccedButton", function () {

            var name = $("#name").val();
            var email = $("#email").val();
            var phone = $("#phone").val();
            var type = $("#type").val();
            var code = $("#code").val();
            var cluster = $("#cluster").val();
            var zone = $("#zone").val();
            var address = $("#address").val();
            var username = $("#username").val();
            var password = $("#password").val();
            var confPassword = $("#confPassword").val();


            if (!name) {
                toastr.error('Name is required', 'Validation Error', options);
                return false;
            }
            if (!email) {
                toastr.error('Email is required', 'Validation Error', options);
                return false;
            }

            if (!phone) {
                toastr.error('Phone is required', 'Validation Error', options);
                return false;
            }
            if (!code) {
                toastr.error('Code is required', 'Validation Error', options);
                return false;
            }
            if (!type || type == "0") {
                toastr.error('Type is invalid', 'Validation Error', options);
                return false;
            }
            if (!address) {
                toastr.error('Address is required', 'Validation Error', options);
                return false;
            }
            if (!username) {
                toastr.error('username is required', 'Validation Error', options);
                return false;
            }
            if (!password) {
                toastr.error('password is required', 'Validation Error', options);
                return false;
            }
            if (!confPassword) {
                toastr.error('confirm Password is required', 'Validation Error', options);
                return false;
            }
            $.ajax({
                url: "{{ URL::to('/showVerifyNumber') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                data: {
                    phone: phone,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {},
                success: function (res) {
                    $("#verfityNumberDiv").html(res.html);
                    $("#verfityNumberDiv").addClass('otp-verification');
                    $("#proccedButton").hide();
                    $("#submitRegistrionButton").show();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] +
                                    '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading,
                                options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '',
                                options);
                    } else {
                        toastr.error('Error', 'Something went wrong',
                                options);
                    }
                    $('#btnSubmit').prop('disabled', false);

                }
            });

        });
        //GET Thana List END
        $(document).on("keyup keydown input change", "#otpCode", function () {
            $("#invalidOtp").text('');
        });
        $(document).on("click", "#resendOtp", function (e) {

            var resendOtpBtn = $(this);
            var phone = $("#phone").val();
            console.log(phone);

            $.ajax({
                url: "{{ URL::to('/showVerifyNumber') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    phone: phone,
                },
                beforeSend: function () {
                    resendOtpBtn.prop("disabled", true);
                },
                success: function (res) {
                    $("#verfityNumberDiv").html(res.html);
                    $("#verfityNumberDiv").addClass('otp-verification');
                    $("#proccedButton").hide();
                    $("#submitRegistrionButton").show();
                    resendOtpBtn.prop("disabled", false);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] +
                                    '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading,
                                options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '',
                                options);
                    } else {
                        toastr.error('Error', 'Something went wrong',
                                options);
                    }

                    resendOtpBtn.prop("disabled", false);
                }
            });
        });
        
        $(document).on("click", "#submitRegistrionButton", function (e) {

            var submitBtn = $(this);
            var otpCode = $("#otpCode").val();
            var sentOtpCode = $("#sentOtp").val();
            if (otpCode !== sentOtpCode) {
//                toastr.error('Invalid OTP code', 'Validation Error', options);
                $("#invalidOtp").text("Invalid OTP code");
                $("#otpCode").focus();
                return false;
            }
            swal({
                title: 'Are you sure?',
                text: "You can not undo this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Register',
                cancelButtonText: 'No, cancel',
                closeOnConfirm: true,
                closeOnCancel: false
            },
                    function (isConfirm) {
                        if (isConfirm) {

                            // Serialize the form data
                            var form_data = new FormData($('#registerForm')[0]);
                            $.ajax({
                                url: "{{ URL::to('/registerCustomer') }}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                beforeSend: function () {
                                    submitBtn.prop("disabled", true);
                                },
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    setTimeout(() => {
                                        location.replace("{{ URL::to('/login') }}")
                                    }, 1000);
                                    submitBtn.prop("disabled", false);
                                },
                                error: function (jqXhr, ajaxOptions, thrownError) {
                                    if (jqXhr.status == 400) {
                                        var errorsHtml = '';
                                        var errors = jqXhr.responseJSON.message;
                                        $.each(errors, function (key, value) {
                                            errorsHtml += '<li>' + value[0] +
                                                    '</li>';
                                        });
                                        toastr.error(errorsHtml, jqXhr.responseJSON.heading,
                                                options);
                                    } else if (jqXhr.status == 401) {
                                        toastr.error(jqXhr.responseJSON.message, '', options);
                                    } else if (jqXhr.readyState === 0) {
                                        toastr.error('No interner connection', 'Connection Error', options);
                                    } else {
                                        toastr.error('Error', 'Something went wrong', options);
                                    }
                                    submitBtn.prop("disabled", false);

                                }
                            });
                        } else {
                            swal('Cancelled', '', 'error');
                        }
                    });
        });


    });
</script>

@stop
