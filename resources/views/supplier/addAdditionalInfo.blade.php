
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
                        <h4><strong>@lang('label.SUPPLIER_PROFILE')</strong></h4>
                    </div>
                </div>
                <div class="row">
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
                            <tr>
                                <td class="bold" width="30%">@lang('label.ADDRESS'):</td>

                                <td width="70%">{{ $target->address ?? '' }}</td>

                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--END OF BASIC ORDER INFORMATION-->

        <!--LC INFORMATION-->


        <div class="row margin-top-10">
            <!--            <div class="col-md-12">
                            <div class="row padding-left-right-15">
                                <div class="col-md-12 border-bottom-1-green-seagreen">
                                    <h5><strong>@lang('label.ADDITIONAL_INFORMATION')</strong></h5>
                                </div>
                            </div>
                        </div>-->
            <div class="col-md-12">
                {!! Form::open(['group' => 'form', 'url' => '', 'files' => true,'id'=>'supplierAddtionalForm', 'class' => 'form-horizontal']) !!}
                {!! Form::hidden('id', $target->id) !!}
                <div class="form-body">
                    <!--Bussiness Information Start-->
                    <div class="col-md-12">
                        <div class="row padding-left-right-15">
                            <div class="col-md-12 border-bottom-1-green-seagreen">
                                <h5><strong>@lang('label.BUSINESS_INFORMATION')</strong></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 margin-top-10">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="col-md-4 text-center" for="businessHour">@lang('label.BUSINESS_HOURS') :</label>
                                <div class="col-md-8">
                                    <div class="input-group bootstrap-touchspin">
                                        {!! Form::text('business_hour', $target->business_hour ?? null, ['id' => 'businessHour', 'class' => 'form-control integer-decimal-only text-right','placeholder'=>'Total Hour']) !!}
                                        <span class="text-danger">{{ $errors->first('owner_name') }}</span>
                                        <span class="input-group-addon bootstrap-touchspin-prefix bold" >Hrs.</span>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4" for="holidays">@lang('label.HOLIDAYS') :</label>
                                <div class="col-md-8">
                                    <div class="input-group bootstrap-touchspin">
                                        {!! Form::text('holidays', $target->holidays ?? null, ['id' => 'holidays', 'class' => 'form-control integer-decimal-only text-right','placeholder'=>'Total Days']) !!}
                                        <span class="input-group-addon bootstrap-touchspin-prefix bold" >Days</span>
                                        <span class="text-danger">{{ $errors->first('owner_name') }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3" for="name">@lang('label.DETAILS_INFO'):</label>
                                <div class="col-md-9">
                                    {!! Form::textarea('details_info', $target->details_info ?? null, ['id' => 'name', 'class' => 'form-control','rows'=>'4']) !!}
                                    <span class="text-danger">{{ $errors->first('owner_name') }}</span>
                                </div>
                            </div>

                        </div>

                    </div>
                    <!--Bussiness Information End-->

                    <!--Supplier information Start-->
                    <div class="col-md-12">
                        <div class="row padding-left-right-15">
                            <div class="col-md-12 border-bottom-1-green-seagreen">
                                <h5><strong>@lang('label.SUPPLIER_INFORMATION')</strong></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 margin-top-10">

                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="col-md-4 text-center" for="supplierType">@lang('label.SUPPLIER_TYPE') :</label>
                                <div class="col-md-8">
                                    {!! Form::select('supplier_type', $supplierTypes, $target->supplier_type ?? null, ['class' => 'form-control js-source-states', 'id' => 'supplierType']) !!}
                                    <span class="text-danger">{{ $errors->first('supplier_type') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 text-center" for="creditPeriod">@lang('label.CREDIT_PERIOD') :</label>
                                <div class="col-md-8">
                                    <div class="input-group bootstrap-touchspin">
                                        {!! Form::text('credit_period', $target->credit_period ?? null, ['id' => 'creditPeriod', 'class' => 'form-control integer-decimal-only text-right',]) !!}
                                        <span class="input-group-addon bootstrap-touchspin-prefix bold">Days</span>
                                    </div>
                                    <span class="text-danger">{{ $errors->first('owner_name') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 text-center" for="returnType">@lang('label.RETURN_TYPE') :</label>
                                <div class="col-md-8">
                                    {!! Form::select('return_option', $returnTypes, $target->return_option ?? null, ['class' => 'form-control js-source-states', 'id' => 'returnType']) !!}
                                    <span class="text-danger">{{ $errors->first('return_type') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 text-center" for="returnTimeline">@lang('label.RETURN_TIMELINE') :</label>
                                <div class="col-md-8">
                                    <div class="input-group bootstrap-touchspin">
                                        {!! Form::text('return_timeline', $target->return_timeline ?? null, ['id' => 'returnTimeline', 'class' => 'form-control integer-decimal-only text-right','placeholder'=>'Return within']) !!}
                                        <span class="input-group-addon bootstrap-touchspin-prefix bold">Days</span>
                                    </div>
                                    <span class="text-danger">{{ $errors->first('owner_name') }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--Supplier information End-->

                    <!--Bank information Start-->
                    <div class="col-md-12">
                        <div class="row padding-left-right-15">
                            <div class="col-md-12 border-bottom-1-green-seagreen">
                                <h5><strong>@lang('label.BANK_INFORMATION')</strong></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 margin-top-10">

                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="col-md-4 text-center" for="bank">@lang('label.BANK') :</label>
                                <div class="col-md-8">
                                    {!! Form::select('bank_id', $bankList, $target->bank_id ?? null, ['class' => 'form-control js-source-states', 'id' => 'bank']) !!}
                                    <span class="text-danger">{{ $errors->first('bank_id') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 text-center" for="branchName">@lang('label.BRANCH_NAME') :</label>
                                <div class="col-md-8">
                                        {!! Form::text('branch_name', $target->branch_name ?? null, ['id' => 'branchName', 'class' => 'form-control','placeholder'=>'Branch Name']) !!}
                                        <span class="text-danger">{{ $errors->first('branch_name') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 text-center" for="routingNumber">@lang('label.ROUTING_NUMBER') :</label>
                                <div class="col-md-8">
                                    {!! Form::text('routing_number', $target->routing_number ?? null, ['id' => 'routingNumber', 'class' => 'form-control','placeholder'=>'Routing Number']) !!}
                                    <span class="text-danger">{{ $errors->first('routing_number') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 text-center" for="accountName">@lang('label.ACCOUNT_NAME') :</label>
                                <div class="col-md-8">
                                    {!! Form::text('account_name', $target->account_name ?? null, ['id' => 'accountName', 'class' => 'form-control','placeholder'=>'Account Name']) !!}
                                    <span class="text-danger">{{ $errors->first('account_name') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 text-center" for="accountNumber">@lang('label.ACCOUNT_NUMBER') :</label>
                                <div class="col-md-8">
                                    {!! Form::text('account_number', $target->account_number ?? null, ['id' => 'accountNumber', 'class' => 'form-control','placeholder'=>'Account Number']) !!}
                                    <span class="text-danger">{{ $errors->first('account_number') }}</span>
                                </div>
                            </div>

                        </div>

                    </div>
                    <!--Bank information End-->
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
        <button class="btn green" type="button" id="supplierAdditionalInfoButton">
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
