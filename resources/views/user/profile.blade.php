@extends('layouts.default.master')
@section('data_count')
<!-- BEGIN CONTENT BODY -->
<!-- BEGIN PORTLET-->
@include('layouts.flash')
<!-- END PORTLET-->
<div class="col-md-12 margin-left-right-0">
    <div class="col-md-12 text-right">
        <div class="actions">
            <a href="{{ URL::to('/dashboard'.Helper::queryPageStr($qpArr)) }}" class="btn btn-sm blue-dark">
                <i class="fa fa-reply"></i>&nbsp;@lang('label.CLICK_TO_GO_TO_DASHBOARD')
            </a>
        </div>

    </div>
    <div class="col-md-12 margin-top-10">
        <!-- BEGIN PROFILE SIDEBAR -->
        <div class="profile">

            <!-- START:: User Basic Info -->
            <div class="row">
                <!-- START::User Image -->
                <div class="col-md-2 text-center">
                    <!-- SIDEBAR USER TITLE -->
                    <div class="profile-userpic">
                        @if(!empty($userInfoData->photo) && File::exists('public/uploads/user/' . $userInfoData->photo))
                        <img src="{{URL::to('/')}}/public/uploads/user/{{$userInfoData->photo}}" class="text-center img-responsive pic-bordered border-default recruit-profile-photo-full"
                             alt="{{ !empty($userInfoData->full_name)? $userInfoData->full_name:''}}" style="width: 100%;height: 100%;" />
                        @else
                        <img src="{{URL::to('/')}}/public/img/unknown.png" class="text-center img-responsive pic-bordered border border-default recruit-profile-photo-full"
                             alt="{{ !empty($userInfoData->full_name)? $userInfoData->full_name:'' }}"  style="width: 100%;height: 100%;" />
                        @endif
                    </div>
                    <div class="profile-usertitle">
                        <div class="text-center margin-bottom-10">

                            <b>{{!empty($userInfoData->full_name)? $userInfoData->full_name . (!empty($userInfoData->nick_name)? ' ('.$userInfoData->nick_name . ')':''):''}}</b>
                        </div>
                    </div>
                </div>
                <!-- END::User Image -->

                <div class="col-md-10">
                    <!--<div class="column sortable ">-->
                    <div class="portlet portlet-sortable box green-color-style">
                        <div class="portlet-title ui-sortable-handle">
                            <div class="caption">
                                <i class="fa fa-info-circle green-color-style-color"></i>@lang('label.BASIC_INFORMATION')
                                @if(AUTH::user()->id == 1)
                                <a class="btn btn-xs label-red-mint tooltips vcenter float-md-right" title="Edit Information" href="{{ URL::to('admin/user/' . auth()->user()->id . '/edit' . Helper::queryPageStr($qpArr)) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 8px !important">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    
                                    <tr >
                                        <td class="vcenter fit bold info" width="20%">@lang('label.USER_GROUP')</td>
                                        <td class="vcenter fit" width="80%"> {{ !empty($userInfoData->user_group) ? $userInfoData->user_group: ''}}</td>
                                    </tr>
                                    <tr >
                                        <td class="vcenter fit bold info" width="20%">@lang('label.DEPARTMENT')</td>
                                        <td class="vcenter fit" width="80%"> {{ !empty($userInfoData->department) ? $userInfoData->department: ''}}</td>
                                    </tr>
                                    <tr >
                                        <td class="vcenter fit bold info" width="20%">@lang('label.DESIGNATION')</td>
                                        <td class="vcenter fit" width="80%"> {{ !empty($userInfoData->designation) ? $userInfoData->designation: ''}}</td>
                                    </tr>
                                    <tr >
                                        <td class="vcenter fit bold info" width="20%">@lang('label.USER_NAME')</td>
                                        <td class="vcenter fit" width="80%"> {{ !empty($userInfoData->username) ? $userInfoData->username: ''}}</td>
                                    </tr>
                                    <tr >
                                        <td class="vcenter fit bold info" width="20%">@lang('label.EMAIL')</td>
                                        <td class="vcenter fit" width="80%"> {{ !empty($userInfoData->email) ? $userInfoData->email: ''}}</td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter fit bold info" width="20%">@lang('label.PHONE')</td>
                                        <td class="vcenter fit" width="80%"> {{ !empty($userInfoData->phone) ? $userInfoData->phone: ''}} </td>
                                    </tr>
                                    @if(!empty($whArr))
                                    <tr>
                                        <td class="vcenter fit bold info" width="20%">@lang('label.ASSIGNED_WAREHOUSES')</td>
                                        <td class="vcenter fit" width="80%"> 
                                            <?php $whI = 0 ?>
                                            @foreach($whArr as $whId => $wh)
                                            <?php $whComma = $whI != (sizeof($whArr) - 1) ? ', ' : ''; ?>
                                            {{$wh.$whComma}}
                                            <?php $whI++; ?>
                                            @endforeach
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--</div>-->
                </div>
            </div>
            <!-- END:: User Basic Info -->

        </div>
    </div>
</div>


@endsection