<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="bottom" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">
            @lang('label.CLOSE')
        </button>
        <h3 class="modal-title text-center">
            @lang('label.VIEW_PRODUCT_DETAILS')
        </h3>
    </div>
    <div class="modal-body">

        <div class="row">
            <div class="col-md-4">
                <strong>@lang('label.REFERENCE_NO')</strong> : {!! $returnInfo->reference_no ?? '' !!}
            </div>
            <div class="col-md-4">
                <b>@lang('label.WAREHOUSE')</b> : {!! $returnInfo->warehouse_name ?? '' !!}
            </div>
            <div class="col-md-4">
                <strong>@lang('label.RETURNED_DATE')</strong> : 
                {!! !empty($returnInfo->return_date) ? Helper::formatDate($returnInfo->return_date) : '' !!}
            </div>
        </div>
        <div class="row margin-top-10">
            <div class="col-md-4">
                <b>@lang('label.RETURNED_BY')</b> : {!! (!empty($returnInfo->created_by) && !empty($userArr[$returnInfo->created_by]))? $userArr[$returnInfo->created_by] : '' !!}
            </div>
            <div class="col-md-7">
                <b>@lang('label.RETURNED_AT')</b> : {!! !empty($returnInfo->created_at) ? Helper::formatDateTime($returnInfo->created_at) : '' !!}
            </div>
        </div>


        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover relation-view-2">
                        <thead>
                            <tr>
                                <th class="text-center vcenter"><strong>@lang('label.SL_NO')</strong></th>
                                <th class="vcenter"><strong>@lang('label.CATEGORY')</strong></th>
                                <th class="vcenter"><strong>@lang('label.SKU')</strong></th>
                                <th class="vcenter"><strong>@lang('label.PRODUCT')</strong></th>
                                <th class="text-center vcenter"><strong>@lang('label.QUANTITY')</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$returnDetailsArr->isEmpty())
                            <?php
                            $sl = 0;
                            ?>
                            @foreach($returnDetailsArr as $item)
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="vcenter">{!! $item->category_name !!}</td>
                                <td class="vcenter">{!! $item->sku !!}</td>
                                <td class="vcenter">{!! $item->product_name !!}</td>
                                <td class="text-right vcenter">{!! !empty($item->quantity) ? Helper::numberFormatDigit2($item->quantity) :'' !!}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="12">@lang('label.EMPTY_DATA')</td>
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
