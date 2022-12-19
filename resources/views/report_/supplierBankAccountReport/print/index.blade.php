<html>
    <head>
        <title>@lang('label.KTI_SALES_MANAGEMENT_TRACKING_SYSTEM')</title>
        @if(Request::get('view') == 'print')
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')}}" rel="stylesheet" type="text/css" /> 
        @elseif(Request::get('view') == 'pdf')
        <link rel="shortcut icon" href="{!! base_path() !!}/public/img/favicon.ico" />
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
                        <span class="header">@lang('label.SUPPLIER_BANK_ACCOUNT_REPORT')</span>
                    </div>
                </div>
            </div>
            <!--SUMMARY-->
            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
<!--                            {{__('label.FROM_DATE')}} : <strong>{{ !empty(Request::get('from_date')) ? Helper::formatDate(Request::get('from_date')) : __('label.N_A') }} |</strong> 
                            {{__('label.TO_DATE')}} : <strong>{{ !empty(Request::get('to_date')) ? Helper::formatDate(Request::get('to_date')) : __('label.N_A') }} </strong>-->
                            {{__('label.BANK')}} : <strong>{{  !empty($bankList[Request::get('bank_id')]) && Request::get('bank_id') != 0 ? $bankList[Request::get('bank_id')] : __('label.N_A') }} </strong> 
                        </h5>
                    </div>
                </div>
            </div>
            <!--END OF SUMMARY-->

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="text-center info">
                                <th class="vcenter text-center">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.SUPPLIER_NAME')</th>
                                <th class="vcenter">@lang('label.BANK_NAME')</th>
                                <th class="vcenter">@lang('label.BRANCH_NAME')</th>
                                <th class="vcenter">@lang('label.ACCOUNT_NAME')</th>
                                <th class="vcenter">@lang('label.ACCOUNT_NUMBER')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!($targetArr->isEmpty()))
                            <?php
                            $sl = 0;
                            ?>
                            @foreach($targetArr as $target)
                            <tr>
                                <td class="vcenter text-center">{{ ++$sl }}</td>
                                <td class="vcenter">{{ $target->name ?? '' }}</td>
                                <td class="vcenter">{{ $target->bank->name ?? '' }}</td>
                                <td class="vcenter">{{ $target->branch_name ?? '' }}</td>
                                <td class="vcenter">{{ $target->account_name ?? '' }}</td>
                                <td class="vcenter">{{ $target->account_number ?? '' }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="12" class="vcenter">@lang('label.NO_SUPPLIER_FOUND')&nbsp; </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

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

    <!--//end of footer-->
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function (event) {
            window.print();
        });
    </script>
</body>
</html>