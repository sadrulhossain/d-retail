@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.CUSTOMER_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[85][2]))
<!--                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('customer/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_CUSTOMER')
                    <i class="fa fa-plus create-new"></i>
                </a>-->
                @endif
            </div>
        </div>
        <div class="portlet-body">

            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'customer/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="search">@lang('label.NAME')</label>
                            <div class="col-md-8">
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
                </div>
                <div class="row">
                    
                    <div class="col-md-4 text-center">
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
                        <tr class="text-center info">
                            <th class="vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.NAME')</th>
                            <th class="vcenter">@lang('label.EMAIL')</th>
                            <th class="vcenter">@lang('label.PHONE')</th>
<!--                            <th class="text-center vcenter">@lang('label.STATUS')</th>
                            <th class="td-actions text-center vcenter">@lang('label.ACTION')</th>-->
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


                            <td class="vcenter">{{ $target->name }}</td>
                            <td class="vcenter">{{ $target->email }}</td>
                            <td class="vcenter">{{ $target->phone }}</td>
<!--                            <td class="text-center vcenter">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    @if(!empty($userAccessArr[85][3]))
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('customer/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[85][4]))
                                    {{ Form::open(array('url' => 'customer/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {{ Form::close() }}
                                    @endif


                                    {{-- @if(!empty($userAccessArr[18][1]))
                                    <button class="btn btn-xs purple tooltips vcenter" href="#mapView" id="mapModal"  data-toggle="modal" title="@lang('label.SHOW_MAP_ON_ADDRESS')" data-buyer-id = {{$target->id }}>
                                        <i class="fa fa-map-marker"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[18][8]))

                                    <a class="btn btn-xs btn-warning tooltips vcenter " title="@lang('label.CLICK_HERE_TO_MAKE_BUYER_ANALYTICS')"
                                       href="{{ URL::to('customer/' . $target->id . '/manageCustomer'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-cog"></i>
                                    </a>
                                    @endif --}}
                                </div>
                            </td>-->
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="12" class="vcenter">@lang('label.NO_BUYER_FOUND')</td>
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


<div class="modal fade" id="mapView" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="mapBlock">

        </div>
    </div>
</div>


<!-- Modal end-->


<script type="text/javascript">

        

</script>


@stop