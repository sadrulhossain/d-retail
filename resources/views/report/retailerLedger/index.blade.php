@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.RETAILER_LEDGER_REPORT')
            </div>
            <div class="actions">
                <span class="text-right">
                    @if(!empty($request->generate) && $request->generate == 'true')
                    @if(!empty($ledgerArr))
                    @if(!empty($userAccessArr[111][6]))
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'&view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    @endif
                    @if(!empty($userAccessArr[111][9]))
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
            {!! Form::open(array('group' => 'form', 'url' => 'admin/retailerLedgerReport/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="retailerId">@lang('label.RETAILER') :<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            {!! Form::select('retailer_id',  $retailerList, Request::get('retailer_id'), ['class' => 'form-control js-source-states','id'=>'retailerId']) !!}
                            <span class="text-danger">{{ $errors->first('retailer_id') }}</span>
                        </div>
                    </div>
                </div> 
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
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit">
                            @lang('label.GENERATE')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->
            @if(Request::get('generate') == 'true')
            <div class="row margin-top-20">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            {{__('label.RETAILER')}} : <strong>{{ $retailerList[$request->retailer_id] ?? __('label.N_A') }} |</strong> 
                            {{__('label.FROM_DATE')}} : <strong>{{ !empty($request->from_date) ? Helper::formatDate($request->from_date) : __('label.N_A') }} |</strong> 
                            {{__('label.TO_DATE')}} : <strong>{{ !empty($request->to_date) ? Helper::formatDate($request->to_date) : __('label.N_A') }} </strong>
                        </h5>
                    </div>
                </div>
                <div class="col-md-12">
                    <div style="max-height: 500px;" class="tableFixHead sample webkit-scrollbar">
                        <table class="table table-bordered table-striped table-head-fixer-color " id="dataTable">
                            <thead>
                                <tr class="blue-light">
                                    <th class="text-center vcenter bold">@lang('label.DATE')</th>
                                    <th class="vcenter bold">@lang('label.INVOICE')</th>
                                    <th class="text-center vcenter bold">@lang('label.BILLED')</th>
                                    <th class="text-center vcenter bold">@lang('label.RECEIVED')</th>
                                    <th class="text-center vcenter bold">@lang('label.BALANCE')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty(Request::get('from_date')))
                                <tr>
                                    <th class="text-center vcenter blue-dark bold font-size-16" colspan="4">@lang('label.PREVIOUS_BALANCE')</th>
                                    <th class="vcenter blue-grey font-size-16 text-right">{!! !empty($previousBalance) ? Helper::numberFormat2Digit($previousBalance) : Helper::numberFormat2Digit(0) !!}&nbsp;@lang('label.TK')</th>
                                </tr>
                                @endif

                                @if(!empty($ledgerArr))
                                @foreach($ledgerArr as $dateTime => $invoiceList)
                                @foreach($invoiceList as $invoideId => $amount)
                                <?php
                                $invTextAlign = !empty($invoiceNoList[$invoideId]) ? '' : 'text-center';
                                $billTextAlign = !empty($amount['billed']) ? 'text-right' : 'text-center';
                                $recievedTextAlign = !empty($amount['received']) ? 'text-right' : 'text-center';
                                ?>
                                <tr>
                                    <td class="text-center vcenter">{!! !empty($dateTime) ? Helper::formatDate($dateTime) : '--' !!}</td>

                                    <td class="{{$invTextAlign}} vcenter">{!! !empty($invoiceNoList[$invoideId]) ? $invoiceNoList[$invoideId] : '--'  !!}</td>
                                    <td class="{{$billTextAlign}} vcenter">{!! !empty($amount['billed']) ? Helper::numberFormat2Digit($amount['billed']) .' '.__('label.TK') : '--' !!}</td>
                                    <td class="{{$recievedTextAlign}} vcenter">{!! !empty($amount['received']) ? Helper::numberFormat2Digit($amount['received']) .' '.__('label.TK'): '--' !!}</td>
                                    <td class="text-right vcenter">{!! !empty($balanceArr[$dateTime][$invoideId]) ? Helper::numberFormat2Digit($balanceArr[$dateTime][$invoideId]) : Helper::numberFormat2Digit(0) !!} &nbsp;@lang('label.TK')</td>
                                </tr>
                                @endforeach
                                @endforeach
                                <tr>
                                    <th class="text-right vcenter blue-dark bold" colspan="4">@lang('label.NET_BALANCE')</th>
    <!--                                    <th class="vcenter blue-grey text-right">${!! !empty($totalBilled) ? Helper::numberFormat2Digit($totalBilled) : Helper::numberFormat2Digit(0) !!}</th>
                                    <th class="vcenter blue-grey text-right">${!! !empty($totalReceived) ? Helper::numberFormat2Digit($totalReceived) : Helper::numberFormat2Digit(0) !!}</th>-->
                                    <th class="vcenter blue-grey text-right">{!! !empty($totalBalance) ? Helper::numberFormat2Digit($totalBalance) : Helper::numberFormat2Digit(0) !!}&nbsp;@lang('label.TK')</th>
                                </tr>
                                @else
                                <tr>
                                    <td class="vcenter text-danger" colspan="10">@lang('label.NO_DATA_FOUND')</td>
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