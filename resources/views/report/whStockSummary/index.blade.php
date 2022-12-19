@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.WH_STOCK_SUMMARY_REPORT')
            </div>
            <div class="actions">
                <span class="text-right">
                    @if(!$targetArr->isEmpty())
                    <?php $view = $request->generate == 'true' ? '&' : '?'; ?>
                    @if(!empty($userAccessArr[119][6]))
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::full().$view.'view=print' }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    @endif
                    @if(!empty($userAccessArr[119][9]))
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
            {!! Form::open(array('group' => 'form', 'url' => 'admin/whStockSummaryReport/filter','class' => 'form-horizontal')) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="whId">@lang('label.WAREHOUSE') :<span class="text-danger"> </span></label>
                        <div class="col-md-8">
                            {!! Form::select('wh_id', $whList, Request::get('wh_id'), ['class' => 'form-control js-source-states', 'id' => 'whId']) !!}
                            <span class="text-danger">{{ $errors->first('wh_id') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4"  for="product">@lang('label.PRODUCT')</label>
                        <div class="col-md-8 show-product">
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
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            {{__('label.WAREHOUSE')}} : <strong>{{ !empty($whList[Request::get('wh_id')]) && Request::get('wh_id') != 0 ? $whList[Request::get('wh_id')] : __('label.ALL') }} </strong>
                        </h5>
                    </div>
                </div>
            </div>

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
                                $whId = null;
                                ?>
                                @foreach($targetArr as $target)
                                <?php
                                if (empty(Request::get('wh_id'))) {
                                    if ($target->warehouse_id != $whId) {
                                        $sl = 0;
                                        $whId = $target->warehouse_id;
                                        ?>
                                        <tr class="bg-grey-steel">
                                            <th colspan="6" class="text-center">{!! $target->warehouse??'' !!}</th>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
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

        $(document).on("change", '#whId', function () {
            var whId = $("#whId").val();

            $.ajax({
                url: "{{URL::to('admin/whStockSummaryReport/getProduct')}}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    wh_id: whId,
                },
                success: function (res) {
                    $('.show-product').html(res.html);

                    $('.js-source-states').select2();
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
                },
            });
        });
    });

</script>
@stop