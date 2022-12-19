@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i>@lang('label.PURCHASED_ITEM_LIST')
            </div>
        </div>
        
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'admin/productCheckInList/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-12 margin-bottom-20">
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="refNo">@lang('label.REFERENCE_NO')</label>
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
                            <div class="col-md-3">
                                <label class="control-label" for="chalanNo">@lang('label.CHALLAN_NO')</label>
                                <div>
                                    {!! Form::text('challan_no',Request::get('challan_no'), ['class' => 'form-control tooltips', 'title' => 'Challan No', 'placeholder' => 'Challan No', 'list'=>'challanNo', 'autocomplete'=>'off']) !!}
                                    <datalist id="challanNo">
                                        @if(!empty($challanNoArr))
                                        @foreach($challanNoArr as $challanNo)
                                        <option value="{{$challanNo->challan_no}}"></option>
                                        @endforeach
                                        @endif
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">@lang('label.CHECKIN_DATE') :</label>

                                <div class="input-group date datepicker2">
                                    {!! Form::text('checkin_date', Request::get('checkin_date'), ['id'=> 'checkinDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!}
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="checkinDate">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="row mb-15">
                    <div class="col-md-2 col-md-offset-5">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit">
                            <i class="fa fa-search"></i> @lang('label.FILTER')
                        </button>
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
                            <th>@lang('label.CHECKIN_DATE')</th>
                            <th>@lang('label.REFERENCE_NO')</th>
                            <th>@lang('label.CHALLAN_NO')</th>
                            <th>@lang('label.CHECKIN_BY')</th>
                            <th>@lang('label.CHECKIN_AT')</th>
                            <th>@lang('label.ACTION')</th>
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
                            <td>{!! !empty($target->checkin_date) ? Helper::formatDate($target->checkin_date) : '' !!}</td>
                            <td>{!! $target->ref_no ?? '' !!}</td>
                            <td>{!! $target->challan_no ?? '' !!}</td>

                            <td>
                                {!! $target->user_full_name ?? '' !!}
                            </td>
                            <td>
                                {!! !empty($target->created_at) ? Helper::formatDateTime($target->created_at) : '' !!}
                            </td>
                            <td class="text-center">
                                @if(!empty($userAccessArr[99][5]))
                                <button type="button" class="btn yellow btn-xs tooltips details-btn" title="View Checked in product Details" id="detailsBtn-{{$target->id}}" data-target="#productDetails" data-toggle="modal" data-id="{{$target->id}}">
                                    <i class="fa fa-navicon text-white"></i>
                                </button>
                                 <!--checking is This user is Warehouse Manager or not Start-->  
                                @if (in_array(Auth::user()->group_id, [1, 11]))
                                @if ($target->approval_status == '0')
                                <button type="button" class="btn green-sharp btn-xs tooltips approve" data-status="1" title="{{__('label.CLICK_HERE_TO_APPROVE')}}"  data-id="{{$target->id}}">
                                    <i class="fa fa-check"></i>
                                </button>
                                <button type="button" class="btn red-mint btn-xs tooltips deny" data-status="0" title="{{__('label.CLICK_HERE_TO_DENY')}}" data-id="{{$target->id}}">
                                    <i class="fa fa-ban"></i>
                                </button>
                                @endif
                                @endif
                                 <!--checking is This user is Warehouse Manager or not End-->  
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="10">@lang('label.NO_PUCHASED_ITEM_FOUND')</td>
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
    $(document).on('click', '.details-btn', function () {

        var masterId = $(this).attr("data-id");
        //alert(refNo);return false;
        $.ajax({
            url: "{{URL::to('admin/productCheckInList/getProductDetails')}}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                master_id: masterId,
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#showProductDetails').html(res.html);
                App.unblockUI();
            },
        });
    });

    // Approval code start
    $(document).on('click', '.approve', function (e) {
        e.preventDefault();
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        swal({
            title: "Do you really want to Approve this product purchase?",
            text: "@lang('label.DO_YOU_WANT_TO_CHANGE_IT')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('label.YES'), Approve it.",
            cancelButtonText: "@lang('label.NO_CANCEL')",
            closeOnConfirm: true,
            closeOnCancel: true,
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "{{ URL::to('admin/productCheckInList/approve') }}",
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id, approve: status
                    },
                    beforeSend: function () {
                        App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                        App.unblockUI();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {

                        if (jqXhr.status == 400) {
                            var errorsHtml = '';
                            var errors = jqXhr.responseJSON.message;
                            $.each(errors, function (key, value) {
                                errorsHtml += '<li>' + value + '</li>';
                            });
                            toastr.error(errorsHtml, jqXhr.responseJSON.heading,
                                    options);
                        } else if (jqXhr.status == 401) {
                            toastr.error(jqXhr.responseJSON.message, '',
                                    options);
                        } else if (jqXhr.status == 422) {
                            toastr.error(jqXhr.responseJSON.message, '', options);
                        } else {
                            toastr.error('Error', "@lang('label.SOMETHING_WENT_WRONG')",
                                    options);
                        }
                        App.unblockUI();
                    }
                });
            }
        });
    });
    // Approval code End
    // 
    // Deny code start
    $(document).on('click', '.deny', function (e) {
        e.preventDefault();
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        swal({
            title: "Do you really want to Deny this product purchase?",
            text: "@lang('label.DO_YOU_WANT_TO_CHANGE_IT')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('label.YES'),  Deny it.",
            cancelButtonText: "@lang('label.NO_CANCEL')",
            closeOnConfirm: true,
            closeOnCancel: true,
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "{{ URL::to('admin/productCheckInList/deny') }}",
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id,
                    },
                    beforeSend: function () {
                        App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                        App.unblockUI();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {

                        if (jqXhr.status == 400) {
                            var errorsHtml = '';
                            var errors = jqXhr.responseJSON.message;
                            $.each(errors, function (key, value) {
                                errorsHtml += '<li>' + value + '</li>';
                            });
                            toastr.error(errorsHtml, jqXhr.responseJSON.heading,
                                    options);
                        } else if (jqXhr.status == 401) {
                            toastr.error(jqXhr.responseJSON.message, '',
                                    options);
                        } else if (jqXhr.status == 422) {
                            toastr.error(jqXhr.responseJSON.message, '', options);
                        } else {
                            toastr.error('Error', "@lang('label.SOMETHING_WENT_WRONG')",
                                    options);
                        }
                        App.unblockUI();
                    }
                });
            }
        });
    });
    // Deny code End


</script>
@stop
