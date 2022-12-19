@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i>@lang('label.RETURNED_PRODUCT_LIST')
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'admin/stockWhReturnList/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-12 margin-bottom-20">
                        <div class="col-md-3">
                            <div class="form">
                                <label class="control-label" for="refNo">@lang('label.REFERENCE_NO')</label>
                                <div>
                                    {!! Form::text('ref_no',Request::get('ref_no'), ['class' => 'form-control tooltips', 'title' => 'Reference', 'placeholder' => 'Reference','list'=>'refNo', 'autocomplete'=>'off']) !!}
                                    <datalist id="refNo">
                                        @if(!empty($refNoArr))
                                        @foreach($refNoArr as $refNo)
                                        <option value="{{$refNo->reference_no}}"></option>
                                        @endforeach
                                        @endif
                                    </datalist>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form">
                                <label class="control-label">@lang('label.RETURNED_DATE') :</label>
                                <div class="input-group date datepicker2">
                                    {!! Form::text('return_date', Request::get('return_date'), ['id'=> 'checkoutDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="checkoutDate">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form">
                                <label class="control-label" for="warehouse">@lang('label.WAREHOUSE'):</label>
                                {!! Form::select('warehouse_id', $warehouseList, Request::get('warehouse_id'), ['class' => 'form-control js-source-states', 'id' => 'warehouse']) !!}
                                <!--<div id="displayProductHints"></div>-->
                            </div>
                        </div>
                        
                        <div class="col-md-3 margin-top-20">
                            <div class="form">
                                <label class="control-label">&nbsp;</label>
                                <button type="submit" class="btn btn-md green btn-outline filter-submit">
                                    <i class="fa fa-search"></i> @lang('label.FILTER')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
                <!-- End Filter -->
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th class="text-center">@lang('label.SL_NO')</th>
                            <th>@lang('label.REFERENCE_NO')</th>
                            <th>@lang('label.RETURNED_DATE')</th>
                            <th class="text-center">@lang('label.WAREHOUSE')</th>
                            <th>@lang('label.RETURNED_BY')</th>
                            <th class="vcenter">@lang('label.RETURNED_AT')</th>
                            <th class="text-center">@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $target)
                        <tr>
                            <td class="text-center">{!! ++$sl !!}</td>
                            <td>{!! $target->reference_no ?? '' !!}</td>
                            <td>{!! !empty($target->return_date) ? Helper::formatDate($target->return_date) : '' !!}</td>
                            <td class="text-center">{!! $target->warehouse_name ?? '' !!}</td>
                            <td>
                                {!! $target->name ?? '' !!}
                            </td>
                            <td>
                                {!! !empty($target->created_at) ? Helper::formatDateTime($target->created_at) : '' !!}
                            </td>
                            <td class="text-center">
                                @if(!empty($userAccessArr[48][5]))
                                <button type="button" class="btn yellow btn-xs tooltips details-btn" title="@lang('label.VIEW_PRODUCT_DETAILS')" id="detailsBtn-{{$target->reference_no}}" data-target="#productDetails" data-toggle="modal" data-id="{{$target->id}}">
                                    <i class="fa fa-navicon text-white"></i>
                                </button>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="8">@lang('label.NO_RETURNED_ITEM_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>
    </div>
</div>

<!-- details modal -->

<div class="modal fade" id="productDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showProductDetails">

        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).on('click', '.details-btn', function() {

        var returnId = $(this).attr("data-id");
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        $.ajax({
            url: "{{URL::to('admin/stockWhReturnList/getProductDetails')}}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                return_id: returnId
            },
            beforeSend: function() {
                App.blockUI({boxed:true});
            },
            success: function(res) {
                $('#showProductDetails').html(res.html);
                App.unblockUI();
            },
        });
    });

</script>
@stop
