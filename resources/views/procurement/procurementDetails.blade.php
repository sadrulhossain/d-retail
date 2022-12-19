<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="bottom" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">
            @lang('label.CLOSE')
        </button>
        <h3 class="modal-title text-center">
            @lang('label.PROCUREMENT_DETAILS')
        </h3>
    </div>
 
    <div class="modal-body">

        <div class="row">
            <div class="col-md-4">
                <strong>@lang('label.REFERENCE_NO')</strong> : {!! $procurementInfo->reference ?? '' !!}
            </div>
            <div class="col-md-4">
                <strong>@lang('label.REQ_DATE')</strong> : 
                {!! !empty($procurementInfo->req_date) ? Helper::formatDate($procurementInfo->req_date) : '' !!}
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
                            @if (!$targetArr->isEmpty())
                            <?php
                            $sl = 0;
                            ?>
                            @foreach($targetArr as $target)
                            <tr>
                                <td class="text-center">{!! ++$sl !!}</td>
                                <td class="text-center">{!! $target->sku !!}</td>
                                <td class="text-right">{!! !empty($target->quantity) ? Helper::numberFormat2Digit($target->quantity) : '' !!}</td>
                                <td class="text-right">{!! !empty($target->unit_price) ? Helper::numberFormat2Digit($target->unit_price) : '' !!}&nbsp;@lang('label.TK')</td>
                                <td class="text-right">{!! !empty($target->total_price) ? Helper::numberFormat2Digit($target->total_price) : '0.00' !!}&nbsp;@lang('label.TK')</td>
                                
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="10">@lang('label.NO_PROCUREMENT_FOUND')</td>
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


