
<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <div class="col-md-7 text-right">
            <h4 class="modal-title">{{ $target->name ??'' }}</h4>
        </div>
        <div class="col-md-5">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips"
                    title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>

        </div>
    </div>
    <div class="modal-body">

        <!--BASIC ORDER INFORMATION-->
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.RETAILER_PROFILE_STATUS')</strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-lg-3 col-sm-3">
                        <table class="table table-borderless margin-bottom-0">
                            <tr>
                                <td>
                                    @if (!empty($target->logo))
                                    <img alt="{{$target->name}}" src="{{URL::to('/')}}/public/uploads/retailer/{{$target->logo}}" width="100" height="100"/>
                                    @else
                                    <img alt="unknown" src="{{URL::to('/')}}/public/img/no_image.png" width="100" height="100"/>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-8 col-lg-9 col-sm-9">
                        <table class="table table-borderless margin-bottom-0">
<!--                            <tr>
                                <td class="bold" width="30%">@lang('label.NAME'):</td>
                                <td width="70%">
                                    {{ $target->name ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="30%">@lang('label.CODE'):</td>
                                <td width="70%">{{ $target->code ?? '' }}</td>
                            </tr>-->
                            <tr>
                                <td class="bold" width="30%">@lang('label.Registration_AS_TYPE',['type'=> $target->type == '1' ? "Retailer" : "Distributor" ]):</td>
                                <td width="70%">
                                    @if($target->user_id )
                                    <span class="label label-sm label-success">
                                        <i class="fa fa-check" aria-hidden="true"></i> 
                                    </span>
                                    &nbsp{{$target->type == '1' ? "Retailer" : "Distributor"}}
                                    @else 
                                    <span class="label label-sm label-danger">
                                        <i class="fa fa-times" aria-hidden="true"></i> 
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="30%">@lang('label.ASSIGNED_WAREHOUSE'):</td>
                                <td width="70%">
                                    @if($target->warehouseToRetailer )
                                    <span class="label label-sm label-success">
                                        <i class="fa fa-check" aria-hidden="true"></i> 
                                    </span>
                                    &nbsp{{$target->warehouseToRetailer->warehouse ? $target->warehouseToRetailer->warehouse->name : "N/A" }}
                                    @else 
                                    <span class="label label-sm label-danger">
                                        <i class="fa fa-times" aria-hidden="true"></i> 
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="30%">@lang('label.ASSIGNED_SR'):</td>
                                <td width="70%">
                                    @if($target->srToRetailer )
                                    <span class="label label-sm label-success">
                                        <i class="fa fa-check" aria-hidden="true"></i> 
                                    </span>
                                    &nbsp{{$target->sr->retailer ? $target->sr->user->first_name .' ' .$target->sr->user->last_name : "N/A" }}
                                    @else 
                                    <span class="label label-sm label-danger">
                                        <i class="fa fa-times" aria-hidden="true"></i> 
                                    </span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--END OF BASIC ORDER INFORMATION-->

        <!--LC INFORMATION-->


<!--        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="row padding-left-right-15">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.PROFILE_STATUS')</strong></h4>
                    </div>
                </div>

            </div>

        </div>-->
        <!--END OF LC INFORMATION-->

        <!--product details-->
        <div class="row padding-2 margin-top-15">
            <div class="col-md-12">

            </div>
        </div>
        <!--END OF PRODUCT DETAILS-->
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips"
                title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>

<script src="{{ asset('public/js/custom.js') }}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
});
$("#hasBankAccountSwitch").bootstrapSwitch({
    offColor: 'danger'

});


</script>
