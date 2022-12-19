@extends('layouts.default.master')
@section('data_count')	
<!-- BEGIN CONTENT BODY -->
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-home"></i>@lang('label.BANNER')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[113][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('admin/banner/create'.Helper::queryPageStr($qpArr)) }}"> {{trans('label.CREATE_NEW')}}
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
                            <th class="vcenter">@lang('label.SL_NO')</th>
                            <th class="text-center vcenter">@lang('label.BANNER_IMAGE')</th>

                            <th class="text-center vcenter">@lang('label.TITLE')</th>
                            <th class="text-center vcenter">@lang('label.SUBTITLE')</th>
                            <th class="text-center vcenter">@lang('label.PRICE_INFO')</th>
                            <th class="text-center vcenter">@lang('label.PRICE')</th>
                            <th class="text-center vcenter">@lang('label.TEXT_POSITION')</th>
                            <th class="text-center vcenter">@lang('label.URL')</th>
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
                            <td class="text-center vcenter">
                                <?php if (!empty($target->img_d_x)) { ?>
                                    <img width="150" height="70" src="{{URL::to('/')}}/public/uploads/content/banner/{{$target->img_d_x}}" alt="{{ $target->caption}}"/>
                                <?php } else { ?>
                                    <img width="150" height="70" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $target->full_name}}"/>
                                <?php } ?>
                            </td>

                            <td class="text-center vcenter">{!! $target->title !!}</td>
                            <td class="text-center vcenter">{{ $target->subtitle }}</td>
                            <td class="text-center vcenter">{{ $target->price_info }}</td>
                            <td class="text-center vcenter">{{ $target->price }}</td>
                            <td class="text-center vcenter">{{ $postionArr[$target->position] }}</td>
                            <td class="text-center vcenter">{{ $target->url }}</td>
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
                                    {{ Form::open(array('url' => 'admin/banner/' . $target->id, 'id'=>'delete')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    @if(!empty($userAccessArr[113][3]))
                                    <a class="btn btn-icon-only btn-primary tooltips" title="Edit" href="{{ URL::to('admin/banner/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[113][4]))
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
                            <td colspan="10" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
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