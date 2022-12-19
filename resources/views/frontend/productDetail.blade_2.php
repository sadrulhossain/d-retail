@extends('frontend.layouts.default.master')
@section('content')

<div class="container">
    @include('layouts.flash')
    <div class="wrap-breadcrumb">
        <ul>
            <li class="item-link"><a href="#" class="link">home</a></li>
            <li class="item-link"><span>detail</span></li>
        </ul>
    </div>
    <div class="row">

        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12 main-content-area">
            <div class="wrap-product-detail row">
                <div class="detail-media col-md-7">



                    <div class="col-md-4">
                        <div id="img-container">
                            <img class="add-main-image" src="{{URL::to('/')}}/public/uploads/product/thumbImage/{{ !empty(end($productImageArr)) ? end($productImageArr) : 'demo.jpg'  }}"  id="show-img"  alt="">
                        </div>

                        <div class="small-img col-md-12">
                            <img src="{{URL::to('/')}}/public/img/online_icon_right.png" class="icon-left" alt="" id="prev-img">

                            <div class="small-container">
                                <div id="small-img-roll">
                                    @foreach($productImageArr as $productName)
                                    <img src="{{URL::to('/')}}/public/uploads/product/thumbImage/{{$productName}}" class="show-small-img" alt="">
                                    @endforeach
                                </div>
                            </div>
                            <img src="{{URL::to('/')}}/public/img/online_icon_right.png" class="icon-right" alt="" id="next-img">
                        </div>
                    </div>




                </div>
                <div class="detail-info col-lg-5">
                    <div class="product-rating">
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <a href="#" class="count-review">(05 review)</a>
                    </div>
                    <h2 class="product-name">{{$target->productName}}</h2>
                    <div class="short-desc">
                        <ul>
                            <li>7,9-inch LED-backlit, 130Gb</li>
                            <li>Dual-core A7 with quad-core graphics</li>
                            <li>FaceTime HD Camera 7.0 MP Photos</li>
                        </ul>
                    </div>
                    <div class="wrap-social">
                        <a class="link-socail" href="#"><img src="{{asset('public/frontend/assets/images/social-list.png')}}" alt=""></a>
                    </div>
                    <div class="wrap-price"><span class="product-price">{{$target->price}}</span></div>
                    <div class="stock-info in-stock">
                        <p class="availability">Availability: <b>In Stock</b></p>
                    </div>
                    @if(!empty($attributeTypeWiseProductAttribute))
                    <div class="row">
                        @foreach ($attributeTypeWiseProductAttribute as $key => $attributeType)
                        <div class="select-attribute col-md-6">
                            <span>{{$attributeType['attribute_type_name']}}</span>
                            <div class="select-attribute-input">
                                <select name="{{$attributeType['attribute_type_name']}}" class="form-control" id="{{$attributeType['attribute_type_name']}}">
                                    @foreach ($attributeType['attribute'] as $k => $attribute)
                                    <option value="{{$k}}">{{$attribute['attribute_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    <div class="quantity">
                        <span>Quantity:</span>
                        <div class="quantity-input">
                            <input type="text" name="product-quatity" value="1" data-max="120" pattern="[0-9]*" id="qty">

                            <a class="btn btn-reduce" href="#"></a>
                            <a class="btn btn-increase" href="#"></a>
                        </div>
                    </div>
                    <div class="wrap-butons">
                        <button class="btn add-to-cart" id="addToCart" data-id="{{ $target->productId }}">Add to Cart</button>
                        <div class="wrap-btn">
                            <a href="#" class="btn btn-compare">Add Compare</a>
                            <button class="btn btn-wishlist {{ !empty($check)? 'hilight':'' }} add-wish-list" data-id="{{ $target->productId }}">Add Wishlist</button>
                        </div>
                    </div>
                </div>
                <div class="advance-info col-lg-12">
                    <div class="tab-control normal">
                        <a href="#description" class="tab-control-item active">description</a>
                        <a href="#add_infomation" class="tab-control-item">Addtional Infomation</a>
                        <a href="#review" class="tab-control-item">Reviews</a>
                    </div>
                    <div class="tab-contents">
                        <div class="tab-content-item active" id="description">
                            {!! $target->productDescription !!}
                        </div>
                        <div class="tab-content-item " id="add_infomation">
                            <table class="shop_attributes">
                                <tbody>
                                    <tr>
                                        <th>Weight</th><td class="product_weight">1 kg</td>
                                    </tr>
                                    <tr>
                                        <th>Dimensions</th><td class="product_dimensions">12 x 15 x 23 cm</td>
                                    </tr>
                                    <tr>
                                        <th>Color</th><td><p>Black, Blue, Grey, Violet, Yellow</p></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-content-item " id="review">

                            <div class="wrap-review-form">

                                <div id="comments">
                                    <h2 class="woocommerce-Reviews-title">01 review for <span>Radiant-360 R6 Chainsaw Omnidirectional [Orage]</span></h2>
                                    <ol class="commentlist">
                                        <li class="comment byuser comment-author-admin bypostauthor even thread-even depth-1" id="li-comment-20">
                                            <div id="comment-20" class="comment_container">
                                                <img alt="" src="{{asset('public/frontend/assets/images/author-avata.jpg')}}" height="80" width="80">
                                                <div class="comment-text">
                                                    <div class="star-rating">
                                                        <span class="width-80-percent">Rated <strong class="rating">5</strong> out of 5</span>
                                                    </div>
                                                    <p class="meta">
                                                        <strong class="woocommerce-review__author">admin</strong>
                                                        <span class="woocommerce-review__dash">–</span>
                                                        <time class="woocommerce-review__published-date" datetime="2008-02-14 20:00" >Tue, Aug 15,  2017</time>
                                                    </p>
                                                    <div class="description">
                                                        <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                </div><!-- #comments -->

                                <div id="review_form_wrapper">
                                    <div id="review_form">
                                        <div id="respond" class="comment-respond">

                                            <form action="#" method="post" id="commentform" class="comment-form" novalidate="">
                                                <p class="comment-notes">
                                                    <span id="email-notes">Your email address will not be published.</span> Required fields are marked <span class="required">*</span>
                                                </p>
                                                <div class="comment-form-rating">
                                                    <span>Your rating</span>
                                                    <p class="stars">

                                                        <label for="rated-1"></label>
                                                        <input type="radio" id="rated-1" name="rating" value="1">
                                                        <label for="rated-2"></label>
                                                        <input type="radio" id="rated-2" name="rating" value="2">
                                                        <label for="rated-3"></label>
                                                        <input type="radio" id="rated-3" name="rating" value="3">
                                                        <label for="rated-4"></label>
                                                        <input type="radio" id="rated-4" name="rating" value="4">
                                                        <label for="rated-5"></label>
                                                        <input type="radio" id="rated-5" name="rating" value="5" checked="checked">
                                                    </p>
                                                </div>
                                                <p class="comment-form-author">
                                                    <label for="author">Name <span class="required">*</span></label>
                                                    <input id="author" name="author" type="text" value="">
                                                </p>
                                                <p class="comment-form-email">
                                                    <label for="email">Email <span class="required">*</span></label>
                                                    <input id="email" name="email" type="email" value="" >
                                                </p>
                                                <p class="comment-form-comment">
                                                    <label for="comment">Your review <span class="required">*</span>
                                                    </label>
                                                    <textarea id="comment" name="comment" cols="45" rows="8"></textarea>
                                                </p>
                                                <p class="form-submit">
                                                    <input name="submit" type="submit" id="submit" class="submit" value="Submit">
                                                </p>
                                            </form>

                                        </div><!-- .comment-respond-->
                                    </div><!-- #review_form -->
                                </div><!-- #review_form_wrapper -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end main products area-->

        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 sitebar">
            <div class="widget widget-our-services ">
                <div class="widget-content">
                    <ul class="our-services">

                        <li class="service">
                            <a class="link-to-service" href="#">
                                <i class="fa fa-truck" aria-hidden="true"></i>
                                <div class="right-content">
                                    <b class="title">Free Shipping</b>
                                    <span class="subtitle">On Oder Over $99</span>
                                    <p class="desc">Lorem Ipsum is simply dummy text of the printing...</p>
                                </div>
                            </a>
                        </li>

                        <li class="service">
                            <a class="link-to-service" href="#">
                                <i class="fa fa-gift" aria-hidden="true"></i>
                                <div class="right-content">
                                    <b class="title">Special Offer</b>
                                    <span class="subtitle">Get a gift!</span>
                                    <p class="desc">Lorem Ipsum is simply dummy text of the printing...</p>
                                </div>
                            </a>
                        </li>

                        <li class="service">
                            <a class="link-to-service" href="#">
                                <i class="fa fa-reply" aria-hidden="true"></i>
                                <div class="right-content">
                                    <b class="title">Order Return</b>
                                    <span class="subtitle">Return within 7 days</span>
                                    <p class="desc">Lorem Ipsum is simply dummy text of the printing...</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div><!-- Categories widget-->

            <div class="widget mercado-widget widget-product">
                <h2 class="widget-title">@lang('label.POPULAR_PRODUCTS')</h2>
                <div class="widget-content">
                    <ul class="products">
                        @foreach($productPopularProduct as $product)
                        <li class="product-item">
                            <div class="product product-widget-style">
                                <div class="thumbnnail">
                                    <a href="{{ url('/productDetail/'.$product->productId) }}" title="{{ $product->productName }}">
                                        <figure><img src="{{URL::to('/')}}/public/uploads/product/thumbImage/{{$product->productImage[0] ?? ''}}" alt="T-Shirt Raw Hem Organic Boro Constrast Denim"></figure>
                                    </a>
                                </div>
                                <div class="product-info">
                                    <a href="{{ url('/productDetail/'.$product->productId) }}" class="product-name"><span>{{ $product->productName }}</span></a>
                                    <div class="wrap-price"><span class="product-price">{{ $product->price }}</span></div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div><!--end sitebar-->

        <div class="single-advance-box col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="wrap-show-advance-info-box style-1 box-in-site">
                <h3 class="title-box">@lang('label.RELATED_PRODUCTS')</h3>
                <div class="wrap-products">
                    <div class="products slide-carousel owl-carousel style-nav-1 equal-container" data-items="5" data-loop="false" data-nav="true" data-dots="false" data-responsive='{"0":{"items":"1"},"480":{"items":"2"},"768":{"items":"3"},"992":{"items":"3"},"1200":{"items":"5"}}' >

                        @foreach($productArr as $product)
                        <div class="product product-style-2 equal-elem ">
                            <div class="product-thumnail">
                                <a href="{{ url('/productDetail/'.$product->productId) }}" title="{{ $product->productName }}">
                                    <figure><img src="{{URL::to('/')}}/public/uploads/product/smallImage/{{$product->productImage[0] ?? ''}}" alt="T-Shirt Raw Hem Organic Boro Constrast Denim"></figure>
                                </a>
                                <div class="group-flash">
                                    <span class="flash-item new-label">@lang('label.NEW')</span>
                                </div>
                                <div class="wrap-btn">
                                    <a href="#" class="function-link">@lang('label.QUICK_VIEW')</a>
                                </div>
                            </div>
                            <div class="product-info">
                                <a href="{{ url('/productDetail/'.$product->productId) }}" class="product-name"><span>{{ $product->productName }}</span></a>
                                <div class="wrap-price"><span class="product-price">{{ $product->price }}</span></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div><!--End wrap-products-->
            </div>
        </div>

    </div><!--end row-->

</div><!--end container-->
<script src="{{asset('public/assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/js/js-image-zoom.js')}}" type="text/javascript"></script>
<script src="{{asset('public/js/main.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {

    


    $(document).on("click", ".add-wish-list", function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null
        };
        var id = $(this).data('id');
        if (id) {
            $.ajax({
                url: "{{URL::to('/addWishlist/')}}/" + id,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    $('#wishlistCount').html(res.wishlistCount);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                }
            });
        } else {
            alert('danger');
        }
    });

    $(document).on("click", "#addToCart", function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null
        };
        var id = $(this).data('id');
        var qty = $('#qty').val();
        if (id) {
            $.ajax({
                url: "{{URL::to('/addToCart/')}}/" + id,
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    qty: qty,
                },
                success: function (res) {
                    $('#cartCount').html(res.cartCount);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                }
            });
        } else {
            alert('danger');
        }
    });
});
</script>


@stop
