@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-industry"></i>@lang('label.RELATE_WAREHOUSE_TO_SR')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'warehouseToSrForm')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="warehouseId">@lang('label.WAREHOUSE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('warehouse_id', $warehouseList, Request::get('warehouse_id'), ['class' => 'form-control js-source-states', 'id' => 'warehouseId']) !!}
                                <span class="text-danger">{{ $errors->first('warehouse_id') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br/>
                        <div id="showSr">
                            @if(!empty(Request::get('warehouse_id')))
                            <div class="row margin-bottom-10">
                                <div class="col-md-12">
                                    <span class="label label-success" >@lang('label.TOTAL_NUM_OF_SR'): {!! !empty($srArr) ? count($srArr):0 !!}</span>
                                    @if(!empty($userAccessArr[41][5]))
                                    <button class='label label-primary tooltips' type="button" href="#modalRelatedSr" id="relatedSr"  data-toggle="modal" title="@lang('label.SHOW_RELATED_WAREHOUSE')">
                                        @lang('label.SR_RELATED_TO_THIS_WAREHOUSE'): {!! !empty($warehouseRelateToSr)?count($warehouseRelateToSr):0 !!}&nbsp; <i class="fa fa-search-plus"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover relation-view">
                                            <thead>
                                                <tr class="active">
                                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                                    @if(!empty($srArr))
                                                    <?php
                                                    $allCheckDisabled = '';
                                                    if(!empty($otherSrWhArr)){
                                                        $allCheckDisabled ='disabled';
                                                    }
                                                    ?>
                                                    <th class="vcenter">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-sr-check', $allCheckDisabled]) !!}
                                                            <label for="checkAll">
                                                                <span class="inc"></span>
                                                                <span class="check mark-caheck"></span>
                                                                <span class="box mark-caheck"></span>
                                                            </label>
                                                            &nbsp;&nbsp;<span>@lang('label.CHECK_ALL')</span>
                                                        </div>
                                                    </th>
                                                    @endif

                                                    <th class="vcenter">@lang('label.SR_NAME')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($srArr))
                                                <?php $sl = 0; ?>
                                                @foreach($srArr as $sr)
                                                <?php
                                                //check and show previous value
                                                $checked = $srTitle = '';
                                                if (!empty($warehouseRelateToSr) && array_key_exists($sr['id'], $warehouseRelateToSr)) {
                                                    $checked = 'checked';
                                                }

                                                $srDisabled = $srTooltips = '';
                                                $checkCondition = 0;
                                                if (!empty($inactiveSrArr) && in_array($sr['id'], $inactiveSrArr)) {
                                                    if ($checked == 'checked') {
                                                        $checkCondition = 1;
                                                    }
                                                    $srDisabled = 'disabled';
                                                    $srTooltips = __('label.INACTIVE');
                                                }
                                                if(!empty($otherSrWhArr)){
                                                    if(array_key_exists($sr['id'],$otherSrWhArr)){
                                                       $srDisabled = 'disabled';
                                                       $srTitle = __('label.ALREADY_ASSIGNED_TO_WAREHOUSE',['wh'=> $otherSrWhArr[$sr['id']]]);
                                                    }     
                                                }
                                                ?>
                                                <tr>
                                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                                    <td class="vcenter">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('sr['.$sr['id'].']', $sr['id'], $checked, ['id' => $sr['id'], 'data-id'=> $sr['id'],'class'=> 'md-check sr-check', $srDisabled]) !!}
                                                            <label for="{!! $sr['id'] !!}">
                                                                <span class="inc tooltips" data-placement="right" title="{{ $srTitle }}"></span>
                                                                <span class="check mark-caheck tooltips" data-placement="right" title="{{ $srTitle }}"></span>
                                                                <span class="box mark-caheck tooltips" data-placement="right" title="{{ $srTitle }}"></span>
                                                            </label>
                                                        </div>
                                                        @if($checkCondition == '1')
                                                        {!! Form::hidden('sr['.$sr['id'].']', $sr['id']) !!}
                                                        @endif
                                                    </td>

                                                    <td class="vcenter">{!! $sr['full_name'] ?? '' !!}</td>

                                                    


                                                </tr>
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td class="vcenter text-danger" colspan="20">@lang('label.NO_ATTRIBUTE_FOUND')</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-4 col-md-8">
                                        @if(!empty($srArr))
                                        @if(!empty($userAccessArr[41][7]))
                                        <button class="btn btn-circle green btn-submit" id="" type="button">
                                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                                        </button>
                                        @endif
                                        @if(!empty($userAccessArr[41][1]))
                                        <a href="{{ URL::to('/admin/warehouseToSr') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<!-- Modal start -->
<div class="modal fade" id="modalRelatedSR" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showRelatedSr">
        </div>
    </div>
</div>




<!-- Modal end-->
<script type="text/javascript">
    $(function () {
//        $('.tooltips').tooltip();
<?php if (!empty($srArr)) { ?>
            $('.relation-view').dataTable({
                "language": {
                    "search": "Search Keywords : ",
                },
                "paging": true,
                "info": true,
                "order": false
            });
<?php } ?>

        $(".sr-check").on("click", function () {
            if ($('.sr-check:checked').length == $('.sr-check').length) {
                $('.all-sr-check').prop("checked", true);
            } else {
                $('.all-sr-check').prop("checked", false);
            }
        });
        $(".all-sr-check").click(function () {
            if ($(this).prop('checked')) {
                $('.sr-check').prop("checked", true);
            } else {
                $('.sr-check').prop("checked", false);
            }
        });
        if ($('.sr-check:checked').length == $('.sr-check').length) {
            $('.all-sr-check').prop("checked", true);
        } else {
            $('.all-sr-check').prop("checked", false);
        }

        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };


        $(document).on('change', '#warehouseId', function () {
            var warehouseId = $('#warehouseId').val();

            if (warehouseId == '0') {
                $('#showSr').html('');
                return false;
            }
            $.ajax({
                url: '{{URL::to("admin/warehouseToSr/getSrToRelate")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    warehouse_id: warehouseId,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showSr').html(res.html);
                    $('.tooltips').tooltip();
                    App.unblockUI();
                }, error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
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
        });
        
        

        $(document).on("click", "#relatedSr", function (e) {
            e.preventDefault();
            var warehouseId = $("#warehouseId").val();
            $.ajax({
                url: "{{ URL::to('/admin/warehouseToSr/getRelatedSr')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    warehouse_id: warehouseId
                },
                beforeSend: function () {
                    $("#showRelatedSr").html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $("#showRelatedSr").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });


        //insert sales person to buyer
        $(document).on("click", ".btn-submit", function (e) {
            e.preventDefault();
            var oTable = $('.relation-view').dataTable();
            var x = oTable.$('input,select,textarea').serializeArray();
            $.each(x, function (i, field) {
                $("#warehouseToSrForm").append(
                        $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', field.name)
                        .val(field.value));
            });
            swal({
                title: 'Are you sure?',
                text: "You can not undo this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true},
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            var options = {
                                closeButton: true,
                                debug: false,
                                positionClass: "toast-bottom-right",
                                onclick: null,
                            };
                            // Serialize the form data
                            var form_data = new FormData($('#warehouseToSrForm')[0]);
                            $.ajax({
                                url: "{{URL::to('admin/warehouseToSr/relateWarehouseToSr')}}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    var warehouseId = $('#warehouseId').val();
                                    location = "warehouseToSr?warehouse_id=" + warehouseId;
                                },
                                error: function (jqXhr, ajaxOptions, thrownError) {
                                    if (jqXhr.status == 400) {
                                        var errorsHtml = '';
                                        var errors = jqXhr.responseJSON.message;
                                        $.each(errors, function (key, value) {
                                            errorsHtml += '<li>' + value[0] + '</li>';
                                        });
                                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                                    } else if (jqXhr.status == 401) {
                                        toastr.error(jqXhr.responseJSON.message, '', options);
                                    } else {
                                        toastr.error('Error', 'Something went wrong', options);
                                    }
                                }
                            });
                        }
                    });
        });

    });
</script>
@stop