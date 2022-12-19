<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.ETA_SUMMARY_OF_DATE', ['date' => $date])
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
                                    <th class="vcenter">@lang('label.BUYER')</th>
                                    <th class="vcenter">@lang('label.SUPPLIER')</th>
                                    <th class="vcenter">@lang('label.SALES_PERSON')</th>
                                    <th class="vcenter">@lang('label.BL_NO')</th>
                                    <th class="vcenter">@lang('label.EXPRESS_TRACKING_NO')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($etaSummaryArr))
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($etaSummaryArr as $deliveryId => $eta)
                                <tr>
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="vcenter">{!! $eta['order_no'] ?? __('label.N_A') !!}</td>
                                    <td class="vcenter">{!! $eta['buyer_name'] ?? __('label.N_A') !!}</td>
                                    <td class="vcenter">{!! $eta['supplier_name'] ?? __('label.N_A') !!}</td>
                                    <td class="vcenter">{!! $eta['sales_person_name'] ?? __('label.N_A') !!}</td>
                                    <td class="vcenter">{!! $eta['bl_no'] ?? __('label.N_A') !!}</td>
                                    <td class="vcenter">{!! $eta['express_tracking_no'] ?? __('label.N_A') !!}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="7" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
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
