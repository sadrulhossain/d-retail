<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.PRODUCT_RETURN')
        </h3>
    </div>
    {!! Form::open(array('group' => 'form', 'url' => '', 'id' =>'productReturnForm', 'class' => 'form-horizontal')) !!}
    {!! Form::hidden('order_id', $order->order_id, ['id' => 'orderId']) !!}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <span class="bold">@lang('label.ORDER_NO'): </span>{!! $order->order_no !!}
            </div>
            <div class="col-md-4">
                <span class="bold">@lang('label.CUSTOMER'): </span>{!! $order->customer_name !!}
            </div>
        </div>
        <div class="row margin-top-20">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="vcenter text-center">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.PRODUCT_NAME')</th>
                                <th class="vcenter">@lang('label.PRODUCT_SKU_CODE')</th>
                                <th class="vcenter text-center">@lang('label.UNIT_PRICE')</th>
                                <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                <th class="vcenter text-center">@lang('label.AMOUNT')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if (!empty($orderDetailInfo))
                            <?php
                            $sl = 0;
                            ?>
                            @foreach($orderDetailInfo as $orders)
                            {!! Form::hidden('product_sku['.$orders->sku_id.'][order_details_id]', $orders->order_details_id, ['id' => 'orderDetailsId_'.$orders->sku_id]) !!}
                            {!! Form::hidden('product_sku['.$orders->sku_id.'][sku_id]', $orders->sku_id, ['id' => 'skuId_'.$orders->sku_id]) !!}
                            {!! Form::hidden('product_sku['.$orders->sku_id.'][product_id]', $orders->product_id, ['id' => 'productId_'.$orders->sku_id]) !!}
                            {!! Form::hidden('product_sku['.$orders->sku_id.'][unit_price]', $orders->unit_price, ['id' => 'unitPrice_'.$orders->sku_id]) !!}
                            {!! Form::hidden('product_sku['.$orders->sku_id.'][quantity]', $orders->quantity, ['id' => 'quantity_'.$orders->sku_id]) !!}
                            {!! Form::hidden('product_sku['.$orders->sku_id.'][total_price]', $orders->total_price, ['id' => 'totalPrice_'.$orders->sku_id]) !!}
                            <tr>
                                <td class="vcenter text-center">{{ ++$sl }}</td>
                                <td class="vcenter">{!! !empty($orders->product_name) ? $orders->product_name : '' !!}</td>
                                <td class="vcenter">{!! !empty($orders->sku) ? $orders->sku : '' !!}</td>
                                <td class="vcenter text-center">{!! !empty($orders->unit_price) ? $orders->unit_price : 0.00 !!}&nbsp;@lang('label.TK')</td>
                                <td class="vcenter text-center">{!! !empty($orders->quantity) ? $orders->quantity : '' !!}</td>
                                <td class="vcenter text-right">{!! !empty($orders->total_price) ? $orders->total_price : 0.00 !!}&nbsp;@lang('label.TK')</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="submitReturnSave">@lang('label.CONFIRM_RETURN')</button>
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
    {!! Form::close() !!}
</div>


