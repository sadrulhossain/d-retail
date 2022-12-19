@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-th-list"></i>@lang('label.INVOICE')
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12 text-right">
                    <div class="bg-blue-soft bg-font-blue-hoki">
                        <a class="btn btn-md yellow-mint btn-primary vcenter tooltips" title="@lang('label.PRINT')" target="_blank"  href="{!! URL::full().'?view=print' !!}">
                            <span><i class="fa fa-print"></i> Customer Copy</span> 
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
                    <img src="{{URL::to('/')}}/public/img/small_logo.png" style="width: 280px; height: 120px;">
                </div>
                <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12 text-right font-size-11">
                    <span class="font-size-11">Safecare</span><br/>
                    <span class="font-size-11">Apt: 3A (2nd Floor),Plot 16, Road 13, Sector 4,Uttara.<br/> Dhaka 1230,Bangladesh.</span><br/>
                    <span class="font-size-11">@lang('label.VAT_REGISTRATION_NO'): </span><span class="font-size-11">xxxxxxx</span><br/>
                    <span class="font-size-11">@lang('label.PHONE'): </span><span class="font-size-11">+8801944555999</span><br/>
                    <span class="font-size-11">@lang('label.EMAIL'): </span><span class="font-size-11">info@konitabd.com</span><br/>
                    <span class="font-size-11">@lang('label.WEBSITE'): </span><span class="font-size-11">http://www.konitabd.com</span>
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
                        @lang('label.INVOICE_NUMBER'): <span class="bold">{!! $order->invoice_number !!}</span>
                    </div>
                    <div class="col-md-12">
                        @lang('label.DATE'): <span class="bold">{!! $order->invoice_date !!}</span>
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
                                $sl = 1;
                                ?>
                                @foreach($orderDetailInfo as $orders)
                                <tr>
                                    <td class="vcenter text-center">{{ $sl }}</td>
                                    <td class="vcenter">{!! !empty($orders->product_name) ? $orders->product_name : '' !!}</td>
                                    <td class="vcenter text-center">{!! !empty($orders->unit_price) ? $orders->unit_price : 0.00 !!}&nbsp;@lang('label.TK')</td>
                                    <td class="vcenter text-center">{!! !empty($orders->quantity) ? $orders->quantity : '' !!}</td>
                                    <td class="vcenter text-right">{!! !empty($orders->total_price) ? $orders->total_price : 0.00 !!}&nbsp;@lang('label.TK')</td>
                                </tr>
                                <?php
                                $sl++;
                                ?>
                                @endforeach
                                @endif

                                <tr>
                                    <td class="vcenter text-right bold" colspan="4">@lang('label.SUBTOTAL')</td>
                                    <td class="vcenter text-right bold">{!! !empty($order->paying_amount) ? $order->paying_amount : 0.00 !!}&nbsp;@lang('label.TK')</td>
                                    {!! Form::hidden('sub_total', $order->paying_amount, ['id' => 'subTotal']) !!}
                                </tr>

                                <tr>
                                    <td class="vcenter text-right bold" colspan="4">@lang('label.VAT')</td>
                                    <td class="vcenter text-right bold">
                                        {!! !empty($order->vat) ? $order->vat : 0.00 !!}&nbsp;@lang('label.TK')

                                    </td>
                                </tr>

                                <tr>
                                    <td class="vcenter text-right bold" colspan="4">@lang('label.NET_PAYABLE_AMOUNT')</td>
                                    <td class="vcenter text-right bold">
                                        <span class="net-payable">{!! !empty($order->paying_amount) ? $order->paying_amount : 0.00 !!}</span>&nbsp;@lang('label.TK')
                                    </td>
                                    {!! Form::hidden('net_payable', $order->paying_amount, ['id' => 'netPayable']) !!}
                                </tr>
                                <tr>
                                    <td class="vcenter text-right bold">@lang('label.IN_WORDS')</td>
                                    <td class="vcenter bold" colspan="4">
                                        <span class="net-payable uppercase italic">{!! !empty($order->paying_amount) ? Helper::numberToWord($order->paying_amount) : Helper::numberToWord(0.00) !!}</span>&nbsp;@lang('label.TK')
                                    </td>
                                </tr>
                            </tbody>

                        </table>

                    </div>
                </div>
            </div>

            @if(!empty($order->special_note))
            <div class="row margin-top-20">
                <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
                    <span class="bold">@lang('label.SPECIAL_NOTE'): </span>
                </div>
                <div class="col-md-10 col-lg-10 col-sm-9 col-xs-12">
                    {!! $order->special_note ?? '' !!}
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

    </div>
</div>


@stop
