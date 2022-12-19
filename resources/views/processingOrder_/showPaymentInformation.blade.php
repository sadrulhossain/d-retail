<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title bold">
            @lang('label.PAYMENT_INFORMATION')
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <?php
            $orderDetailInfo = !empty($orderDetailInfoData) ? json_decode($orderDetailInfoData, true) : [];
            ?>
            {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal','files' => true,'id'=>'setInformationForm')) !!}
            {!! Form::hidden('delivery',$orderDetailInfoData) !!}
            {!! Form::hidden('order_id',$orderDetailInfo['order_id']) !!}
            {{csrf_field()}}
            <div class="col-md-12">

                <h4 class="form-section bold text-center">@lang('label.ORDER_DETAILS')</h4>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter" colspan="8">
                                        @lang('label.ORDER_NO'):
                                        <label class="label label-danger font-white bold">{{$orderNo->order_no ?? ''}}</label>
                                    </th>

                                </tr>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="vcenter">@lang('label.PRODUCT')</th>
                                    <th class="vcenter">@lang('label.PRODUCT_SKU')</th>
                                    <th class="vcenter">@lang('label.CUSTOMER_DEMAND')</th>
                                    <th class="vcenter">@lang('label.AVAILABLE_QTY')</th>
                                    <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                    <th class="vcenter text-center  width-80">@lang('label.PRICE')</th>
                                    <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 0; @endphp
                                @foreach($orderDetailInfo['delivery'] as $id => $target)
                                <tr>
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="vcenter">{{ $target['product_name'] }}</td>
                                    <td class="vcenter">{{ $target['product_sku'] }}</td>
                                    <td class="text-right vcenter width-80">{{ $target['customer_demand'] }}</td>
                                    <td class="text-right vcenter width-80">{{ $target['available_quantity'] }}</td>
                                    <td class="text-right vcenter width-80">{{ $target['quantity'] }}</td>
                                    <td class="text-right vcenter width-150">
                                        {{ $target['unit_price'] }} &#2547;
                                    </td>
                                    <td class="text-right vcenter width-150">
                                        {{ $target['total_price'] }} &#2547;
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right vcenter width-80" colspan="7">
                                        <strong>@lang('label.GRAND_TOTAL')</strong>
                                    </td>
                                    <td class="text-right vcenter grand-total">{{ $orderDetailInfo['grand_total_price'] }} &#2547;</td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="col-md-12 text-center">
                <div class="row">
                    <h3 class="form-section bold">@lang('label.PAYMENT_MODE')</h3>
                    <div class="md-radio-inline">
                        <div class="md-radio">
                            {!! Form::radio('payment_mode', '1',null, ['id' => 'paymentMode1', 'class' => 'md-radiobtn payment-mode','data-val' => '1']) !!}
                            <label for="paymentMode1">
                                <span class="inc"></span>
                                <span class="check"></span>
                                <span class="box"></span> @lang('label.CASH')
                            </label>
                        </div>
                        <div class="md-radio">
                            {!! Form::radio('payment_mode', '2', null,['id' => 'paymentMode2', 'class' => 'md-radiobtn payment-mode','data-val' => '2']) !!}
                            <label for="paymentMode2">
                                <span class="inc"></span>
                                <span class="check"></span>
                                <span class="box"></span> @lang('label.CREDIT') 
                            </label>
                        </div>
                        <div class="md-radio">
                            {!! Form::radio('payment_mode', '3', null,['id' => 'paymentMode3', 'class' => 'md-radiobtn payment-mode','data-val' => '3']) !!}
                            <label for="paymentMode3">
                                <span class="inc"></span>
                                <span class="check"></span>
                                <span class="box"></span> @lang('label.MFS') 
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn green" id="confirmPayment" data-id="{{$id}}"  type="button"><i class="fa fa-check"></i> @lang('label.CONFIRM_PAYMENT')</button>
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>