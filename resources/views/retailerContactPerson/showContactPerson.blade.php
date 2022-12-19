<?php
$v3 = 'z' . uniqid();
?>
<div class="col-md-12 contact-person-div">
    <div class="row">
        <button class="btn btn-danger remove tooltips pull-right block-remove" title="@lang('label.CLICK_HERE_TO_DELETE_THIS_BLOCK')" type="button">
            &nbsp;@lang('label.DELETE')&nbsp;<i class="fa fa-remove"></i>
        </button>
    </div>
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
            <br/>
        </div>
    </div>
</div> 
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
    $(document).on('click', '.remove', function () {
        $(this).parent().parent().remove();
        return false;
    });
});
</script>