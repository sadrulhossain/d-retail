<div class="form">
    <div class="col-md-3">
        <label class="control-label" for="supplierAddress">@lang('label.SUPPLIER_ADDRESS'):</label>
        {!! Form::text('address',$address,['id' => 'address','class' => 'form-control','readonly']) !!}
    </div>
</div>