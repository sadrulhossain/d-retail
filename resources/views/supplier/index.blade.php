@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.SUPPLIER_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[90][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('admin/supplier/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_SUPPLIER')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- Begin Filter-->
                    {!! Form::open(array('group' => 'form', 'url' => 'admin/supplier/filter','class' => 'form-horizontal')) !!}
                    {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="search">@lang('label.NAME')</label>
                            <div class="col-md-9">
                                {!! Form::text('search',  Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Name', 'placeholder' => 'Name', 'list'=>'search', 'autocomplete'=>'off']) !!}
                                <datalist id="search">
                                    @if(!empty($nameArr))
                                    @foreach($nameArr as $name)
                                    <option value="{{$name->name}}"></option>
                                    @endforeach
                                    @endif
                                </datalist>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS')</label>
                            <div class="col-md-8">
                                {!! Form::select('status',  $status, Request::get('status'), ['class' => 'form-control js-source-states','id'=>'status']) !!}
                            </div>
                        </div>
                    </div>


                    <div class="col-md-1">
                        <div class="form  text-right">
                            <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                                <i class="fa fa-search"></i> @lang('label.FILTER')
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <!-- End Filter -->
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="text-center info">
                            <th class="vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.NAME')</th>
                            <th class="vcenter">@lang('label.CODE')</th>
                            <th class="vcenter">@lang('label.ADDRESS')</th>
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
                            <th class="td-actions text-center vcenter">@lang('label.ACTION')</th>
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
                            <td class="vcenter">{{ ++$sl }}</td>

                            <td class="vcenter">
                                {{ $target->name }}
                            </td>
                            <td class="vcenter">{{ $target->code }}</td>

                            <td class="vcenter">{{ $target->address }}</td>

                            <td class="text-center vcenter">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                <div>
                                    @if(!empty($userAccessArr[90][3]))
                                    <button type="button" class="btn yellow btn-xs tooltips" id="supplierDataFormButton" data-target="#supplierDataForm" data-toggle="modal"
                                            data-id="{{ $target->id }}" data-original-title="Click Here to Add More Details">
                                        <i class="fa fa-plus text-white"></i>
                                    </button>
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('admin/supplier/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[90][4]))
                                    {{ Form::open(array('url' => 'admin/supplier/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {{ Form::close() }}
                                    @endif
                                    @if(!empty($userAccessArr[90][5]))
                                    <button class="btn btn-xs btn-info tooltips vcenter margin-top-2" href="#contactPersonDetails" id="contactPersonData"  data-toggle="modal" title="@lang('label.SHOW_CONTACT_PERSON_DETAILS')" data-supplier-id="{{$target->id}}">
                                        <i class="fa fa-phone"></i>
                                    </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="12" class="vcenter">@lang('label.NO_SUPPLIER_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>
    </div>
</div>
<!-- Modal start -->
<div class="modal fade" id="contactPersonDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showDetailsContactPerson">
        </div>
    </div>
</div>
<!-- Modal end-->
<!-- Add new field Modal Start -->
<div class="modal fade" id="supplierDataForm" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="addAdditionalInfo">
        </div>
    </div>
</div>
<!-- Add new field Modal end -->

<script type="text/javascript">
    $(function () {
        $(document).on("click", "#contactPersonData", function (e) {
            e.preventDefault();
            var supplierId = $(this).data('supplier-id');
            $.ajax({
                url: "{{ route('supplier.detailsOfContactPerson')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    supplier_id: supplierId
                },
                success: function (res) {
                    $("#showDetailsContactPerson").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

    });

    // Get Modal Page START
    $(document).on('click', '#supplierDataFormButton', function (e) {
        e.preventDefault()
        var supplierId = $(this).attr('data-id');

        //ajax atart
        $.ajax({
            url: "{{ URL::to('admin/supplier/getSupplierAdditionalInfo')}}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                supplierId: supplierId
            },
            beforeSend: function () {
                App.blockUI({
                    boxed: true
                });
            },
            success: function (res) {
                $("#addAdditionalInfo").html(res.html);
                App.unblockUI();
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
                App.unblockUI();
            }
        }); //ajax end
    });
    // Get Modal Page END
    // 
    // Submit additional info START
    $(document).on('click', '#supplierAdditionalInfoButton', function (e) {
        e.preventDefault()
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        var formData = new FormData($('#supplierAddtionalForm')[0]);
        swal({
            title: 'Are you sure?',
            text: "You can not undo this action!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, Update',
            cancelButtonText: 'No, Cancel',
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "{{ URL::to('admin/supplier/setSupplierAdditionalInfo') }}",
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    beforeSend: function () {
                        App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                        App.unblockUI();
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
                        App.unblockUI();
                    }
                }); //ajax end
            }
        });
    });
    // Submit additional info END
</script>
@stop
