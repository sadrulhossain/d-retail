@extends('frontend.layouts.default.master')
@section('content')

<div class="container">
    <div class="wrap-breadcrumb">
        <ul>

        </ul>
    </div>
    <div class="row">
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12 col-md-offset-3">							
            <div class=" main-content-area">

                <div class="wrap-login-item ">
                    <div class="login-form form-item form-stl">
                        {!! Form::open(['url' => '' , 'id' => 'buyerForm' ,'group' => 'form', 'class' => 'form-horizontal']) !!}
                        {!! Form::hidden('ref', $ref) !!}
                        @csrf
                        <fieldset class="wrap-title">
                            @include('layouts.flash')
                            <h3 class="form-title">@lang('label.RESET_PASSWORD')</h3>										
                        </fieldset>
                        <fieldset class="wrap-input">
                            <label for="frm-reg-pass">@lang('label.NEW_PASSWORD')<span class="required"> *</span></label>
                            {!! Form::password('password', ['id'=> 'password', 'class' => 'form-control','autocomplete' => 'off','placeholder' => 'Enter Password']) !!} 
                            <span class="required">{{ $errors->first('password') }}</span>
                            <div class="clearfix margin-top-10">
                                <span class="label label-danger">@lang('label.NOTE')</span>
                                @lang('label.COMPLEX_PASSWORD_INSTRUCTION')
                            </div>
                        </fieldset>
                        <fieldset class="wrap-input">
                            <label for="frm-reg-cfpass">@lang('label.CONFIRM_PASSWORD')</label><span class="required"> *</span>
                            {!! Form::password('conf_password', ['id'=> 'confPassword', 'class' => 'form-control','autocomplete' => 'off','placeholder' => 'Enter Confirm Password']) !!}
                            <span class="required">{{ $errors->first('conf_password') }}</span>
                        </fieldset>
                        <fieldset class="wrap-input">
                            <input type="button" class="btn btn-submit login-btn" value="Submit" name="submit">
                        </fieldset>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div><!--end main products area-->		
        </div>
    </div><!--end row-->

</div><!--end container-->
<script src="{{asset('public/assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
<script>

$(function () {
    var options = {
        closeButton: true,
        debug: false,
        positionClass: "toast-bottom-right",
        onclick: null
    };
    $(document).on('click', '.btn-submit', function (e) {
        e.preventDefault();
        var form_data = new FormData($('#buyerForm')[0]);

        $.ajax({
            url: "{{URL::to('resetPassword')}}",
            type: "POST",
            datatype: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            beforeSend: function () {
                $('.btn-submit').prop('disabled', true);
            },
            success: function (res) {
                $('.btn-submit').prop('disabled', false);
                toastr.success(res.message, res.heading, options);
                setTimeout(function () {
                    window.location.href = "{{ route('customerLogin')}}";
                }, 1000);
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
                $('.btn-submit').prop('disabled', false);
            }

        });
    });
});
</script>
@stop
