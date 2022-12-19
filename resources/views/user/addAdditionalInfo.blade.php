
<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <div class="col-md-7 text-right">
            <h4 class="modal-title">{{ $target->first_name . ' ' . $target->last_name }}</h4>
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
                        <h4><strong>@lang('label.USER_PROFILE')</strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-lg-3 col-sm-3">
                        <table class="table table-borderless margin-bottom-0">
                            <tr>
                                <td>
                                    @if (!empty($target->photo) && File::exists('public/uploads/user/' . $target->photo))
                                    <img width="100" height="100"
                                         src="{{ URL::to('/') }}/public/uploads/user/{{ $target->photo }}"
                                         alt="{{ $target->full_name }}" />
                                    @else
                                    <img width="100" height="100" src="{{ URL::to('/') }}/public/img/unknown.png"
                                         alt="{{ $target->full_name }}" />
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-9 col-lg-9 col-sm-9">
                        <table class="table table-borderless margin-bottom-0">
                            <tr>
                                <td class="bold" width="30%">@lang('label.NAME')</td>
                                <td width="70%">
                                    {{ $target->first_name ?? '' . ' ' . $target->last_name??'' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="30%">@lang('label.USER_GROUP')</td>

                                <td width="70%">{{ $target->group_name ?? '' }}</td>

                            </tr>
                            <tr>
                                <td class="bold" width="30%">@lang('label.DEPARTMENT')</td>

                                <td width="70%">{{ $target->department_name ?? '' }}</td>

                            </tr>
                            <tr>
                                <td class="bold" width="30%">@lang('label.DESIGNATION')</td>

                                <td width="70%">{{ $target->designation_name ?? '' }}</td>

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
                        <h4><strong>@lang('label.ADDITIONAL_INFORMATION')</strong></h4>
                    </div>
                </div>

            </div>
            <div class="col-md-12">
                {!! Form::open(['group' => 'form', 'files' => true,'id'=>'userAddtionalIfoForm', 'class' => 'form-horizontal']) !!}
                {!! Form::hidden('id', $target->id) !!}
                <div class="form-body">
                    <div class="col-md-12 margin-top-10">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-5" for="alternativeContacts">@lang('label.ALTERNATIVE_CONTACT') :</label>
                                    <div class="col-md-7">
                                        {!! Form::text('alternative_contacts', $target->alternative_contacts ?? null,['id' => 'alternativeContacts', 'class' => 'form-control integer-only','placeholder'=>'(optional)']) !!}
                                        <span class="text-danger">{{ $errors->first('alternative_contacts') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-5" for="nidPassport">@lang('label.NID_PASSPORT') :<span class="text-danger"> *</span></label>
                                    <div class="col-md-7">
                                        {!! Form::text('nid_passport', $target->nid_passport ?? null, ['id' => 'nidPassport', 'class' => 'form-control  integer-only','placeholder'=>'NID/passport number']) !!}
                                        <span class="text-danger">{{ $errors->first('nid_passport') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-5" for="presentAddress">@lang('label.PRESENT_ADDRESS') :</label>
                                    <div class="col-md-7">
                                        {!! Form::textarea('present_address', $target->present_address ?? null, ['id' => 'presentAddress', 'class' => 'form-control','placeholder'=>'Enter address','rows' => 5 ]) !!}
                                        <span class="text-danger">{{ $errors->first('present_address') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-5 text-small" for="permanentAddress">@lang('label.PERMANENT_ADDRESS') :</label>
                                    <div class="col-md-7">
                                        {!! Form::textarea('permanent_address', $target->permanent_address ?? null, ['id' => 'permanentAddress', 'class' => 'form-control','placeholder'=>'Enter address','rows' => 5]) !!}
                                        <span class="text-danger">{{ $errors->first('permanent_address') }}</span>
                                    </div>
                                </div>
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
        <button class="btn green" type="button" id="submitUserAdditionalInfo">
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
</script>
