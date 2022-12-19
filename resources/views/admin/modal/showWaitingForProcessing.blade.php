
<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center">
            @lang('label.WAITING_FOR_PROCESSING')
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.CUSTOMER_NAME')</th>
                                <th class="vcenter">@lang('label.PRODUCT_SKU')</th>
                                <th class="vcenter text-center">@lang('label.UNIT_PRICE')</th>
                                <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                                <th class="vcenter text-center">@lang('label.TOTAL_PAYING_AMOUNT')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if (!empty($oderArr))
                            <?php
                            $page = Request::get('page');
                            $page = empty($page) ? 1 : $page;
                            $sl = ($page - 1) * Session::get('paginatorCount');
                            ?>
                            @foreach($oderArr as $orderId => $order)
                            <tr>
                                <td class="text-center vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{!! ++$sl !!}</td>
                                <td class="vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{{ $order['customer_name'] }}</td>

                                @if(!empty($order['products']))
                                <?php $i = 0; ?>
                                @foreach($order['products'] as $detailsId => $details)
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="vcenter"> {{$details['sku']}} </td>
                                <td class="vcenter text-right"> {{$details['unit_price']}}&nbsp;@lang('label.TK') </td>
                                <td class="vcenter text-right"> {{$details['quantity']}} </td>
                                <td class="vcenter text-right"> {{$details['total_price']}}&nbsp;@lang('label.TK') </td>

                                @if($i == 0)
                                <td class="text-right vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">
                                    {{ $order['paying_amount'] }}&nbsp;@lang('label.TK')
                                </td>
                                @endif

                                <?php
                                if ($i < (sizeof($order['products']) - 1)) {
                                    echo '</tr>';
                                }
                                $i++;
                                ?>
                                @endforeach
                                @endif
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="9" class="vcenter">@lang('label.NO_PROCESSING_ORDER_FOUND')</td>
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