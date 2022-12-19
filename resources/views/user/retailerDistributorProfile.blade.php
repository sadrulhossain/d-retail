@extends('layouts.default.master')
@section('data_count')
@include('layouts.flash')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.MY_PROFILE')
            </div>
            <div class="actions">
                <a href="{{ URL::to('/dashboard'.Helper::queryPageStr($qpArr)) }}" class="btn btn-sm blue-dark">
                    <i class="fa fa-reply"></i>&nbsp;@lang('label.CLICK_TO_GO_BACK')
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <!--Start :: Basic Information-->
            <div class="row padding-10">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h4><strong>@lang('label.BASIC_INFORMATION')</strong></h4>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 margin-top-10 text-center">
                    @if(!empty($userInfoData->logo) && File::exists('public/uploads/retailer/' . $userInfoData->logo))
                    <img alt="{{$userInfoData->name}}" src="{{URL::to('/')}}/public/uploads/retailer/{{$userInfoData->logo}}" width="150" height="150"/>
                    @else
                    <img alt="unknown" src="{{URL::to('/')}}/public/img/no_image.png" width="150" height="150"/>
                    @endif
                    @if(!empty($userInfoData->name))
                    <h5 class="bold text-center margin-top-10">
                        {{!empty($userInfoData->name)? $userInfoData->name . (!empty($userInfoData->code) ? ' (' . $userInfoData->code . ')' : '') :''}}
                    </h5>
                    @endif
                </div>
                <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 margin-top-10">
                    <div class="table-responsive webkit-scrollbar">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="fit bold info">@lang('label.OWNER_NAME')</td>
                                    <td class="active"colspan="5">{!! $userInfoData->owner_name ?? __('label.N_A') !!}</td>

                                    <td class="fit bold info">@lang('label.STATUS')</td>
                                    <td class="active"colspan="5">
                                        @if($userInfoData->status == '1')
                                        <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                        @else
                                        <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit bold info">@lang('label.DIVISION')</td>
                                    <td class="active"colspan="5">{!! $userInfoData->division ?? __('label.N_A') !!}</td>

                                    <td class="fit bold info">@lang('label.INFRASTRUCTURE_TYPE')</td>
                                    <td class="active"colspan="5">
                                        @if($userInfoData->infrastructure_type == '1')
                                        <span class="label label-sm label-blue-steel">@lang('label.PERMANENT')</span>
                                        @else
                                        <span class="label label-sm label-red-flamingo">@lang('label.TEMPORARY')</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit bold info">@lang('label.DISTRICT')</td>
                                    <td class="active"colspan="5">{!! $userInfoData->district ?? __('label.N_A') !!}</td>

                                    <td class="fit bold info">@lang('label.HAS_BANK_ACCOUNT')</td>
                                    <td class="active"colspan="5">
                                        @if($userInfoData->has_bank_account == '1')
                                        <span class="label label-sm label-blue-steel">@lang('label.YES')</span>
                                        @else
                                        <span class="label label-sm label-red-flamingo">@lang('label.NO')</span>
                                        @endif
                                    </td>

                                </tr>

                                <tr>
                                    <td class="fit bold info">@lang('label.THANA')</td>
                                    <td class="active"colspan="5">{!! $userInfoData->thana ?? __('label.N_A') !!}</td>
                                    <td class="fit bold info">@lang('label.AVERAGE_MONTHLY_TRANSACTION')</td>
                                    <td class=" active"colspan="5">{!! !empty($userInfoData->avg_monthly_transaction_value) ? $userInfoData->avg_monthly_transaction_value . ' ' . __('label.TK') : '' !!}</td>
                                </tr>
                                <tr>
                                    <td class="fit bold info">@lang('label.ZONE')</td>
                                    <td class="active"colspan="5">{!! $userInfoData->zone ?? __('label.N_A') !!}</td>
                                    <td class="fit bold info">@lang('label.NID_PASSPORT')</td>
                                    <td class="active"colspan="5">{!! $userInfoData->nid_passport ?? __('label.N_A') !!}</td>

                                </tr>

                                <tr>
                                    <td class="fit bold info">@lang('label.ADDRESS')</td>
                                    <td class="active"colspan="10">{!! $userInfoData->address ?? __('label.N_A') !!}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--End :: Basic Information-->

            <!--Start :: Contact Person Information-->
            <div class="row padding-10">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h4><strong>@lang('label.CONTACT_PERSON_INFORMATION')</strong></h4>
                </div>
                <div class="col-md-12 margin-top-10">
                    <div class="table-responsive webkit-scrollbar">
                        <table class="table table-bordered">
                            <thead>
                                <tr  class="info">
                                    <th class="vcenter text-center">@lang('label.SL')</th>
                                    <th class="vcenter text-center">@lang('label.NAME')</th>
                                    <th class="vcenter text-center">@lang('label.PHONE')</th>
                                    <th class="vcenter text-center">@lang('label.REMARKS')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($contactPersonArr))
                                <?php $sl = 0; ?>
                                @foreach($contactPersonArr as $key => $contact)
                                <tr>
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="vcenter">{!! $contact['name'] ?? __('label.N_A') !!}</td>
                                    <td class="vcenter">{!! $contact['phone'] ?? __('label.N_A') !!}</td>
                                    <td class="vcenter">{!! $contact['remarks'] ?? __('label.N_A') !!}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6"> @lang('label.NO_DATA_FOUND')</td>
                                </tr>                    
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--End :: Contact Person Information-->


        </div>
    </div>
</div>


<!-- Modal start -->
<!--related sales person list-->
<div class="modal fade" id="modalInvolvedOrderList" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showInvolvedOrderList"></div>
    </div>
</div>

<!-- Modal end -->

<script src="{{asset('public/js/apexcharts.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {

//related sales person list modal
    $(".involved-order-list").on("click", function (e) {
        e.preventDefault();
        var buyerId = $(this).attr("data-buyer-id");
        var salesPersonId = $(this).attr("data-sales-person-id");
        var typeId = $(this).attr("data-type-id");
        $.ajax({
            url: "{{ URL::to('/buyer/getInvolvedOrderList')}}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                buyer_id: buyerId,
                sales_person_id: salesPersonId,
                type_id: typeId,
            },
            beforeSend: function () {
                $("#showInvolvedOrderList").html('');
            },
            success: function (res) {
                $("#showInvolvedOrderList").html(res.html);
                $('.tooltips').tooltip();
                //table header fix
                $(".table-head-fixer-color").tableHeadFixer();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            }
        }); //ajax
    });

    //start :: order summary pie
    var orderSummaryPieOptions = {
<?php
$upcoming = $inquiryCountArr['upcoming'] ?? 0;
$pipeline = $inquiryCountArr['pipeline'] ?? 0;
$confirmed = $inquiryCountArr['confirmed'] ?? 0;
$accomplished = $inquiryCountArr['accomplished'] ?? 0;
$cancelled = $inquiryCountArr['failed'] ?? 0;
?>
        series: [
<?php
echo $upcoming . ', ' . $pipeline . ', ' . $confirmed . ', ' . $accomplished . ', ' . $cancelled;
?>
        ],
        labels: ["@lang('label.UPCOMING')", "@lang('label.PIPE_LINE')"
                    , "@lang('label.CONFIRMED')", "@lang('label.ACCOMPLISHED')"
                    , "@lang('label.CANCELLED')"],
        chart: {
            width: 380,
            type: 'donut',
        },
        dataLabels: {
            enabled: true
        },
        colors: ["#4C87B9", "#8E44AD", "#F2784B", "#1BA39C", "#EF4836"],
        fill: {
            type: 'gradient',
        },
        legend: {
            fontSize: '12px',
            fontFamily: 'Helvetica, Arial',
            fontWeight: 600,
            formatter: function (val, opts) {
                var indx = opts.w.globals.series[opts.seriesIndex];
                return val + ': ' + indx
            },
            labels: {
                colors: ['#FFFFFF'],
                useSeriesColors: true
            },
            markers: {
                width: 12,
                height: 12,
                strokeWidth: 0,
                strokeColor: '#fff',
                fillColors: [],
                radius: 12,
                customHTML: undefined,
                onClick: undefined,
                offsetX: 0,
                offsetY: 0
            },
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return  val
                },

            }
        },
        responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 250
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
    };
    var orderSummaryPie = new ApexCharts(document.querySelector("#orderSummaryPie"), orderSummaryPieOptions);
    orderSummaryPie.render();
    //end :: order summary pie

    //start :: sales volume last five years
    var salesVolumeLastFiveYearsOptions = {
        series: [
            {
                name: "@lang('label.SALES_VOLUME')",
                data: [
<?php
if (!empty($yearArr)) {
    foreach ($yearArr as $year => $yearName) {
        $volume = $salesSummaryArr[$year]['volume'] ?? 0;
        echo "'$volume',";
    }
}
?>
                ]
            },
        ],
        chart: {
            height: 250,
            type: 'line',
            dropShadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 0.2
            },
            toolbar: {
                show: false
            }
        },
        colors: ['#1BA39C'],
        dataLabels: {
            enabled: true,
        },
        stroke: {
            curve: 'smooth',
        },
        title: {
            text: "@lang('label.SALES_VOLUME') (@lang('label.LAST_5_YEAR'))",
            align: 'left'
        },
        grid: {
            borderColor: '#e7e7e7',
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        markers: {
            size: 1
        },
        xaxis: {
            categories: [
<?php
if (!empty($yearArr)) {
    foreach ($yearArr as $year => $yearName) {
        echo "'$yearName',";
    }
}
?>
            ],
            title: {
                text: "@lang('label.YEARS')"
            }
        },
        yaxis: {
            title: {
                text: "@lang('label.VOLUME') (@lang('label.UNIT'))",
            },
        },
        tooltip: {
            y: [
                {
                    formatter: function (val, { series, seriesIndex, dataPointIndex, w }) {
                        return val + " @lang('label.UNIT')"
                                + growthOrDecline(val, series[seriesIndex][dataPointIndex - 1])
                    }

                },
            ]
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            floating: true,
            offsetY: 15,
            offsetX: -5
        }
    };

    var salesVolumeLastFiveYears = new ApexCharts(document.querySelector("#salesVolumeLastFiveYears"), salesVolumeLastFiveYearsOptions);
    salesVolumeLastFiveYears.render();
    //end :: sales volume last five years

    //start :: sales amount last five years
    var salesAmountLastFiveYearsOptions = {
        series: [
            {
                name: "@lang('label.SALES_AMOUNT')",
                data: [
<?php
if (!empty($yearArr)) {
    foreach ($yearArr as $year => $yearName) {
        $amount = $salesSummaryArr[$year]['amount'] ?? 0;
        echo "'$amount',";
    }
}
?>
                ]
            },
        ],
        chart: {
            height: 250,
            type: 'line',
            dropShadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 0.2
            },
            toolbar: {
                show: false
            }
        },
        colors: ['#8E44AD'],
        dataLabels: {
            enabled: true,
        },
        stroke: {
            curve: 'smooth',
        },
        title: {
            text: "@lang('label.SALES_AMOUNT') (@lang('label.LAST_5_YEAR'))",
            align: 'left'
        },
        grid: {
            borderColor: '#e7e7e7',
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        markers: {
            size: 1
        },
        xaxis: {
            categories: [
<?php
if (!empty($yearArr)) {
    foreach ($yearArr as $year => $yearName) {
        echo "'$yearName',";
    }
}
?>
            ],
            title: {
                text: "@lang('label.YEARS')"
            }
        },
        yaxis: {
            title: {
                text: "@lang('label.SALES_AMOUNT') ($)",
            },
        },
        tooltip: {
            y: [
                {
                    formatter: function (val, { series, seriesIndex, dataPointIndex, w }) {
                        return "$" + val
                                + growthOrDecline(val, series[seriesIndex][dataPointIndex - 1])
                    }

                },
            ]
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            floating: true,
            offsetY: 15,
            offsetX: -5
        }
    };

    var salesAmountLastFiveYears = new ApexCharts(document.querySelector("#salesAmountLastFiveYears"), salesAmountLastFiveYearsOptions);
    salesAmountLastFiveYears.render();
    //end :: sales amount last five years

});

function growthOrDecline(thisYear, prevYear) {
    var rateText = '';
    var rate = 0;
    var defaultPrevYear = 1;

    if (thisYear >= prevYear) {
        if (prevYear > 0) {
            defaultPrevYear = prevYear;
        }
        rate = ((thisYear - prevYear) * 100) / defaultPrevYear;
        rate = parseFloat(rate).toFixed(2);
        rateText = "<span class='text-green-seagreen'>&nbsp;(+" + rate + "% form previous year)</span>";
    } else if (thisYear < prevYear) {
        rate = ((prevYear - thisYear) * 100) / prevYear;
        rate = parseFloat(rate).toFixed(2);
        rateText = "<span class='text-danger'>&nbsp;(-" + rate + "% form previous year)</span>";
    } else {
        rateText = "";
    }

    return rateText;
}
</script>
@stop

