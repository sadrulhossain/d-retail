<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.PENDING_FOR_SHIPMENT_LIST')
        </h3>
    </div>
    <div class="modal-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive max-height-500 webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="vcenter">@lang('label.ORDER_NO')</th>
                                    <th class="vcenter">@lang('label.PURCHASE_ORDER_NO')</th>
                                    <th class="vcenter">@lang('label.BUYER')</th>
                                    <th class="vcenter">@lang('label.PRODUCT')</th>
                                    <th class="vcenter">@lang('label.BRAND')</th>
                                    <th class="vcenter">@lang('label.GRADE')</th>
                                    <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                    <th class="vcenter text-center">@lang('label.INQUIRY_DATE')</th>
                                    <th class="vcenter text-center">@lang('label.PI_DATE')</th>
                                    <th class="vcenter">@lang('label.LC_NO')</th>
                                    <th class="text-center vcenter">@lang('label.LC_DATE')</th>
                                    <th class="text-center vcenter">@lang('label.LC_TRANSMITTED_COPY_DONE')</th>
                                    <th class="vcenter">@lang('label.LC_ISSUE_DATE')</th>
                                    <th class="vcenter">@lang('label.LSD_DATE')</th>
                                    <th class="text-center vcenter">@lang('label.STATUS')</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if (!$targetArr->isEmpty())
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($targetArr as $key=>$target)
                                <tr>
                                    <td class="text-center vcenter" rowspan="{{$rowspanCount[$target->id]}}">{!! ++$sl !!}</td>
                                    <td class="vcenter" rowspan="{{$rowspanCount[$target->id]}}">{!! $target->order_no !!}</td>
                                    <td class="vcenter" rowspan="{{$rowspanCount[$target->id]}}">{!! $target->purchase_order_no !!}</td>
                                    <td class="vcenter" rowspan="{{$rowspanCount[$target->id]}}">{!! $target->buyerName !!}</td>
                                    <?php
                                    $j = 0;
                                    ?>
                                    @foreach($target->inquiryDetails as $productId=> $productData)

                                    <?php
                                    if ($j > 0) {
                                        echo '<tr>';
                                    }
                                    ?>
                                    <td class="vcenter" rowspan="{{$rowspanArr4[$target->id][$productId]}}">

                                        {{!empty($productArr[$productId])?$productArr[$productId]:''}}
                                    </td>
                                    @foreach($productData as $brandId=> $brandData)
                                    <?php
                                    $i = 0;
                                    ?>
                                    <td class="vcenter" rowspan="{{$rowspanArr3[$target->id][$productId][$brandId]}}">
                                        {{!empty($brandArr[$brandId])?$brandArr[$brandId]:''}}
                                    </td>
                                    @foreach($brandData as $gradeId=> $item)
                                    <?php
                                    if ($i > 0) {
                                        echo '<tr>';
                                    }
                                    ?>
                                    <td class="vcenter">{{!empty($gradeArr[$gradeId])?$gradeArr[$gradeId]:''}}</td>
                                    <td class="vcenter text-right">{{$item['quantity']}}&nbsp;{{$item['unit_name']}}</td>
                                    <?php
                                    $i++;
                                    ?>
                                    @if($j == '0' && $i=='1')
                                    <!--:::::::: rowspan part :::::::-->
                                    <td class="text-center vcenter" rowspan="{{$rowspanCount[$target->id]}}">{!! Helper::formatDate($target->creation_date) !!}</td>
                                    <td class="text-center vcenter" rowspan="{{$rowspanCount[$target->id]}}">{!! !empty($target->pi_date)?Helper::formatDate($target->pi_date):'' !!}</td>
                                    <td class="vcenter" rowspan="{{$rowspanCount[$target->id]}}">{!! $target->lc_no !!}</td>
                                    <td class="text-center vcenter" rowspan="{{$rowspanCount[$target->id]}}">{!! Helper::formatDate($target->lc_date) !!}</td>
                                    <td class="text-center vcenter" rowspan="{{$rowspanCount[$target->id]}}">
                                        @if($target->lc_transmitted_copy_done == '1')
                                        <span class="label label-sm label-info">@lang('label.YES')</span>
                                        @elseif($target->lc_transmitted_copy_done == '0')
                                        <span class="label label-sm label-warning">@lang('label.NO')</span>
                                        @endif
                                    </td>
                                    <td class="vcenter" rowspan="{{$rowspanCount[$target->id]}}">
                                        {!! !empty($target->lc_issue_date)?Helper::formatDate($target->lc_issue_date):'' !!}
                                    </td>
                                    <td class="vcenter" rowspan="{{$rowspanCount[$target->id]}}">
                                        {!! $target->lsd !!}
                                    </td>
                                    <td class="text-center vcenter" rowspan="{{$rowspanCount[$target->id]}}">
                                        @if($target->order_status == '2')
                                        <span class="label label-sm label-primary">@lang('label.CONFIRMED')</span>
                                        @elseif($target->order_status == '3')
                                        <span class="label label-sm label-info">@lang('label.PROCESSING_DELIVERY')</span>
                                        @elseif($target->order_status == '4')
                                        <span class="label label-sm label-success">@lang('label.ACCOMPLISHED')</span>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                                <?php
                                $j++;
                                ?>
                                @endforeach
                                </tr>
                                @endforeach
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="16" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn btn-outline grey-mint pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
