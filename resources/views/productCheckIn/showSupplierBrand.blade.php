<div class="form">
    <div class="col-md-3">
        <label class="control-label" for="brand">@lang('label.BRAND'):</label>
        {!! Form::text('brand',!empty($brand->name)?$brand->name:'',['id' => 'brand','class' => 'form-control','readonly']) !!}
    </div>
</div>

<div class="form">
    <div class="col-md-3">
        <label class="control-label" for="supplierId">@lang('label.SUPPLIER'): <span class="text-danger"> *</span></label>
        {!! Form::select('supplier_id', $supplierArr, null, ['class' => 'form-control js-source-states', 'id' => 'supplierId']) !!}
    </div>
</div>