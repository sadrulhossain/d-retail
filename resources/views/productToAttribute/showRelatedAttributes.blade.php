<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.RELATED_ATTRIBUTE_LIST')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row margin-bottom-10">
            <div class="col-md-4">
                @lang('label.PRODUCT'): <strong>{!! $product->name ?? ''!!}</strong>
            </div>
            <div class="col-md-4">
                @lang('label.CODE'): <strong>{!! $product->code ?? ''!!}</strong>
            </div>
            <div class="col-md-4">
                @lang('label.PRODUCT_CATEGORY'): <strong>{!! $product->category_name ?? ''!!}</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover relation-view-2">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.ATTRIBUTE')</th>
                            </tr>
                        </thead>
                        <tbody id="exerciseData">
                            @if(!empty($attributeArr))
                            @php $sl = 0 @endphp
                            @foreach($attributeArr as $attribute)
                            <?php
                            $attributeStatusColor = 'green-seagreen';
                            $attributeStatusTitle = __('label.ACTIVE');
                            if (!empty($inactiveAttributeArr) && in_array($attribute['id'], $inactiveAttributeArr)) {
                                $attributeStatusColor = 'red-soft';
                                $attributeStatusTitle = __('label.INACTIVE');
                            }
                            ?>

                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="vcenter">
                                    {!! $attribute['name'] ?? ''!!}
                                    <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle {{$attributeStatusColor}} tooltips" title="{{ $attributeStatusTitle }}">
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5" class="text-danger">
                                    @lang('label.NO_ATTRIBUTE_FOUND_RELATED_TO_THIS_PRODUCT')
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
