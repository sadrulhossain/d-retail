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
                        <span class="header">@lang('label.CLUSTER_PERFORMANCE_REPORT')</span>
                    </div>
                </div>
            </div>
            <!--SUMMARY-->
            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
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
                                <th class="vcenter" rowspan="2">@lang('label.CLUSTER')</th>
                                <th class="vcenter text-center" rowspan="2">@lang('label.NO_OF_ORDER')</th>
                                <th class="vcenter text-center" rowspan="2">@lang('label.SALES_VOLUME')</th>
                                <th class="vcenter text-center" rowspan="2">@lang('label.DELIVERED_VOLUME')</th>
                                <th class="vcenter text-center" rowspan="2">@lang('label.PENDING_VOLUME')</th>
                            </tr>
                          
                        </thead>
                        <tbody>
                                @if (!empty($clusterList))
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($clusterList as $id => $name)
                                <tr>
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="vcenter text-center">{!! !empty($name) ? $name : '' !!}</td>
                                    <td class="vcenter text-right">{!! !empty($targetArr[$id]['no_of_order'] ) ? $targetArr[$id]['no_of_order']  : '0' !!}</td>
                                    <td class="vcenter text-right">{!! !empty($targetArr[$id]['sales_volume'] ) ? Helper::numberFormat2Digit($targetArr[$id]['sales_volume'] ) : '0.00' !!}
                                        &nbsp;@lang('label.TK')
                                    </td>
                                    <td class="vcenter text-right">{!! !empty($targetArr[$id]['delivered_volume']) ? Helper::numberFormat2Digit($targetArr[$id]['delivered_volume']) : '0.00' !!}
                                        &nbsp;@lang('label.TK')
                                    </td>
                                    <td class="vcenter text-right">{!! !empty($targetArr[$id]['pending_volume'] ) ? Helper::numberFormat2Digit($targetArr[$id]['pending_volume'] ) : '0.00' !!}
                                        &nbsp;@lang('label.TK')
                                    </td>
                                </tr>

                                @endforeach

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