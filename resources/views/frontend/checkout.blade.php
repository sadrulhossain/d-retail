@extends('frontend.layouts.default.master')
@section('content')

<div class="container">

    <div class="wrap-breadcrumb">
        {{-- <ul>
            <li class="item-link"><a href="#" class="link">home</a></li>
        <li class="item-link"><span>login</span></li>
        </ul> --}}
        @include('frontend.layouts.default.flash')
    </div>
    <div class=" main-content-area">
        @if (Cart::count()==0)
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>@lang('label.CART_EMPTY')</h2>
                <a class="btn btn-submit btn-submitx" href="{{ url('/inDepoProducts') }}">
                    <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>&nbsp;@lang('label.CONTINUE_SHOPPING')
                </a>
            </div>
        </div>
        @else
        {!! Form::open(['route' => 'placeOrder' , 'id' => 'checkOutForm' ,'group' => 'form', 'class' => 'form-horizontal']) !!}
        @csrf
        <div class="summary summary-checkout">
            <div class="summary-item payment-method">
                <h4 class="title-box">@lang('label.PAYMENT_METHODS')</h4>
                <p class="summary-info"><span class="title">@lang('label.CHECK/MONEY_ORDER')</span></p>
                <p class="summary-info"><span class="title">@lang('label.CREDIT_CARD')</span></p>
                <div class="choose-payment-methods">
                    <label class="">
                        {!! Form::radio('cash_on_delivery',1, ['id' => 'forCourseMember']) !!}
                        <span>@lang('label.CASH_ON_DELIVERY')</span><br>
                        <!--<span class="payment-desc">@lang('label.CASH_ON_DELIVERY_MESSAGE')</span>-->
                    </label>
                </div>
            </div>

            <div class="summary-item shipping-method">
                <h4 class="title-box f-title">@lang('label.SHIPPING_ADDRESS')</h4>
                <p class="row-in-form">
                    <label for="add">@lang('label.ADDRESS')</label>
                    {!! Form::textarea('address', null, ['class' => 'form-control', 'id' => 'address','rows' => '2']) !!}
                    <span class="required">{{ $errors->first('address') }}</span>
                </p>
                @if($customer->checkin_source != 1)
                <p class="row-in-form">
                    <label for="phoneId">@lang('label.MOBILE')<span class="required">*</span></label>
                    {!! Form::text('mobile', !empty($customer->phone) ? $customer->phone : null, ['class' => 'form-control', 'id' => 'phoneId']) !!}
                    <br /><span class="required">{{ $errors->first('mobile') }}</span>
                </p>
                @endif
                <p class="summary-info grand-total"><span>@lang('label.GRAND_TOTAL')</span> <span class="grand-total-price">{{Cart::total()}}</span></p>
                <button type="submit" class="btn btn-medium" id="placeOrder">@lang('label.PLACE_ORDER')</button>
                <!--                <h4 class="title-box">@lang('label.DISCOUNT_CODE')</h4>
                                <p class="row-in-form">
                                    <label for="coupon-code">@lang('label.COUPON_CODE')</label>
                                    {!! Form::text('coupon_code', null, ['class' => 'form-control js-source-states', 'id' => 'couponCode']) !!}
                                </p>
                                <a href="#" class="btn btn-small">@lang('label.APPLY')</a>-->
            </div>
        </div>
        {!! Form::close() !!}

        <div class="wrap-show-advance-info-box style-1 box-in-site">
            <h3 class="title-box">@lang('label.MOST_VIEWED_PRODUCTS')</h3>
            <div class="wrap-products">
                <div class="products slide-carousel owl-carousel style-nav-1 equal-container" data-items="5" data-loop="false" data-nav="true" data-dots="false" data-responsive='{"0":{"items":"1"},"480":{"items":"2"},"768":{"items":"3"},"992":{"items":"3"},"1200":{"items":"5"}}' >
                    @foreach($productPopularProduct as $product)
                    <div class="product product-style-2 equal-elem ">
                        <div class="product-thumnail">
                            <a href="{{ url('/productDetail/'.$product->productId) }}" title="{{ $product->productName }}">
                                <figure><img src="{{URL::to('/')}}/public/uploads/product/smallImage/{{$product->productImage[0] ?? ''}}" alt="{{ $product->productName }}"></figure>
                            </a>
                            <div class="group-flash">
                                <span class="flash-item sale-label">@lang('label.SALE')</span>
                            </div>
                            <div class="wrap-btn">
                                <a href="#modalProductQuickView" data-toggle="modal" data-id="{!! $product->productId !!}" data-product-flag="2" sku-code="{!! $product->sku !!}" class="function-link product-quick-view">@lang('label.QUICK_VIEW')</a>
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
        @endif

    </div><!--end main content area-->
</div><!--end container-->

<!--set product quickview modal-->
<div class="modal fade" id="modalProductQuickView" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div id="showProductQuickView">

        </div>
    </div>
</div>
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>-->
<script type="text/javascript">
$(document).ready(function () {
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
                sku_code: skuCode,
                product_flag: productFlag // product flag for All Products
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
