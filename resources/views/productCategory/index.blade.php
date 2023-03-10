@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.PRODUCT_CATEGORY_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[7][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('admin/productCategory/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_PRODUCT_CATEGORY')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'admin/productCategory/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="search">@lang('label.SEARCH')</label>
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

                    <div class="col-md-2">
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
                        <tr>
                            <th class="vcenter text-center">@lang('label.SL_NO')</th>
                            <th class="vcenter text-center">@lang('label.NAME')</th>
                            <th class="vcenter text-center">@lang('label.THUMBNAIL')</th>
                            <th class="vcenter text-center">@lang('label.CODE')</th>
                            <th class="vcenter text-center">@lang('label.PARENT_CATEGORY')</th>
                            <th class="vcenter text-center">@lang('label.HIGHLIGHTED_FOR_HOME_PAGE')</th>
                            <th class="vcenter text-center">@lang('label.ORDER')</th>
                            <th class="vcenter text-center">@lang('label.STATUS')</th>
                            <th class="vcenter text-center">@lang('label.ACTION')</th>
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
                            <td class="vcenter text-center">{{ ++$sl }}</td>
                            <td class="vcenter">{{ $target->name }}</td>
                            <td class="vcenter text-center" width="40px">
                                @if(!empty($target->image) && file_exists('public/uploads/category/'.$target->image))
                                <img width="40px" height="40px" src="{{URL::to('/')}}/public/uploads/category/{{$target->image}}" alt="{{ $target->name }}" class="border-radius-50">
                                @else
                                <img width="40px" height="40px" src="{{URL::to('/')}}/public/img/no_image.png" alt="{{$target->name}}" class="border-radius-50">
                                @endif
                            </td>
                            <td class="vcenter">{{ $target->code }}</td>
                            <td class="vcenter">
                                <?php
                                if (isset($parentArr[$target->id])) {
                                    echo $parentArr[$target->id];
                                } else {
                                    echo '';
                                }
                                ?>
                            </td>
                            <td class="vcenter text-center">
                                @if($target->highlighted == '1')
                                <span class="label label-sm label-green-steel">@lang('label.YES')</span>
                                @else
                                <span class="label label-sm label-gray-mint">@lang('label.NO')</span>
                                @endif
                            </td>
                            <td class="vcenter text-center">{{ $target->order }}</td>
                            <td class="vcenter text-center">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    @if(!empty($userAccessArr[7][3]))
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('admin/productCategory/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[7][4]))
                                    {{ Form::open(array('url' => 'admin/productCategory/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
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
                            <td colspan="8">@lang('label.NO_PRODUCT_CATEGORY_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>
@stop