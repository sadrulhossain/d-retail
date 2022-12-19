<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="bottom" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">
            @lang('label.CLOSE')
        </button>
        <h3 class="modal-title text-center">
            @lang('label.RETURN_PRODUCT_DETAILS')
        </h3>
    </div>

    <div class="modal-body">

        <div class="row">
            <div class="col-md-4">
                <strong>@lang('label.REFERENCE_NO')</strong> : {!! $return->reference_no ?? '' !!}
            </div>
            <div class="col-md-4">
                <strong>@lang('label.SUPPLIER')</strong> : {!! $return->supplier ?? '' !!}
            </div>
            <div class="col-md-4">
                <strong>@lang('label.RETURN_DATE')</strong> : {!! !empty($return->return_date) ? Helper::formatDate($return->return_date) : '' !!}
            </div>
            <div class="col-md-4">
                <strong>@lang('label.PURCHASE_REFERENCE')</strong> : {!! !empty($return->return_date) ? Helper::formatDate($return->return_date) : '' !!}
            </div>
            <div class="col-md-4">
                <strong>@lang('label.RETURNED_BY')</strong> : {!! $return->returned_by ?? '' !!}
            </div>
            <div class="col-md-4">
                <strong>@lang('label.RETURNED_AT')</strong> : {!! !empty($return->created_at) ? Helper::formatDateTime($return->created_at) : '' !!}
            </div>
        </div>
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover relation-view-2">
                        <thead>
                            <tr>
                                <th class="text-center vcenter"><strong>@lang('label.SL_NO')</strong></th>
                                <th class="text-center vcenter"><strong>@lang('label.PRODUCT_SKU')</strong></th>
                                <th class="text-center vcenter"><strong>@lang('label.PURCHASE_QUANTITY')</strong></th>
                                <th class="text-center vcenter"><strong>@lang('label.AVAILABLE_QUANTITY')</strong></th>
                                <th class="text-center vcenter"><strong>@lang('label.RETURN_QUANTITY')</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($returnProductInfo))
                            <?php
                            $sl = 0;
                            ?>
                            @foreach($returnProductInfo as $product)
                            <tr>
                                <td class="text-center">{!! ++$sl !!}</td>
                                <td class="text-center">{!! $product['sku'] ?? '' !!}</td>
                                <td class="text-right">{!! $product['purchase_quantity'] !!}</td>
                                <td class="text-right">{!! $product['available_quantity'] !!}</td>
                                <td class="text-right">{!! $product['return_quantity'] !!}</td>

                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="10">@lang('label.NO_RETURN_PRODUCT_FOUND')</td>
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




