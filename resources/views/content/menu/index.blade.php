@extends('layouts.default.master')
@section('data_count')	
<!-- BEGIN CONTENT BODY -->
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-home"></i>@lang('label.MENU')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[116][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('admin/menu/create'.Helper::queryPageStr($qpArr)) }}"> {{trans('label.CREATE_NEW')}}
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">


            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="vcenter" width="80">@lang('label.SL_NO')</th>
                   
                            <th class="text-center vcenter">@lang('label.TITLE')</th>
                            <th class="text-center vcenter">@lang('label.URL')</th>
                            <th class=" text-center vcenter">@lang('label.FOR_LOGGED_IN_USERS')</th>
                            <th class="text-center vcenter">@lang('label.ORDER')</th>
                            <th class=" text-center vcenter">@lang('label.STATUS')</th>
                            <th class="td-actions text-center vcenter">@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * (Session::get('paginatorCount'));
                        ?>
                        @foreach($targetArr as $target)
                        <tr>
                            <td class="vcenter">{{ ++$sl }}</td>
                            <td class="text-center vcenter">{{ $target->title }}</td>
                            <td class="text-center vcenter">{{ $target->url }}</td>
                            <td class="text-center vcenter">
                                 @if($target->for_logged_in_users == '1')
                                <span class="label label-sm label-success">@lang('label.YES')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.NO')</span>
                                @endif
                            </td>
                            <td class="text-center vcenter">{{ $target->order }}</td>
                            <td class="text-center vcenter">
                                @if($target->status_id == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td  class="text-center vcenter">
                                <div>
                                    {{ Form::open(array('url' => 'admin/menu/' . $target->id, 'id'=>'delete')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    @if(!empty($userAccessArr[116][3]))
                                    <a class="btn btn-icon-only btn-primary tooltips" title="Edit" href="{{ URL::to('admin/menu/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[116][4]))
                                    <button class="btn btn-icon-only btn-danger tooltips" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    @endif
                                    {{ Form::close() }}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="9" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>


<script type="text/javascript">
    $(document).on("submit", '#delete', function (e) {
        alert
        //This function use for sweetalert confirm message
        e.preventDefault();
        var form = this;
        swal({
            title: 'Are you sure you want to Delete?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete",
            closeOnConfirm: false
        },
                function (isConfirm) {
                    if (isConfirm) {
                        toastr.info("Loading...", "Please Wait.", {"closeButton": true});
                        form.submit();
                    } else {
                        //swal(sa_popupTitleCancel, sa_popupMessageCancel, "error");

                    }
                });
    });
</script>
@stop