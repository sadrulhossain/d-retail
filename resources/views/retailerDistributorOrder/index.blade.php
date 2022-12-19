@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-th-list"></i>@lang('label.ORDER_LIST')
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'admin/retailerDistributorOrder/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="fromDate">@lang('label.FROM_DATE') :</label>
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
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="toDate">@lang('label.TO_DATE') </label>
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
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="orderNo">@lang('label.ORDER_NO')</label>
                        <div class="col-md-8">
                            {!! Form::select('order_no', $orderNoList, Request::get('order_no'), ['class' => 'form-control js-source-states','id'=>'orderNo']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-12 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.FILTER')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->


            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.ORDER_NO')</th>
                            <!--<th class="vcenter">@lang('label.RETAILER')</th>-->
                            <th class="vcenter">@lang('label.SR')</th>
                            <th class="vcenter">@lang('label.PRODUCT')</th>
                            <th class="vcenter">@lang('label.BRAND')</th>
                            <th class="vcenter">@lang('label.SKU')</th>
                            <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                            <th class="vcenter text-center">@lang('label.PRICE')</th>
                            <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                            <th class="vcenter text-center">@lang('label.TOTAL_PAYING_AMOUNT')</th>
                            <th class="text-center vcenter">@lang('label.CREATION_DATE')</th>
                            <th class="text-center vcenter">@lang('label.PAYMENT')</th>
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
                            <!--<th class="text-center vcenter">@lang('label.ACTION')</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $data)
                        <tr>
                            <?php
                            $order = !empty($data->order_id) && !empty($orderArr[$data->order_id]) ? $orderArr[$data->order_id] : 0;
                            ?>
                            <td class="text-center vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{!! ++$sl !!}</td>
                            <td class="vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{{ $order['order_no'] }}</td>
                            <!--<td class="vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">{{ $order['retailer_name'] }}</td>-->
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
                            <td class="vcenter text-right"> {{$details['unit_price']}}&nbsp;@lang('label.TK') </td>

                            <?php
                            $text = 'red-intense';
                            if (!empty($details['quantity']) && !empty($details['available_quantity'])) {
                                if ($details['quantity'] <= $details['available_quantity']) {
                                    $text = 'green-steel';
                                }
                            }
                            ?>
                            
                            <td class="vcenter text-right"> {{$details['total_price']}}&nbsp;@lang('label.TK') </td>


                            @if($i == 0)
                            <td class="vcenter text-right" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}"> {{$order['grand_total']}}&nbsp;@lang('label.TK') </td>

                            <td class="text-center vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">
                                {{ !empty($order['created_at']) ? Helper::formatDate($order['created_at']) : '' }}
                            </td>
                            <td class="text-center vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">
                                @if($data->payment_collection == '0')
                                <span class="label label-sm label-green-steel">@lang('label.CASH')</span>
                                @else
                                <span class="label label-sm label-purple-wisteria">@lang('label.CREDIT')</span>
                                @endif

                            </td>
                            <td class="text-center vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">

                                @if($order['status'] == '0')
                                <span class="label label-sm label-blue-soft">@lang('label.PENDING')</span>
                                @elseif(in_array($order['status'], ['1', '2', '3']))
                                <span class="label label-sm label-purple-wisteria">@lang('label.PROCESSING')</span>
                                @elseif(in_array($order['status'], ['4']))
                                <span class="label label-sm label-yellow-mint">@lang('label.RETURNED')</span>
                                @elseif(in_array($order['status'], ['5']))
                                <span class="label label-sm label-green-steel">@lang('label.DELIVERED')</span>
                                @elseif(in_array($order['status'], ['8']))
                                <span class="label label-sm label-red-haze">@lang('label.CANCELLED')</span>
                                @endif
                            </td>
<!--                            <td class="td-actions text-center vcenter" rowspan="{{!empty($order['products']) ? sizeof($order['products']) : 1}}">

                                <div class="width-inherit">
                                    @if(in_array(Auth::user()->group_id, [12]))
                                    @if(!empty($userAccessArr[102][3]))
                                    <a class="btn btn-xs btn-primary tooltips vcenter edit" title="Edit" href="{{ URL::to('admin/pendingOrder/' . $order['order_id'] . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[102][4]))
                                    {{ Form::open(array('url' => 'admin/pendingOrder/' . $order['order_id'] .'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <button class="btn btn-xs btn-danger tooltips vcenter delete" data-id="{!! $order['order_id'] !!}" title="@lang('label.DELETE')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {{ Form::close() }}
                                    @endif
                                    @endif
                                    
                                    @if(!empty($userAccessArr[102][5]))
                                    <button class="btn btn-xs yellow-mint tooltips vcenter view-order-details" data-id="{!! $order['order_id'] !!}" href="#modalViewStockDemand"  data-toggle="modal" title="@lang('label.VIEW_STOCK_DEMAND')">
                                        <i class="fa fa-file-text-o"></i>
                                    </button>
                                    @endif
                                    
                                    @if($order['status'] == '0')
                                    @if(in_array(Auth::user()->group_id, [12]))
                                    @if(!empty($userAccessArr[102][12]))
                                    <button class="btn btn-xs tooltips vcenter green-steel confirm-order" data-id="{!! $order['order_id'] !!}" data-flag='1' data-placement="top" data-rel="tooltip" title="@lang('label.CONFIRM_ORDER')">
                                        <i class="fa fa-check-square-o"></i>
                                    </button>
                                    @endif
                                    @endif
                                    @endif
                                    @if($order['status'] == '1')
                                    @if(!empty($userAccessArr[102][15]))
                                    <button class="btn btn-xs tooltips vcenter purple-sharp start-processing" data-id="{!! $order['order_id'] !!}" data-flag='2' data-placement="top" data-rel="tooltip" title="@lang('label.START_PROCESSING_ORDER')">
                                        <i class="fa fa-play"></i>
                                    </button>
                                    @endif
                                    @endif
                                    
                                    @if(in_array(Auth::user()->group_id, [12]))
                                    @if(!empty($userAccessArr[102][13]))
                                    <button class="btn btn-xs tooltips vcenter red-soft cancel-order" data-id="{!! $order['order_id'] !!}" data-flag='8' data-placement="top" data-rel="tooltip" title="@lang('label.CANCEL_ORDER')">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                    @endif
                                    @endif
                                </div>
                            </td>-->
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
            </div>
            @include('layouts.paginator')


        </div>

    </div>
</div>

@stop