<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.INVOICE')
        </h3>
    </div>
    {!! Form::open(array('group' => 'form', 'url' => '', 'id' =>'invoiceSaveForm', 'class' => 'form-horizontal')) !!}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
                <img src="{{URL::to('/')}}/public/img/small_logo.png" style="width: 280px; height: 120px;">
            </div>
            <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12 text-right font-size-11">
                <span class="bold">{{!empty($konitaInfo->name)?$konitaInfo->name:''}}</span><br/>
                <span>{!! !empty($konitaInfo->address)?$konitaInfo->address:''!!}</span>
                <span>@lang('label.PHONE'): </span><span>{{!empty($phoneNumber)?$phoneNumber:''}}</span><br/>
                <span>@lang('label.EMAIL'): </span><span>{{!empty($konitaInfo->email)?$konitaInfo->email.', ':''}}</span>
                <span>@lang('label.WEBSITE'): </span><span>{{!empty($konitaInfo->website)?$konitaInfo->website:''}}</span>
            </div>
        </div>
        <div class="row margin-top-20">
            <div class="text-center col-md-12">
                <span class="bold uppercase inv-border-bottom">@lang('label.INVOICE')</span>
            </div>
        </div>
        <div class="row margin-top-20">
            <div class="col-md-8 col-sm-8 col-lg-7">
                <span class="bold">@lang('label.BILL_TO'): </span><br/>
                <span class="bold">{!! $order->customer_name !!} </span><br/>
                <span class="">{!! $order->email !!}</span><br/>
                <span class="">{!! $order->phone !!}</span><br/>
                <span class="">{!! $order->shipping_address !!}</span><br/>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-5 col-xs-12 ">
                <div class="col-md-12">
                    @lang('label.INVOICE_NUMBER'): <span class="bold">{!! $request->invoice_number !!}</span>
                </div>
                <div class="col-md-12">
                    @lang('label.DATE'): <span class="bold">{!! !empty($request->date) ? Helper::formatDate($request->date) : '' !!}</span>
                </div>
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
                            <tr>
                                <td class="vcenter text-center">{{ ++$sl }}</td>
                                <td class="vcenter">{!! !empty($orders->product_name) ? $orders->product_name : '' !!}</td>
                                <td class="vcenter text-center">{!! !empty($orders->unit_price) ? Helper::numberFormat2Digit($orders->unit_price) : '0.00' !!}&nbsp;@lang('label.TK')</td>
                                <td class="vcenter text-center">{!! !empty($orders->quantity) ? $orders->quantity : '' !!}</td>
                                <td class="vcenter text-right">{!! !empty($orders->total_price) ? Helper::numberFormat2Digit($orders->total_price) : '0.00' !!}&nbsp;@lang('label.TK')</td>
                            </tr>
                            @endforeach
                            @endif

                            <tr>
                                <td class="vcenter text-right bold" colspan="4">@lang('label.SUBTOTAL')</td>
                                <td class="vcenter text-right bold">{!! !empty($request->sub_total) ? Helper::numberFormat2Digit($request->sub_total) : 0.00 !!}&nbsp;@lang('label.TK')</td>
                                {!! Form::hidden('sub_total', Helper::numberFormat2Digit($request->sub_total), ['id' => 'subTotal']) !!}
                            </tr>

                            <tr>
                                <td class="vcenter text-right bold" colspan="4">@lang('label.VAT')</td>
                                <td class="vcenter text-right bold">
                                    {!! !empty($request->vat) ? Helper::numberFormat2Digit($request->vat) : 0.00 !!}&nbsp;@lang('label.TK')
                                </td>
                            </tr>

                            <tr>
                                <td class="vcenter text-right bold" colspan="4">@lang('label.DELIVERY_CHARGE')</td>
                                <td class="vcenter text-right bold">
                                    {!! !empty($request->delivery_charge) ? Helper::numberFormat2Digit($request->delivery_charge) : '0.00' !!}&nbsp;@lang('label.TK')
                                </td>
                            </tr>

                            <tr>
                                <td class="vcenter text-right bold" colspan="4">@lang('label.NET_PAYABLE_AMOUNT')</td>
                                <td class="vcenter text-right bold">
                                    <span class="net-payable">{!! !empty($order->paying_amount) ? Helper::numberFormat2Digit($order->paying_amount) : '0.00' !!}</span>&nbsp;@lang('label.TK')
                                </td>
                                {!! Form::hidden('net_payable', $order->paying_amount, ['id' => 'netPayable']) !!}
                            </tr>
                            <tr>
                                <td class="vcenter text-right bold">@lang('label.IN_WORDS')</td>
                                <td class="vcenter bold" colspan="3">
                                    <span class="net-payable uppercase italic">{!! !empty($order->paying_amount) ? Helper::numberToWord($order->paying_amount) : Helper::numberToWord(0.00) !!}</span>&nbsp;@lang('label.TK')
                                </td>
                            </tr>
                        </tbody>

                    </table>

                </div>
            </div>
        </div>

        @if(!empty($request->special_note))
        <div class="row margin-top-20">
            <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
                <span class="bold">@lang('label.SPECIAL_NOTE'): </span>
            </div>
            <div class="col-md-10 col-lg-10 col-sm-9 col-xs-12">
                {!! $request->special_note ?? '' !!}
            </div>
        </div>
        @endif

        <div class="row margin-top-75">
            <div class="col-md-8 col-lg-8 col-sm-8 col-xs-12 margin-top-75">
                ------------------------------<br/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@lang('label.RECEIVED_BY')
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4 col-xs-12 margin-top-75">
                ------------------------------<br/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@lang('label.ISSUED_BY')
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="submitInvoiceSave">@lang('label.CONFIRM_SUBMIT')</button>
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>


