@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i>@lang('label.RETURN_PRO_LIST')
            </div>
        </div>

        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'admin/returnProductList/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-12 margin-bottom-20">
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="refNo">@lang('label.REFERENCE'):</label>
                                <div>
                                    {!! Form::text('ref_no',Request::get('ref_no'), ['class' => 'form-control tooltips', 'title' => 'Reference', 'placeholder' => 'Reference', 'list'=>'refNo', 'autocomplete'=>'off']) !!}
                                    <datalist id="refNo">
                                        @if(!empty($refNoArr))
                                        @foreach($refNoArr as $refNo)
                                        <option value="{{$refNo->ref_no}}"></option>
                                        @endforeach
                                        @endif
                                    </datalist>
                                </div>
                            </div>

                            <div class="col-md-3  margin-top-27">
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
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.REFERENCE')</th>
                            <th class="text-center">@lang('label.PURCHASE_REFERENCE')</th>
                            <th class="text-center vcenter">@lang('label.RETURN_DATE')</th>
                            <th class="text-center">@lang('label.RETURNED_BY')</th>
                            <th class="text-center vcenter">@lang('label.RETURNED_AT')</th>
                            <th class="text-center vcenter">@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($targetArr))
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $target)
                        <tr>
                            <td class="text-center vcenter">{!! ++$sl !!}</td>
                            <td class="vcenter">{!! $target->reference_no ?? '' !!}</td>
                            <td class="vcenter">{!! $target->purchase_ref_no ?? '' !!}</td>
                            <td class="vcenter">{!! $target->supplier ?? '' !!}</td>
                            <td class="vcenter">{!! !empty($target->return_date) ? Helper::formatDate($target->return_date) : '' !!}</td>
                            <td class="vcenter">{!! $target->returned_by ?? '' !!}</td>
                            <td class="vcenter">{!! !empty($target->created_at) ? Helper::formatDateTime($target->created_at) : '' !!}</td>
                            
                            <td class="text-center vcenter">
                                @if(!empty($userAccessArr[48][5]))
                                <button type="button" class="btn yellow btn-xs tooltips returnProductDetails-btn" title="Return Product Details" id="detailsBtn-{{$target->id}}" data-target="#returnProductDetails" data-toggle="modal" data-id="{{$target->id}}">
                                    <i class="fa fa-navicon text-white"></i>
                                </button>
                                @endif
                            </td>
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
            @include('layouts.paginator')
        </div>
    </div>
</div>

<!-- details modal -->
<div class="modal fade" id="returnProductDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showReturnProductDetails">

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#fixedHeadTable').tableHeadFixer();
        
        $(document).on('click', '.returnProductDetails-btn', function () {

        var returnId = $(this).attr("data-id");
        //alert(refNo);return false;
        $.ajax({
            url: "{{URL::to('admin/returnProductList/getReturnProductModal')}}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                return_id: returnId,
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#showReturnProductDetails').html(res.html);
                App.unblockUI();
            },
        });
    });

    });
</script>
@stop
