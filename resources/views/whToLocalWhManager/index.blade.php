@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-industry"></i>@lang('label.MANAGER_TO_THANA_WAREHOUSE')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'warehouseToLWMForm')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <br/>
                        <div id="showSr">
                            @if(!empty($warehouseList))
                            <div class="row margin-bottom-10">
                                <div class="col-md-12">
                                    <span class="label label-blue-steel" >@lang('label.TOTAL_NUM_OF_WAREHOUSE'): {!! !empty($warehouseList) ? sizeof($warehouseList):0 !!}</span>
                                    <span class="label label-green-soft" >@lang('label.TOTAL_NUM_OF_THANA_WH_MANAGER'): {!! !empty($lwmList) && sizeof($lwmList) > 1 ? sizeof($lwmList)-1 :0 !!}</span>

                                    @if(!empty($userAccessArr[44][5]))
                                    <button class='label label-purple-wisteria tooltips' href="#modalRelatedThanaWarehouseManager" id="relateManager"  data-toggle="modal" title="@lang('label.SHOW_RELATED_WAREHOUSE_MANAGER')">
                                        @lang('label.WAREHOUSE_RELATED_THANA_WAREHOUSE_MANAGER'): {!! !empty($relatedLwmArr) ? sizeof($relatedLwmArr) :0 !!}&nbsp; <i class="fa fa-search-plus"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>

                            <div class="row margin-bottom-10">
                                <div class="col-md-12">
                                    <div class="table-responsive max-height-500 webkit-scrollbar">
                                        <table class="table table-bordered table-hover relation-view">
                                            <thead>
                                                <tr>
                                                    <th class="vcenter text-center">@lang('label.SL_NO')</th>
                                                    <th class="vcenter">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check']) !!}
                                                            <label for="checkAll">
                                                                <span class="inc"></span>
                                                                <span class="check mark-caheck"></span>
                                                                <span class="box mark-caheck"></span>
                                                            </label>
                                                            &nbsp;&nbsp;<span class="bold">@lang('label.CHECK_ALL')</span>
                                                        </div>
                                                    </th>
                                                    <th class="vcenter">@lang('label.WAREHOUSE')</th>
                                                    <th class="vcenter text-center">@lang('label.THANA_WH_MANAGER')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $checkDisabledCount = $sl = 0;
                                                ?>
                                                @foreach($warehouseList as $whId => $wh)
                                                <?php
                                                //check and show previous value
                                                $checked = '';
                                                $disabled = 'disabled';
                                                if (!empty($relatedLwmArr)) {
                                                    if (array_key_exists($whId, $relatedLwmArr)) {
                                                        $checked = 'checked';
                                                        $disabled = '';
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td class="vcenter text-center width-100">{!! ++$sl !!}</td>
                                                    <td class="vcenter width-120">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('warehouse_id['.$whId.']', $whId, $checked, ['id' => $whId, 'data-id'=>$whId,'class'=> 'md-check wh-check']) !!}
                                                            <label for="{!! $whId !!}">
                                                                <span class="inc"></span>
                                                                <span class="check mark-caheck"></span>
                                                                <span class="box mark-caheck"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="vcenter width-480">{!! $wh !!}</td>
                                                    {!! Form::hidden('warehouse['.$whId.']', $wh) !!}
                                                    <td class="vcenter text-center width-480">
                                                        <select class="form-control width-inherit js-source-states lwm-list" name="lwm_id[{!! $whId !!}]" id="lwm-{!! $whId !!}" {!! $disabled !!}>
                                                            <?php $i = 0; ?>
                                                            @foreach($lwmList as $lwmId => $lwm)
                                                            <?php
                                                            $selectData = !empty($relatedLwmArr[$whId]) ? $relatedLwmArr[$whId] : '0';
                                                            $optionId = ($i == 0) ? '0' : $lwmId;
                                                            $i++;
                                                            ?>
                                                            <option value="{!! $lwmId !!}" id="{!! $whId.'-'.$optionId !!}" 
                                                                    data-wh-id="{!! $whId !!}" data-option-id="{!! $optionId !!}"
                                                                    <?php
                                                                    if ($selectData == $lwmId) {
                                                                        echo 'selected="selected"';
                                                                    } else {
                                                                        echo '';
                                                                    }
                                                                    ?> >{!! $lwm !!} 
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-4 col-md-8">
                                        @if(!empty($warehouseList))
                                        @if(!empty($userAccessArr[44][7]))
                                        <button class="btn btn-circle green btn-submit" id="" type="button">
                                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                                        </button>
                                        @endif
                                        @if(!empty($userAccessArr[44][1]))
                                        <a href="{{ URL::to('/admin/whToLocalWhManager') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger alert-dismissable">
                                        <p><strong><i class="fa fa-bell-o fa-fw"></i> {!! __('label.NO_WAREHOUSE_FOUND') !!}</strong></p>
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
<div class="modal fade" id="modalRelatedThanaWarehouseManager" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showRelatedThanaWhManager">
        </div>
    </div>
</div>
<!-- Modal end-->
<script type="text/javascript">
    $(function () {
        $('.relation-view').tableHeadFixer();
//        $('.tooltips').tooltip();
<?php if (!empty($warehouseList)) { ?>
            //            $('.relation-view').dataTable({
            //                "language": {
            //                    "search": "Search Keywords : ",
            //                },
            //                "paging": true,
            //                "info": true,
            //                "order": false
            //            });
<?php } ?>

        $('#checkAll').on('change', function () {  //'check all' change
            if (this.checked) {
                $('.wh-check').prop('checked', true); //change all 'checkbox' checked status
                $(".lwm-list").prop('disabled', false);
            } else {
                $('.wh-check').prop('checked', false);
                $(".lwm-list").prop('disabled', true);
            }
        });

        $('.wh-check').on('change', function () {
            var whId = $(this).attr('data-id');
            if (this.checked == false) { //if this item is unchecked
                $('#checkAll')[0].checked = false; //change 'check all' checked status to false
                $("#lwm-" + whId).prop('disabled', true);
            } else {
                $("#lwm-" + whId).prop('disabled', false);
            }

            //check 'check all' if all checkbox items are checked
            if ($('.wh-check:checked').length == $('.wh-check').length) {
                $('#checkAll')[0].checked = true; //change 'check all' checked status to true
            }
//            $("select.js-source-states").select2();
        });


        var selections = [];
        $('select.lwm-list option:selected').each(function () {
            if ($(this).val() != '0') {
                var optionId = $(this).attr('data-option-id');
                selections.push(optionId);
            }
        });
        console.log(selections);

        $('select.lwm-list option').each(function () {
            $(this).attr('disabled', $.inArray($(this).val(), selections) > -1 && !$(this).is(":selected"));
        });

        $(document).on('change', 'select.lwm-list', function () {
            var selections = [];
            $('select.lwm-list option:selected').each(function () {
                if ($(this).val() != '0') {
                    var optionId = $(this).attr('data-option-id');
                    selections.push(optionId);
                }

            });
            console.log(selections);

            $('select.lwm-list option').each(function () {
                $(this).attr('disabled', $.inArray($(this).val(), selections) > -1 && !$(this).is(":selected"));
            });
//            $("select.js-source-states").select2();
        });
        $('[data-toggle="tooltip"]').tooltip();

        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };







        //insert sales person to buyer
        $(document).on("click", ".btn-submit", function (e) {
            e.preventDefault();
//            var oTable = $('.relation-view').dataTable();
//            var x = oTable.$('input,select,textarea').serializeArray();
//            $.each(x, function (i, field) {
//                $("#warehouseToLWMForm").append(
//                        $('<input>')
//                        .attr('type', 'hidden')
//                        .attr('name', field.name)
//                        .val(field.value));
//            });
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
                            var form_data = new FormData($('#warehouseToLWMForm')[0]);
                            $.ajax({
                                url: "{{URL::to('admin/whToLocalWhManager/relateWhToLWM')}}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    location.reload();
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


        //********************************** Start :: To Show Modal for Related Thana Warehouse Manager with Warehouse **********//
        $(document).on("click", "#relateManager", function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('whToLocalWhManager.showRelatedLWhManager')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#showRelatedThanaWhManager").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
//********************************** END :: To Show Modal for Related Thana Warehouse Manager with Warehouse **********//

    });
</script>
@stop