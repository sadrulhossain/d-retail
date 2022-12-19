
<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center">
            @lang('label.STOCK_AND_DEMAND')
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="">
                                <th class="vcenter">@lang('label.SL_NO')</th>
                                <th class="vcenter"> {{__('label.SKU')}} </th>
                                <th class="vcenter"> {{__('label.PRODUCT_NAME')}} </th>
                                <th class="vcenter text-center"> {{__('label.QUANTITY_THIS_ORDER')}} </th>
                                <th class="vcenter text-center"> {{__('label.DEMAND')}} </th>
                                <th class="vcenter text-center"> {{__('label.STOCK')}} </th>
                            </tr>
                        </thead>
                        @if(!empty($targetArr))
                        <?php $sl = 0; ?>
                        @foreach($targetArr as $productId => $order)
                        <tr>
                            <td class="text-center vcenter">{{++$sl}}</td>
                            <td class="vcenter">
                                {{ $targetArr[$productId]['sku'] }}
                            </td>
                            <td class="vcenter">
                                {{ $targetArr[$productId]['name'] }}
                            </td>
                            <td class="vcenter text-right">
                                {{ !empty($targetArr[$productId]['quantity_this_order']) ? number_format($targetArr[$productId]['quantity_this_order'], 0) : 0 }}
                            </td>
                            <td class="vcenter text-right">
                                {{ !empty($targetArr[$productId]['demand']) ? number_format($targetArr[$productId]['demand'], 0) : 0 }}
                            </td>

                            <?php
                            $text = 'red-intense';
                            if (!empty($targetArr[$productId]['demand']) && !empty($targetArr[$productId]['stock'])) {
                                if ($targetArr[$productId]['demand'] <= $targetArr[$productId]['stock']) {
                                    $text = 'green-steel';
                                }
                            }
                            ?>

                            <td class="vcenter text-right text-{{$text}}">
                                {{ !empty($targetArr[$productId]['stock']) ? number_format($targetArr[$productId]['stock'], 0) : 0 }}
                            </td>

                        </tr>
                        @endforeach
                        @endif
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>