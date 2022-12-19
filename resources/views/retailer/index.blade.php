@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.RETAILER_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[50][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('admin/retailer/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_RETAILER')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'admin/retailer/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="search">@lang('label.SEARCH')</label>
                            <div class="col-md-8">
                                {!! Form::text('search',  Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Name / Code', 'placeholder' => 'Name / Code', 'list'=>'search', 'autocomplete'=>'off']) !!}
                                <datalist id="search">
                                    @if(!empty($nameArr))
                                    @foreach($nameArr as $name)
                                    <option value="{{$name->code}}"></option>
                                    @endforeach
                                    @endif
                                </datalist>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS')</label>
                            <div class="col-md-8">
                                {!! Form::select('status',  $status, Request::get('status'), ['class' => 'form-control js-source-states','id'=>'status']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.REGISTERED_BY')</label>
                            <div class="col-md-7">
                                {!! Form::select('registered_by',  $registeredBy, Request::get('registered_by'), ['class' => 'form-control js-source-states','id'=>'registeredId']) !!}
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="form">
                            <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                                <i class="fa fa-search"></i> @lang('label.FILTER')
                            </button>
                        </div>
                    </div>
                </div>


                {!! Form::close() !!}
                <!-- End Filter -->
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th>@lang('label.SL_NO')</th>
                            <th>@lang('label.LOGO')</th>
                            <th>@lang('label.NAME')</th>
                            <th>@lang('label.CODE')</th>
                            <th>@lang('label.LONGITUDE')/@lang('label.LATITUDE')</th>
                            <th>@lang('label.PROFILE_COMPLETION')</th>
                            <th>@lang('label.USERNAME')</th>
                            <th class="text-center">@lang('label.ORDER')</th>
                            <th class="text-center">@lang('label.STATUS')</th>
                            <th class="text-center">@lang('label.APPROVAL_STATUS')</th>
                            <th class="td-actions text-center">@lang('label.ACTION')</th>
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
                        <?php
                        ?>
                        <tr>
                            <td class="vcenter ">{{ ++$sl }}</td>
                            <td class="vcenter">
                                @if (!empty($target->logo))
                                <img  alt="{{$target->name}}" src="{{URL::to('/')}}/public/uploads/retailer/{{$target->logo}}" width="40" height="40"/>
                                @else
                                <img  alt="unknown" src="{{URL::to('/')}}/public/img/no_image.png" width="40" height="40"/>
                                @endif
                            </td>
                            <td class="vcenter" role="button">{{ $target->name }}</td>
                            <td class="vcenter">{{ $target->code }}</td>
                            <td class="vcenter">
                                <?php
                                if (!empty($target->longitude)) {
                                    echo __('label.LONGITUDE') . ': ' . $target->longitude;
                                }
                                echo '<br/>';
                                if (!empty($target->latitude)) {
                                    echo __('label.LATITUDE') . ': ' . $target->latitude;
                                }
                                ?>
                            </td>
                            <td class="vcenter">
                                @if($target->srToRetailer && !$target->warehouseToRetailer)                                
                                    <div class="progress label-gray-mint ">
                                        <div class="progress-bar progress-bar-striped blue-light complition" role="progressbar" aria-valuenow="67" data-status="67"
                                             data-target="#showRetailerProfileStatus" data-toggle="modal" data-id="{{$target->id}} aria-valuemin="0" aria-valuemax="100" style="width: 67%">67%</div>
                                    </div>
                                @elseif($target->warehouseToRetailer && !$target->srToRetailer)
                                    <div class="progress label-gray-mint ">
                                        <div class="progress-bar progress-bar-striped blue-light complition" role="progressbar" aria-valuenow="67" data-status="67"
                                             data-target="#showRetailerProfileStatus" data-toggle="modal" data-id="{{$target->id}} aria-valuemin="0" aria-valuemax="100" style="width: 67%">67%</div>
                                    </div>
                                @elseif ($target->srToRetailer && $target->warehouseToRetailer && $target->user)
                                    <div class="progress label-gray-mint ">
                                        <div class="progress-bar progress-bar-striped label-green-sharp complition" role="progressbar" aria-valuenow="100" data-status="1000"
                                             data-target="#showRetailerProfileStatus" data-toggle="modal" data-id="{{$target->id}} aria-valuemin="0" aria-valuemax="100" style="width: 100%">100%</div>
                                    </div>
                                @else
                                    <div class="progress label-gray-mint">
                                        <div class="progress-bar progress-bar-striped label-danger complition" role="progressbar" aria-valuenow="31" data-status="31"
                                             data-target="#showRetailerProfileStatus" data-toggle="modal" data-id="{{$target->id}} aria-valuemin="0" aria-valuemax="100" style="width: 31%">31%</div>
                                    </div>
                                @endif


                            </td>
                            <td class="vcenter">{!! $target->username ?? '' !!}</td>
                            <td class="text-center vcenter">{{ $target->order }}</td>
                            <td class="text-center vcenter">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                @if($target->approval_status == '1')
                                <span class="label label-sm label-primary">@lang('label.APPROVED')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.PENDING')</span>
                                @endif
                            </td>
                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    @if(!empty($userAccessArr[50][25]))
                                    @if ($target->by_rtl_dist == '1' && $target->approval_status == '0')
                                    <button class="btn btn-xs green-seagreen tooltips approve vcenter" title="@lang('label.CLICK_TO_APPROVE')" data-id="{{ $target->id }}">
                                        <i class="fa fa-check-circle"></i>
                                    </button>

                                    @if(!empty($userAccessArr[50][26]))
                                    <button class="btn btn-xs red-intense tooltips deny" href="#claimTcDeniedModal" data-id="{!! $target->id !!}" 
                                            data-toggle="modal" data-placement="top" data-rel="tooltip" title="@lang('label.CLICK_HERE_TO_DENY')">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                    @endif
                                    @endif
                                    @endif
                                    @if(!empty($userAccessArr[50][3]))
                                    <button type="button" class="btn yellow btn-xs tooltips" id="showAdditionalInfo" data-target="#retailerToUserForm" data-toggle="modal"
                                            data-id="{{ $target->id }}" data-original-title="Click Here to Set Login Information">
                                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" class="btn yellow btn-xs tooltips" id="addRetailerDataButton" data-target="#retailerDataForm" data-toggle="modal"
                                            data-id="{{ $target->id }}" data-original-title="Click Here to Add More Details">
                                        <i class="fa fa-plus text-white"></i>
                                    </button>
                                    <a class="btn btn-xs btn-primary tooltips" title="Edit" href="{{ URL::to('admin/retailer/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif

                                    @if(!empty($userAccessArr[50][5]))
                                    <button class="btn btn-xs btn-info tooltips vcenter"  href="#contactPersonDetails" id="contactPersonData"  data-toggle="modal" 
                                            title="@lang('label.SHOW_CONTACT_PERSON_DETAILS')" data-retailer-id="{{$target->id }}">
                                        <i class="fa fa-phone"></i>
                                    </button>
                                    @endif


                                    @if(!empty($userAccessArr[50][4]))
                                    {{ Form::open(array('url' => 'admin/retailer/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <button class="btn btn-xs btn-danger delete tooltips" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {{ Form::close() }}
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="8">@lang('label.NO_RETAILER_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>
    </div>
</div>
<!-- START:: Show Contact Person Details ---->
<div class="modal fade" id="contactPersonDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showDetailsContactPerson">
        </div>
    </div>
</div>
<!-- END:: Show Contact Person Details ---->

<!-- Add new field Modal start  -->
<div class="modal fade" id="retailerDataForm" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="addAdditionalInfo">
        </div>
    </div>
</div>
<!-- Add new field Modal end -->
<!-- Add new field Modal start  -->
<div class="modal fade" id="retailerToUserForm" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="addAdditionalUserInfo">
        </div>
    </div>
</div>
<!-- Add new field Modal end -->
<!-- Add new field Modal start  -->
<div class="modal fade" id="showRetailerProfileStatus" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="placeRetailerProfileStatus">
        </div>
    </div>
</div>
<!-- Add new field Modal end -->

<script type="text/javascript">
    $(document).ready(function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };

        // Contact person data START
        $(document).on("click", "#contactPersonData", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            var retailerId = $(this).data('retailer-id');
            $.ajax({
                url: "{{ route('retailer.detailsOfContactPerson')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    retailer_id: retailerId
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showDetailsContactPerson").html(res.html);
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
            }); //ajax
        });
        // Contact person data END

        // Get Modal Page START
        $(document).on('click', '#addRetailerDataButton', function (e) {
            e.preventDefault()
            var retailerId = $(this).attr('data-id');
            //ajax atart
            $.ajax({
                url: "{{ URL::to('admin/retailer/getRetailerAdditionalInfo')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    retailerId: retailerId
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

        //GET Division List START
        $(document).on('change', '#divisionList', function (e) {
            e.preventDefault()
            var divisionId = $(this).val();
            // console.log(divisionId);return false;
            //ajax atart
            $.ajax({
                url: "{{ URL::to('admin/retailer/getDistrict')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    divisionId: divisionId
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#districtListDiv").html(res.html);
                    $("#thanaListDiv").html(res.html2);
                    $('.js-source-states').select2();
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
        //GET Division List END

        //GET Thana List START
        $(document).on('change', '#districtList', function (e) {
            e.preventDefault()
            var thanaId = $(this).val();
            //ajax atart
            $.ajax({
                url: "{{ URL::to('admin/retailer/getThana')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    thanaId: thanaId
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#thanaListDiv").html(res.html);
                    $('.js-source-states').select2();
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
        //GET Thana List END

        // Submit additional info START
        $(document).on('click', '#retailerAdditionalInfoButton', function (e) {
            e.preventDefault()
            var formData = new FormData($('#retailerAddtionaInfoForm')[0]);
            swal({
                title: 'Are you sure?',
                text: "You can not undo this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    // Serialize the form data
                    $.ajax({
                        url: "{{ URL::to('admin/retailer/setRetailerAdditionalInfo') }}",
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
                            }, 1000);
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
        // 
        // Submit additional info START
        $(document).on('click', '#showRetailerLoginAdditionalInfoButton', function (e) {
            e.preventDefault()
            var formData = new FormData($('#showAddtionaInfoForm')[0]);
            swal({
                title: 'Are you sure?',
                text: "You can not undo this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    // Serialize the form data
                    $.ajax({
                        url: "{{ URL::to('admin/retailer/setRetailerLoginInformation') }}",
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
                            }, 1000);
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



        // Get Retailer to user Modal Page START
        $(document).on('click', '#showAdditionalInfo', function (e) {
            e.preventDefault()
            var retailerId = $(this).attr('data-id');
            //ajax atart
            $.ajax({
                url: "{{ URL::to('admin/retailer/getRetailerLoginInformation')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    retailer_id: retailerId
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#addAdditionalUserInfo").html(res.html);
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
        // Get Retailer to user Modal Page END

    });

    //approve data
    $(document).on("click", ".approve", function (e) {
        e.preventDefault();
        var approvedId = $(this).attr("data-id");
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        
        
        swal({
            title: 'Are you sure you want to approve this retailer?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, Approve',
            cancelButtonText: 'No, Cancel',
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "{{ URL::to('admin/retailer/approve')}}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        approved_id: approvedId,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
                        setTimeout(() => {
                            location.reload();
                        }, 1000)
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
                        App.unblockUI();
                        setTimeout(() => {
                            location.reload();
                        }, 100)
                    }
                });
            }
        });
    });

    //deny retailer
    $(document).on("click", ".deny", function (e) {

        e.preventDefault();
        var retailerId = $(this).attr("data-id");
        console.log(retailerId);
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        swal({
            title: 'Are you sure you want to deny this retailer?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, Deny',
            cancelButtonText: 'No, Cancel',
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                var token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: 'post',
                    url: "{{ URL::to('admin/retailer/deny') }}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        retailerId: retailerId
                    },

                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
                        setTimeout(() => {

                            location.reload();
                        }, 1000)
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
                        App.unblockUI();
                    }
                });
            }
        });
    });
    //deny retailer
    //deny retailer
    $(document).on("click", ".complition", function (e) {
        e.preventDefault();
        var retailer_id = $(this).attr("data-id");
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        $.ajax({
            type: 'post',
            url: "{{ URL::to('admin/retailer/showProfileCompitionStatus') }}",
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                retailer_id: retailer_id
            },

            success: function (res) {
                $("#placeRetailerProfileStatus").html(res.html);
                App.unblockUI();
//                setTimeout(() => {
//
//                    location.reload();
//                }, 1000)
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
                App.unblockUI();
            }
        });
    });
    //deny retailer

</script>
@stop
