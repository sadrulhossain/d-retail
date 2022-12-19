@extends('frontend.layouts.default.master')
@section('content')

<div class="container">

    <div class="wrap-breadcrumb">
        <ul>
            <li class="item-link"><a href="#" class="link">home</a></li>
            <li class="item-link"><span>Thank You</span></li>
        </ul>
    </div>
</div>

<div class="container pb-60">
    <div class="row">
        <div class="col-md-12 text-center">
            <h2>@lang('label.THANK_YOU_MESSAGE')</h2>
            <a class="btn btn-submit btn-submitx" href="{{ url('/shop') }}">@lang('label.CONTINUE_SHOPPING')</a>
        </div>
    </div>
</div><!--end container-->
@stop
