<div class="modal-content margin-top-60">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn grey-mint pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center">
            <h4 class="modal-title" id="exampleModalLavel">@lang('label.EDIT_PROFILE')</h4>
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'saveProfileData')) !!}

                <fieldset class="wrap-input">
                    <label for="frm-reg-lname">@lang('label.NAME')</label>
                    {!! Form::text('name', $targetArr->name, ['id'=> 'name', 'class' => 'form-control','autocomplete' => 'off','placeholder' => 'Enter Name']) !!}
                    <span class="required">{{ $errors->first('name') }}</span>
                </fieldset>

                <fieldset class="wrap-input">
                    <label for="frm-reg-email">@lang('label.EMAIL')<span class="required"> *</span></label>
                    {!! Form::text('email', $targetArr->email, ['id'=> 'email', 'class' => 'form-control','autocomplete' => 'off','placeholder' => 'Enter Email']) !!}
                    <span class="required">{{ $errors->first('email') }}</span>
                </fieldset>

                <fieldset class="wrap-input">
                    <label for="frm-reg-lname">@lang('label.MOBILE')<span class="required"> *</span></label>
                    {!! Form::text('phone', $targetArr->phone, ['id'=> 'phone', 'class' => 'form-control','autocomplete' => 'off','placeholder' => '+880']) !!}
                    <span class="required">{{ $errors->first('phone') }}</span>
                </fieldset>


            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn pull-right tooltips grey-mint" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <button class="btn btn-sign text-right red-kk" type="button" id='submitProfile'>
            <i class="fa fa-check"></i> @lang('label.SUBMIT')
        </button>
        &nbsp;

        {!! Form::close() !!}
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script type="text/javascript">
$(document).ready(function () {
    $(document).on("click", "#submitProfile", function (e) {
        e.preventDefault();
        swal({
            title: 'Are you sure?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, save',
            cancelButtonText: 'No, cancel',
            closeOnConfirm: true,
            closeOnCancel: true},
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        var options = {
                            closeButton: true,
                            debug: false,
                            positionClass: "toast-bottom-right",
                            onclick: null,
                        };

                        // Serialize the form data
                        var formData = new FormData($('#saveProfileData')[0]);
                        formData.append('stat', '2');
                        $.ajax({
                            url: "{{URL::to('/updateProfile')}}",
                            type: "POST",
                            dataType: 'json', // what to expect back from the PHP script, if anything
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData,
                            success: function (res) {
                                toastr.success(res.data, res.message, options);
                                location.reload();
                            },
                            error: function (jqXhr, ajaxOptions, thrownError) {

                                if (jqXhr.status == 400) {
                                    var errorsHtml = '';
                                    var errors = jqXhr.responseJSON.message;
                                    $.each(errors, function (key, value) {
                                        errorsHtml += '<li>' + value[0] + '</li>';
                                    });
                                    toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                                } else if (jqXhr.status == 401) {
                                    toastr.error(jqXhr.responseJSON.message, '', options);
                                } else {
                                    toastr.error('Error', 'Something went wrong', options);
                                }
                                App.unblockUI();
                            }
                        });
                    }
                });

    });
});
</script>
