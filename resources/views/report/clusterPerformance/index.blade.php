@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.CLUSTER_PERFORMANCE_REPORT')
            </div>
            <div class="actions">
                <span class="text-right">
                    @if(Request::get('generate') == 'true')
                    @if (!empty($clusterList))
                    @if(!empty($userAccessArr[138][6]))
                    <a class="btn btn-sm btn-inline blue-soft btn-print tooltips vcenter" data-placement="left" target="_blank" href="{{ URL::to($request->fullUrl().'&view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    @endif
                    @if(!empty($userAccessArr[138][9]))
                    <a class="btn btn-sm btn-inline yellow btn-pdf tooltips vcenter" data-placement="left" target="_blank" href="{{ URL::to($request->fullUrl().'&view=pdf') }}"  title="@lang('label.DOWNLOAD')">
                        <i class="fa fa-file-pdf-o"></i>
                    </a>
                    <button class="btn green-seagreen btn-sm btn-chart-view tooltips" type="button" data-placement="left" title="@lang('label.CLICK_TO_SEE_GRAPHICAL_VIEW')">
                        <i class="fa fa-line-chart"></i>
                    </button>
                    <button class="btn green-seagreen btn-sm btn-tabular-view tooltips" type="button" data-placement="left" title="@lang('label.CLICK_TO_SEE_TABULAR_VIEW')">
                        <i class="fa fa-list"></i>

                        @endif
                        @endif
                        @endif
                </span>

            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'admin/clusterPerformanceReport/filter','class' => 'form-horizontal')) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4 tooltips" title="@lang('label.FROM_DATE')"  for="fromDate">@lang('label.FROM_DATE') <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                {!! Form::text('from_date', Request::get('from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!}
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="fromDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger">{{ $errors->first('from_date') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4 tooltips" title="@lang('label.TO_DATE')" for="toDate">@lang('label.TO_DATE') <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                {!! Form::text('to_date', Request::get('to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!}
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="toDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger">{{ $errors->first('to_date') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit">
                            <i class="fa fa-search"></i> @lang('label.GENERATE')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->

            @if(Request::get('generate') == 'true')
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            {{__('label.FROM_DATE')}} : <strong>{{ !empty($fromDate) ? Helper::formatDate($fromDate) : __('label.N_A') }} |</strong>
                            {{__('label.TO_DATE')}} : <strong>{{ !empty($toDate) ? Helper::formatDate($toDate) : __('label.N_A') }} </strong>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="row margin-top-5">
                <div class="col-md-4 chart-view">

                    <div id="clusterPerformancePiChart" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
                <div class="col-md-8 chart-view">
                    <div id="clusterPerformanceChart" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
                <div class="col-md-12 tabular-view">
                    <div class="tableFixHead max-height-500 webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color" id="fixTable">
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
            @endif
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $("#fixTable").tableHeadFixer();
    });</script>

<script src="{{asset('public/js/apexcharts.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null
        };
//default setting
        $(".btn-chart-view").show();
        $(".btn-tabular-view").hide();
        $(".btn-print").show();
        $(".btn-pdf").show();
        $(".chart-view").hide();
        $(".no-chart").show();
        $(".tabular-view").show();
//when click tabular view button
        $(document).on("click", ".btn-tabular-view", function () {
            $(".btn-chart-view").show();
            $(".btn-tabular-view").hide();
            $(".btn-print").show();
            $(".btn-pdf").show();
            $(".chart-view").hide();
            $(".no-chart").hide();
            $(".tabular-view").show();
        });
//when click graphical view button
        $(document).on("click", ".btn-chart-view", function () {
            $(".btn-chart-view").hide();
            $(".btn-tabular-view").show();
            $(".btn-print").hide();
            $(".btn-pdf").hide();
            $(".chart-view").show();
            $(".no-chart").show();
            $(".tabular-view").hide();
        });
        //***************************Start:: cluster pi chart ************************************
        var clusterNoOfOrderOptions = {
            series: [
<?php
if (!empty($clusterList)) {
    foreach ($clusterList as $id => $name) {
        $orderNo = !empty($targetArr[$id]['no_of_order']) ? $targetArr[$id]['no_of_order'] : '0';
        echo $orderNo . ',';
    }
}
?>
            ],
            labels: [
<?php
if (!empty($clusterList)) {
    foreach ($clusterList as $id => $name) {
        echo "'$name', ";
    }
}
?>
            ],
            chart: {
                width: 415,
                type: 'donut',
            },
            plotOptions: {
                pie: {
                    startAngle: -90,
                    endAngle: 270
                }
            },
            colors: ['#295939', '#0f3057', '#3390ff', '#1c195c'],
            dataLabels: {
                enabled: true
            },
            fill: {
                type: 'gradient',
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val
                    }
                }
            },
            legend: {
                position: 'bottom',
                formatter: function (val, opts) {
                    var orderNo = parseFloat(opts.w.globals.series[opts.seriesIndex]).toFixed(0);
                    return val + ": " + orderNo
                }
            },
            title: {
                text: ''
            },
            responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
            title: {
                text: "@lang('label.NO_OF_ORDER')",
                align: 'left',
                margin: 10,
                offsetX: 0,
                offsetY: 0,
                floating: false,
                style: {
                    fontSize: '14px',
                    fontWeight: 'bold',
                    fontFamily: undefined,
                    color: '#263238'
                },
            },
        };
        var clusterNoOfOrderStatus = new ApexCharts(document.querySelector("#clusterPerformancePiChart"), clusterNoOfOrderOptions);
        clusterNoOfOrderStatus.render();
//**************************End:: cluster pi chart ****************************************


//*******************************Start ::  cluster performance ***************************************
        var clusterPerformanceOptions = {

            chart: {
                height: 400,
                type: 'line',
                shadow: {
                    enabled: true,
                    color: '#000',
                    top: 18,
                    left: 7,
                    blur: 10,
                    opacity: 1
                },
                toolbar: {
                    show: false
                }
            },
            colors: ["#4C87B9", "#8E44AD", "#F36A5A", "#1BA39C", "#D91E18"],
            dataLabels: {
                enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                    return    parseFloat(val).toFixed(2) + " @lang('label.TK')"
                },
                textAnchor: 'middle',
                distributed: false,
                offsetX: 0,
                offsetY: -10,
                style: {
                    fontSize: '12px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 'bold',
                    colors: ["#4C87B9", "#8E44AD", "#F36A5A", "#1BA39C", "#D91E18"]
                },
                background: {
                    enabled: true,
                    foreColor: '#fff',
                    padding: 4,
                    borderRadius: 2,
                    borderWidth: 1,
                    borderColor: '#fff',
                    opacity: 0.9,
                    dropShadow: {
                        enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                    }
                },
                dropShadow: {
                    enabled: false,
                    top: 1,
                    left: 1,
                    blur: 1,
                    color: '#000',
                    opacity: 0.45
                }
            },
            stroke: {
                curve: 'smooth'
            },
            series: [

                {
                    name: "@lang('label.SALES_VOLUME')",
                    data: [
<?php
if (!empty($clusterList)) {
    foreach ($clusterList as $id => $name) {
        $salesVol = !empty($targetArr[$id]['sales_volume']) ? $targetArr[$id]['sales_volume'] : '0';
        ?>
                                "{{$salesVol}}",
        <?php
    }
}
?>
                    ]
                },
            ],
            grid: {
                borderColor: '#e7e7e7',
                        row: {
                            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                                    opacity: 0.5
                        },
                padding: {
                    top: 10,
                    right: 25,
                    bottom: 10,
                    left: 50,
                },
            },
            markers: {

                size: 6
            },
            xaxis: {
                labels: {
                    show: true,
                    rotate: -60,
                    rotateAlways: true,
                    hideOverlappingLabels: false,
                    showDuplicates: true,
                    trim: true,
                    minHeight: 80,
                    maxHeight: 160,
                    offsetX: 0,
                    offsetY: 0,
                    format: undefined,
                    formatter: undefined,
                },
                categories: [
<?php
if (!empty($clusterList)) {
    foreach ($clusterList as $id => $name) {
        echo "'$name',";
    }
}
?>
                ],
                title: {
                    text: "@lang('label.CLUSTER')",
                    offsetX: -40,
                    offsetY: 0,
                    style: {
                        color: undefined,
                        fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 700,
                        cssClass: 'apexcharts-xaxis-title',
                    },
                },
            },
            yaxis: {
                title: {
                    text: "@lang('label.AMOUNT') @lang('label.TK')",
                    offsetX: 0,
                    offsetY: 0,
                },
                labels: {
                    show: true,
                    align: 'right',
                    minWidth: 50,
                    maxWidth: 200,
                    formatter: (val) => {
                        var ftVal = parseFloat(val).toFixed(2);
                        var valLength = ftVal.toString().length;
                        var fnlVal = parseInt(val);
                        var valT = '';
                        if (valLength > 6) {
                            fnlVal = parseInt(val / 1000);
                            valT = 'K';
                        }
                        if (valLength > 9) {
                            fnlVal = parseInt(val / 1000000);
                            valT = 'M';
                        }
                        if (valLength > 12) {
                            fnlVal = parseInt(val / 1000000000);
                            valT = 'B';
                        }
                        return fnlVal + '' + valT
                    },
                },
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return parseFloat(val).toFixed(2) + " @lang('label.TK')"
                    }
                }
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                offsetY: 10,
                offsetX: 0,
                width: undefined,
                height: 100,
            },
            title: {
                text: "@lang('label.CLUSTER_PERFORMANCE')",
                align: 'left',
                margin: 10,
                offsetX: 0,
                offsetY: 0,
                floating: false,
                style: {
                    fontSize: '14px',
                    fontWeight: 'bold',
                    fontFamily: undefined,
                    color: '#263238'
                },
            },
        };

        var clusterPerformanceStatus = new ApexCharts(
                document.querySelector("#clusterPerformanceChart"), clusterPerformanceOptions
                );
        clusterPerformanceStatus.render();
//*****************************End:: cluster performance ********************************
    });
</script>
@stop
