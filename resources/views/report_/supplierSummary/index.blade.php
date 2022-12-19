@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.SUPPLIER_SUMMARY_REPORT')
            </div>
            <div class="actions">
                <span class="text-right">
                    @if(Request::get('generate') == 'true')
                    @if(!empty($userAccessArr[108][6]))
                    <a class="btn btn-sm btn-inline blue-soft btn-print tooltips vcenter" data-placement="left" target="_blank" href="{{ URL::to($request->fullUrl().'&view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    @endif
                    @if(!empty($userAccessArr[108][9]))
                    <a class="btn btn-sm btn-inline yellow btn-pdf tooltips vcenter" data-placement="left" target="_blank" href="{{ URL::to($request->fullUrl().'&view=pdf') }}"  title="@lang('label.DOWNLOAD')">
                        <i class="fa fa-file-pdf-o"></i>
                    </a>
                    @endif
                    @endif 
                </span>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'admin/supplierSummaryReport/filter','class' => 'form-horizontal')) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="supplierId">@lang('label.SUPPLIER') <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            {!! Form::select('supplier_id', $supplierList, Request::get('supplier_id'), ['class' => 'form-control js-source-states', 'id' => 'supplierId']) !!}
                            <span class="text-danger">{{ $errors->first('supplier_id') }}</span>
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
                            {{__('label.SUPPLIER')}} : <strong>{{  !empty($supplierList[Request::get('supplier_id')]) && Request::get('supplier_id') != 0 ? $supplierList[Request::get('supplier_id')] : __('label.N_A') }} |</strong> 
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