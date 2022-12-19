

<div class="form-group">
    <label class="control-label col-md-4" for="address">@lang('label.ADDRESS') :<span class="text-danger"> *</span></label>
    <div class="col-md-8">
        {{ Form::textarea('address', $branchInfo->location_details, ['id'=> 'address', 'class' => 'form-control','size' => '30x2','autocomplete' => 'off', 'readonly']) }}
        <span class="text-danger">{{ $errors->first('address') }}</span>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4" for="branchContactNo">@lang('label.CONTACT_NO') :<span class="text-danger"> *</span></label>
    <div class="col-md-8">
        {!! Form::text('branch_contact_no',$branchInfo->branch_contact_no, ['id'=> 'branchContactNo', 'class' => 'form-control', 'readonly']) !!} 
        <span class="text-danger">{{ $errors->first('branch_contact_no') }}</span>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4" for="email">@lang('label.EMAIL') :<span class="text-danger"> *</span></label>
    <div class="col-md-8">
        {!! Form::text('email',$branchInfo->email, ['id'=> 'email', 'class' => 'form-control', 'readonly']) !!} 
        <span class="text-danger">{{ $errors->first('email') }}</span>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4" for="deliveryCharge">@lang('label.DELIVERY_CHARGE') (@lang('label.IN_TK')) : </label>
    <div class="col-md-8">
        {!! Form::text('delivery_charge',null, ['id'=> 'deliveryCharge', 'class' => 'form-control text-right integer-decimal-only']) !!} 
        <span class="text-danger">{{ $errors->first('delivery_charge') }}</span>
    </div>
</div>

<div class="row">
    <div class="col-md-offset-5 col-md-8">
        <button class="btn btn-circle green" type="button" id='submitCourierDetail'>
            <i class="fa fa-check"></i> @lang('label.SUBMIT')
        </button>
        <a href="{{ URL::to('/admin/processingOrder') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
    </div>
</div>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
