<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="bottom" class="btn red pull-right tooltips margin-left-2" title="@lang('label.CLOSE_THIS_POPUP')">
            @lang('label.CLOSE')
        </button>
        <!--pdf-->
        <a class="btn green-sharp tooltips vcenter pull-right margin-left-right-2" target="_blank" href="{{ URL::to('admin/procurementList/workOrderPdf?view=pdf&workorder_master_id=' . $info->id) }}"  title="@lang('label.DOWNLOAD')">
            <i class="fa fa-download"></i>
        </a>
        <a class="btn blue-soft tooltips vcenter pull-right margin-left-right-2" target="_blank" href="{{ URL::to('admin/procurementList/workOrderPrint?view=print&workorder_master_id=' . $info->id) }}"  title="@lang('label.PRINT')">
            <i class="fa fa-print"></i>
        </a>

        <h3 class="modal-title text-center">
            @lang('label.WORK_ORDER')
        </h3>
    </div>
    <div class="modal-body">

        <div class="row">
            <div class="col-md-4">
                <strong>@lang('label.REFERENCE_NO')</strong> : {!! $info->reference ?? '' !!}
            </div>
            <div class="col-md-4">
                <strong>@lang('label.SUPPLIER')</strong> : 
                {!! !empty($info->supplier_id) && !empty($supplierList[$info->supplier_id]) ? $supplierList[$info->supplier_id] : '' !!}
            </div>
            <div class="col-md-4">
                <strong>@lang('label.ISSUE_DATE')</strong> : 
                {!! !empty($info->issue_date)  ? Helper::formatDate($info->issue_date) : '' !!}
            </div>


        </div>

        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover relation-view-2">
                        <thead>
                            <tr>
                                <th class="text-center vcenter"><strong>@lang('label.SL_NO')</strong></th>
                                <th class="text-center vcenter"><strong>@lang('label.SKU')</strong></th>
                                <th class="text-center vcenter"><strong>@lang('label.QUANTITY')</strong></th>
                                <th class="text-center vcenter"><strong>@lang('label.UNIT_PRICE')</strong></th>
                                <th class="text-center vcenter"><strong>@lang('label.TOTAL_PRICE')</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($targetArr))
                            <?php
                            $sl = 0;
                            ?>
                            @foreach($targetArr as $target)
                            <tr>
                                <td class="text-center">{!! ++$sl !!}</td>
                                <td class="text-right">{!!  $skuList[$target['sku_id']] !!}</td>
                                <td class="text-right">{!! !empty($target['quantity']) ? Helper::numberFormat2Digit($target['quantity']) : '' !!}</td>
                                <td class="text-right">{!! !empty($target['unit_price']) ? Helper::numberFormat2Digit($target['unit_price']) : '' !!}&nbsp;@lang('label.TK')</td>
                                <td class="text-right">{!! !empty($target['total_price']) ? Helper::numberFormat2Digit($target['total_price']) : '0.00' !!}&nbsp;@lang('label.TK')</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class=" vcenter text-right" colspan="4">@lang('label.GRAND_TOTAL')</td>
                                <td class="text-right">
                                    {!! Helper::numberFormat2Digit($info->grand_total) !!}&nbsp;@lang('label.TK')
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="10">@lang('label.NO_WORK_ORDER_FOUND')</td>
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


