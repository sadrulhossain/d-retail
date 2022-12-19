<div class="form-group">
    <label class="control-label col-md-4" for="name">@lang('label.BRAND_NAME') :</label>
    <div class="col-md-8">
        {!! Form::text('brand_name', $product->brand_name, ['id'=> 'brandName', 'class' => 'form-control','autocomplete' => 'off','readonly']) !!} 
        <span class="text-danger">{{ $errors->first('brand_name') }}</span>
        <div id="productName"></div>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4" for="productCode">@lang('label.CATEGORY_NAME') :</label>
    <div class="col-md-8">
        {!! Form::text('category_name', $product->category_name, ['id'=> 'categoryName', 'class' => 'form-control','autocomplete' => 'off','readonly']) !!} 
        <span class="text-danger">{{ $errors->first('category_name') }}</span>
    </div>
</div>
{!! Form::hidden('code', $product->code,['id'=> 'code']) !!}
<div class="form-group">
    <label class="control-label col-md-4" for="productCode">@lang('label.SKU_CODE') :<span class="text-danger"> *</span></label>
    <div class="col-md-8">
        {!! Form::text('sku_code', null, ['id'=> 'skuCode', 'class' => 'form-control','autocomplete' => 'off']) !!} 
        <span class="text-danger">{{ $errors->first('sku_code') }}</span>
    </div>
</div>

<div class="row margin-bottom-10">
    <div class="col-md-12">
        @if(!empty($userAccessArr[96][5]))
        <button class='label label-primary tooltips' href="#modalRelatedAttribute" id="assignedSKU"  data-toggle="modal" title="@lang('label.SHOW_ASSIGNED_SKU_CODES')">
            @lang('label.SHOW_ASSIGNED_SKU_CODES'): {!! !empty($productSKU)?count($productSKU):0 !!}&nbsp; <i class="fa fa-search-plus"></i>
        </button>
        @endif
    </div>
</div>