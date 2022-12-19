@extends('frontend.layouts.default.master')
@section('content')

<div class="container">

    <div class="wrap-breadcrumb">
        <ul>
            <li class="item-link"><a href="{{url('/') }}" class="link">home</a></li>

        </ul>
    </div>
    <div class="row">

        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12 main-content-area">
            @if(!empty($advertisementInfo))
            <div class="banner-shop">
                <a href="{!!$advertisementInfo->url!!}" class="banner-link">
                    <figure><img src="{{URL::to('/')}}/public/uploads/content/advertisement/{{$advertisementInfo->img_d_x}}" alt=""></figure>
                </a>
            </div>
            @endif
            @if(!empty($searchedText))
            <div class="wrap-shop-control">
                <h1 class="shop-title">@lang('label.SEARCHED_RESULT_FOR') "<?php echo $searchedText ?>"</h1>
            </div>
            @endif

            <div class="row">
                @if(!$target->isEmpty())
                <ul class="product-list grid-products equal-container">

                    @foreach($target as $product)
                    <li class="col-lg-4 col-md-6 col-sm-6 col-xs-6 ">
                        <div class="product product-style-3 equal-elem ">
                            <div class="product-thumnail">

                                <a href="{{ url('/productDetail/'.$product->productId.'/'.$product->sku) }}" title="{{ $product->productName }} {{$product->productAttribute}}">
                                    <figure>
                                        @if(!empty($product->productImage[0]) && file_exists('public/uploads/product/smallImage/'.$product->productImage[0]))
                                        <img src="{{URL::to('/')}}/public/uploads/product/smallImage/{{!empty($product->productImage[0]) ? $product->productImage[0] : ''}}" alt="{{!empty($product->productName) ? $product->productName : ''}} {{ !empty($product->productAttribute) ? $product->productAttribute : '' }}">

                                        @else
                                        <img width="300px;" src="{{URL::to('/')}}/public/img/no_image.png" alt="{{ $product->productName }} {{ $product->productAttribute }}">
                                        @endif
                                    </figure>
                                </a>
                            </div>
                            <div class="product-info">
                                <a href="{{ url('/productDetail/'.$product->productId.'/'.$product->sku) }}" class="product-name">
                                    <span>
                                        <strong>{{ $product->productName }}</strong> {{ $product->productAttribute }}
                                    </span>
                                </a>


                                <div class="wrap-price">
                                    <span class="product-price {{0}}">@lang('label.TK'){{ $product->price }}</span>

                                </div>
                                <a href="#modalProductQuickView" data-toggle="modal" data-id="{!! $product->productId !!}" sku-code="{{$product->sku}}" class="btn add-to-cart product-quick-view">@lang('label.QUICK_VIEW')</a>
                            </div>
                        </div>
                    </li>
                    @endforeach

                </ul>
                @else
                <div class="aboutus-info style-center">
                    <b class="box-title">@lang('label.PRODUCTS_NOT_AVAILABLE_FOR',['for'=>Request::get('search')])</b>
                </div>
                @endif
            </div>

            <div class="wrap-pagination-info">
                <div class="col-md-8">
                    {{ $target->appends(Request::all())->links() }}
                    <?php
                    $start = empty($target->total()) ? 0 : (($target->currentPage() - 1) * $target->perPage() + 1);
                    $end = ($target->currentPage() * $target->perPage() > $target->total()) ? $target->total() : ($target->currentPage() * $target->perPage());
                    ?> 
                </div>
                <div class="col-md-4">
                    <p class="result-count">
                        @lang('label.SHOWING') {{ $start }} @lang('label.TO') {{$end}} @lang('label.OF')  {{$target->total()}} @lang('label.RECORDS')
                    </p>

                </div>

            </div>
        </div><!--end main products area-->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 sitebar">
            <div class="widget mercado-widget categories-widget">
                <div class="widget-content">
                    <ul class="list-category">

                        @if(!empty($leftCategoryArr))
                        @foreach($leftCategoryArr as $catId=>$leftCategory)
                        @if(empty($leftCategory))
                        <li class="category-item has-child-cate">
                            <a href="{!! route('category.products.show', $catId) !!}" class="cate-link">{!!$categoryList[$catId]??''!!}</a>
                        </li>
                        @else

                        <li class="category-item has-child-cate {{(($catId==$id) || ((isset($categoryInfo->parent_id)) && ($categoryInfo->parent_id==$catId)))?'open':''}}">
                            <a href="{!! route('category.products.show', $catId) !!}" class="cate-link" style="{{($catId==$id)?'color:red;':''}}">{!!$categoryList[$catId]??''!!}</a>
                            <span class="toggle-control">+</span>
                            <ul class="sub-cate">
                                @foreach($leftCategory as $cId=>$left)
                                <li class="category-item"><a href="{!! route('category.products.show', $cId) !!}" class="cate-link" style="{{($cId==$id)?'color:red;':''}}">{!!$categoryList[$cId]??''!!}</a></li>
                                @endforeach
                            </ul>
                        </li>

                        @endif

                        @endforeach
                        @endif
                    </ul>
                </div>
            </div><!-- Categories widget-->

            <div class="widget mercado-widget widget-product">
                <h2 class="widget-title">@lang('label.SPECIAL_PRODUCT')</h2>
                <div class="widget-content">
                    <ul class="products">
                        @foreach($specialProductInfo as $product)
                        <li class="product-item">
                            <div class="product product-widget-style">
                                <div class="thumbnnail">
                                    <a href="{{ url('/productDetail/'.$product->productId.'/'.$product->sku) }}" title="{{ $product->productName }} {{ $product->productAttribute }}">
                                        <figure>
                                            @if(!empty($product->productImage[0]))
                                            <img src="{{URL::to('/')}}/public/uploads/product/thumbImage/{{$product->productImage[0] ?? ''}}" alt="{{ $product->productName }} {{ $product->productAttribute }}">
                                            @else
                                            <img src="{{URL::to('/')}}/public/img/no_image.png" alt="{{ $product->productName }} {{ $product->productAttribute }}">
                                            @endif

                                        </figure>
                                    </a>
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
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div><!-- brand widget-->

        </div><!--end sitebar-->



    </div><!--end row-->

</div><!--end container-->

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
