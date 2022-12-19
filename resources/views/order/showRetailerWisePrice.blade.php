<thead>
    <tr>
        <th class="text-center vcenter">@lang('label.SL_NO')</th>
        <th class="vcenter" width="15%">

            <div class="md-checkbox has-success">
                {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'all-sku md-check']) !!}
                <label for="checkAll">
                    <span class="inc"></span>
                    <span class="check mark-caheck"></span>
                    <span class="box mark-caheck"></span>
                </label>&nbsp;&nbsp;
                <span class="bold">@lang('label.CHECK_ALL')</span>
            </div>
        </th>
        <th class="vcenter">@lang('label.PRODUCT_SKU')</th>
        <th class="vcenter text-center">@lang('label.CUSTOMER_DEMAND')</th>
        <th class="vcenter text-center">@lang('label.AVAILABLE_QTY')</th>
        <th class="vcenter text-center">@lang('label.QUANTITY')</th>
        <th class="vcenter text-center">@lang('label.PRICE')</th>
        <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
    </tr>
</thead>
<tbody>
    @php $sl = 0;  @endphp
    @if(!empty($targetArr))
    @foreach($targetArr as $target)
    <?php
    ?>
    <tr>
        <td class="text-center vcenter">{!! ++$sl !!}</td>
        <td class="vcenter">
            <div class="md-checkbox has-success tooltips" >
                {!! Form::checkbox('sku['.$target->id.']',$target->id, 0, ['id' => 'sku_'.$target->id, 'data-id'=>$target->id, 'class'=> 'md-check sku']) !!}
                <label for="sku_{!! $target->id !!}">
                    <span class="inc"></span>
                    <span class="check mark-caheck tooltips" title=""></span>
                    <span class="box mark-caheck tooltips" title=""></span>
                </label>
            </div>
        </td>
        <td class="vcenter">{!! $target->sku!!}</td>
        <td class="text-center vcenter width-80">
            {!! Form::text('customer_demand['.$target->id.']', null, ['id'=> 'customerDemand_'.$target->id, 'data-id' => $target->id, 'class' => 'form-control width-inherit text-right integer-decimal-only customer-demand','disabled']) !!}
        </td>
        <td class="text-center vcenter width-80">
            <span class="text-primary">{{!empty($target->available_quantity) ? number_format($target->available_quantity, 0) : 0}}</span>
            {!! Form::hidden('available_qty['.$target->id.']', $target->available_quantity ?? '', ['id' => 'availableQty_'.$target->id,'data-id'=> $target->id]) !!}
        </td>
        <td class="text-center vcenter width-80">
            {!! Form::text('product_quantity['.$target->id.']', null, ['id'=> 'productQuantity_'.$target->id, 'data-id' => $target->id, 'class' => 'form-control width-inherit text-right integer-decimal-only product-quantity','disabled']) !!}
            {!! Form::hidden('product_id['.$target->id.']', $target->product_id ?? '', ['id' => 'productId']) !!}
        </td>

        <td class="text-center vcenter width-150">
            <div class="input-group bootstrap-touchspin width-inherit">
                {!! Form::text('product_price['.$target->id.']', $target->selling_price, ['id'=> 'productPrice_'.$target->id, 'data-id' => $target->id, 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per product-price', 'readonly']) !!}
                <span class="input-group-addon bootstrap-touchspin-postfix bold">&#2547;</span>
            </div>
        </td>
        <td class="text-center vcenter width-150">
            <div class="input-group bootstrap-touchspin width-inherit">
                {!! Form::text('product_total_price['.$target->id.']', null, ['id'=> 'productTotalPrice_'.$target->id, 'data-id' => $target->id, 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per product-total-price', 'readonly']) !!}
                <span class="input-group-addon bootstrap-touchspin-postfix bold">&#2547;</span>
            </div>
        </td>


    </tr>
    @endforeach
    @else
    <tr class="info">
        <td class="vcenter bold" colspan="9">@lang('label.NO_PRODUCT_FOUND')</td>
        <!--<td class="text-right vcenter bold">{!! !empty($totalAmount) ? Helper::numberFormat2Digit($totalAmount) : '0.00' !!}&nbsp;</td>-->
    </tr>
    @endif
    @if(!empty($targetArr))
    <tr>
        <td class="text-right vcenter width-150" colspan="7">
            <strong>@lang('label.GRAND_TOTAL')</strong>
        </td>
        <td class="text-right vcenter width-150">
            <div class="input-group bootstrap-touchspin width-inherit">
                {!! Form::text('grand_total_price', null, ['id'=> 'grandTotalPrice', 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per grand-total-price', 'readonly']) !!}
                <span class="input-group-addon bootstrap-touchspin-postfix bold">&#2547;</span>
            </div>
        </td>
    </tr>
    @endif
</tbody>


<script type="text/javascript">
    
    $(document).ready(function(){
        if ($('.sku:checked').length == $('.sku').length) {
            $('.all-sku').prop("checked", true);
        } else {
            $('.all-sku').prop("checked", false);
        }
    });
    
</script>