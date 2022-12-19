<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @if($request->ref == '1')
            @lang('label.ESTIMATED_TIME_OF_SHIPMENT')
            @elseif($request->ref == '2')
            @lang('label.ESTIMATED_TIME_OF_ARRIVAL')
            @endif
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
                                    <th class="text-center vcenter">@lang('label.ORDER_NO')</th>
                                    <th class="text-center vcenter">@lang('label.PURCHASE_ORDER_NO')</th>
                                    <th class="text-center vcenter">@lang('label.BUYER')</th>
                                    <th class="text-center vcenter">@lang('label.SUPPLIER')</th>
                                    <th class="text-center vcenter">@lang('label.BL_NO')</th>
                                    @if($request->ref == '1')
                                    <th class="text-center vcenter">@lang('label.ETS_DATE')</th>
                                    <th class="text-center vcenter">@lang('label.ETS_NOTIFICATION_DATE')</th>
                                    @elseif($request->ref == '2')
                                    <th class="text-center vcenter">@lang('label.ETA_DATE')</th>
                                    <th class="text-center vcenter">@lang('label.ETA_NOTIFICATION_DATE')</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($targetArr))
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($targetArr as $inquiryId=>$target)
                                <?php
                                $rowspan = !empty($blNoArr[$inquiryId]) ? count($blNoArr[$inquiryId]) : 0;
                                ?>
                                <tr>
                                    <td class="text-center vcenter" rowspan="{{$rowspan}}">{!! ++$sl !!}</td>
                                    <td class="vcenter" rowspan="{{$rowspan}}">{!! $target['order_no'] !!}</td>
                                    <td class="vcenter" rowspan="{{$rowspan}}">{!! $target['purchase_order_no'] !!}</td>
                                    <td class="vcenter" rowspan="{{$rowspan}}">{!! $target['buyer_name'] !!}</td>
                                    <td class="vcenter" rowspan="{{$rowspan}}">{!! $target['supplier_name'] !!}</td>
                                    <?php
                                    $i = 0;
                                    ?>
                                    @foreach($blNoArr[$inquiryId] as $blNO)
                                    <?php
                                    if ($i > 0) {
                                        echo '<tr>';
                                    }
                                    ?>
                                    <td class="vcenter">{!! $blNO['bl_no'] !!}</td>
                                    @if($request->ref == '1')
                                    <td class="text-center vcenter">{!! $blNO['ets'] ?? __('label.N_A') !!}</td>
                                    <td class="text-center vcenter">{!! $blNO['ets_notification'] ?? __('label.N_A') !!}</td>
                                    @elseif($request->ref == '2')
                                    <td class="text-center vcenter">{!! $blNO['eta'] ?? __('label.N_A') !!}</td>
                                    <td class="text-center vcenter">{!! $blNO['eta_notification'] ?? __('label.N_A') !!}</td>
                                    @endif
                                </tr>
                                <?php
                                $i++;
                                ?>
                                @endforeach
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
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
