<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.ASSIGNED_SKU_LIST')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row margin-bottom-10">
            <div class="col-md-4">
                @lang('label.PRODUCT'): <strong>{!! $product->name ?? ''!!}</strong>
            </div>
            <div class="col-md-4">
                @lang('label.CODE'): <strong>{!! $product->code ?? ''!!}</strong>
            </div>
            <div class="col-md-4">
                @lang('label.PRODUCT_CATEGORY'): <strong>{!! $product->category_name ?? ''!!}</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover relation-view-2">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.PRODUCT_SKU_CODE')</th>
                                <th class="vcenter">@lang('label.ACTION')</th>
                            </tr>
                        </thead>
                        <tbody id="exerciseData">
                            @if(!$productSKU->isEmpty())
                            @php $sl = 0 @endphp
                            @foreach($productSKU as $sku)

                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="vcenter">
                                    {!! $sku['sku_code'] ?? ''!!}
                                </td>
                                <td class="text-center vcenter">
                                    @if(!empty($userAccessArr[96][4]))
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="button" data-placement="top" data-id="{!!$sku->id!!}" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5" class="text-danger">
                                    @lang('label.NO_ASSIGNED_SKU_CODES')
                                </td>
                            </tr>
                            @endif      
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    $(document).on('click', '#buttonSubmit', function (e) {
        e.preventDefault();
        var form_data = new FormData($('#productSKUCodeForm')[0]);
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
                    url: "{{URL::to('admin/productSKUCode/relateProductToSKUCode')}}",
                    type: "POST",
                    datatype: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    success: function (res) {
                        toastr.success(res, "@lang('label.SKU_CREATED_SUCCESSFULLY')", options);
                        //App.blockUI({ boxed: false });
                        //setTimeout(location.reload.bind(location), 1000);
                        $("#productId").trigger("change");
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
                        App.unblockUI();
                    }
                });
            }
        });
    });
</script>
