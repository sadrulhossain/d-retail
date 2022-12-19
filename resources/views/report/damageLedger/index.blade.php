@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.DAMAGE_LEDGER_REPORT')
            </div>
            <div class="actions">
                <span class="text-right">
                    @if(Request::get('generate') == 'true')
                    @if (!empty($ledgerArr))
                    @if(!empty($userAccessArr[119][6]))
                    <a class="btn btn-sm btn-inline blue-soft btn-print tooltips vcenter" data-placement="left" target="_blank" href="{{ URL::to($request->fullUrl().'&view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    @endif
                    @if(!empty($userAccessArr[119][9]))
                    <a class="btn btn-sm btn-inline yellow btn-pdf tooltips vcenter" data-placement="left" target="_blank" href="{{ URL::to($request->fullUrl().'&view=pdf') }}"  title="@lang('label.DOWNLOAD')">
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
            {!! Form::open(array('group' => 'form', 'url' => 'admin/damageLedgerReport/filter','class' => 'form-horizontal')) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productId">@lang('label.PRODUCT') <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            {!! Form::select('product_id', $productList, Request::get('product_id'), ['class' => 'form-control js-source-states', 'id' => 'productId']) !!}
                            <span class="text-danger">{{ $errors->first('product_id') }}</span>
                        </div>
                    </div>
                </div>
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
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
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
                            {{__('label.PRODUCT')}} : <strong>{{  !empty($productList[Request::get('product_id')]) && Request::get('product_id') != 0 ? $productList[Request::get('product_id')] : __('label.ALL') }} |</strong> 
                            {{__('label.FROM_DATE')}} : <strong>{{ !empty($fromDate) ? Helper::formatDate($fromDate) : __('label.N_A') }} |</strong> 
                            {{__('label.TO_DATE')}} : <strong>{{ !empty($toDate) ? Helper::formatDate($toDate) : __('label.N_A') }} </strong>
                        </h5>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="tableFixHead max-height-500 webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color" id="fixTable">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                                    <th class="vcenter text-center" rowspan="2">@lang('label.DATE')</th>
                                    <th class="vcenter" rowspan="2">@lang('label.DESCRIPTION')</th>
                                    <th class="vcenter" rowspan="2">@lang('label.PRODUCT')</th>
                                    <th class="vcenter" rowspan="2">@lang('label.BRAND')</th>
                                    <th class="vcenter" rowspan="2">@lang('label.SKU')</th>
                                    <th class="text-center vcenter" rowspan="2">@lang('label.QUANTITY')</th>
                                    <th class="text-center vcenter" rowspan="2">@lang('label.RATE')</th>
                                    <th class="text-center vcenter" rowspan="2">@lang('label.AMOUNT')</th>
                                    <th class="text-center vcenter" colspan="2">@lang('label.BALANCE')</th>
                                </tr>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                    <th class="text-center vcenter">@lang('label.AMOUNT')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($ledgerArr))
                                <?php
                                $sl = 0;
                                ?>
                                <tr class="info">
                                    <td class="vcenter text-right bold" colspan="9">@lang('label.PREVIOUS_BALANCE')</td>
                                    <td class="text-right vcenter bold">
                                        {!! !empty($previousBalance['quantity']) ? Helper::numberFormat($previousBalance['quantity'], 0) : '0' !!} 
                                        &nbsp;@lang('label.UNIT')
                                    </td>
                                    <td class="text-right vcenter bold">
                                        {!! !empty($previousBalance['amount']) ? Helper::numberFormat2Digit($previousBalance['amount']) : '0.00' !!} 
                                        &nbsp;@lang('label.TK')
                                    </td>
                                </tr>
                                @foreach($ledgerArr as $date => $ledgerInfo)
                                @foreach($ledgerInfo as $index => $indexInfo)
                                @foreach($indexInfo as $type => $info)
                                <?php  
                                $sign = '';
                                ?>
                                <tr>
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="vcenter text-center">{!! !empty($date) ? Helper::formatDate($date) : '' !!}</td>
                                    <td class="vcenter width-150">
                                        <div class="width-inherit">
                                            <!--<span class="bold">{!! !empty($info['type']) ? $info['type'].'<br/>' : '' !!}</span>-->
                                            @if($type == '1')
                                            <span class="bold">@lang('label.REFERENCE_NO'): </span>{!! !empty($info['ref_no']) ? $info['ref_no'].'<br/>' : '' !!}
                                            <span class="bold">@lang('label.CHALLAN_NO'): </span>{!! !empty($info['challan_no']) ? $info['challan_no'].'<br/>' : '' !!};

                                            @elseif($type == '2')
                                            <span class="bold">@lang('label.REFERENCE_NO'): </span>{!! !empty($info['ref_no']) ? $info['ref_no'].'<br/>' : '' !!}

                                            @elseif($type == '3')
                                            <span class="bold">@lang('label.ORDER_NO'): </span>{!! !empty($info['order_no']) ? $info['order_no'].'<br/>' : '' !!}

                                            @elseif($type == '4')
                                            <span class="bold">@lang('label.ORDER_NO'): </span>{!! !empty($info['order_no']) ? $info['order_no'].'<br/>' : '' !!}

                                            @endif
                                        </div>
                                    </td>
                                    <td class="vcenter">{!! $info['product'] !!}</td>
                                    <td class="vcenter">{!! $info['brand'] !!}</td>
                                    <td class="vcenter bold">{!! $info['sku_code'] !!}</td>
                                    <td class="text-right vcenter">
                                        {!! !empty($info['quantity']) ? '<span class="bold">'. $sign . '</span>' . Helper::numberFormat($info['quantity'], 0) : '0' !!} 
                                        &nbsp;{!! !empty($info['unit']) ? $info['unit'] : '' !!}
                                    </td>
                                    <td class="text-right vcenter">
                                        {!! !empty($info['rate']) ? Helper::numberFormat2Digit($info['rate']) : '0.00' !!} 
                                        &nbsp;@lang('label.TK')/{!! !empty($info['unit']) ? $info['unit'] : '' !!}
                                    </td>
                                    <td class="text-right vcenter">
                                        {!! !empty($info['amount']) ? '<span class="bold">'. $sign . '</span>' . Helper::numberFormat2Digit($info['amount']) : '0.00' !!} 
                                        &nbsp;@lang('label.TK')
                                    </td>
                                    <td class="text-right vcenter">
                                        {!! !empty($balanceArr[$date][$index][$type]['quantity']) ? Helper::numberFormat($balanceArr[$date][$index][$type]['quantity'], 0) : '0' !!} 
                                        &nbsp;@lang('label.UNIT')
                                    </td>
                                    <td class="text-right vcenter">
                                        {!! !empty($balanceArr[$date][$index][$type]['amount']) ? Helper::numberFormat2Digit($balanceArr[$date][$index][$type]['amount']) : '0.00' !!} 
                                        &nbsp;@lang('label.TK')
                                    </td>
                                </tr>
                                @endforeach
                                @endforeach
                                @endforeach
                                <tr class="info">
                                    <td class="vcenter text-right bold" colspan="9">@lang('label.NET_BALANCE')</td>
                                    <td class="text-right vcenter bold">
                                        {!! !empty($totalBalance['quantity']) ? Helper::numberFormat($totalBalance['quantity'], 0) : '0' !!} 
                                        &nbsp;@lang('label.UNIT')
                                    </td>
                                    <td class="text-right vcenter bold">
                                        {!! !empty($totalBalance['amount']) ? Helper::numberFormat2Digit($totalBalance['amount']) : '0.00' !!} 
                                        &nbsp;@lang('label.TK')
                                    </td>
                                </tr>
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
    });

</script>
@stop