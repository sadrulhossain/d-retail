
<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center">
            @lang('label.LOW_QUANTITY_SKU')
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
                                <th class="vcenter text-center"> {{__('label.AVAILABLE_QUANTITY')}} </th>
                                <th class="vcenter text-center"> {{__('label.REORDER_LEVEL')}} </th>
                            </tr>
                        </thead>
                        @if(!empty($lowQuantityProductList))
                        <?php $serial = 0; ?> 
                        @foreach($lowQuantityProductList as  $data)
                        <tr>
                            <td class="text-center vcenter">{!! ++$serial !!}</td>
                            <td class="vcenter">
                                {{ $data->sku }}
                            </td>
                            <td class="vcenter">
                                {{ $data->name }}
                            </td>
                            <td class="text-right vcenter">
                                {{ !empty($data->available_quantity) ? number_format($data->available_quantity, 0, '.', '') : 0 }}
                                &nbsp;{{$data->unit}}
                            </td>
                            <td class="text-right vcenter">
                                {{ !empty($data->reorder_level) ? number_format($data->reorder_level, 0, '.', '') : 0 }}
                                &nbsp;{{$data->unit}}
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