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
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">							
            <div class=" main-content-area">
                <div class="wrap-login-item ">
                    <div class="login-form form-item form-stl-2">
                        {!! Form::open(['route' => 'customer.authenticate' , 'id' => 'buyerForm' ,'group' => 'form', 'class' => 'form-horizontal']) !!}
                        {!! Form::hidden('go_to_payment', 1) !!}
                        @csrf
                        <fieldset class="wrap-title">
                            @include('layouts.flash')
                            <h3 class="form-title">@lang('label.LOGIN_TO_YOUR_ACCOUNT')</h3>										
                        </fieldset>
                        <fieldset class="wrap-input">
                            <label for="username">@lang('label.MOBILE')<span class="required"><span class="required"> *</span></span></label>
                            {!! Form::text('username', null, ['id'=> 'username', 'class' => 'form-control','autocomplete' => 'off','placeholder' => '+88']) !!}
                        </fieldset>
                        <fieldset class="wrap-input">
                            <label for="password">@lang('label.PASSWORD')<span class="required"><span class="required"> *</span></span></label>
                            {!! Form::password('password', ['id'=> 'password', 'class' => 'form-control','autocomplete' => 'off','placeholder' => 'Enter Password']) !!} 

                        </fieldset>

                        <fieldset class="wrap-input">
                            <input type="submit" class="btn btn-submit login-btn" value="Login" name="submit">
                            <a class="link-function left-position" href="#" title="Forgotten password?">@lang('label.FORGOT_PASSWORD')</a>
                            <div class="social-login row">
                                <div class="col-md-6">
                                    <div id="googleButton"></div>
                                </div>
                                <div class="col-md-6">
                                    <a onclick="fbLogin();" class="fb-button"><i class="fa fa-facebook fa-fw"></i> @lang('label.LOGIN_WITH_FACEBOOK')</a>
                                </div>
                            </div>
                        </fieldset>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div><!--end main products area-->			
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">							
            <div class=" main-content-area form-stl-3">
                <div class="wrap-login-item ">
                    <div class="register-form form-item">
                        {!! Form::open(['route' => 'customer.store' , 'id' => 'buyerForm' ,'group' => 'form', 'class' => 'form-horizontal']) !!}
                        {!! Form::hidden('go_to_payment', 1) !!}
                        {!! Form::hidden('this_route', 'loginAndRegister') !!}
                        @csrf
                        <fieldset class="wrap-title">
                            <h3 class="form-title">@lang('label.CREATE_AN_ACCOUNT')</h3>
                        </fieldset>		

                        <fieldset class="wrap-input">
                            <label for="frm-reg-lname">@lang('label.NAME') (@lang('label.OPTIONAL'))</label>
                            {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control','autocomplete' => 'off','placeholder' => 'Enter Name']) !!}
                            <span class="required">{{ $errors->first('name') }}</span>
                        </fieldset>

                        <!--                        <fieldset class="wrap-input">
                                                    <label for="frm-reg-email">@lang('label.PHONE')<span class="required"> *</span></label>
                                                    {!! Form::text('phone', null, ['id'=> 'phone', 'class' => 'form-control','autocomplete' => 'off','placeholder' => '+880']) !!}
                                                    <span class="required">{{ $errors->first('phone') }}</span>
                                                </fieldset>-->
                        <fieldset class="wrap-input">
                            <label for="frm-reg-email">@lang('label.EMAIL')<span class="required"> *</span></label>
                            {!! Form::text('email', null, ['id'=> 'email', 'class' => 'form-control','autocomplete' => 'off','placeholder' => 'Enter Email']) !!}
                            <span class="required">{{ $errors->first('email') }}</span>
                        </fieldset>
                        <!--                        <fieldset class="wrap-functions ">
                                                    <label class="remember-field">
                                                        <input name="newletter" id="new-letter" value="forever" type="checkbox"><span>Sign Up for Newsletter</span>
                                                    </label>
                                                </fieldset>-->
                        <fieldset class="wrap-title">
                            <h3 class="form-title">@lang('label.LOGIN_INFORMATION')</h3>
                        </fieldset>
                        <fieldset class="wrap-input">
                            <label for="frm-reg-lname">@lang('label.MOBILE')<span class="required"> *</span></label>
                            {!! Form::text('username', null, ['id'=> 'username', 'class' => 'form-control','autocomplete' => 'off','placeholder' => '+88']) !!}
                            <span class="required">{{ $errors->first('username') }}</span>
                        </fieldset>
                        <fieldset class="wrap-input item-width-in-half left-item ">
                            <label for="frm-reg-pass">@lang('label.PASSWORD')<span class="required"> *</span></label>
                            {!! Form::password('password', ['id'=> 'password', 'class' => 'form-control','autocomplete' => 'off','placeholder' => 'Enter Password']) !!} 
                            <span class="required">{{ $errors->first('password') }}</span>
                            <div class="clearfix margin-top-10">
                                <span class="label label-danger">@lang('label.NOTE')</span>
                                @lang('label.COMPLEX_PASSWORD_INSTRUCTION')
                            </div>
                        </fieldset>
                        <fieldset class="wrap-input item-width-in-half ">
                            <label for="frm-reg-cfpass">@lang('label.CONFIRM_PASSWORD')</label><span class="required"> *</span>
                            {!! Form::password('conf_password', ['id'=> 'confPassword', 'class' => 'form-control','autocomplete' => 'off','placeholder' => 'Enter Confirm Password']) !!}
                            <span class="required">{{ $errors->first('conf_password') }}</span>
                        </fieldset>
                        <button class="btn btn-sign" type="submit" id='submitBuyer'>
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        {!! Form::close() !!}
                    </div>											
                </div>
            </div><!--end main products area-->		
        </div>
    </div><!--end row-->

</div><!--end container-->
<script src="{{asset('public/js/fbLogin.js')}}"></script>
<script>
    
    // Facebook Login Area

// Facebook login with JavaScript SDK
    function fbLogin() {
        FB.login(function (response) {
            if (response.authResponse) {
                // Get and display the user profile data
               getFbUserData();
                
            } else {
                // There was an error.
            }
        }, {scope: 'email'});
    }

// Fetch the user profile data from facebook
    function getFbUserData() {
        FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,name,email,picture'},
                function (response) {
                    var fbId = response.id;
                    var email = response.email;
                    var fullName = response.name;
                    var photo = response.picture.data.url;
                    var options = {
                        closeButton: true,
                        debug: false,
                        positionClass: "toast-bottom-right",
                        onclick: null
                    };
                    $.ajax({
                        url: "{{route('customer.facebookLogin')}}",
                        type: "POST",
                        datatype: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            fb_id: fbId,
                            full_name: fullName,
                            email: email,
                            photo:photo,
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            window.location.href = "{{url('/checkout')}}";
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
                                toastr.error(jqXhr.responseJSON.message, 'Error', options);
                            } else {
                                toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                            }
                        }

                    });
                });
    }
// End Facebook Login Area

// Google Login Area
    function renderButton() {
        gapi.signin2.render('googleButton', {
            'scope': 'profile email',
            'width': 240,
            'height': 50,
            'longtitle': true,
            'theme': 'dark'
        });
    }
    $('#googleButton').click(function () {
        auth2.grantOfflineAccess().then(signInCallback);
    });

    function signInCallback(authResult) {
        if (authResult['code']) {
            setTimeout(function () {
                if (auth2.isSignedIn.get()) {
                    var profile = auth2.currentUser.get().getBasicProfile();
                    var googleId = profile.getId();
                    var fullName = profile.getName();
                    var email = profile.getEmail();
                    var photo = profile.getImageUrl();
                    var options = {
                        closeButton: true,
                        debug: false,
                        positionClass: "toast-bottom-right",
                        onclick: null
                    };
                    $.ajax({
                        url: "{{route('customer.googleLogin')}}",
                        type: "POST",
                        datatype: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            google_id: googleId,
                            full_name: fullName,
                            email: email,
                            photo:photo,
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            window.location.href = "{{url('/checkout')}}";
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
                                toastr.error(jqXhr.responseJSON.message, 'Error', options);
                            } else {
                                toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                            }
                        }

                    });
                }
            }, 1000);

        } else {
            // There was an error.
        }
    }
    // End Google Login Area
</script>
@stop
