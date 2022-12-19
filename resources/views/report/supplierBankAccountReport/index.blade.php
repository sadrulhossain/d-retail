@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i>@lang('label.SUPPLIER_BANK_ACCOUNT_REPORT')
            </div>
            <div class="actions">
                <span class="text-right">
                    @if(Request::get('generate') == 'true')
                    @if(!($targetArr->isEmpty()))
                        @if(!empty($userAccessArr[129][6]))
                        <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'&view=print') }}"  title="@lang('label.PRINT')">
                            <i class="fa fa-print"></i>
                        </a>
                        @endif
                        @if(!empty($userAccessArr[129][9]))
                        <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'&view=pdf') }}"  title="@lang('label.DOWNLOAD')">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                        @endif
                    @endif 
                    @endif 
                </span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- Begin Filter-->
                    {!! Form::open(array('group' => 'form', 'url' => 'admin/supplierBankAccountReport/filter','class' => 'form-horizontal')) !!}
                    {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="bankId">@lang('label.BANK'):<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                {!! Form::select('bank_id',  $bankList, Request::get('bank_id'), ['class' => 'form-control js-source-states','id'=>'bankId']) !!}
                                <span class="text-danger">{{ $errors->first('bank_id') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form  text-right">
                            <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                                <i class="fa fa-search"></i> @lang('label.GENERATE')
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <!-- End Filter -->
                </div>
            </div>
            @if(Request::get('generate') == 'true')
             <div class="row margin-top-10">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            {{__('label.BANK')}} : <strong>{{  !empty($bankList[Request::get('bank_id')]) && Request::get('bank_id') != 0 ? $bankList[Request::get('bank_id')] : __('label.N_A') }} </strong> 
                        </h5>
                    </div>
                </div>
            </div>
            <div class="table-responsive">

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="text-center info">
                            <th class="vcenter text-center">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.SUPPLIER_NAME')</th>
                            <th class="vcenter">@lang('label.BANK_NAME')</th>
                            <th class="vcenter">@lang('label.BRANCH_NAME')</th>
                            <th class="vcenter">@lang('label.ACCOUNT_NAME')</th>
                            <th class="vcenter">@lang('label.ACCOUNT_NUMBER')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!($targetArr->isEmpty()))
                        <?php
                        $sl = 0;
                        ?>
                        @foreach($targetArr as $target)
                        <tr>
                            <td class="vcenter text-center">{{ ++$sl }}</td>
                            <td class="vcenter">{{ $target->name ?? '' }}</td>
                            <td class="vcenter">{{ $target->bank->name ?? '' }}</td>
                            <td class="vcenter">{{ $target->branch_name ?? '' }}</td>
                            <td class="vcenter">{{ $target->account_name ?? '' }}</td>
                            <td class="vcenter">{{ $target->account_number ?? '' }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="12" class="vcenter">@lang('label.NO_SUPPLIER_FOUND')&nbsp;</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @endif

        </div>
    </div>
</div>

@stop
