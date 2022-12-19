<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.RELATED_SUPPLIER_LIST')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row margin-bottom-10">
            <div class="col-md-4">
                @lang('label.PRODUCT'): <strong>{!! $product->name ?? ''!!}</strong>
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
                                <th class="vcenter">@lang('label.SUPPLIER')</th>
                            </tr>
                        </thead>
                        <tbody id="exerciseData">
                            @if(!$productWiseSupplierInfo->isEmpty())
                            @php $sl = 0 @endphp
                            @foreach($productWiseSupplierInfo as $supplier)
                            <?php
                            $supplierStatusColor = 'green-seagreen';
                            $supplierStatusTitle = __('label.ACTIVE');
                            if (!empty($inactiveSupplierArr) && in_array($supplier->id, $inactiveSupplierArr)) {
                                $supplierStatusColor = 'red-soft';
                                $supplierStatusTitle = __('label.INACTIVE');
                            }
                            ?>

                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="vcenter">
                                    {!! $supplier->name ?? ''!!}
                                    <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle {{$supplierStatusColor}} tooltips" title="{{ $supplierStatusTitle }}">
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5" class="text-danger">
                                    @lang('label.NO_PRODUCT_FOUND_RELATED_TO_THIS_SUPPLIER')
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
