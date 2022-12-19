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
                                <span>{!! !empty($konitaInfo->address)?$konitaInfo->address:''!!}</span><br/>
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
                        <span class="header">@lang('label.STOCK_SUMMARY_REPORT')</span>
                    </div>
                </div>
            </div>
            <!--SUMMARY-->
            <!--END OF SUMMARY-->
            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.CATEGORY')</th>
                                <th class="vcenter">@lang('label.PRODUCT')</th>
                                <th class="vcenter">@lang('label.BRAND')</th>
                                <th class="vcenter">@lang('label.SKU')</th>
                                <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$targetArr->isEmpty())
                            <?php
                            $sl = 0;
                            ?>
                            @foreach($targetArr as $target)
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="vcenter">{!! $target->product_category !!}</td>
                                <td class="vcenter">{!! $target->product !!}</td>
                                <td class="vcenter">{!! $target->brand !!}</td>
                                <td class="vcenter">{!! $target->sku !!}</td>
                                <td class="text-right vcenter">
                                    {!! !empty($target->available_quantity) ? Helper::numberFormat($target->available_quantity, 0) : '0' !!} 
                                    &nbsp;{!! !empty($target->unit) ? $target->unit : '' !!}
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="6" class="vcenter">@lang('label.NO_PRODUCT_FOUND')</td>
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