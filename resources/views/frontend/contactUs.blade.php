@extends('frontend.layouts.default.master')
@section('content')

<div class="container">

    <div class="wrap-breadcrumb">
        <ul>
            <li class="item-link"><a href="#" class="link">home</a></li>
            <li class="item-link"><span>Contact us</span></li>
        </ul>
    </div>
    <div class="container mb-3" style="padding-bottom: 30px;">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-lg-4">
                <div class="box">
                    <div class="icon">
                        <div class="image"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                        <div class="info">
                            <h3 class="title">@lang('label.MAIL')</h3>
                            <p>
                            {{!empty($konitaInfo->email)?$konitaInfo->email:''}}
                            </p>

                        </div>
                    </div>
                    <div class="space"></div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-lg-4">
                <div class="box">
                    <div class="icon">
                        <div class="image"><i class="fa fa-mobile" aria-hidden="true"></i></div>
                        <div class="info">
                            <h3 class="title">@lang('label.CONTACT')</h3>
                           
                            <p>
                              {{!empty($phoneNumber)?$phoneNumber:''}}
                               
                            </p>
                        </div>
                    </div>
                    <div class="space"></div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-lg-4">
                <div class="box">
                    <div class="icon">
                        <div class="image"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                        <div class="info">
                            <h3 class="title">@lang('label.ADDRESS')</h3>
                            <p>
                              {!! !empty($konitaInfo->address)?$konitaInfo->address:'' !!}
                            </p>
                        </div>
                    </div>
                    <div class="space"></div>
                </div>
            </div>
            <!-- /Boxes de Acoes -->

            <!--My Portfolio  dont Copy this -->

        </div>
    </div>
    <div class="container mb-3" style="padding-bottom: 30px;">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-12 col-md-12 map-view width-full">
                <iframe src=" {!! !empty($konitaInfo->google_emed)?$konitaInfo->google_emed:'' !!}" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            </div>
        </div>
    </div>
</div>


</div><!--end container-->

@stop