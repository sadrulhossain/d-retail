@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.STOCK_SUMMARY_REPORT')
            </div>
            <div class="actions">
                <span class="text-right">
                    @if(!$targetArr->isEmpty())
                    <?php $view = $request->generate == 'true' ? '&' : '?'; ?>
                    @if(!empty($userAccessArr[108][6]) && !empty($targetArr))
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::full().$view.'view=print' }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    @endif
                    @if(!empty($userAccessArr[108][9]) && !empty($targetArr))
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::full().$view.'view=pdf' }}"  title="@lang('label.DOWNLOAD')">
                        <i class="fa fa-file-pdf-o"></i>
                    </a>
                    @endif
                    @endif
                </span>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'admin/centralStockSummaryReport/filter','class' => 'form-horizontal')) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4"  for="product">@lang('label.PRODUCT')</label>
                        <div class="col-md-8">
                            <?php $productList = explode(",", Request::get('product')); ?>
                            {!! Form::select('product[]', $productArr, $productList, ['class' => 'form-control mt-multiselect btn btn-default', 'id' => 'product', 'multiple' => 'multiple', 'data-width' => '100%']) !!}
                            <span class="text-danger">{{ $errors->first('product') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit">
                            <i class="fa fa-search"></i> @lang('label.GENERATE')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->

            <div class="row">
                <div class="col-md-12">
                    <div class="tableFixHead max-height-500 webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color" id="fixTable">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="vcenter">@lang('label.CATEGORY')</th>
                                    <th class="vcenter">@lang('label.PRODUCT')</th>
                                    <th class="vcenter">@lang('label.BRAND')</th>
                                    <th class="vcenter">@lang('label.SKU')</th>
                                    <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$targetArr->isEmpty())
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($targetArr as $target)
                                <tr>
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="vcenter">{!! $target->product_category !!}</td>
                                    <td class="vcenter">{!! $target->product !!}</td>
                                    <td class="vcenter">{!! $target->brand !!}</td>
                                    <td class="vcenter bold">{!! $target->sku !!}</td>
                                    <td class="text-right vcenter">
                                        {!! !empty($target->available_quantity) ? Helper::numberFormat($target->available_quantity, 0) : '0' !!}
                                        &nbsp;{!! !empty($target->unit) ? $target->unit : '' !!}
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6" class="vcenter">@lang('label.NO_PRODUCT_FOUND')</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $("#fixTable").tableHeadFixer();

        var productAllSelected = false;
        $('#product').multiselect({
            numberDisplayed: 0,
            includeSelectAllOption: true,
            buttonWidth: '100%',
            maxHeight: 250,
            nonSelectedText: "@lang('label.SELECT_PRODUCT')",
//        enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            onSelectAll: function () {
                productAllSelected = true;
            },
            onChange: function () {
                productAllSelected = false;
            }
        });

//        $(document).on("change", '#productId', function () {
//            var productId = $("#productId").val();
//            //alert(productId);return false;
//            var options = {
//                closeButton: true,
//                debug: false,
//                positionClass: "toast-bottom-right",
//                onclick: null,
//            };
//
//            $.ajax({
//                url: "{{URL::to('admin/stockSummaryReport/getSupplierManufacturer')}}",
//                type: 'POST',
//                dataType: 'json',
//                headers: {
//                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                },
//                data: {
//                    product_id: productId,
//                },
//                success: function (res) {
//                    $('#showSupplierManufacturer').html(res.html);
//                    $('.js-source-states').select2();
//                },
//            });
//        });
    });

</script>
@stop
