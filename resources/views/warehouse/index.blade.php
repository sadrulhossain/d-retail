@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.WAREHOUSE_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[49][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('admin/warehouse/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_WAREHOUSE')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'admin/warehouse/filter','class' => 'form-horizontal')) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.SEARCH')</label>
                        <div class="col-md-8">
                            {!! Form::text('search',  Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Name / Short Name', 'placeholder' => 'Name / Short Name', 'list'=>'search', 'autocomplete'=>'off']) !!}
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
                        <label class="control-label col-md-4" for="thana">@lang('label.THANA')</label>
                        <div class="col-md-8">
                            {!! Form::select('thana',  $thana, Request::get('thana'), ['class' => 'form-control js-source-states','id'=>'thana']) !!}
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

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th>@lang('label.SL_NO')</th>
                            <th>@lang('label.NAME')</th>
                            <th class="text-center vcenter">@lang('label.CENTRAL_WAREHOUSE')</th>
                            <th class="text-center vcenter">@lang('label.DIVISION')</th>
                            <th class="text-center vcenter">@lang('label.DISTRICT')</th>
                            <th class="text-center vcenter">@lang('label.THANA')</th>
                            <th class=" vcenter">@lang('label.ADDRESS')</th>
                            <th class="text-center vcenter">@lang('label.ORDER')</th>
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
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
                            <td class="text-center vcenter">{{ ++$sl }}</td>
                            <td class="vcenter">{{ $target->name }}</td>
                            <td class="text-center vcenter">
                                @if($target->allowed_for_central_warehouse == '1')
                                <span class="label label-sm label-success">@lang('label.YES')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.NO')</span>
                                @endif
                            </td>
                            <td class="text-center vcenter">{{ (!empty($divisionList)&& !empty($target->division_id))?$divisionList[$target->division_id]: '' }}</td>
                            <td class="text-center vcenter">{{ (!empty($districtList)&& !empty($target->district_id))?$districtList[$target->district_id]: '' }}</td>
                            <td class="text-center vcenter">{{ (!empty($thanaList)&& !empty($target->thana_id))?$thanaList[$target->thana_id]: '' }}</td>
                            <td class="vcenter">{!! $target->address !!}</td>
                            <td class="text-center vcenter">{{ $target->order }}</td>
                            <td class="text-center vcenter">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    @if(!empty($userAccessArr[49][3]))
                                    <a class="btn btn-xs btn-primary tooltips" title="Edit" href="{{ URL::to('admin/warehouse/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @if($target->allowed_for_central_warehouse == '0')
                                    <button class="btn btn-xs green-sharp mark-cwh tooltips" data-id="{{$target->id}}" title="@lang('label.MARK_AS_CENTRAL_WH')" type="button" data-placement="top" data-rel="tooltip" data-original-title="@lang('label.MARK_AS_CENTRAL_WH')">
                                        <i class="fa fa-check"></i>
                                    </button>
                                    @endif
                                    @endif

                                    @if(!empty($userAccessArr[49][4]))
                                    {{ Form::open(array('url' => 'admin/warehouse/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline')) }}
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
                            <td colspan="8">@lang('label.NO_WAREHOUSE_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>
<script>
    $(document).ready(function () {

        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        $('.mark-cwh').on('click', function () {
            var id = $(this).attr("data-id"); 
            $.ajax({
                url: "{{ route('warehouse.getCheckCwh') }}",
                type: "POST",
                data: {

                },
                dataType: 'json', // what to expect back from the PHP script, if anything
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    swal({
                        title: "'" + res['name'] + "' @lang('label.IS_ALREADY_ADDED_AS_CENTRAL_WAREHOUSE')",
                        text: "@lang('label.DO_YOU_WANT_TO_CHANGE_IT')",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "@lang('label.YES_CHANGE_IT')",
                        cancelButtonText: "@lang('label.NO_CANCEL')",
                        closeOnConfirm: true,
                        closeOnCancel: true,
                    }, function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "{{ route('warehouse.changeCwh') }}",
                                type: "POST",
                                data: {
                                    id : id,
                                },
                                dataType: 'json', // what to expect back from the PHP script, if anything
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                                        toastr.error(jqXhr.responseJSON.message, '', options);
                                    } else {
                                        toastr.error('Error', 'Something went wrong', options);
                                    }
                                }
                            });
                        }
                    });
                },
            });

        });
    });
</script>

@stop