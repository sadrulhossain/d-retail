@extends('frontend.layouts.default.master')
@section('content')

<div class="container">
    <div class="main-content post-detail">
        <div class="page-title-container">
            <div class="container">
                <h2 class="article-title first-word mt-15">{!! $postDetail->title ?? '' !!}</h2>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="post-content text-justify">
                        {!! $postDetail->content ?? '' !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div><!--end container-->

@stop