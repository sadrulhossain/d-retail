@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-th-list"></i>@lang('label.RETURN_PRODUCT')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'returnProductForm')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="form ">
                        <div class="col-md-3">
                            <label class="control-label" for="referenceNo">@lang('label.PURCHASE_REFERENCE') :<span class="text-danger"> *</span></label>
                            {!! Form::text('ref_no', $referenceNo, ['class' => 'form-control', 'id' => 'referenceNo', 'readonly']) !!}
                            <span class="text-danger">{{ $errors->first('ref_no') }}</span>
                        </div>
                    </div>
                    <div class="form ">
                        <div class="col-md-3">
                            <label class="control-label" for="purchaseReference">@lang('label.PURCHASE_REFERENCE') :<span class="text-danger"> *</span></label>
                            {!! Form::select('purchase_reference_id', $purchaseReferenceList, Request::get('purchase_reference_id'), ['class' => 'form-control js-source-states', 'id' => 'purchaseReference']) !!}
                            <span class="text-danger">{{ $errors->first('purchase_reference_id') }}</span>
                        </div>
                    </div>
                    <div class="form ">
                        <div class="col-md-3">
                            <label class="control-label" for="returnDate">@lang('label.RETURN_DATE') :<span class="text-danger"> *</span></label>
                            <div class="input-group date datepicker2">
                                {!! Form::text('return_date',  null , ['id'=> 'returnDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off', 'style' => 'min-width:150px']) !!}
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="returnDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger">{{ $errors->first('return_date') }}</span>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-10" id="productTable">
                    
                </div>

            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


<script type="text/javascript">
    $(function () {
        $(document).on('change', '#purchaseReference', function () {
            var purchaseReferenceId = $('#purchaseReference').val();

            if (purchaseReferenceId == '0') {
                $('#productTable').html('');
                return false;
            }
            $.ajax({
                url: '{{URL::to("admin/returnProduct/getProduct")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    purchase_reference_id: purchaseReferenceId,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#productTable').html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        toastr.error(errors, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                }
            });
        });



        $(document).on("click", ".btn-submit", function (e) {
            e.preventDefault();

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
            var formData = new FormData($('#returnProductForm')[0]);
            swal({
                title: "Are you sure to submit ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Approve It",
                closeOnConfirm: true,
                closeOnCancel: true,
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{URL::to('admin/returnProduct/store')}}",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function () {
                            $('.btn-submit').prop('disabled', true);
                            App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            toastr.success(res.data, 'Product has been returned to supplier successfully', options);
                            setTimeout(() => {
                                window.location.replace('{{URL::to("admin/returnProductList")}}');
                            }, 2000);
                            App.unblockUI();

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
                            $('.btn-submit').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });

    });
</script>
@stop

