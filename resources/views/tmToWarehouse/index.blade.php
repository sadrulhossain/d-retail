@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-industry"></i>@lang('label.RELATE_TM_TO_WAREHOUSE')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'tmToWarehouseForm')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="tmId">@lang('label.TERRITORIAL_MANAGER') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('tm_id', $tmList, Request::get('tm_id'), ['class' => 'form-control js-source-states', 'id' => 'tmId']) !!}
                                <span class="text-danger">{{ $errors->first('tm_id') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br/>
                        <div id="showWarehouses">
                            @if(!empty(Request::get('tm_id')))
                            <div class="row margin-bottom-10">
                                <div class="col-md-12">
                                    <span class="label label-success" >@lang('label.TOTAL_NUM_OF_WAREHOUSE'): {!! !empty($warehouseArr) ? count($warehouseArr):0 !!}</span>
                                    @if(!empty($userAccessArr[40][5]))
                                    <button class='label label-primary tooltips' href="#modalRelatedWarehouse" id="relateWarehouse"  data-toggle="modal" title="@lang('label.SHOW_RELATED_WAREHOUSE')">
                                        @lang('label.WAREHOUSE_RELATED_TO_THIS_TM'): {!! !empty($warehouseRelateToTm)?count($warehouseRelateToTm):0 !!}&nbsp; <i class="fa fa-search-plus"></i>
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
                                                    @if(!empty($warehouseArr))
                                                    <?php
                                                    $allCheckDisabled = '';
                                                    if(!empty($otherTmWhArr)){
                                                        $allCheckDisabled ='disabled';
                                                    }
                                                    ?>
                                                    <th class="vcenter">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-warehouse-check', $allCheckDisabled]) !!}
                                                            <label for="checkAll">
                                                                <span class="inc"></span>
                                                                <span class="check mark-caheck"></span>
                                                                <span class="box mark-caheck"></span>
                                                            </label>
                                                            &nbsp;&nbsp;<span>@lang('label.CHECK_ALL')</span>
                                                        </div>
                                                    </th>
                                                    @endif

                                                    <th class="vcenter">@lang('label.WAREHOUSE_NAME')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($warehouseArr))
                                                <?php $sl = 0; ?>
                                                @foreach($warehouseArr as $warehouse)
                                                <?php
                                                //check and show previous value
                                                $checked = $tmTitle = '';
                                                if (!empty($warehouseRelateToTm) && array_key_exists($warehouse['id'], $warehouseRelateToTm)) {
                                                    $checked = 'checked';
                                                }

                                                $warehouseDisabled = $warehouseTooltips = '';
                                                $checkCondition = 0;
                                                if (!empty($inactiveWarehouseArr) && in_array($warehouse['id'], $inactiveWarehouseArr)) {
                                                    if ($checked == 'checked') {
                                                        $checkCondition = 1;
                                                    }
                                                    $warehouseDisabled = 'disabled';
                                                    $warehouseTooltips = __('label.INACTIVE');
                                                }
                                                if(!empty($otherTmWhArr)){
                                                    if(array_key_exists($warehouse['id'],$otherTmWhArr)){
                                                       $warehouseDisabled = 'disabled';
                                                       $tmTitle = __('label.ALREADY_ASSIGNED_TO_TM',['tm'=> $otherTmWhArr[$warehouse['id']]]);
                                                    }     
                                                }
                                                ?>
                                                <tr>
                                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                                    <td class="vcenter">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('warehouse['.$warehouse['id'].']', $warehouse['id'], $checked, ['id' => $warehouse['id'], 'data-id'=> $warehouse['id'],'class'=> 'md-check warehouse-check', $warehouseDisabled]) !!}
                                                            <label for="{!! $warehouse['id'] !!}">
                                                                <span class="inc tooltips" data-placement="right" title="{{ $tmTitle }}"></span>
                                                                <span class="check mark-caheck tooltips" data-placement="right" title="{{ $tmTitle }}"></span>
                                                                <span class="box mark-caheck tooltips" data-placement="right" title="{{ $tmTitle }}"></span>
                                                            </label>
                                                        </div>
                                                        @if($checkCondition == '1')
                                                        {!! Form::hidden('warehouse['.$warehouse['id'].']', $warehouse['id']) !!}
                                                        @endif
                                                    </td>

                                                    <td class="vcenter">{!! $warehouse['name'] ?? '' !!}</td>

                                                    


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
                                        @if(!empty($warehouseArr))
                                        @if(!empty($userAccessArr[40][7]))
                                        <button class="btn btn-circle green btn-submit" id="" type="button">
                                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                                        </button>
                                        @endif
                                        @if(!empty($userAccessArr[40][1]))
                                        <a href="{{ URL::to('/admin/tmToWarehouse') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
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
<div class="modal fade" id="modalRelatedWarehouse" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showRelatedWarehouse">
        </div>
    </div>
</div>

<div class="modal fade" id="modalRelatedSupplier" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showRelatedSupplier">
        </div>
    </div>
</div>


<!-- Modal end-->
<script type="text/javascript">
    $(function () {
//        $('.tooltips').tooltip();
<?php if (!empty($warehouseArr)) { ?>
            $('.relation-view').dataTable({
                "language": {
                    "search": "Search Keywords : ",
                },
                "paging": true,
                "info": true,
                "order": false
            });
<?php } ?>

        $(".warehouse-check").on("click", function () {
            if ($('.warehouse-check:checked').length == $('.warehouse-check').length) {
                $('.all-warehouse-check').prop("checked", true);
            } else {
                $('.all-warehouse-check').prop("checked", false);
            }
        });
        $(".all-warehouse-check").click(function () {
            if ($(this).prop('checked')) {
                $('.warehouse-check').prop("checked", true);
            } else {
                $('.warehouse-check').prop("checked", false);
            }
        });
        if ($('.warehouse-check:checked').length == $('.warehouse-check').length) {
            $('.all-warehouse-check').prop("checked", true);
        } else {
            $('.all-warehouse-check').prop("checked", false);
        }

        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };


        $(document).on('change', '#tmId', function () {
            var tmId = $('#tmId').val();

            if (tmId == '0') {
                $('#showWarehouses').html('');
                return false;
            }
            $.ajax({
                url: '{{URL::to("admin/tmToWarehouse/getWarehouseToRelate")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    tm_id: tmId,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showWarehouses').html(res.html);
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
        
        

        $(document).on("click", "#relateWarehouse", function (e) {
            e.preventDefault();
            var tmId = $("#tmId").val();
            $.ajax({
                url: "{{ URL::to('/admin/tmToWarehouse/getRelatedWarehouse')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    tm_id: tmId
                },
                beforeSend: function () {
                    $("#showRelatedWarehouse").html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $("#showRelatedWarehouse").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });


        $(document).on("click", "#relateSupplier", function (e) {
            e.preventDefault();
            var warehouseId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/admin/supplierToWarehouse/getRelatedSuppliers')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    warehouse_id: warehouseId
                },
                beforeSend: function () {
                    $("#showRelatedSupplier").html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $("#showRelatedSupplier").html(res.html);
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
                $("#tmToWarehouseForm").append(
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
                            var form_data = new FormData($('#tmToWarehouseForm')[0]);
                            $.ajax({
                                url: "{{URL::to('admin/tmToWarehouse/relateTmTowarehouse')}}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    var tmId = $('#tmId').val();
                                    location = "tmToWarehouse?tm_id=" + tmId;
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