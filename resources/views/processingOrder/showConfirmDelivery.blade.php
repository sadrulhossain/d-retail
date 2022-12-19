<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title bold">
            @lang('label.CONFIRM_DELIVERY')
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal','files' => true,'id'=>'saveDeliveryForm')) !!}
                {!! Form::hidden('delivery_info',$orderDetailInfoData['delivery']) !!}
                {!! Form::hidden('payment_mode',$orderDetailInfoData['payment_mode']) !!}
                {!! Form::hidden('order_id',$orderDetailInfoData['order_id']) !!}
                {{csrf_field()}}
                <h4 class="form-section bold text-center">@lang('label.ORDER_DETAILS')</h4>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter" colspan="11">
                                        @lang('label.ORDER_NO'):
                                        <label class="label label-danger font-white bold">{{$orderNo->order_no ?? ''}}</label>
                                    </th>

                                </tr>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="vcenter">@lang('label.PRODUCT')</th>
                                    <th class="vcenter">@lang('label.PRODUCT_SKU')</th>
                                    <th class="vcenter">@lang('label.CUSTOMER_DEMAND')</th>
                                    <th class="vcenter">@lang('label.STOCK')</th>
                                    <th class="vcenter">@lang('label.AVAILABLE_QTY')</th>
                                    <th class="vcenter text-center">@lang('label.ORDER_QTY')</th>
                                    <th class="vcenter text-center">@lang('label.DELIVERED_QUANTITY')</th>
                                    <th class="vcenter text-center">@lang('label.CHALLAN_QUANTITY')</th>
                                    <th class="vcenter text-center">@lang('label.REMAINING_QTY')</th>
                                    <th class="vcenter text-center  width-80">@lang('label.PRICE')</th>
                                    <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 0; @endphp
                                <?php
                                $orderDetailInfo = !empty($orderDetailInfoData['delivery']) ? json_decode($orderDetailInfoData['delivery'], true) : [];
                                ?>
                                @foreach($orderDetailInfo['delivery'] as $id => $target)
                                <?php
                                $grandTotal = !empty($target['grand_total']) ? $target['grand_total'] : 0.00;
                                $freezeStockArr  = Common::getFreezeStock($target['warehouse_id'], $orderDetailInfoData['order_id']);
                                ?>
                                @if(!empty($target['quantity']))
                                <tr>
                                    
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="vcenter">{{ $target['product_name'] }}</td>
                                    <td class="vcenter">{{ $target['product_sku'] }}</td>
                                    <td class="text-center vcenter width-80">{{ $target['customer_demand'] }}</td>
                                    <td class="text-center vcenter width-80">{{ $target['available_quantity'] }}</td>
                                    <td class="text-center vcenter width-80">
                                        {{ isset($freezeStockArr[$target['sku_id']]) 
                                            ? (int)$target['available_quantity'] - $freezeStockArr[$target['sku_id']] 
                                            : $target['available_quantity'] 
                                        }}
                                    </td>
                                    <td class="text-center vcenter width-80">{{ (int)$target['assign_quantity'] }}</td>
                                    <td class="text-center vcenter width-80">{{ (int)$target['delivered_quantity'] }}</td>
                                    <td class="text-center vcenter width-80">{{ (int)$target['quantity'] }}</td>
                                    <td class="text-center vcenter width-80">{{ (int)$target['due'] - (int)$target['quantity'] }}</td>
                                    <td class="text-right vcenter width-150">
                                        {{ $target['unit_price'] }} &nbsp;@lang('label.TK')
                                    </td>
                                    <td class="text-right vcenter width-150">
                                        {{ $target['total_price'] }} &nbsp;@lang('label.TK')
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                                <tr>
                                    <td class="text-right vcenter width-80" colspan="11">
                                        <strong>@lang('label.GRAND_TOTAL')</strong>
                                    </td>
                                    <td class="text-right vcenter grand-total">{{ $orderDetailInfo['grand_total_price'] }} &nbsp;@lang('label.TK')</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="col-md-12">
                <h4 class="bold text-center"> Paid: {{ $orderDetailInfo['grand_total_price'] }} &nbsp;@lang('label.TK')</h4>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary confirm-delivery" id="confirmDelivery" data-id="{{$id}}"  type="button"><i class="fa fa-cart-plus"></i> @lang('label.CONFIRM_DELIVERY')</button>
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <br/>
        <span id="confirmDeliveryLoading"></span>
    </div>
</div>