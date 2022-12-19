
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
                        <h4><strong>@lang('label.RETAILER_PROFILE')</strong></h4>
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
                            <tr>
                                <td class="bold" width="30%">@lang('label.NAME'):</td>
                                <td width="70%">
                                    {{ $target->name ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="30%">@lang('label.CODE'):</td>

                                <td width="70%">{{ $target->code ?? '' }}</td>

                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--END OF BASIC ORDER INFORMATION-->

        <!--LC INFORMATION-->


        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="row padding-left-right-15">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.LOGIN_INFORMATION')</strong></h4>
                    </div>
                </div>

            </div>
            <div class="col-md-12 margin-top-20">
                {!! Form::open(['group' => 'form', 'files' => true,'id'=>'showAddtionaInfoForm', 'class' => 'form-horizontal']) !!}
                {!! Form::hidden('id', $target->id) !!}
                <div class="form-body">
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <div class="col-md-10 offset-1">
                                <div class="form-group">
                                    <label class="control-label col-md-4" for="ownerNme">@lang('label.USERNAME') :<span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        {!! Form::text('username', $target->username ?? null, ['id' => 'ownerNme', 'class' => 'form-control','placeholder'=>'Name']) !!}
                                        <span class="text-danger">{{ $errors->first('owner_name') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4" for="password">@lang('label.PASSWORD') :<span class="text-danger"> *</span></label>
                                    <div class="col-md-8">
                                        <div class="input-group bootstrap-touchspin">
                                            {!! Form::password('password', ['id'=> 'password', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                                            <span class="input-group-btn">
                                                <button class="btn default show-pass" type="button" id="showPass">
                                                    <i class="fa fa-eye" id="passIcon"></i>
                                                </button>
                                            </span>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                        <div class="clearfix margin-top-10">
                                            <span class="label label-danger">@lang('label.NOTE')</span>
                                            @lang('label.COMPLEX_PASSWORD_INSTRUCTION')
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4" for="confPassword">@lang('label.CONF_PASSWORD') :<span class="text-danger"> *</span></label>
                                    <div class="col-md-8">
                                        <div class="input-group bootstrap-touchspin">
                                            {!! Form::password('conf_password', ['id'=> 'confPassword', 'class' => 'form-control']) !!}
                                            <span class="input-group-btn">
                                                <button class="btn default show-pass" type="button" id="showConfPass">
                                                    <i class="fa fa-eye" id="confPassIcon"></i>
                                                </button>
                                            </span>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('conf_password') }}</span>
                                    </div>
                                </div>
<!--                                <div class="form-group">
                                    <label class="col-md-4" for="nidPassport">@lang('label.NID_PASSPORT') :</label>
                                    <div class="col-md-8">
                                        {!! Form::text('nid_passport', $target->nid_passport ?? null, ['id' => 'nidPassport', 'class' => 'form-control  integer-only','placeholder'=>'Enter NID/Passport number']) !!}
                                        <span class="text-danger">{{ $errors->first('nid_passport') }}</span>
                                    </div>
                                </div>-->


                            </div>

                        </div>

                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
        <!--END OF LC INFORMATION-->

        <!--product details-->
        <div class="row padding-2 margin-top-15">
            <div class="col-md-12">

            </div>
        </div>
        <!--END OF PRODUCT DETAILS-->
    </div>
    <div class="modal-footer">
        <button class="btn green" type="button" id="showRetailerLoginAdditionalInfoButton">
            <i class="fa fa-check "></i> Submit</button>
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
