<div class="modal-content margin-top-10">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center">
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-9 col-md-offset-1 col-lg-8 col-lg-offset-1 col-sm-9 col-sm-offset-1 col-xs-12">
                {!! Form::open(array('group' => 'form', 'url' => '','class' => 'form-horizontal','id' => 'submitSubsciberForm')) !!}
                {{csrf_field()}}
                {!! Form::hidden('sum', $sum) !!}
                {!! Form::hidden('subscriber_email', $request->email) !!}
                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6 margin-top-6">
                    <span class="bold font-size-12">{{$varOne}}&nbsp;+&nbsp;{{$varTwo}}&nbsp;=</span>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6 margin-top-6">
                    {!! Form::text('sum_val', null, ['id' => 'sumVal', 'class' => 'bold captcha-text text-center']) !!}
                </div>
                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6">
                    <button class="btn btn-sm red-kk" type="button" id='submitCaptcha'>
                        @lang('label.SUBMIT')
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
