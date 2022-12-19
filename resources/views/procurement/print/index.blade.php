<?php
$basePath = URL::to('/');
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
                        <span class="header">@lang('label.WORK_ORDER')</span>
                    </div>
                </div>
            </div>

            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <table class="table no-border">
                        <tr class="no-border">
                            <td class="no-border v-top" width="65%">
                                <span class="bold">@lang('label.REFERENCE'): </span>{!! $info->reference ?? '' !!}<br/>
                             </td>
                            <td class="no-border v-top" width="35%">
                                <span class="bold">@lang('label.ISSUE_DATE'): </span>{!! !empty($info->issue_date)  ? Helper::formatDate($info->issue_date) : '' !!}<br/>
                             </td>
                        </tr>
                        <tr>
                            <td class="no-border v-top" width="65%">
                                 <strong>@lang('label.SUPPLIER')</strong> : {!! !empty($info->supplier_id) && !empty($supplierList[$info->supplier_id]) ? $supplierList[$info->supplier_id] : '' !!}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row margin-top-20">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover relation-view-2">
                        <thead>
                            <tr>
                                <th class="text-center vcenter"><strong>@lang('label.SL_NO')</strong></th>
                                <th class="text-center vcenter"><strong>@lang('label.SKU')</strong></th>
                                <th class="text-center vcenter"><strong>@lang('label.QUANTITY')</strong></th>
                                <th class="text-center vcenter"><strong>@lang('label.UNIT_PRICE')</strong></th>
                                <th class="text-center vcenter"><strong>@lang('label.TOTAL_PRICE')</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($targetArr))
                            <?php
                            $sl = 0;
                            ?>
                            @foreach($targetArr as $target)
                            <tr>
                                <td class="text-center">{!! ++$sl !!}</td>
                                <td class="text-right">{!! $skuList[$target['sku_id']] !!}</td>
                                <td class="text-right">{!! !empty($target['quantity']) ? Helper::numberFormat2Digit($target['quantity']) : '' !!}</td>
                                <td class="text-right">{!! !empty($target['unit_price']) ? Helper::numberFormat2Digit($target['unit_price']) : '' !!}&nbsp;@lang('label.TK')</td>
                                <td class="text-right">{!! !empty($target['total_price']) ? Helper::numberFormat2Digit($target['total_price']) : '0.00' !!}&nbsp;@lang('label.TK')</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class=" vcenter text-right" colspan="4">@lang('label.GRAND_TOTAL')</td>
                                <td class="text-right">
                                    {!! Helper::numberFormat2Digit($info->grand_total) !!}&nbsp;@lang('label.TK')
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="10">@lang('label.NO_WORK_ORDER_FOUND')</td>
                            </tr>
                            @endif
                        </tbody>

                    </table>

                    </div>
                </div>
            </div>


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
                    @lang('label.GENERATED_ON') {!! '<strong>'.Helper::formatDate(date('Y-m-d H:i:s')).'</strong> by <strong>'.Auth::user()->first_name.' '.Auth::user()->last_name.'</strong>' !!}.
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
