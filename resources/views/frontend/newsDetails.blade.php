@extends('frontend.layouts.default.master')
@section('content')

<div class="container">
    <div class="main-content post-detail">
        <div class="page-title-container">
            <div class="container">
                <h2 class="article-title first-word mt-15">{!! $postDetail->title ?? '' !!}</h2>
                <div class="post-date">
                    @if(!empty($postDetail->publish_date))
                    <i class="fa fa-calendar"></i>
                    {{  !empty($postDetail->publish_date)? Helper::formatDateTimeForPost($postDetail->publish_date):'' }}
                    @endif
                    &nbsp;
                    @if(!empty($postDetail->location))
                    <i class="fa fa-map-marker"></i>
                    {{ !empty($postDetail->location) ? $postDetail->location : ''}}
                    @endif
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if(isset($postDetail->featured_image))
                    <div class="featured-image pull-left news-details-img">
                        <img src="{{ asset('public/uploads/NewsAndEvents/'.$postDetail->featured_image ?? 'demo-featured-img.png') }}" alt="featured image">
                    </div>
                    @endif
                    <div class="post-content text-justify">
                        {!! $postDetail->content ?? '' !!}
                    </div>
                </div>
                <div class="col-md-12 pb-15 text-center">
                    @if(!empty(Helper::prevPost('news_and_events', $postDetail->order)))
                    <a class="btn btn-md btn-info" href="{!! URL::to('/').'/news-and-events/'. Helper::prevPost('news_and_events', $postDetail->order)->slug !!}"><i class="fa fa-backward"></i>&nbsp;&nbsp;@lang('label.PREVIOUS')</a>
                    @endif

                    @if(!empty(Helper::nextPost('news_and_events', $postDetail->order)))
                    <a class="btn btn-md btn-info" href="{!! URL::to('/').'/news-and-events/'. Helper::nextPost('news_and_events', $postDetail->order)->slug !!}">@lang('label.NEXT')&nbsp;&nbsp;<i class="fa fa-forward"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div><!--end container-->

@stop