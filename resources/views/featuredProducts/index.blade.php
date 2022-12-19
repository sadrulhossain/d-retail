@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>@lang('label.FEATURED_PRODUCT_LIST')
            </div>
        </div>
        <div class="portlet-body">
            {!! Form::open(array('group' => 'form', 'url' => '#','class' => 'form-horizontal','id' => 'submitForm')) !!}
            {{csrf_field()}}
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        @if (!$targetArr->isEmpty())

                        <div class="col-md-12">
                            <div class="row margin-bottom-10">
                                <div class="col-md-12">
                                    <span class="label label-success" >@lang('label.TOTAL_NUM_OF_SKU'): {!! !empty($targetArr)?count($targetArr):0 !!}</span>

                                    <button class="label label-primary tooltips" href="#modalSelectedSKU" id="selectedSKU"  data-toggle="modal" title="@lang('label.SHOW_SELECTED_SKU')">
                                        @lang('label.TOTAL_NUM_OF_SELECTED_SKU'): &nbsp;{!! !empty($prevSku) ? sizeof($prevSku) : 0 !!}&nbsp; <i class="fa fa-search-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <table class="table table-bordered table-hover" id="dataTable">
                                <thead>
                                    <tr>
                                        <th class="vcenter text-center">@lang('label.SL_NO')</th>
                                        <th class="vcenter">
                                            <div class="md-checkbox has-success tooltips" title="@lang('label.SELECT_ALL')">
                                                {!! Form::checkbox('check_all',1,false,['id' => 'checkedAll','class'=> 'md-check']) !!} 
                                                <label for="checkedAll">
                                                    <span></span>
                                                    <span class="check mark-caheck"></span>
                                                    <span class="box mark-caheck"></span>
                                                </label>
                                            </div>
                                        </th>
                                        <th class="vcenter">@lang('label.SKU')</th>
                                        <th class="vcenter">@lang('label.PRODUCT_NAME')</th>
                                        <th class="vcenter">@lang('label.BRAND')</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sl = 0; ?>

                                    @foreach($targetArr as $item)

                                    <?php
                                    $checked = '';
                                    if (!empty($prevSku)) {
                                        $checked = array_key_exists($item->id, $prevSku) ? 'checked' : '';
                                    }
                                    ?>
                                    <tr>
                                        <td class="vcenter text-center">{!! ++$sl !!}</td>
                                        <td class="vcenter text-center">
                                            <div class="md-checkbox has-success tooltips">
                                                {!! Form::checkbox('sku['.$item->id.']', $item->id,$checked,['id' => $item->id, 'class'=> 'md-check product-sku-check']) !!}

                                                <label for="{{ $item->id }}">
                                                    <span class="inc"></span>
                                                    <span class="check mark-caheck"></span>
                                                    <span class="box mark-caheck"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="vcenter">{{ $item->sku }}</td>
                                        <td class="vcenter">{{ $item->product_name }}</td>
                                        <td class="vcenter">{!! $item->brand_name !!}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-5 col-md-5">
                                        <button class="btn btn-circle green button-submit" type="button" id="buttonSubmit" >
                                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissable">
                                <p><i class="fa fa-bell-o fa-fw"></i>@lang('label.NO_SKU_FOUND')</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>

<!--selected sku list-->
<div class="modal fade" id="modalSelectedSKU" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showSelectedSKU">

        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
<?php
if (!$targetArr->isEmpty()) {
    ?>
            $('#dataTable').dataTable({
                "paging": true,
                "info": false,
                "order": false
            });

            // this code for  database 'check all' if all checkbox items are checked
            if ($('.product-sku-check:checked').length == $('.product-sku-check').length) {
                $('#checkedAll')[0].checked = true; //change 'check all' checked status to true
            }

            $("#checkedAll").change(function () {
                if (this.checked) {
                    $(".md-check").each(function () {
                        if (!this.hasAttribute("disabled")) {
                            this.checked = true;
                        }
                    });
                } else {
                    $(".md-check").each(function () {
                        this.checked = false;
                    });
                }
            });

            $('.product-sku-check').change(function () {
                if (this.checked == false) { //if this item is unchecked
                    $('#checkedAll')[0].checked = false; //change 'check all' checked status to false
                }

                //check 'check all' if all checkbox items are checked
                allCheck();
            });
            allCheck();
    <?php
}
?>
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };


        $(document).on('click', '#buttonSubmit', function (e) {
            e.preventDefault();
            var oTable = $('#dataTable').dataTable();
            var x = oTable.$('input,select,textarea').serializeArray();
            $.each(x, function (i, field) {

                $("#submitForm").append(
                        $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', field.name)
                        .val(field.value));
            });
            var form_data = new FormData($('#submitForm')[0]);
            swal({
                title: 'Are you sure?',

                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{URL::to('admin/featuredProducts/saveProducts')}}",
                        type: "POST",
                        datatype: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        beforeSend: function () {
                            $('#buttonSubmit').prop('disabled', true);
                            App.blockUI({boxed: true});
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
                                    errorsHtml += '<li>' + value[0] + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                //toastr.error(jqXhr.responseJSON.message, '', options);
                                var errors = jqXhr.responseJSON.message;
                                var errorsHtml = '';
                                if (typeof (errors) === 'object') {
                                    $.each(errors, function (key, value) {
                                        errorsHtml += '<li>' + value + '</li>';
                                    });
                                    toastr.error(errorsHtml, '', options);
                                } else {
                                    toastr.error(jqXhr.responseJSON.message, '', options);
                                }
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            $('#buttonSubmit').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });

    });

    function allCheck() {
        if ($('.cm-group-member-check:checked').length == $('.cm-group-member-check').length) {
            $('#checkedAll')[0].checked = true; //change 'check all' checked status to true
        } else {
            $('#checkedAll')[0].checked = false;
        }
    }
    
    // Start Show Assigned CM Modal
    $(document).on("click", "#selectedSKU", function (e) {
        e.preventDefault();
        $.ajax({
            url: "{{ URL::to('admin/featuredProducts/getSelectedSKU')}}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                $("#showSelectedSKU").html(res.html);
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            }
        }); //ajax
    });
    // End Show Assigned CM Modal

</script>
@stop