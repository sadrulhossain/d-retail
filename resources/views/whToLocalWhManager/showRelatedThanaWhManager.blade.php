<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.RELATED_WAREHOUSE_AND_THANA_WAREHOUSE_MANAGER')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover relation-view">
                        <thead>
                            <tr>
                                <th class="vcenter text-center">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.WAREHOUSE')</th>
                                <th class="vcenter text-center">@lang('label.THANA_WH_MANAGER')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sl = 0;
                            ?>
                            @if(!empty($relatedLwmArr))
                            @foreach($relatedLwmArr as $whId => $lWhMId)
                            <tr>
                                <td class="vcenter text-center width-100">{!! ++$sl !!}</td>
                                <td class="vcenter width-480">{!! !empty($warehouseList) && !empty($whId) ?  $warehouseList[$whId] : '' !!}</td>
                                <td class="vcenter width-480">{!! !empty($lwmList) && !empty($lWhMId) ?  $lwmList[$lWhMId] : '' !!}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="vcenter text-center width-100" colspan="4">{{trans('label.NO_RELATED_WAREHOUSE_AND_THANA_WAREHOUSE_MANAGER_FOUND')}}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        </div>
    </div>
</div>
<!-- END:: Contact Person Information-->