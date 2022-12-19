
<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center">
            @lang('label.PENDING_ORDER')
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.ORDER_NO')</th>
                            <th class="vcenter">@lang('label.RETAILER')</th>
                            <th class="vcenter">@lang('label.SR')</th>
                            <th class="vcenter">@lang('label.PRODUCT')</th>
                            <th class="vcenter">@lang('label.BRAND')</th>
                            <th class="vcenter">@lang('label.SKU')</th>
                            <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                            <th class="vcenter text-center">@lang('label.PRICE')</th>
                            <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                            <th class="vcenter text-center">@lang('label.TOTAL_PAYING_AMOUNT')</th>
                            <th class="text-center vcenter">@lang('label.CREATION_DATE')</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        
                        @if (!empty($targetArr))
                       <?php $sl = 0; ?>
                        @foreach($targetArr as $data)
                        <tr>
                            
                            
                            <td class="text-center vcenter">{!! ++$sl; !!}</td>
                            <td class="text-center vcenter">{!! $data['order_no'] !!}</td>
                            <td class="text-center vcenter">{!! $data['retailer_name'] !!}</td>
                            <td class="text-center vcenter">{!! $data['sr_name'] !!}</td>
                            <td class="text-center vcenter">{!! $data['product_name'] !!}</td>
                            <td class="text-center vcenter">{!! $data['brand_name'] !!}</td>
                            <td class="text-center vcenter">{!! $data['sku'] !!}</td>
                            <td class="text-center vcenter">{!! $data['quantity'] !!}</td>
                            <td class="text-center vcenter">{!! $data['unit_price'] !!}&nbsp;@lang('label.TK')</td>
                            <td class="text-center vcenter">{!! $data['total_price'] !!}&nbsp;@lang('label.TK')</td>
                            <td class="text-center vcenter">{!! $data['paying_amount'] !!}&nbsp;@lang('label.TK')</td>
                            <td class="text-center vcenter">{!! Helper::formatDate($data['created_at']) !!}</td>
                            
                        @endforeach
                        @else
                        <tr>
                            <td colspan="18" class="vcenter">@lang('label.NO_PENDING_ORDER_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        </div>
    </div>
</div>


