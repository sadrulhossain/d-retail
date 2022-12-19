
<div class="form">
    <div class="col-md-12">
        <h3 class="form-title text-center">@lang('label.ENTER_OTP_CODE')</h3>
    </div>
</div>
<div class="form">
    <div class="col-md-6">
        <label for="frm-reg-pass">@lang('label.OTP_CODE')<span class="required">*</span></label>

        {!! Form::text('otp_code',null, ['id' => 'otpCode', 'class' => 'form-control integer-decimal-only', 'autocomplete' => 'false', 'placeholder' => 'Enter OTP']) !!}

        <span class="">@lang('label.PLEASE_ENTER_THE_OTP_CODE_FOR_VERIFICATION_YOUR_OTP', ['code' => $phone])</span>
        </br><span class="required bold" id="invalidOtp">{{ $errors->first('otp_code') }}</span>
    </div>
    {!! Form::hidden('sent_otp', $SixDigitRandomNumber, ['id' => 'sentOtp']) !!}

    <div class="row text-center">
        <button class="btn btn-sign" type="submit" id='resendOtp'>
            <i class="fa fa-undo"></i> @lang('label.RESEND')
        </button>
    </div>


</div>
