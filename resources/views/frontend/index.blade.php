@extends('frontend.layouts.default.master')
@section('content')

<div class="container">
    @include ('frontend.layouts.default.carosel')
    @include ('frontend.layouts.default.banner')

    @if(!$highlightedCategoryInfo->isEmpty())
    <div class="wrap-show-advance-info-box style-1">
        <h3 class="title-box">@lang('label.PRODUCT_CATEGORIES')</h3>

        <div class="wrap-products">
            <div class="wrap-product-tab tab-style-1">
                <div class="tab-contents">
                    <div class="tab-content-item active" id="digital_1a">
                        <div class="wrap-products slide-carousel owl-carousel style-nav-1 equal-container" data-items="5" data-loop="false" data-nav="true" data-dots="false" data-responsive='{"0":{"items":"1"},"480":{"items":"2"},"768":{"items":"3"},"992":{"items":"4"},"1200":{"items":"5"}}' >
                            @foreach($highlightedCategoryInfo as $category)

                            <div class="product product-style-2 equal-elem ">
                                <div class="product-thumnail width-inherit">

                                    <a href="{!! route('category.products.show', $category->id) !!}" class="width-inherit">
                                        <figure>
                                            @if(!empty($category->image) && file_exists('public/uploads/category/'.$category->image))
                                            <img  src="{{URL::to('/')}}/public/uploads/category/{{$category->image}}" alt="{{ $category->name }}"style="width:226px; height: 216px;">
                                            @else
                                            <img src="{{URL::to('/')}}/public/img/no_image.png" alt="{{ $category->name }}" style="width:226px;" >
                                            @endif
                                        </figure>
                                    </a>
                                </div>
                                <div class="product-info">
                                    <a href="{!! route('category.products.show', $category->id) !!}" class="product-name">
                                        <span class="category-text-center">
                                            <strong>{{ $category->name }}</strong>
                                        </span>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!--On Sale-->
    @if(!$featuredProductInfo->isEmpty())
    <div class="wrap-show-advance-info-box style-1">
        <h3 class="title-box">@lang('label.FEATURED_PRODUCTS')</h3>

        <div class="wrap-products">
            <div class="wrap-product-tab tab-style-1">
                <div class="tab-contents">
                    <div class="tab-content-item active" id="digital_1a">
                        <div class="wrap-products slide-carousel owl-carousel style-nav-1 equal-container" data-items="5" data-loop="false" data-nav="true" data-dots="false" data-responsive='{"0":{"items":"1"},"480":{"items":"2"},"768":{"items":"3"},"992":{"items":"4"},"1200":{"items":"5"}}' >
                            @foreach($featuredProductInfo as $product)

                            <div class="product product-style-2 equal-elem ">
                                <div class="product-thumnail">

                                    <a href="{{ url('/productDetail/'.$product->productId.'/'.$product->sku) }}" title="{{ $product->productName }} {{ $product->productAttribute }}">
                                        <figure>
                                            @if(!empty($product->productImage[0]) && file_exists('public/uploads/product/smallImage/'.$product->productImage[0]))
                                            <img src="{{URL::to('/')}}/public/uploads/product/smallImage/{{$product->productImage[0]}}" alt="{{ $product->productName }} {{ $product->productAttribute }}">
                                            @else
                                            <img src="{{URL::to('/')}}/public/img/no_image.png" alt="{{ $product->productName }} {{ $product->productAttribute }}">
                                            @endif
                                        </figure>
                                    </a>
                                    <div class="group-flash">
                                        <span class="flash-item sale-label">@lang('label.FEATURED')</span>
                                    </div>
                                    <div class="wrap-btn">
                                        <a href="#modalProductQuickView" data-toggle="modal" data-id="{!! $product->productId !!}" sku-code="{{$product->sku}}" class="function-link product-quick-view">@lang('label.QUICK_VIEW')</a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <a href="{{ url('/productDetail/'.$product->productId.'/'.$product->sku) }}" class="product-name">
                                        <span>
                                            <strong>{{ $product->productName }}</strong> {{ $product->productAttribute }}
                                        </span>
                                    </a>
                                    @auth
                                    @if(Auth::user()->group_id == 19)
                                    <div class="wrap-price"><span class="product-price">@lang('label.PRICE') : {{$product->price}} @lang('label.TK')</span></div>
                                    @endif
                                    @if(Auth::user()->group_id == 18)
                                    <div class="wrap-price"><span class="product-price">@lang('label.PRICE') : {{$product->distributor_price ?? '00'}} @lang('label.TK')</span></div>
                                    @endif
                                    @if(Auth::user() && !in_array(Auth::user()->group_id,[18,19]))
                                    <div class="wrap-price"><span class="product-price">@lang('label.RETAILER_PRICE') : {{$product->price}} @lang('label.TK')</span></div>
                                    <div class="wrap-price"><span class="product-price">@lang('label.DISTRIBUTOR_PRICE') : {{$product->distributor_price ?? '00'}} @lang('label.TK')</span></div>
                                    @endif
                                    @endauth

                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!--Latest Products-->
    @if(!$latestProductInfo->isEmpty())
    <div class="wrap-show-advance-info-box style-1">
        <h3 class="title-box">@lang('label.LATEST_PRODUCTS')</h3>
        <!--<div class="wrap-top-banner">
            <a href="#" class="link-banner banner-effect-2">
                <figure><img src="{{asset('public/frontend/assets/images/digital-electronic-banner.jpg')}}" width="1170" height="240" alt=""></figure>
            </a>
        </div>!-->
        <div class="wrap-products">
            <div class="wrap-product-tab tab-style-1">
                <div class="tab-contents">
                    <div class="tab-content-item active" id="digital_1a">
                        <div class="wrap-products slide-carousel owl-carousel style-nav-1 equal-container" data-items="5" data-loop="false" data-nav="true" data-dots="false" data-responsive='{"0":{"items":"1"},"480":{"items":"2"},"768":{"items":"3"},"992":{"items":"4"},"1200":{"items":"5"}}' >
                            @foreach($latestProductInfo as $product)
                            <div class="product product-style-2 equal-elem ">
                                <div class="product-thumnail">

                                    <a href="{{ url('/productDetail/'.$product->productId.'/'.$product->sku) }}" title="{{ $product->productName }} {{ $product->productAttribute }}">
                                        <figure>
                                            @if(!empty($product->productImage[0]) && file_exists('public/uploads/product/smallImage/'.$product->productImage[0]))
                                            <img src="{{URL::to('/')}}/public/uploads/product/smallImage/{{$product->productImage[0]}}" alt="{{ $product->productName }} {{ $product->productAttribute }}">
                                            @else
                                            <img src="{{URL::to('/')}}/public/img/no_image.png" alt="{{ $product->productName }} {{ $product->productAttribute }}">
                                            @endif
                                        </figure>
                                    </a>
                                    <div class="group-flash">
                                        <span class="flash-item new-label">@lang('label.NEW')</span>
                                    </div>
                                    <div class="wrap-btn">
                                        <a href="#modalProductQuickView" data-toggle="modal" data-id="{!! $product->productId !!}" sku-code="{{$product->sku}}" class="function-link product-quick-view">@lang('label.QUICK_VIEW')</a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <a href="{{ url('/productDetail/'.$product->productId.'/'.$product->sku) }}" class="product-name">
                                        <span>
                                            <strong>{{ $product->productName }}</strong> {{ $product->productAttribute }}
                                        </span>
                                    </a>
                                    @auth
                                    @if(Auth::user()->group_id == 19)
                                    <div class="wrap-price"><span class="product-price">@lang('label.PRICE') : {{$product->price}} @lang('label.TK')</span></div>
                                    @endif
                                    @if(Auth::user()->group_id == 18)
                                    <div class="wrap-price"><span class="product-price">@lang('label.PRICE') : {{$product->distributor_price ?? '00'}} @lang('label.TK')</span></div>
                                    @endif
                                    @if(!in_array(Auth::user()->group_id,[18,19]))
                                    <div class="wrap-price"><span class="product-price">@lang('label.RETAILER_PRICE') : {{$product->price}} @lang('label.TK')</span></div>
                                    <div class="wrap-price"><span class="product-price">@lang('label.DISTRIBUTOR_PRICE') : {{$product->distributor_price ?? '00'}} @lang('label.TK')</span></div>
                                    @endif
                                    @endauth

                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Special Product!-->
    <!--Latest Products-->
    @if(!$specialProductInfo->isEmpty())
    <div class="wrap-show-advance-info-box style-1">
        <h3 class="title-box">@lang('label.SPECIAL_PRODUCTS')</h3>
        <div class="wrap-products">
            <div class="wrap-product-tab tab-style-1">
                <div class="tab-contents">
                    <div class="tab-content-item active" id="digital_1a">
                        <div class="wrap-products slide-carousel owl-carousel style-nav-1 equal-container" data-items="5" data-loop="false" data-nav="true" data-dots="false" data-responsive='{"0":{"items":"1"},"480":{"items":"2"},"768":{"items":"3"},"992":{"items":"4"},"1200":{"items":"5"}}' >
                            @foreach($specialProductInfo as $product)
                            <div class="product product-style-2 equal-elem ">
                                <div class="product-thumnail">

                                    <a href="{{ url('/productDetail/'.$product->productId.'/'.$product->sku) }}" title="{{ $product->productName }} {{ $product->productAttribute }}">
                                        <figure>
                                            @if(!empty($product->productImage[0]) && file_exists('public/uploads/product/smallImage/'.$product->productImage[0]))
                                            <img src="{{URL::to('/')}}/public/uploads/product/smallImage/{{$product->productImage[0]}}" alt="{{ $product->productName }} {{ $product->productAttribute }}">
                                            @else
                                            <img src="{{URL::to('/')}}/public/img/no_image.png" alt="{{ $product->productName }} {{ $product->productAttribute }}">
                                            @endif
                                        </figure>
                                    </a>
                                    <div class="group-flash">
                                        <span class="flash-item new-label">@lang('label.SPECIAL')</span>
                                    </div>
                                    <div class="wrap-btn">
                                        <a href="#modalProductQuickView" data-toggle="modal" data-id="{!! $product->productId !!}" sku-code="{{$product->sku}}" class="function-link product-quick-view">@lang('label.QUICK_VIEW')</a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <a href="{{ url('/productDetail/'.$product->productId.'/'.$product->sku) }}" class="product-name">
                                        <span>
                                            <strong>{{ $product->productName }}</strong> {{ $product->productAttribute }}
                                        </span>
                                    </a>
                                    @auth
                                    @if(Auth::user()->group_id == 19)
                                    <div class="wrap-price"><span class="product-price">@lang('label.PRICE') : {{$product->price}} @lang('label.TK')</span></div>
                                    @endif
                                    @if(Auth::user()->group_id == 18)
                                    <div class="wrap-price"><span class="product-price">@lang('label.PRICE') : {{$product->distributor_price ?? '00'}} @lang('label.TK')</span></div>
                                    @endif
                                    @if(Auth::user() && !in_array(Auth::user()->group_id,[18,19]))
                                    <div class="wrap-price"><span class="product-price">@lang('label.RETAILER_PRICE') : {{$product->price}} @lang('label.TK')</span></div>
                                    <div class="wrap-price"><span class="product-price">@lang('label.DISTRIBUTOR_PRICE') : {{$product->distributor_price ?? '00'}} @lang('label.TK')</span></div>
                                    @endif
                                    @endauth

                                    
                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <h3 class="title-box news">@lang('label.NEWS')</h3>
    <div class="news-block">
        <div class="row">
            @if(!$newsAndEvents->isEmpty())
            @foreach($newsAndEvents as $post)
            <div class="item  col-xs-12 col-lg-4 grid-group-item">
                <div class="post-thumbnail image-cover">
                    <div class="featured-image">
                        <a  href="{{ URL::to('/news-and-events').'/'.$post->slug }}" class="post-featured-img image-cover">
                            @if(!empty($post->featured_image) && file_exists('public/uploads/NewsAndEvents/'.$post->featured_image))
                            <img class="group list-group-image " src="{{ asset('public/uploads/NewsAndEvents/'. $post->featured_image) }}" alt="featured Image" />
                            @else
                            <img class="group list-group-image" src="{{ asset('public/uploads/img/no-image.png') }}" alt="" />
                            @endif
                        </a>
                    </div>
                    <div class="post-caption news-title">
                        <a href="{{ URL::to('/').'/news-and-events/'.$post->slug }}" class="group inner list-group-item-heading">{!! $post->title ?? '' !!}</a>
                    </div>

                    <h3 class="post-date group inner">
                        @if(!empty($post->publish_date))
                        <i class="fa fa-calendar"></i>
                        {{  !empty($post->publish_date)? Helper::formatDateTimeForPost($post->publish_date):'' }}
                        @endif
                        &nbsp;
                        @if(!empty($post->location))
                        <i class="fa fa-map-marker"></i>
                        {{ !empty($post->location) ? $post->location : ''}}
                        @endif
                    </h3>
                    @if(!empty($post->featured_image))
                    <div class="post-content group inner list-group-item-text text-justify">
                        {!!  Helper::limitTextWords($post->content, 60, (URL::to('/').'/news-and-events/'.$post->slug))  !!}
                    </div>
                    @endif
                </div>
            </div>

            @endforeach
            @endif
        </div>
    </div>
</div>
<!--set product quickview modal-->
<div class="modal fade" id="modalProductQuickView" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div id="showProductQuickView">

        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script type="text/javascript">
$(document).ready(function () {

    //product quickview modal
    $(".product-quick-view").on("click", function (e) {
        e.preventDefault();
        var productId = $(this).attr("data-id");
        var skuCode = $(this).attr("sku-code");
        var productFlag = $(this).data("product-flag");
        $.ajax({
            url: "{{ URL::to('/productQuickView')}}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: productId,
                sku_code: skuCode
            },
            success: function (res) {
                $("#showProductQuickView").html(res.html);
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            }
        }); //ajax
    });




});
</script>


@stop
