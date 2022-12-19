@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-industry"></i>@lang('label.RELATE_SR_TO_RETAILER')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'srToRetailerForm')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="srId">@lang('label.SR') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('sr_id',$srList, Request::get('sr_id'), ['class' => 'form-control js-source-states', 'id' => 'srId']) !!}
                                <span class="text-danger">{{ $errors->first('sr_id') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br/>
                        <div id="showRetailer">
                            @if(!empty(Request::get('sr_id')))
                            <div class="row margin-bottom-10">
                                <div class="col-md-12">
                                    <span class="label label-success" >@lang('label.TOTAL_NUM_OF_RETAILER'): {!! !empty($retailerArr) ? count($retailerArr):0 !!}</span>
                                    @if(!empty($userAccessArr[42][5]))
                                    <button class='label label-primary tooltips' type="button" href="#modalRelatedRetailer" id="relatedRetailer"  data-toggle="modal" title="@lang('label.SHOW_RELATED_SR')">
                                        @lang('label.RETAILER_RELATED_TO_THIS_SR'): {!! !empty($srRelateToRetailer)?count($srRelateToRetailer):0 !!}&nbsp; <i class="fa fa-search-plus"></i>
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
                                                    @if(!empty($retailerArr))
                                                    <?php
                                                    $allCheckDisabled = '';
                                                    
                                                    if(!empty($otherRetailerWhArr)){
                                                        $allCheckDisabled ='disabled';
                                                    }
                                                    
                                                    ?>
                                                    <th class="vcenter">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-retailer-check', $allCheckDisabled]) !!}
                                                            <label for="checkAll">
                                                                <span class="inc"></span>
                                                                <span class="check mark-caheck"></span>
                                                                <span class="box mark-caheck"></span>
                                                            </label>
                                                            &nbsp;&nbsp;<span>@lang('label.CHECK_ALL')</span>
                                                        </div>
                                                    </th>
                                                    @endif

                                                    <th class="vcenter">@lang('label.RETAILER_NAME')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($retailerArr))
                                                <?php $sl = 0; ?>
                                                @foreach($retailerArr as $retailer)
                                                <?php
                                                //check and show previous value
                                                $checked = $retailerTitle = '';
                                                if (!empty($srRelateToRetailer) && array_key_exists($retailer['id'], $srRelateToRetailer)) {
                                                    $checked = 'checked';
                                                }

                                                $retailerDisabled = $retailerTooltips = '';
                                                $checkCondition = 0;
                                                if (!empty($inactiveRetailerArr) && in_array($retailer['id'], $inactiveRetailerArr)) {
                                                    if ($checked == 'checked') {
                                                        $checkCondition = 1;
                                                    }
                                                    $retailerDisabled = 'disabled';
                                                    $retailerTooltips = __('label.INACTIVE');
                                                }
                                                if(!empty($otherRetailerWhArr)){
                                                    if(array_key_exists($retailer['id'],$otherRetailerWhArr)){
                                                       $retailerDisabled = 'disabled';
                                                       $retailerTitle = __('label.ALREADY_ASSIGNED_TO_SR',['sr'=> $otherRetailerWhArr[$retailer['id']]]);
                                                    }     
                                                }
                                                ?>
                                                <tr>
                                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                                    <td class="vcenter">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('retailer['.$retailer['id'].']', $retailer['id'], $checked, ['id' => $retailer['id'], 'data-id'=> $retailer['id'],'class'=> 'md-check retailer-check', $retailerDisabled]) !!}
                                                            <label for="{!! $retailer['id'] !!}">
                                                                <span class="inc tooltips" data-placement="right" title="{{ $retailerTitle }}"></span>
                                                                <span class="check mark-caheck tooltips" data-placement="right" title="{{ $retailerTitle }}"></span>
                                                                <span class="box mark-caheck tooltips" data-placement="right" title="{{ $retailerTitle }}"></span>
                                                            </label>
                                                        </div>
                                                        @if($checkCondition == '1')
                                                        {!! Form::hidden('retailer['.$retailer['id'].']', $retailer['id']) !!}
                                                        @endif
                                                    </td>

                                                    <td class="vcenter">{!! $retailer['name'] ?? '' !!}</td>

                                                    


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
                                        @if(!empty($retailerArr))
                                        @if(!empty($userAccessArr[42][7]))
                                        <button class="btn btn-circle green btn-submit" id="" type="button">
                                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                                        </button>
                                        @endif
                                        @if(!empty($userAccessArr[42][1]))
                                        <a href="{{ URL::to('/admin/srToRetailer') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
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
<div class="modal fade" id="modalRelatedRetailer" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showRelatedRetailer">
        </div>
    </div>
</div>




<!-- Modal end-->
<script type="text/javascript">
    $(function () {
//        $('.tooltips').tooltip();
<?php if (!empty($retailerArr)) { ?>
            $('.relation-view').dataTable({
                "language": {
                    "search": "Search Keywords : ",
                },
                "paging": true,
                "info": true,
                "order": false
            });
<?php } ?>

        $(".retailer-check").on("click", function () {
            if ($('.retailer-check:checked').length == $('.retailer-check').length) {
                $('.all-retailer-check').prop("checked", true);
            } else {
                $('.all-retailer-check').prop("checked", false);
            }
        });
        $(".all-retailer-check").click(function () {
            if ($(this).prop('checked')) {
                $('.retailer-check').prop("checked", true);
            } else {
                $('.retailer-check').prop("checked", false);
            }
        });
        if ($('.retailer-check:checked').length == $('.retailer-check').length) {
            $('.all-retailer-check').prop("checked", true);
        } else {
            $('.all-retailer-check').prop("checked", false);
        }

        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };


        $(document).on('change', '#srId', function () {
            var srId = $('#srId').val();

            if (srId == '0') {
                $('#showRetailer').html('');
                return false;
            }
            $.ajax({
                url: '{{URL::to("admin/srToRetailer/getRetailerToRelate")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sr_id: srId,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showRetailer').html(res.html);
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
        
        

        $(document).on("click", "#relatedRetailer", function (e) {
            e.preventDefault();
            var srId = $("#srId").val();
            $.ajax({
                url: "{{ URL::to('/admin/srToRetailer/getRelatedRetailer')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sr_id: srId
                },
                beforeSend: function () {
                    $("#showRelatedRetailer").html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $("#showRelatedRetailer").html(res.html);
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
                $("#srToRetailerForm").append(
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
                            var form_data = new FormData($('#srToRetailerForm')[0]);
                            $.ajax({
                                url: "{{URL::to('admin/srToRetailer/relateSrToRetailer')}}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    var srId = $('#srId').val();
                                    location = "srToRetailer?sr_id=" + srId;
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