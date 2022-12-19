@extends('layouts.default.master')
@section('data_count')	
  

    <div class="row">
          @include('layouts.flash')
        <div class="col-md-12">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gift"></i>{{trans('label.HOTLINE')}} </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                        <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                        <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    {{ Form::model($hotlineArr, array('route' => array('hotline.update'), 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'hotlineUpdate')) }}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">{{trans('label.HOTLINE')}} :</label>
                                    <div class="col-md-6">
                                        {{ Form::text('hotline', !empty($hotlineArr->hotline) ? $hotlineArr->hotline : '', array('id'=> 'title', 'class' => 'form-control', 'placeholder' => '')) }}
                                    </div>
                                </div>				
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-circle green">Submit</button>
                                
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                    <!-- END FORM-->
                </div>
            </div>
        </div>
    </div>
</div>

<link href="{{asset('public/assets/global/plugins/bootstrap-summernote/summernote.css')}}" rel="stylesheet" type="text/css" />
 <script src="{{asset('public/assets/pages/scripts/components-editors.min.js')}}" type="text/javascript"></script>
 <script src="{{asset('public/assets/global/plugins/bootstrap-summernote/summernote.min.js')}}" type="text/javascript"></script>

<script type="text/javascript">
	$(document).on("submit", '#configurationUpdate', function (e) {
        //This function use for sweetalert confirm message
		e.preventDefault();
		var form = this;
        swal({
            title: 'Are you sure you want to Submit?',
            text: '<strong></strong>',
            type: 'warning',
            html: true,
            allowOutsideClick: true,
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonClass: 'btn-info',
            cancelButtonClass: 'btn-danger',
            confirmButtonText: 'Yes, I agree',
            cancelButtonText: 'No, I do not agree',
        },
        function (isConfirm) {
			if (isConfirm) {
				toastr.info("Loading...", "Please Wait.", {"closeButton": true});
				 form.submit();
			} else {
				//swal(sa_popupTitleCancel, sa_popupMessageCancel, "error");
				
			}
        });
    });
</script>

@stop
