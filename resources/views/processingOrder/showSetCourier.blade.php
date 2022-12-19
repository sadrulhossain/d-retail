@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.SET_COURIER')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal','files' => true,'id'=>'setCourierForm')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-offset-1 col-md-7">
                                {!! Form::hidden('order_id', $id) !!}

                                <div class="form-group">
                                    <label class="control-label col-md-4" for="courierId">@lang('label.COURIER') :<span class="text-danger"> *</span></label>
                                    <div class="col-md-8">
                                        {!! Form::select('courier_id', array('0' => __('label.SELECT_COURIER_OPT')) + $courierList, null, ['class' => 'form-control js-source-states', 'id' => 'courierId']) !!}
                                        <span class="text-danger">{{ $errors->first('courier_id') }}</span>
                                    </div>
                                </div>
                                <div class="form-group" style="display: none" id="branchSelect">
                                    <label class="control-label col-md-4">@lang('label.SELECT_BRANCH') :<span class="text-danger"> *</span></label>
                                    <div class="col-md-4">
                                        <div class="md-checkbox vcenter module-check">
                                            {!! Form::checkbox('courier_branch', 'head_office', false, ['id' => 'headOffice', 'class'=> 'md-check']) !!}
                                            <label for="headOffice">
                                                <span class="inc"></span>
                                                <span class="check"></span>
                                                <span class="box"></span>
                                            </label>
                                            <span class="vcenter">@lang('label.HEAD_OFFICE')</span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="md-checkbox vcenter module-check">
                                            {!! Form::checkbox('courier_branch', 'branch', false, ['id' => 'branch', 'class'=> 'md-check']) !!}
                                            <label for="branch">
                                                <span class="inc"></span>
                                                <span class="check"></span>
                                                <span class="box"></span>
                                            </label>
                                            <span class="vcenter">@lang('label.BRANCH')</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="officeDetail"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {

        $(document).on("change", "#courierId", function () {
            var courierId = $("#courierId").val();

            $("#branch").prop("checked", false);
            $("#headOffice").prop("checked", false);

            $("#officeDetail").html('');
            if (courierId != 0) {
                $("#branchSelect").show(500);
            } else {
                $("#branchSelect").hide(500);
            }
        });

        $(document).on("click", "#headOffice", function () {
            var courierId = $("#courierId").val();
            $("#branch").prop("checked", false);

            if ($(this).prop('checked')) {
                $.ajax({
                    url: "{{ URL::to('/admin/processingOrder/getHeadOffice')}}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        courier_id: courierId
                    },
                    beforeSend: function () {
                        App.blockUI({boxed: true});
                        $("#officeDetail").html('');
                    },
                    success: function (res) {
                        $("#officeDetail").html(res.html);
                        App.unblockUI();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                        App.unblockUI();
                    }
                });//ajax
            } else {
                $("#officeDetail").html('');
            }

        });
        $(document).on("click", "#branch", function () {
            var courierId = $("#courierId").val();
            $("#headOffice").prop("checked", false);

            if ($(this).prop('checked')) {
                $.ajax({
                    url: "{{ URL::to('/admin/processingOrder/getBranch')}}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        courier_id: courierId
                    },
                    beforeSend: function () {
                        App.blockUI({boxed: true});
                        $("#officeDetail").html('');
                    },
                    success: function (res) {
                        $("#officeDetail").html(res.html);
                        $(".js-source-states").select2();
                        App.unblockUI();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                        App.unblockUI();
                    }
                });//ajax
            } else {
                $("#officeDetail").html('');
            }
        });

        $(document).on("change", "#branchId", function () {
            var courierId = $("#courierId").val();
            var branchId = $("#branchId").val();

            if (branchId != 0) {
                $.ajax({
                    url: "{{ URL::to('/admin/processingOrder/getBranchDetail')}}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        courier_id: courierId,
                        branch_id: branchId
                    },
                    beforeSend: function () {
                        App.blockUI({boxed: true});
                        $("#branchDetails").html('');
                    },
                    success: function (res) {
                        $("#branchDetails").html(res.html);
                        App.unblockUI();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                        App.unblockUI();
                    }
                });//ajax
            } else {
                $("#branchDetails").html('');
            }
        });


        $(document).on("click", "#submitCourierDetail", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            // Serialize the form data
            var formData = new FormData($('#setCourierForm')[0]);

            $.ajax({
                url: "{{URL::to('admin/processingOrder/saveSetCourier')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $("#submitCourierDetail").prop('disabled', true);
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    toastr.success(res, '@lang("label.SET_COURIER_SUCCESSFULLY")', options);
                    setTimeout(
                            window.location.replace('{{ route("processingOrder.index")}}'
                                    ), 3000);
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
                    $("#submitCourierDetail").prop('disabled', false);
                    App.unblockUI();
                }
            });

        });
    });

</script>
@stop
