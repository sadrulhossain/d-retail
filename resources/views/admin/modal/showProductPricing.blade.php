
<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center">
            @lang('label.PRODUCT_PRICING')
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="">
                                <th class="vcenter text-center">@lang('label.SL_NO')</th>
                                <th class="vcenter"> {{__('label.SKU')}} </th>
                                <th class="vcenter"> {{__('label.PRODUCT_NAME')}} </th>
                                @if(in_array(Auth::user()->group_id, [1, 11, 12, 15]))
                                <th class="vcenter text-center"> {{__('label.AVAILABLE_QUANTITY')}} </th>
                                @endif
                                <th class="vcenter text-center"> {{__('label.SELLING_PRICE')}} </th>
                            </tr>
                        </thead>
                        @if(!empty($productPricingList))
                        <?php $serial = 0; ?> 
                        @foreach($productPricingList as  $pricing)
                        <tr>
                            <td class="text-center vcenter">{!! ++$serial !!}</td>
                            <td class="vcenter">
                                {{ $pricing->sku ?? '' }}
                            </td>
                            <td class="vcenter">
                                {{ $pricing->name ?? '' }}
                            </td>
                            @if(in_array(Auth::user()->group_id, [1, 11, 12, 15]))
                            <td class="vcenter text-right">
                                {{ (!empty($pricing->available_quantity) ? number_format($pricing->available_quantity, 0, '.', ',') : '0'). ' ' . ($pricing->unit ?? '') }} 
                            </td>
                            @endif
                            <td class="vcenter text-right">
                                {{ !empty($pricing->selling_price) ? Helper::numberFormat2Digit($pricing->selling_price) : '0.00' }} @lang('label.TK')
                            </td>

                        </tr>
                        @endforeach 
                        @else
                        <tr>
                            <td colspan="4">{{trans('label.NO_SKU_AVAILABLE')}}</td>
                        </tr>
                        @endif
                    </table>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        </div>
    </div>
</div>