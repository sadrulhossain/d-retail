@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-th-list"></i>@lang('label.PROCUREMENT_LIST')
            </div>
        </div>

        <div class="portlet-body">
            {!! Form::open(array('group' => 'form', 'url' => 'admin/procurementList/filter','class' => 'form-horizontal')) !!}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12 margin-bottom-20">
                        <!-- Begin Filter-->
                        <div class=" col-md-3">
                            <div class="input-group">
                                <label class="control-label" for="reference">@lang('label.REFERENCE'):</label>
                                {!! Form::text('reference',Request::get('reference'), ['id'=> 'reference', 'class' => 'form-control','autocomplete' => 'off']) !!}
                                <div id="displayQtyDetails"></div>
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <label class="control-label">@lang('label.REQ_DATE_FROM') :</label>
                            <div class="input-group date datepicker2">
                                {!! Form::text('req_date_from', Request::get('req_date_from'), ['id'=> 'reqDateFrom', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!}
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
                        <div class="col-md-3">
                            <label class="control-label">@lang('label.REQ_DATE_TO') :</label>
                            <div class="input-group date datepicker2">
                                {!! Form::text('req_date_to', Request::get('req_date_to'), ['id'=> 'reqDateTo', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!}
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
                        <div class="col-md-3 margin-top-27">

                            <button type="submit" class="btn btn-md green btn-outline filter-submit">
                                <i class="fa fa-search"></i> @lang('label.FILTER')
                            </button>

                        </div>
                    </div>

                </div>
                {!! Form::close() !!}

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="center">
                                <th class="text-center">@lang('label.SL_NO')</th>
                                <th class="text-center">@lang('label.REFERENCE')</th>
                                <th class="text-center">@lang('label.REQ_DATE')</th>
                                <th class="text-center">@lang('label.REQ_BY')</th>
                                <th class="text-center">@lang('label.TOTAL')</th>
                                <th class="text-center">@lang('label.APPROVAL_STATUS')</th>
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
                                <td class="text-center">{!! $target->reference ? $target->reference : '' !!}</td>
                                <td class="text-center">{!! !empty($target->req_date) ? Helper::formatDate($target->req_date) : '' !!}</td>
                                <td class="text-center">{!! $target->user_full_name ?? '' !!}</td>
                                <td class="text-right">{!! !empty($target->total) ? Helper::numberFormat2Digit($target->total) : '0.00' !!}&nbsp;@lang('label.TK')</td>
                                <td class="text-center vcenter">
                                        @if($target->approval_status == '1')
                                        <span class="label label-sm label-green-seagreen">@lang('label.APPROVED')</span>
                                        @else
                                        <span class="label label-sm label-blue-steel">@lang('label.PENDING_FOR_APPROVAL')</span>
                                        @endif
                                    </td>
                                <td class="text-center">
                                    
                                    <!--checking is This user is Warehouse Manager or not Start-->  
                                    @if (in_array(Auth::user()->group_id, [1, 11]))
                                    @if ($target->approval_status == '0')
                                    @if(!empty($userAccessArr[135][25]))
                                    <button type="button" class="btn green-sharp btn-xs tooltips approve" data-status="1" title="{{__('label.CLICK_HERE_TO_APPROVE')}}"  data-id="{{$target->id}}">
                                        <i class="fa fa-check"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[135][26]))
                                    <button type="button" class="btn red-mint btn-xs tooltips deny" data-status="0" title="{{__('label.CLICK_HERE_TO_DENY')}}" data-id="{{$target->id}}">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                    @endif
                                    @endif
                                    
                                    @if ($target->approval_status == '1')
                                    @if(in_array($target->id, $procurementMasterId))
                                    @if(!empty($userAccessArr[135][5]))
                                    <button type="button" class="btn blue-steel btn-xs tooltips workOrder-btn" title="View Work Order" id="workOrderBtn-{{$target->id}}" data-target="#workOrder" data-toggle="modal" data-id="{{$target->id}}">
                                        <i class="fa fa-navicon"></i>
                                    </button>
                                    @endif
                                    @else
                                    @if(!empty($userAccessArr[135][5]))
                                    <a class="btn btn-xs purple-sharp tooltips vcenter" href="{{ URL::to('admin/procurementList/workOrder/' . $target->id) }}" data-placement="top" data-rel="tooltip" title="Work Order">
                                        <i class="fa fa-navicon text-secondary"></i>
                                    </a>
                                    @endif
                                    @endif
                                    @endif
                                   
                                    @endif
                                    
                                    @if(!empty($userAccessArr[135][5]))
                                    <button type="button" class="btn yellow btn-xs tooltips procurementDetails-btn" title="Procurement Details" id="detailsBtn-{{$target->id}}" data-target="#procurementDetails" data-toggle="modal" data-id="{{$target->id}}">
                                        <i class="fa fa-navicon text-white"></i>
                                    </button>
                                    <!--checking is This user is Warehouse Manager or not End-->  
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="10">@lang('label.NO_PROCUREMENT_FOUND')</td>
                            </tr>
                            @endif
                        </tbody>

                    </table>
                </div>
                @include('layouts.paginator')

            </div>


        </div>
    </div>
    <!-- END BORDERED TABLE PORTLET-->
</div>


</div>

<!-- modal -->
<div class="modal fade" id="procurementDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showProcurementDetails">

        </div>
    </div>
</div>

<div class="modal fade" id="workOrder" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showWorkOrder">

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#fixedHeadTable').tableHeadFixer();
        //approve payment
        $(document).on("click", ".approve", function (e) {
            e.preventDefault();
            var procurementId = $(this).attr("data-id");
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure you want to approve this procurement?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Approve',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ URL::to('admin/procurementList/approve')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            procurement_id: procurementId,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            location.reload();
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {

                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            App.unblockUI();
                        }
                    });
                }
            });
        });

        //deny payment
        $(document).on("click", ".deny", function (e) {
            e.preventDefault();
            var procurementId = $(this).attr("data-id");
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure you want to deny this procurement?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Deny',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ URL::to('admin/procurementList/deny')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            procurement_id: procurementId,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            location.reload();
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {

                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            App.unblockUI();
                        }
                    });
                }
            });
        });
        
        $(document).on('click', '.procurementDetails-btn', function () {

        var procurementId = $(this).attr("data-id");
        //alert(refNo);return false;
        $.ajax({
            url: "{{URL::to('admin/procurementList/getProcurementModal')}}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                procurement_id: procurementId,
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#showProcurementDetails').html(res.html);
                App.unblockUI();
            },
        });
    });
        $(document).on('click', '.workOrder-btn', function () {

        var procurementMasterId = $(this).attr("data-id");
        //alert(refNo);return false;
        $.ajax({
            url: "{{URL::to('admin/procurementList/getWorkOrderModal')}}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                procurement_master_id: procurementMasterId,
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#showWorkOrder').html(res.html);
                App.unblockUI();
            },
        });
    });


        
       



    });
</script>
@stop