<div class="form">
    <div class="col-md-3">
        <label class="control-label" for="brand">@lang('label.PRODUCT'):</label>
        {!! Form::text('product',!empty($productDetail->product_name)?$productDetail->product_name:'',['id' => 'product','class' => 'form-control','readonly']) !!}
        {!! Form::hidden('product_id',!empty($productDetail->product_id)?$productDetail->product_id:0,['id' => 'productId']) !!}
    </div>
</div>
<div class="form">
    <div class="col-md-3">
        <label class="control-label" for="brand">@lang('label.BRAND'):</label>
        {!! Form::text('brand',!empty($productDetail->brand_name)?$productDetail->brand_name:'',['id' => 'brand','class' => 'form-control','readonly']) !!}
        {!! Form::hidden('brand_id',!empty($productDetail->brand_id)?$productDetail->brand_id:0,['id' => 'brandId']) !!}
    </div>
</div>
<div class="form">
    <div class="col-md-3">
        <label class="control-label" for="supplierId">@lang('label.SUPPLIER'): <span class="text-danger"> *</span></label>
        {!! Form::select('supplier_id', $supplierArr, null, ['class' => 'form-control js-source-states', 'id' => 'supplierId']) !!}
    </div>
</div>