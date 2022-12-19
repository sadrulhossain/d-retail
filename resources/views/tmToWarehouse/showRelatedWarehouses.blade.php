<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.RELATED_WAREHOUSE_LIST')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row margin-bottom-10">
            <div class="col-md-12">
                @lang('label.TERRITORIAL_MANAGER'): <strong>{!! $tm->full_name ?? ''!!}</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover relation-view-2">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.WAREHOUSE')</th>
                                <th class="vcenter">@lang('label.ADDRESS')</th>
                            </tr>
                        </thead>
                        <tbody id="exerciseData">
                            @if(!empty($warehouseArr))
                            @php $sl = 0 @endphp
                            @foreach($warehouseArr as $warehouse)
                            <?php
                            $warehouseStatusColor = 'green-seagreen';
                            $warehouseStatusTitle = __('label.ACTIVE');
                            if (!empty($inactiveWarehouseArr) && in_array($warehouse['id'], $inactiveWarehouseArr)) {
                                $warehouseStatusColor = 'red-soft';
                                $warehouseStatusTitle = __('label.INACTIVE');
                            }
                            ?>

                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="vcenter">
                                    {!! $warehouse['warehouse_name'] ?? ''!!}
                                    <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle {{$warehouseStatusColor}} tooltips" title="{{ $warehouseStatusTitle }}">
                                    </button>
                                </td>
                                <td class="vcenter">
                                    {!! $warehouse['address'] ?? ''!!}
                                    <p class="mt-5">
                                    <strong>@lang('label.THANA') : </strong> {!! $warehouse['thana'] ?? ''!!}<br>
                                    <strong>@lang('label.DISTRICT') : </strong> {!! $warehouse['district'] ?? ''!!}<br>
                                    <strong>@lang('label.DIVISION') : </strong> {!! $warehouse['division'] ?? ''!!}
                                    </p>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5" class="text-danger">
                                    @lang('label.NO_WAREHOUSE_FOUND_RELATED_TO_THIS_TM')
                                </td>
                            </tr>
                            @endif      
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $(".tooltips").tooltip();
        $('.relation-view-2').tableHeadFixer();
    });
</script>
<style>
    #exerciseData p{
        margin:0;
    }
    .mt-5{
        margin-top: 5px !important;
    }
</style>
