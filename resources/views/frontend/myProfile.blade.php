@extends('frontend.layouts.default.master')
@section('content')

<div class="container">

    <div class="wrap-breadcrumb">
        <ul>
            <li class="item-link"><a href="#" class="link">@lang('label.HOME')</a></li>
            <li class="item-link"><span>@lang('label.MY_PROFILE')</span></li>
        </ul>
    </div>
    <div class="wishlist-box font-size-14 style-1">
        <div class=" main-content-area">
            <div class="row">

                <div class="col-md-4 col-lg-4 col-sm-5 col-xs-12  text-center">
                    <div class="form-group last">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail text-center" style="width: 150px; height: 150px;">
                                @if($targetArr->checkin_source == 1 && !empty($targetArr->photo) && file_exists('public/frontend/assets/images/userImg/'.$targetArr->photo))
                                <img class="" width="150px" height="150px" src="{{ asset('public/frontend/assets/images/userImg/'.$targetArr->photo) }}">
                                @elseif(($targetArr->checkin_source == 2 || $targetArr->checkin_source == 3) && !empty($targetArr->photo))
                                <img class="" width="150px" height="150px" src="{!! $targetArr->photo !!}">
                                @else
                                <img class="" width="150px" height="150px" src="{{ asset('public/frontend/assets/images/avatar/avatar.png') }}">
                                @endif
                            </div>

                            <div class="fileinput-preview fileinput-exists thumbnail text-center" style="width: 200px; height: 200px;"> </div>
                            {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'saveProfilePhoto')) !!}

                            <div>
                                <span class="btn btn-block default btn-file">
                                    <span class="fileinput-new"> @lang('label.SELECT_IMAGE') </span>
                                    <span class="fileinput-exists"> @lang('label.CHANGE') </span>
                                    {!! Form::file('logo',['id'=> 'logo']) !!}
                                </span>
                                <span class="help-block text-danger">{!! $errors->first('logo') !!}</span>
                                <button class="btn fileinput-exists btn-block red-kk" type="button" id='submitProfilePhoto'>
                                    @lang('label.SUBMIT')
                                </button>
                                <a href="javascript:;" class="btn red fileinput-exists btn-block" data-dismiss="fileinput"> @lang('label.REMOVE') </a>
                            </div>

                            {!! Form::close() !!}
                        </div>
                        <div class="clearfix margin-top-20">
                            <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.HAZARD_IMAGE_FOR_IMAGE_DESCRIPTION')
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-lg-8 col-sm-7 col-xs-12">

                    <h3 class="title-box profile-title-box">
                        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 text-left profile-heading">
                            @lang('label.MY_PROFILE')
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 text-right">
                            <button class="btn btn-default btn-sm edit-my-profile" href="#modalEditProfile" data-toggle="modal" title="@lang('label.EDIT_PROFILE')">
                                @lang('label.EDIT_PROFILE')
                            </button>
                        </div>
                    </h3>

                    <div class="col-md-2 col-lg-2 col-sm-2 col-xs-4">
                        <span class="bold">@lang('label.NAME')</span>
                    </div>
                    <div class="col-md-10 col-lg-10 col-sm-10 col-xs-8">
                        :&nbsp;{!! $targetArr->name !!}
                    </div>

                    <div class="col-md-2 col-lg-2 col-sm-2 col-xs-4 margin-top-10">
                        <span class="bold">@lang('label.USERNAME')</span>
                    </div>
                    <div class="col-md-10 col-lg-10 col-sm-10 col-xs-8 margin-top-10">
                        :&nbsp;{!! $targetArr->username !!}
                    </div>

                    <div class="col-md-2 col-lg-2 col-sm-2 col-xs-4 margin-top-10">
                        <span class="bold">@lang('label.EMAIL')</span>
                    </div>
                    <div class="col-md-10 col-lg-10 col-sm-10 col-xs-8 margin-top-10">
                        :&nbsp;{!! $targetArr->email !!}
                    </div>

                    <div class="col-md-2 col-lg-2 col-sm-2 col-xs-4 margin-top-10">
                        <span class="bold">@lang('label.MOBILE')</span>
                    </div>
                    <div class="col-md-10 col-lg-10 col-sm-10 col-xs-8 margin-top-10">
                        :&nbsp;{!! $targetArr->phone !!}
                    </div>

                </div>   
            </div>
        </div>

    </div><!--end main content area-->
</div>

<!--edit profile-->
<div class="modal fade" id="modalEditProfile" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-sm ">
        <div id="showEditProfile">

        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script type="text/javascript">
$(document).ready(function () {

    //product quickview modal
    $(".edit-my-profile").on("click", function (e) {
        e.preventDefault();
        $.ajax({
            url: "{{ URL::to('/editMyProfile')}}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                $("#showEditProfile").html(res.html);
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            }
        }); //ajax
    });


    $(document).on("click", "#submitProfilePhoto", function (e) {
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
                        var formData = new FormData($('#saveProfilePhoto')[0]);
                        formData.append('stat', '1');
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
@stop
