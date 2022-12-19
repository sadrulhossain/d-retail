<?php
$basePath = URL::to('/');
?>
@if (Request::get('view') == 'pdf' || Request::get('view') == 'printCustomer' || Request::get('view') == 'printCourier') 
<?php
if (Request::get('view') == 'pdf') {
    $basePath = base_path();
}
?>
<html>
    <head>
        <title>@lang('label.SAFECARE')</title>
        <link rel="shortcut icon" href="{{$basePath}}/public/img/favicon_sint.png" />
        <meta charset="UTF-8">
        <link href="{{asset('public/fonts/css.css?family=Open Sans')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('public/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/jqvmap/jqvmap/jqvmap.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />


        <!--BEGIN THEME LAYOUT STYLES--> 
        <!--<link href="{{asset('public/assets/layouts/layout/css/layout.min.css')}}" rel="stylesheet" type="text/css" />-->
        <link href="{{asset('public/assets/layouts/layout/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')}}" rel="stylesheet" type="text/css" /> 

        <style type="text/css" media="print">
            hr, p{
                margin: 0 !important;
            }
            * {
                -webkit-print-color-adjust: exact !important; 
                color-adjust: exact !important; 
            }
        </style>

        <script src="{{asset('public/assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
    </head>
    <body>
        <div class="portlet-body">
            
            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <table class="table no-border">
                        <tr class="no-border">
                            @if(!empty($imagePath))
                            <td class="no-border v-top" width="75%">
                                <img src="{{URL::to('/')}}{{ $imagePath }}" style="width: 280px; height: 125px;">
                            </td>
                            @endif
                            <td class="no-border v-top" width="25%">
                                <span class="font-size-14 bold">{{ $companyInfo->name }}</span><br/>
                                <span class="font-size-11">{!! $companyInfo->address !!}</span>
                                <span class="font-size-11">@lang('label.PHONE'): </span><span class="font-size-11">{{ $companyNumber }}</span><br/>
                                <span class="font-size-11">@lang('label.EMAIL'): </span><span class="font-size-11">{{ $companyInfo->email }}</span><br/>
                                @if(!empty($companyInfo->website))
                                <span class="font-size-11">@lang('label.WEBSITE'): </span><span class="font-size-11">{{ $companyInfo->website }}</span>
                                @endif
                            </td>
                            @if(empty($imagePath))
                            <td class="no-border" width="75%"></td>
                            @endif
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <div class="text-center bold uppercase">
                        <span class="header">@lang('label.INVOICE_COPY', ['n' => Request::get('view') == 'printCustomer' ? __('label.CUSTOMER') : __('label.COURIER')])</span>
                    </div>
                </div>
            </div>

            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <table class="table no-border">
                        <tr class="no-border">
                            <td class="no-border v-top" width="75%">
                                <span class="bold">@lang('label.BILL_TO'): </span><br/>
                                <span class="bold">{!! $order->customer_name !!} </span><br/>
                                <span class="">{!! $order->email !!}</span><br/>
                                <span class="">{!! $order->phone !!}</span><br/>
                                <span class="">{!! $order->shipping_address !!}</span><br/>
                            </td>
                            <td class="no-border v-top" width="25%">
                                <span class="bold">@lang('label.INVOICE_NUMBER'): </span>{!! $order->invoice_number ?? '' !!}<br/>
                                <span class="bold">@lang('label.DATE'): </span>{!! !empty($order->invoice_date) ? Helper::formatDate($order->invoice_date) : '' !!}<br/>
                            </td>
                        </tr>
                    </table>
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
                                $sl = $subTotal = 0;
                                ?>
                                @foreach($orderDetailInfo as $orders)
                                <?php $subTotal += (!empty($orders->total_price) ? $orders->total_price : 0);?>
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
                                    <td class="vcenter text-right bold">{!! !empty($subTotal) ? Helper::numberFormat2Digit($subTotal) : '0.00' !!}&nbsp;@lang('label.TK')</td>
                                </tr>

                                <tr>
                                    <td class="vcenter text-right bold" colspan="4">@lang('label.VAT')</td>
                                    <td class="vcenter text-right bold">
                                        {!! !empty($order->vat) ? Helper::numberFormat2Digit($order->vat) : '0.00' !!}&nbsp;@lang('label.TK')
                                    </td>
                                </tr>
                                <tr>
                                    <td class="vcenter text-right bold" colspan="4">@lang('label.DELIVERY_CHARGE')</td>
                                    <td class="vcenter text-right bold">
                                        {!! !empty($order->delivery_charge) ? Helper::numberFormat2Digit($order->delivery_charge) : '0.00' !!}&nbsp;@lang('label.TK')
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
            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <table class="table no-border">
                        <tr class="no-border">
                            <td class="no-border v-top" width="15%">
                                <span class="bold">@lang('label.SPECIAL_NOTE'): </span>
                            </td>
                            <td class="no-border v-top" width="85%">
                                {!! $order->special_note ?? '' !!}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            @endif

            <div class="row margin-top-75">
                <div class="col-md-12">
                    <table class="table no-border">
                        <tr class="no-border">
                            <td class="no-border v-top" width="67%">
                                ------------------------------<br/>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@lang('label.RECEIVED_BY')
                            </td>
                            <td class="no-border v-top" width="33%">
                                ------------------------------<br/>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@lang('label.ISSUED_BY')
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


        </div>
        <table class="no-border margin-top-10">
            <tr>
                <td class="no-border text-left">
                    @lang('label.GENERATED_ON') {!! '<strong>'.Helper::formatDate(date('Y-m-d H:i:s')).'</strong> by <strong>'.Auth::user()->full_name.'</strong>' !!}.
                </td>
                <td class="no-border text-right">
                    <strong>@lang('label.GENERATED_FROM_SAFECARE')</strong>
                </td>
            </tr>
        </table>

        <script src="{{asset('public/assets/global/plugins/bootstrap/js/bootstrap.min.js')}}"  type="text/javascript"></script>
        <script src="{{asset('public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->


        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{asset('public/assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->

        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!--<script src="{{asset('public/assets/layouts/layout/scripts/layout.min.js')}}" type="text/javascript"></script>-->



        <!--<script src="{{asset('public/js/apexcharts.min.js')}}" type="text/javascript"></script>-->


        <script>
document.addEventListener("DOMContentLoaded", function (event) {
    window.print();
});
        </script>
    </body>
</html>

@endif
