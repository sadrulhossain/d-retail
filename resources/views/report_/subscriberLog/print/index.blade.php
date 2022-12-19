<html>
    <head>
        <title>@lang('label.KTI_SALES_MANAGEMENT_TRACKING_SYSTEM')</title>
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />
        <link href="{{asset('public/assets/layouts/layout/css/custom.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')}}" rel="stylesheet" type="text/css" /> 

    </head>
    <body>

        <table class="table no-border">
            <tr>
                <td class="" colspan="9">
                    <strong>{!!__('label.SUBSCRIBER_LOG_REPORT')!!}</strong>
                </td>
            </tr>
        </table>
        <!--SUMMARY-->

        <table class="no-border margin-top-10">
            <tr>
                <td class="" colspan="9">
                    <h5 style="padding: 10px;">
                        {{__('label.NO_OF_SUBSCRIBER')}} : <strong>{{  !empty($targetArr) ? sizeof($targetArr) : 0 }} |</strong> 
                        {{__('label.FROM_DATE')}} : <strong>{{ !empty($fromDate) ? Helper::formatDate($fromDate) : __('label.N_A') }} |</strong> 
                        {{__('label.TO_DATE')}} : <strong>{{ !empty($toDate) ? Helper::formatDate($toDate) : __('label.N_A') }} </strong>
                    </h5>
                </td>
            </tr>
        </table>
        <!--END OF SUMMARY-->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th  colspan="2"  class="text-center vcenter">@lang('label.SL_NO')</th>
                    <th colspan="3" class="vcenter text-center">@lang('label.DATE')</th>
                    <th colspan="4" class="vcenter">@lang('label.EMAIL')</th>
                </tr>
            </thead>
            <tbody>
                @if (!$targetArr->isEmpty())
                <?php
                $sl = 0;
                ?>
                @foreach($targetArr as $target)
                <tr>
                    <td  colspan="2" class="text-center vcenter">{!! ++$sl !!}</td>
                    <td colspan="3" class="vcenter text-center">{!! !empty($target->created_at) ? Helper::formatDateTime($target->created_at) : '' !!}</td>
                    <td colspan="4" class="vcenter">{!! $target->email ?? '' !!}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="9" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
                </tr>
                @endif
            </tbody>
        </table>
        <!--footer-->
        <table class="table no-border">
            <tr class="no-border">
                <td class="no-border text-left" colspan="5">
                    @lang('label.GENERATED_ON') {{ Helper::formatDate(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}
                </td>
                <td class="no-border text-right font-size-11" colspan="4">
                    @lang('label.GENERATED_FROM_KTI')
                </td>
            </tr>
        </table>
    </body>
</html>