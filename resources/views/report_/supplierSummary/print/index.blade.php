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
                        <span class="header">@lang('label.SUPPLIER_SUMMARY_REPORT')</span>
                    </div>
                </div>
            </div>
            <!--SUMMARY-->
            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            {{__('label.SUPPLIER')}} : <strong>{{  !empty($supplierList[Request::get('supplier_id')]) && Request::get('supplier_id') != 0 ? $supplierList[Request::get('supplier_id')] : __('label.N_A') }} |</strong> 
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
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="vcenter text-center">@lang('label.DATE')</th>
                                <th class="vcenter">@lang('label.REFERENCE_NO')</th>
                                <th class="vcenter">@lang('label.CHALLAN_NO')</th>
                                <th class="vcenter">@lang('label.PRODUCT')</th>
                                <th class="vcenter">@lang('label.BRAND')</th>
                                <th class="vcenter">@lang('label.SKU')</th>
                                <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                <th class="text-center vcenter">@lang('label.RATE')</th>
                                <th class="text-center vcenter">@lang('label.AMOUNT')</th>
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
                                <td class="vcenter text-center">{!! !empty($target->date) ? Helper::formatDate($target->date) : '' !!}</td>
                                <td class="vcenter">{!! $target->ref_no ?? '' !!}</td>
                                <td class="vcenter">{!! $target->challan_no ?? '' !!}</td>
                                <td class="vcenter">{!! $target->product !!}</td>
                                <td class="vcenter">{!! $target->brand !!}</td>
                                <td class="vcenter bold">{!! $target->sku_code !!}</td>
                                <td class="text-right vcenter">
                                    {!! !empty($target->quantity) ? Helper::numberFormat($target->quantity, 0) : '0' !!} 
                                    &nbsp;{!! !empty($target->unit) ? $target->unit : '' !!}
                                </td>
                                <td class="text-right vcenter">
                                    {!! !empty($target->rate) ? Helper::numberFormat2Digit($target->rate) : '0.00' !!} 
                                    &nbsp;@lang('label.TK')/{!! !empty($target->unit) ? $target->unit : '' !!}
                                </td>
                                <td class="text-right vcenter">
                                    {!! !empty($target->amount) ? Helper::numberFormat2Digit($target->amount) : '0.00' !!} 
                                    &nbsp;@lang('label.TK')
                                </td>
                            </tr>
                            @endforeach
                            <tr class="info">
                                <td class="vcenter text-right bold" colspan="9">@lang('label.TOTAL')</td>
                                <td class="text-right vcenter bold">
                                    {!! !empty($totalAmount) ? Helper::numberFormat2Digit($totalAmount) : '0.00' !!} 
                                    &nbsp;@lang('label.TK')
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="10" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
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