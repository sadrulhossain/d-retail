<div class="row margin-bottom-10">
    <div class="col-md-12">
        <span class="label label-success" >@lang('label.TOTAL_NUM_OF_PRODUCTS'): {!! !$productArr->isEmpty()?count($productArr):0 !!}</span>
        @if(!empty($userAccessArr[94][5]))
        <button class='label label-primary tooltips' href="#modalRelatedProduct" id="relateProduct"  data-toggle="modal" title="@lang('label.SHOW_RELATED_PRODUCTS')">
            @lang('label.PRODUCT_RELATED_TO_THIS_SUPPLIER'): {!! !empty($productRelateToSupplier)?count($productRelateToSupplier):0 !!}&nbsp; <i class="fa fa-search-plus"></i>
        </button>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover relation-view">
                <thead>
                    <tr class="active">
                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                        @if(!$productArr->isEmpty())
                        <?php
                        $allCheckDisabled = '';
                        if (!empty($dependentAttributeArr[$request->product_id])) {
                            $allCheckDisabled = 'disabled';
                        }
                        ?>
                        <th class="vcenter">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-product-check', $allCheckDisabled]) !!}
                                <label for="checkAll">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                &nbsp;&nbsp;<span>@lang('label.CHECK_ALL')</span>
                            </div>
                        </th>
                        @endif

                        <th class="vcenter">@lang('label.PRODUCT_NAME')</th>

                        <th class="vcenter">@lang('label.PRODUCT_ASSIGNED_TO_OTHER_SUPPLIERS')</th>

                    </tr>
                </thead>
                <tbody>
                    @if(!$productArr->isEmpty())
                    <?php $sl = 0; ?>
                    @foreach($productArr as $product)
                    <?php
                    //check and show previous value
                    $checked = '';
                    if (!empty($productRelateToSupplier) && array_key_exists($product->id, $productRelateToSupplier)) {
                        $checked = 'checked';
                    }

                    $productDisabled = $attributeTooltips = '';
                    $checkCondition = 0;
                    if (!empty($inactiveProductArr) && in_array($product->id, $inactiveProductArr)) {
                        if ($checked == 'checked') {
                            $checkCondition = 1;
                        }
                        $productDisabled = 'disabled';
                        $attributeTooltips = __('label.INACTIVE');
                    }
                    ?>
                    <tr>
                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                        <td class="vcenter">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('product['.$product->id.']', $product->id, $checked, ['id' => $product->id, 'data-id'=> $product->id,'class'=> 'md-check product-check', $productDisabled]) !!}
                                <label for="{!! $product->id !!}">
                                    <span class="inc tooltips" data-placement="right" title="{{ $attributeTooltips }}"></span>
                                    <span class="check mark-caheck tooltips" data-placement="right" title="{{ $attributeTooltips }}"></span>
                                    <span class="box mark-caheck tooltips" data-placement="right" title="{{ $attributeTooltips }}"></span>
                                </label>
                            </div>
                            @if($checkCondition == '1')
                            {!! Form::hidden('product['.$product->id.']', $product->id) !!}
                            @endif
                        </td>

                        <td class="vcenter">{!! $product->name ?? '' !!}</td>

                        
                        <td>
                            <button class='label btn-primary tooltips' href="#modalRelatedSupplier" id="relateSupplier" data-id="{{ $product->id }}" data-toggle="modal" title="@lang('label.SHOW_RELATED_SUPPLIERS')">
                                <span class="badge badge-primary" >
                                    {{ !empty($productWiseSupplierArr[$product->id]) ? sizeof($productWiseSupplierArr[$product->id]) : 0 }}
                                </span>
                            </button>
                        </td>

                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td class="vcenter text-danger" colspan="20">@lang('label.NO_BRAND_FOUND')</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-4 col-md-8">
            @if(!$productArr->isEmpty())
            @if(!empty($userAccessArr[94][7]))
            <button class="btn btn-circle green btn-submit" id="" type="button">
                <i class="fa fa-check"></i> @lang('label.SUBMIT')
            </button>
            @endif
            @if(!empty($userAccessArr[94][1]))
            <a href="{{ URL::to('/admin/supplierToProduct') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
            @endif
            @endif
        </div>
    </div>
</div>




<script type="text/javascript">
    $(document).ready(function () {
//        $('.tooltips').tooltip();
<?php if (!$productArr->isEmpty()) { ?>
            $('.relation-view').dataTable({
                "language": {
                    "search": "Search Keywords : ",
                },
                "paging": true,
                "info": true,
                "order": false
            });
<?php } ?>

        $(".product-check").on("click", function () {
            if ($('.product-check:checked').length == $('.product-check').length) {
                $('.all-product-check').prop("checked", true);
            } else {
                $('.all-product-check').prop("checked", false);
            }
        });
        $(".all-product-check").click(function () {
            if ($(this).prop('checked')) {
                $('.product-check').prop("checked", true);
            } else {
                $('.product-check').prop("checked", false);
            }

        });
        if ($('.product-check:checked').length == $('.product-check').length) {
            $('.all-product-check').prop("checked", true);
        } else {
            $('.all-product-check').prop("checked", false);
        }
    });
</script>