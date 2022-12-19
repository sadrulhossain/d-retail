@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.SALES_STATUS_REPORT')
            </div>
            <div class="actions">
                <span class="text-right">
                    @if(Request::get('generate') == 'true')
                    @if(!empty($orderArr))
                    @if(!empty($userAccessArr[128][6]))
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'&view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    @endif
                    @if(!empty($userAccessArr[128][9]))
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'&view=pdf') }}"  title="@lang('label.DOWNLOAD')">
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
            {!! Form::open(array('group' => 'form', 'url' => 'admin/salesStatusReport/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="fromDate">@lang('label.FROM_DATE') :<span class="text-danger"> *</span></label>
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
                        <label class="control-label col-md-4" for="toDate">@lang('label.TO_DATE') :<span class="text-danger"> *</span></label>
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
                    <div class="form-group">
                        <label class="control-label col-md-4" for="orderNo">@lang('label.ORDER_NO') :</label>
                        <div class="col-md-8">
                            {!! Form::select('order_no', $orderNoList, Request::get('order_no'), ['class' => 'form-control js-source-states','id'=>'orderNo']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="orderNo">@lang('label.RETAILER'):</label>
                        <div class="col-md-8">
                            {!! Form::select('retailer_id', $retailerList, Request::get('retailer_id'), ['class' => 'form-control js-source-states','id'=>'retailerId']) !!}
                        </div>
                    </div>
                </div>
                @if(Auth::user()->group_id != 14)
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="srId">@lang('label.SR'):</label>
                        <div class="col-md-8">
                            {!! Form::select('sr_id', $srList, Request::get('sr_id'), ['class' => 'form-control js-source-states','id'=>'srId']) !!}
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-md-4 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.GENERATE')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->
            @if(Request::get('generate') == 'true')
            <div class="row">
                <!--SUMMARY-->
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.SALES_PARAMETER')</th>
                                    <th class="text-center vcenter">@lang('label.TOTAL_SALES_VALUE')</th>
                                    <th class="text-center vcenter">@lang('label.TOTAL_SALES_AMOUNT')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center vcenter">
                                        @lang('label.PENDING')
                                    </td>
                                    <td class="text-right vcenter">
                                        {{!empty($salesSummuryArr['pending']['volume']) ? $salesSummuryArr['pending']['volume'] : '0'}} @lang('label.UNIT')
                                    </td>
                                    <td class="text-right vcenter">
                                        {{!empty($salesSummuryArr['pending']['amount']) ? Helper::numberFormat2Digit($salesSummuryArr['pending']['amount']) : '0.00'}}&nbsp;@lang('label.TK')
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center vcenter">
                                        @lang('label.DELIVERED')
                                    </td>
                                    <td class="text-right vcenter">
                                        {{!empty($salesSummuryArr['delivered']['volume']) ? $salesSummuryArr['delivered']['volume'] : '0'}}  @lang('label.UNIT')
                                    </td>
                                    <td class="text-right vcenter">
                                        {{!empty($salesSummuryArr['delivered']['amount']) ? Helper::numberFormat2Digit($salesSummuryArr['delivered']['amount']) : '0.00'}}&nbsp;@lang('label.TK')
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center vcenter bold">
                                        @lang('label.TOTAL')
                                    </td>

                                    <td class="text-right vcenter bold">
                                        {{!empty($salesSummuryArr['delivered']['totalVolume']) ? $salesSummuryArr['delivered']['totalVolume'] : 0}} @lang('label.UNIT')
                                    </td>
                                    <td class="text-right vcenter bold">
                                        {{!empty($salesSummuryArr['delivered']['totalAmount']) ? $salesSummuryArr['delivered']['totalAmount'] : 0}}&nbsp;@lang('label.TK')

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--END OF SUMMARY-->


                <div class="col-md-12">
                    <div class="table-responsive max-height-500 webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color" id="fixTable">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="vcenter">@lang('label.ORDER_NO')</th>
                                    <th class="vcenter">@lang('label.RETAILER')</th>
                                    <th class="vcenter">@lang('label.SR')</th>
                                    <th class="vcenter">@lang('label.PRODUCT')</th>
                                    <th class="vcenter">@lang('label.BRAND')</th>
                                    <th class="vcenter">@lang('label.SKU')</th>
                                    <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                    <th class="vcenter text-center">@lang('label.PRICE')</th>
                                    <th class="vcenter text-center">@lang('label.STOCK')</th>
                                    <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                                    <th class="vcenter text-center">@lang('label.TOTAL_PAYING_AMOUNT')</th>
                                    <th class="text-center vcenter">@lang('label.CREATION_DATE')</th>
                                    <th class="text-center vcenter">@lang('label.STATUS')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($orderArr))
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($orderArr as $orderId => $order)
                                <tr>
                                    <td class="text-center vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{!! ++$sl !!}</td>
                                    <td class="vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{{ $order['order_no'] }}</td>
                                    <td class="vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{{ $order['retailer_name'] }}</td>
                                    <td class="vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{{ $order['user_name'] }}</td>
                                    @if(!empty($order['products']))
                                    <?php $i = 0; ?>
                                    @foreach($order['products'] as $detailsId => $details)
                                    <?php
                                    if ($i > 0) {
                                        echo '<tr>';
                                    }
                                    ?>
                                    <!--<td class="vcenter"> {{$details['sku']}} </td>-->

                                    <td class="vcenter"> {{$details['product_name']}} </td>
                                    <td class="vcenter"> {{$details['brand_name']}} </td>
                                    <td class="vcenter"> {{$details['sku']}} </td>
                                    <td class="vcenter text-right"> {{$details['quantity']}} </td>
                                    <td class="vcenter text-right">{{$details['unit_price']}}&nbsp;@lang('label.TK')/@lang('label.UNIT')</td>

                                    <?php
                                    $text = 'red-intense';
                                    if (!empty($details['quantity']) && !empty($details['available_quantity'])) {
                                        if ($details['quantity'] <= $details['available_quantity']) {
                                            $text = 'green-steel';
                                        }
                                    }
                                    ?>
                                    <td class="vcenter text-right text-{{$text}}"> 
                                        {{ !empty($details['available_quantity']) ? number_format($details['available_quantity'], 0) : 0 }} 
                                    </td>
                                    <td class="vcenter text-right">{{$details['total_price']}}&nbsp;@lang('label.TK')</td>


                                    @if($i == 0)
                                    <td class="vcenter text-right" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{{$order['grand_total']}}&nbsp;@lang('label.TK')</td>

                                    <td class="text-center vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">
                                        {{ !empty($order['created_at']) ? Helper::formatDate($order['created_at']) : '' }}
                                    </td>
                                    <td class="text-center vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">

                                        @if($order['status'] == '0')
                                        <span class="label label-sm label-blue-soft">@lang('label.PENDING')</span>
                                        @elseif($order['status'] == '5')
                                        <span class="label label-sm label-green-steel">@lang('label.DELIVERED')</span>
                                        @endif
                                    </td>
                                    @endif

                                    <?php
                                    if ($i < (sizeof($order['products']) - 1)) {
                                        echo '</tr>';
                                    }
                                    $i++;
                                    ?>
                                    @endforeach
                                    @endif
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="18" class="vcenter">@lang('label.NO_ORDER_FOUND')</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>	
    </div>
</div>
<!-- Modal start -->
<!--shipment details-->
<div class="modal fade" id="modalShipmentDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showShipmentDetails"></div>
    </div>
</div>

@stop