<html>
    <head>
        <title>@lang('label.KTI_SALES_MANAGEMENT_TRACKING_SYSTEM')</title>
        @if(Request::get('view') == 'print')
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />
        <link href="{{asset('public/assets/layouts/layout/css/custom.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')}}" rel="stylesheet" type="text/css" /> 
        @elseif(Request::get('view') == 'pdf')
        <link rel="shortcut icon" href="{!! base_path() !!}/public/img/favicon.ico" />
        <link href="{{ base_path().'/public/assets/layouts/layout/css/custom.css'}}" rel="stylesheet" type="text/css"/>
        <link href="{{ base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/print.css'}}" rel="stylesheet" type="text/css"/>
        <link href="{{ base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css'}}" rel="stylesheet" type="text/css"/>
        <link href="{{ base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/pdf.css'}}" rel="stylesheet" type="text/css"/> 
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
                        <span class="header">@lang('label.CHECKIN_LEDGER_REPORT')</span>
                    </div>
                </div>
            </div>
            <!--SUMMARY-->
            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            {{__('label.PRODUCT')}} : <strong>{{  !empty($productList[Request::get('product_id')]) && Request::get('product_id') != 0 ? $productList[Request::get('product_id')] : __('label.ALL') }} |</strong> 
                            {{__('label.FROM_DATE')}} : <strong>{{ !empty($fromDate) ? Helper::formatDate($fromDate) : __('label.N_A') }} |</strong> 
                            {{__('label.TO_DATE')}} : <strong>{{ !empty($toDate) ? Helper::formatDate($toDate) : __('label.N_A') }} </strong>
                        </h5>
                    </div>
                </div>
            </div>
            <!--END OF SUMMARY-->
            
            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                                <th class="vcenter text-center" rowspan="2">@lang('label.DATE')</th>
                                <th class="vcenter" rowspan="2">@lang('label.DESCRIPTION')</th>
                                <th class="vcenter" rowspan="2">@lang('label.PRODUCT')</th>
                                <th class="vcenter" rowspan="2">@lang('label.BRAND')</th>
                                <th class="vcenter" rowspan="2">@lang('label.SKU')</th>
                                <th class="text-center vcenter" rowspan="2">@lang('label.QUANTITY')</th>
                                <th class="text-center vcenter" rowspan="2">@lang('label.RATE')</th>
                                <th class="text-center vcenter" rowspan="2">@lang('label.AMOUNT')</th>
                                <th class="text-center vcenter" colspan="2">@lang('label.BALANCE')</th>
                            </tr>
                            <tr>
                                <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                <th class="text-center vcenter">@lang('label.AMOUNT')</th>
                            </tr>
                        </thead>
                            <tbody>
                                @if (!empty($ledgerArr))
                                <?php
                                $sl = 0;
                                ?>
                                <tr class="info">
                                    <td class="vcenter text-right bold" colspan="9">@lang('label.PREVIOUS_BALANCE')</td>
                                    <td class="text-right vcenter bold">
                                        {!! !empty($previousBalance['quantity']) ? Helper::numberFormat($previousBalance['quantity'], 0) : '0' !!} 
                                        &nbsp;@lang('label.UNIT')
                                    </td>
                                    <td class="text-right vcenter bold">
                                        {!! !empty($previousBalance['amount']) ? Helper::numberFormat2Digit($previousBalance['amount']) : '0.00' !!} 
                                        &nbsp;@lang('label.TK')
                                    </td>
                                </tr>
                                @foreach($ledgerArr as $date => $ledgerInfo)
                                @foreach($ledgerInfo as $index => $indexInfo)
                                @foreach($indexInfo as $type => $info)
                                <?php  
                                $sign = '';
                                ?>
                                <tr>
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="vcenter text-center">{!! !empty($date) ? Helper::formatDate($date) : '' !!}</td>
                                    <td class="vcenter width-150">
                                        <div class="width-inherit">
                                            <!--<span class="bold">{!! !empty($info['type']) ? $info['type'].'<br/>' : '' !!}</span>-->
                                            @if($type == '1')
                                            <span class="bold">@lang('label.REFERENCE_NO'): </span>{!! !empty($info['ref_no']) ? $info['ref_no'].'<br/>' : '' !!}
                                            <span class="bold">@lang('label.CHALLAN_NO'): </span>{!! !empty($info['challan_no']) ? $info['challan_no'].'<br/>' : '' !!};

                                            @elseif($type == '2')
                                            <span class="bold">@lang('label.REFERENCE_NO'): </span>{!! !empty($info['ref_no']) ? $info['ref_no'].'<br/>' : '' !!}

                                            @elseif($type == '3')
                                            <span class="bold">@lang('label.ORDER_NO'): </span>{!! !empty($info['order_no']) ? $info['order_no'].'<br/>' : '' !!}

                                            @elseif($type == '4')
                                            <span class="bold">@lang('label.ORDER_NO'): </span>{!! !empty($info['order_no']) ? $info['order_no'].'<br/>' : '' !!}

                                            @endif
                                        </div>
                                    </td>
                                    <td class="vcenter">{!! $info['product'] !!}</td>
                                    <td class="vcenter">{!! $info['brand'] !!}</td>
                                    <td class="vcenter bold">{!! $info['sku_code'] !!}</td>
                                    <td class="text-right vcenter">
                                        {!! !empty($info['quantity']) ? '<span class="bold">'. $sign . '</span>' . Helper::numberFormat($info['quantity'], 0) : '0' !!} 
                                        &nbsp;{!! !empty($info['unit']) ? $info['unit'] : '' !!}
                                    </td>
                                    <td class="text-right vcenter">
                                        {!! !empty($info['rate']) ? Helper::numberFormat2Digit($info['rate']) : '0.00' !!} 
                                        &nbsp;@lang('label.TK')/{!! !empty($info['unit']) ? $info['unit'] : '' !!}
                                    </td>
                                    <td class="text-right vcenter">
                                        {!! !empty($info['amount']) ? '<span class="bold">'. $sign . '</span>' . Helper::numberFormat2Digit($info['amount']) : '0.00' !!} 
                                        &nbsp;@lang('label.TK')
                                    </td>
                                    <td class="text-right vcenter">
                                        {!! !empty($balanceArr[$date][$index][$type]['quantity']) ? Helper::numberFormat($balanceArr[$date][$index][$type]['quantity'], 0) : '0' !!} 
                                        &nbsp;@lang('label.UNIT')
                                    </td>
                                    <td class="text-right vcenter">
                                        {!! !empty($balanceArr[$date][$index][$type]['amount']) ? Helper::numberFormat2Digit($balanceArr[$date][$index][$type]['amount']) : '0.00' !!} 
                                        &nbsp;@lang('label.TK')
                                    </td>
                                </tr>
                                @endforeach
                                @endforeach
                                @endforeach
                                <tr class="info">
                                    <td class="vcenter text-right bold" colspan="9">@lang('label.NET_BALANCE')</td>
                                    <td class="text-right vcenter bold">
                                        {!! !empty($totalBalance['quantity']) ? Helper::numberFormat($totalBalance['quantity'], 0) : '0' !!} 
                                        &nbsp;@lang('label.UNIT')
                                    </td>
                                    <td class="text-right vcenter bold">
                                        {!! !empty($totalBalance['amount']) ? Helper::numberFormat2Digit($totalBalance['amount']) : '0.00' !!} 
                                        &nbsp;@lang('label.TK')
                                    </td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="11" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
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