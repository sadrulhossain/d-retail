<div class="form">
    <div class="col-md-3">
        <label class="control-label" for="product">@lang('label.PRODUCT'):</label>
        {!! Form::text('product',!empty($productDetail->product_name)?$productDetail->product_name:'',['id' => 'product','class' => 'form-control','readonly']) !!}
        {!! Form::hidden('product_id',!empty($productDetail->product_id)?$productDetail->product_id:'',['id' => 'productId']) !!}
    </div>
</div>
