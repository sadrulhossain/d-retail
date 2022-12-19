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
                        @csrf
                        <fieldset class="wrap-title">
                            @include('layouts.flash')
                            <h3 class="form-title">@lang('label.REQUEST_RECOVERY_PASSWORD')</h3>										
                        </fieldset>
                        <fieldset class="wrap-input">
                            <label for="frm-reg-email">@lang('label.EMAIL')<span class="required"> *</span></label>
                            {!! Form::text('email', null, ['id'=> 'email', 'class' => 'form-control','autocomplete' => 'off','placeholder' => 'Enter Email']) !!}
                            <span class="required">{{ $errors->first('email') }}</span>
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
            url: "{{URL::to('forgotPassword')}}",
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
