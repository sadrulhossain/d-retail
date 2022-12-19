<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="bottom" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">
            @lang('label.CLOSE')
        </button>
        <h3 class="modal-title text-center">
            @lang('label.SELECTED_SKU_LIST')
        </h3>
    </div>
    <div class="modal-body">

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover relation-view-2">
                        <thead>
                            <tr class="active">
                                <th class="vcenter text-center">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.SKU')</th>
                                <th class="vcenter">@lang('label.PRODUCT_NAME')</th>
                                <th class="vcenter">@lang('label.BRAND')</th>
                            </tr>
                        </thead>
                        <tbody id="exerciseData">

                            @if (!$targetArr->isEmpty())
                            <?php $sl = 0; ?>
                            @foreach($targetArr as $item)
                            <tr>
                                <td class="vcenter text-center">{!! ++$sl !!}</td>
                                <td class="vcenter">{{ $item->sku }}</td>
                                <td class="vcenter">{{ $item->product_name }}</td>
                                <td class="vcenter">{!! $item->brand_name !!}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="6" class="text-danger">
                                    @lang('label.NO_SELECTED_SKU')
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
