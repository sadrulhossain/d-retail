@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.RETAILER_DISTRIBUTOR_PAYMENT_DUE_REPORT')
            </div>
            <div class="actions">
                <span class="text-right">
                    @if(!empty($request->generate) && $request->generate == 'true')
                    @if(!empty($retailerList))
                    @if(!empty($userAccessArr[140][6]))
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'&view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    @endif
                    @if(!empty($userAccessArr[140][9]))
                    <a class="btn btn-sm btn-inline green-seagreen tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'&view=pdf') }}"  title="@lang('label.DOWNLOAD')">
                        <i class="fa fa-file-pdf-o"></i>
                    </a>
                    @endif
                    @endif
                    @endif
                </span>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'admin/paymentDueByRetailerReport/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="fromDate">@lang('label.FROM_DATE') :</label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                {!! Form::text('from_date', Request::get('from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off', 'style' => 'min-width:150px']) !!} 
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
                        <label class="control-label col-md-4" for="toDate">@lang('label.TO_DATE') :</label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                {!! Form::text('to_date', Request::get('to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off', 'style' => 'min-width:150px']) !!} 
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
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="form">
                            <button type="submit" class="btn btn-md green btn-outline filter-submit">
                                @lang('label.GENERATE')
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
            <!-- End Filter -->
            @if(Request::get('generate') == 'true')
            <div class="row margin-top-20">

                <div class="col-md-12">
                    <div style="max-height: 500px;" class="tableFixHead sample webkit-scrollbar">
                        <table class="table table-bordered table-striped table-head-fixer-color " id="dataTable">
                            <thead>
                                <tr class="blue-light">

                                    <th class="text-center vcenter bold">@lang('label.SL_NO')</th>
                                    <th class="vcenter text-center bold">@lang('label.RETAILER')</th>
                                    <th class="text-center vcenter bold">@lang('label.TOTAL_INVOICED_AMOUNT')</th>
                                    <th class="text-center vcenter bold">@lang('label.TOTAL_RECEIVED')</th>
                                    <th class="text-center vcenter bold">@lang('label.TOTAL_DUE')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($retailerList))
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($retailerList as $rtlId => $rtlName)
                                <?php
                                $totalReceived = $receivedArr[$rtlId] ?? 0;
                                $totalInvoice = $invoiceArr[$rtlId] ?? 0;
                                $dueAmount = $totalInvoice - $totalReceived;
                                ?>
                                <tr>
                                    <td class="vcenter text-center">{{ ++$sl }}</td>
                                    <td class="vcenter text-left ">{{ $rtlName }}</td>
                                    <td class="vcenter text-right"> {{ Helper::numberFormat2Digit($invoiceArr[$rtlId] ?? '')}}&nbsp;@lang('label.TK') </td>
                                    <td class="vcenter text-right"> {{ Helper::numberFormat2Digit($receivedArr[$rtlId] ?? '') }}&nbsp;@lang('label.TK') </td>
                                    <td class="vcenter text-right"> {{ Helper::numberFormat2Digit( $dueAmount )}}&nbsp;@lang('label.TK') </td>

                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8">@lang('label.NO_DATA_FOUND')</td>
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
<!-- Modal start -->
<script type="text/javascript">
    $(function () {
        //table header fix
        $("#dataTable").tableHeadFixer();

//        $('.sample').floatingScrollbar();
    });
</script>
@stop