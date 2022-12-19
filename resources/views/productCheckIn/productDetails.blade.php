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
                <strong>@lang('label.REFERENCE_NO')</strong> : {!! $target->ref_no ?? '' !!}
            </div>
            <div class="col-md-4">
                <b>@lang('label.CHALLAN_NO')</b> : {!!  $target->challan_no ?? '' !!}
            </div>
            <div class="col-md-4">
                <strong>@lang('label.CHECKIN_DATE')</strong> :
                {!! !empty($target->checkin_date) ? Helper::formatDate($target->checkin_date) : '' !!}
            </div>

        </div>
        <div class="row margin-top-10">
            <div class="col-md-4">
                <b>@lang('label.CHECKIN_BY')</b> : {!! $target->user_full_name ?? '' !!}
            </div>
            <div class="col-md-7">
                <b>@lang('label.CHECKIN_AT')</b> : {!! !empty($target->created_at) ? Helper::formatDateTime($target->created_at) : '' !!}
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
                                <th class="vcenter"><strong>@lang('label.SUPPLIER')</strong></th>
                                <th class="text-center vcenter"><strong>@lang('label.QUANTITY')</strong></th>
                                <th class="text-right vcenter"><strong>@lang('label.RATE')</strong></th>
                                <th class="text-right vcenter"><strong>@lang('label.TOTAL_PRICE')</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$targetArr->isEmpty())
                            <?php
                            $sl = $totalPrice = 0;
                            ?>
                            @foreach($targetArr as $item)
                            <?php
                            $totalPrice += $item->rate * $item->quantity;
                            ?>
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="vcenter">{!! $item->category_name !!}</td>
                                <td class="vcenter">{!! $item->sku !!}</td>
                                <td class="vcenter">{!! $item->product_name !!}</td>
                                <td class="vcenter">{!! $item->supplier_name !!}</td>
                                <td class="text-right vcenter">{!! $item->quantity !!}</td>
                                <td class="text-right vcenter">{!! Helper::numberFormatDigit2($item->rate)!!} @lang('label.TK')/@lang('label.UNIT_PIS')</td>
                                <td class="text-right vcenter">{!! Helper::numberFormatDigit2($item->rate * $item->quantity)!!} &#2547;</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="text-right" colspan="7"><b>@lang('label.TOTAL') : </b></td>
                                <td class="text-right">{!! Helper::numberFormatDigit2($totalPrice) !!} &#2547;</td>
                            </tr>
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
