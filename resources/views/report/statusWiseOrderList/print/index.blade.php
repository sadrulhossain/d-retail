<html>

    <head>
        <title>@lang('label.KTI_SALES_MANAGEMENT_TRACKING_SYSTEM')</title>
        @if(Request::get('view') == 'print')

        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />
        <link href="{{asset('public/assets/layouts/layout/css/custom.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')}}" rel="stylesheet" type="text/css" /> 

        @elseif(Request::get('view') == 'pdf')

        <link rel="shortcut icon" href="{{base_path()}}/public/img/favicon.ico" />
        <link href="{{base_path().'/public/fonts/css.css?family=Open Sans'}}" rel="stylesheet" type="text/css">
        <link href="{{base_path().'/public/assets/global/plugins/font-awesome/css/font-awesome.min.css'}}" rel="stylesheet" type="text/css" />
        
        <link href="{{base_path().'/public/assets/layouts/layout/css/custom.css'}}" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link href="{{base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/print.css'}}" rel="stylesheet" type="text/css" /> 
        @endif
        <style type="text/css" media="print">
            hr, p{
                margin: 0 !important;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        </style>

    </head>
    <body>
        <?php
        $basePath = URL::to('/');
        if (Request::get('view') == 'pdf') {
            $basePath = base_path();
        }
        ?>
        <div class="portlet-body">

            <div class="row margin-bottom-10">
                <!--header-->
                <div class="col-md-12">
                    <table class="table no-border">
                        <tr class="no-border">
                            <td width='40%' class="no-border">
                                <span>
                                    <img src="{{$basePath}}/public/img/small_logo.png" style="width: 280px; height: 80px;">
                                </span>
                            </td>
                            <td class="text-right font-size-11 no-border" width='60%'>
                                <span>{{!empty($konitaInfo->name)?$konitaInfo->name:''}}</span><br/>
                                <span>{!! !empty($konitaInfo->address)?$konitaInfo->address:''!!}</span>
                                <span>@lang('label.PHONE'): </span><span>{{!empty($phoneNumber)?$phoneNumber:''}}</span><br/>
                                <span>@lang('label.EMAIL'): </span><span>{{!empty($konitaInfo->email)?$konitaInfo->email.', ':''}}</span>
                                <span>@lang('label.WEBSITE'): </span><span>{{!empty($konitaInfo->website)?$konitaInfo->website:''}}</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--End of Header-->
            </div>
            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <div class="text-center bold uppercase">
                        <span class="header">@lang('label.STATUS_WISE_ORDER_LIST_REPORT')</span>
                    </div>
                </div>
            </div>
            <!--SUMMARY-->
            <!--            <div class="row margin-bottom-10">
                            <div class="col-md-12">
                                <div class="bg-blue-hoki bg-font-blue-hoki">
                                    <h5 style="padding: 10px;">
                                        {{__('label.FROM_DATE')}} : <strong>{{ !empty(Request::get('from_date')) ? Helper::formatDate(Request::get('from_date')) : __('label.N_A') }} |</strong> 
                                        {{__('label.TO_DATE')}} : <strong>{{ !empty(Request::get('to_date')) ? Helper::formatDate(Request::get('to_date')) : __('label.N_A') }} </strong>
                                    </h5>
                                </div>
                            </div>
                        </div>-->
            <!--END OF SUMMARY-->
            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.ORDER_NO')</th>
                                <th class="vcenter">@lang('label.RETAILER')</th>
                                <th class="vcenter">@lang('label.SR')</th>
                                <th class="vcenter">@lang('label.PRODUCT')</th>
                                <th class="vcenter">@lang('label.BRAND')</th>
                                <!--<th class="vcenter">@lang('label.SKU')</th>-->
                                <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                <th class="vcenter text-center">@lang('label.PRICE')</th>
                                <th class="vcenter text-center">@lang('label.STOCK')</th>
                                <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                                <th class="vcenter text-center">@lang('label.TOTAL_PAYING_AMOUNT')</th>
                                <th class="text-center vcenter">@lang('label.CREATION_DATE')</th>
                                <th class="text-center vcenter">@lang('label.STATUS')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($orderArr))
                            <?php
                            $sl = 0;
                            ?>
                            @foreach($orderArr as $orderId => $order)
                            <tr>
                                <td class="text-center vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{!! ++$sl !!}</td>
                                <td class="vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{{ $order['order_no'] }}</td>
                                <td class="vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{{ $order['retailer_name'] }}</td>
                                <td class="vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{{ $order['user_name'] }}</td>
                                @if(!empty($order['products']))
                                <?php $i = 0; ?>
                                @foreach($order['products'] as $detailsId => $details)
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <!--<td class="vcenter"> {{$details['sku']}} </td>-->

                                <td class="vcenter"> {{$details['product_name']}} </td>
                                <td class="vcenter"> {{$details['brand_name']}} </td>
                                <!--<td class="vcenter"> {{$details['sku']}} </td>-->
                                <td class="vcenter text-right"> {{$details['quantity']}} </td>
                                <td class="vcenter text-right">{{$details['unit_price']}}&nbsp;@lang('label.TK')/@lang('label.UNIT')</td>

                                <?php
                                $text = 'red-intense';
                                if (!empty($details['quantity']) && !empty($details['available_quantity'])) {
                                    if ($details['quantity'] <= $details['available_quantity']) {
                                        $text = 'green-steel';
                                    }
                                }
                                ?>
                                <td class="vcenter text-right text-{{$text}}"> 
                                    {{ !empty($details['available_quantity']) ? number_format($details['available_quantity'], 0) : 0 }} 
                                </td>
                                <td class="vcenter text-right">{{$details['total_price']}}&nbsp;@lang('label.TK')</td>


                                @if($i == 0)
                                <td class="vcenter text-right" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{{$order['grand_total']}}&nbsp;@lang('label.TK')</td>

                                <td class="text-center vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">
                                    {{ !empty($order['created_at']) ? Helper::formatDate($order['created_at']) : '' }}
                                </td>
                                <td class="text-center vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">

                                    @if($order['status'] == '0')
                                    <span class="label label-sm label-blue-soft">@lang('label.PENDING')</span>
                                    @endif
                                    @if($order['status'] == '5')
                                    <span class="label label-sm label-green-steel">@lang('label.DELIVERED')</span>
                                    @endif
                                    @if($order['status'] == '8')
                                    <span class="label label-sm label-red-mint">@lang('label.CANCELLED')</span>
                                    @endif
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
                                <td colspan="18" class="vcenter">@lang('label.NO_ORDER_FOUND')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--footer-->
        <table class="table no-border">
            <tr class="no-border">
                <td class="no-border text-left font-size-11">
                    @lang('label.GENERATED_ON') {{ Helper::formatDate(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}
                </td>
                <td class="no-border text-right font-size-11">
                    @lang('label.GENERATED_FROM_KTI')
                </td>
            </tr>
        </table>


        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function (event) {
                window.print();
            });
        </script>
    </body>
</html>