@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-industry"></i>@lang('label.RELATE_PRODUCT_TO_ATTRIBUTE')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'productToAttributeRelateForm')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="productId">@lang('label.PRODUCT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('product_id', $productArr, Request::get('product_id'), ['class' => 'form-control js-source-states', 'id' => 'productId']) !!}
                                <span class="text-danger">{{ $errors->first('product_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="productId">@lang('label.ATTRIBUTE_TYPE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('attributeType_id', $attributeTypeArr, Request::get('attributeType_id'), ['class' => 'form-control js-source-states', 'id' => 'attributeTypeId']) !!}
                                <span class="text-danger">{{ $errors->first('attributeType_id') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br/>
                        <div id="showAttributes">
                            @if(!empty(Request::get('product_id')) && !empty(Request::get('attributeType_id')))
                            <div class="row margin-bottom-10">
                                <div class="col-md-12">
                                    <span class="label label-success" >@lang('label.TOTAL_NUM_OF_ATTRIBUTES'): {!! !empty($attributeArr)?count($attributeArr):0 !!}</span>
                                    @if(!empty($userAccessArr[92][5]))
                                    <button class='label label-primary tooltips' href="#modalRelatedAttribute" id="relateAttribute"  data-toggle="modal" title="@lang('label.SHOW_RELATED_ATTRIBUTES')">
                                        @lang('label.ATTRIBUTE_RELATED_TO_THIS_PRODUCT'): {!! !empty($attributeRelateToProduct)?count($attributeRelateToProduct):0 !!}&nbsp; <i class="fa fa-search-plus"></i>
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
                                                    @if(!empty($attributeArr))
                                                    <?php
                                                    $allCheckDisabled = '';
                                                    if (!empty($dependentAttributeArr[$request->get('product_id')])) {
                                                        $allCheckDisabled = 'disabled';
                                                    }
                                                    ?>
                                                    <th class="vcenter">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-attribute-check', $allCheckDisabled]) !!}
                                                            <label for="checkAll">
                                                                <span class="inc"></span>
                                                                <span class="check mark-caheck"></span>
                                                                <span class="box mark-caheck"></span>
                                                            </label>
                                                            &nbsp;&nbsp;<span>@lang('label.CHECK_ALL')</span>
                                                        </div>
                                                    </th>
                                                    @endif

                                                    <th class="vcenter">@lang('label.ATTRIBUTE_NAME')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($attributeArr))
                                                <?php $sl = 0; ?>
                                                @foreach($attributeArr as $attribute)
                                                <?php
                                                //check and show previous value
                                                $checked = '';
                                                if (!empty($attributeRelateToProduct) && array_key_exists($attribute['id'], $attributeRelateToProduct)) {
                                                    $checked = 'checked';
                                                }

                                                $attributeDisabled = $attributeTooltips = '';
                                                $checkCondition = 0;
                                                if (!empty($inactiveAttributeArr) && in_array($attribute['id'], $inactiveAttributeArr)) {
                                                    if ($checked == 'checked') {
                                                        $checkCondition = 1;
                                                    }
                                                    $attributeDisabled = 'disabled';
                                                    $attributeTooltips = __('label.INACTIVE');
                                                }
                                                ?>
                                                <tr>
                                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                                    <td class="vcenter">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('attribute['.$attribute['id'].']', $attribute['id'], $checked, ['id' => $attribute['id'], 'data-id'=> $attribute['id'],'class'=> 'md-check attribute-check', $attributeDisabled]) !!}
                                                            <label for="{!! $attribute['id'] !!}">
                                                                <span class="inc tooltips" data-placement="right" title="{{ $attributeTooltips }}"></span>
                                                                <span class="check mark-caheck tooltips" data-placement="right" title="{{ $attributeTooltips }}"></span>
                                                                <span class="box mark-caheck tooltips" data-placement="right" title="{{ $attributeTooltips }}"></span>
                                                            </label>
                                                        </div>
                                                        @if($checkCondition == '1')
                                                        {!! Form::hidden('attribute['.$attribute['id'].']', $attribute['id']) !!}
                                                        @endif
                                                    </td>

                                                    <td class="vcenter">{!! $attribute['name'] ?? '' !!}</td>


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
                                        @if(!empty($attributeArr))
                                        @if(!empty($userAccessArr[92][7]))
                                        <button class="btn btn-circle green btn-submit" id="" type="button">
                                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                                        </button>
                                        @endif
                                        @if(!empty($userAccessArr[92][1]))
                                        <a href="{{ URL::to('/admin/productToAttribute') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
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
<div class="modal fade" id="modalRelatedAttribute" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showRelatedAttribute">
        </div>
    </div>
</div>
<!-- Modal end-->
<script type="text/javascript">
    $(function () {
//        $('.tooltips').tooltip();
<?php if (!empty($attributeArr)) { ?>
            $('.relation-view').dataTable({
                "language": {
                    "search": "Search Keywords : ",
                },
                "paging": true,
                "info": true,
                "order": false
            });
<?php } ?>

        $(".attribute-check").on("click", function () {
            if ($('.attribute-check:checked').length == $('.attribute-check').length) {
                $('.all-attribute-check').prop("checked", true);
            } else {
                $('.all-attribute-check').prop("checked", false);
            }
        });
        $(".all-attribute-check").click(function () {
            if ($(this).prop('checked')) {
                $('.attribute-check').prop("checked", true);
            } else {
                $('.attribute-check').prop("checked", false);
            }
        });
        if ($('.attribute-check:checked').length == $('.attribute-check').length) {
            $('.all-attribute-check').prop("checked", true);
        } else {
            $('.all-attribute-check').prop("checked", false);
        }

        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };


        $(document).on('change', '#attributeTypeId,#productId', function () {
            var productId = $('#productId').val();
            var attributeTypeId = $('#attributeTypeId').val();

            if (productId == '0' || attributeTypeId == '0') {
                $('#showAttributes').html('');
                return false;
            }
            $.ajax({
                url: '{{URL::to("admin/productToAttribute/getAttributesToRelate")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId,
                    attribute_type_id: attributeTypeId,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showAttributes').html(res.html);
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

        $(document).on("click", "#relateAttribute", function (e) {
            e.preventDefault();
            var productId = $("#productId").val();
            $.ajax({
                url: "{{ URL::to('/admin/productToAttribute/getRelatedAttributes')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId
                },
                beforeSend: function () {
                    $("#showRelatedAttribute").html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $("#showRelatedAttribute").html(res.html);
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
                $("#productToAttributeRelateForm").append(
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
                            var form_data = new FormData($('#productToAttributeRelateForm')[0]);
                            $.ajax({
                                url: "{{URL::to('admin/productToAttribute/relateProductToAttribute')}}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    var productId = $('#productId').val();
                                    location = "productToAttribute?product_id=" + productId;
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