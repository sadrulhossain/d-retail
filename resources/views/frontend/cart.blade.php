@extends('frontend.layouts.default.master')
@section('content')

<div class="container">

    <div class="wrap-breadcrumb">
        <ul>
            <li class="item-link"><a href="#" class="link">@lang('label.HOME')</a></li>
            <li class="item-link"><span>@lang('label.CART')</span></li>
        </ul>
    </div>
    <div class=" main-content-area">

        @if (Cart::count()==0)
        <h1>@lang('label.CART_EMPTY')</h1>
        <a class="btn btn-submit btn-submitx" href="{{ url('/shop') }}">@lang('label.CONTINUE_SHOPPING')</a>
        @else
        <div class="wrap-iten-in-cart">
            <div>
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="box-title">@lang('label.PRODUCT_NAME')</h3>
                    </div>
                    <div class="col-md-6">
                        <button class="custom-clear-cart-btn" id="clearCart">@lang('label.CLEAR_SHOPPING_CART')</button>
                    </div>
                </div>
            </div>
            <ul class="products-cart">
                @if(!$content->isEmpty())
                @foreach($content as $cartItem)
                <li class="pr-cart-item">
                    <div class="quantity">
                        <div class="quantity-input">
                            <button class="btn btn-increase qty-change" data-key="{{$cartItem->rowId}}"></button>
                            <input type="text" name="product-quatity" value="{{ $cartItem->qty }}" data-max="120" pattern="[0-9]*" id="qty{{$cartItem->rowId}}">
                            <button class="btn btn-reduce qty-change" data-key="{{$cartItem->rowId}}"></button>
                        </div>
                    </div>

                    <div class="product-image">
                        <figure><img  src="{{URL::to('/')}}/public/uploads/product/smallImage/{{$cartItem->options->image}}" alt="T-Shirt Raw Hem Organic Boro Constrast Denim"></figure>
                    </div>
                    <div class="product-name">
                        <a class="link-to-product" href="#">{{ $cartItem->name }}</a>
                        <p class="price"><span id="productPrice_{{$cartItem->rowId}}">{{ $cartItem->price }}</span> <small>@lang('label.TAKA/UNIT')</small></p>
                    </div>


                    <div class="price-field sub-total"><p class="price"><span id="productSubTotal_{{$cartItem->rowId}}" class="product-sub-total">{{ $cartItem->price * $cartItem->qty}}</span> <small>@lang('label.TK')</small></p></div>
                    <div class="delete">
                        <button class="btn btn-delete remove-item" title="" data-id="{{ $cartItem->rowId }}">
                            <i class="fa fa-times-circle" aria-hidden="true"></i>
                        </button>
                    </div>
                </li>
                @endforeach
                @else

                @endif
            </ul>
        </div>

        <div class="summary my-cart">
            <div class="row">
                <div class="checkout-info col-md-6">
                    <!--                    <label class="checkbox-field">
                                            <input class="frm-input " name="have-code" id="have-code" value="" type="checkbox"><span>@lang('label.I_HAVE_PROMO_CODE')</span>
                                        </label>-->
                    <a class="btn btn-checkout" href="{{ url('/checkout') }}">@lang('label.CHECK_OUT')</a>
                    <a class="link-to-shop" href="{{ url('/shop') }}">@lang('label.CONTINUE_SHOPPING')<i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
                </div>
                <!--                <div class="update-clear ">
                
                                    <a class="btn btn-update" href="#">@lang('label.UPDATE_SHOPPING_CART')</a>
                                </div>-->
                <?php
                $vat = !empty($companyInfo->vat) ? $companyInfo->vat : 0.00;
                ?>
                <div class="order-summary ">
                    <p class="summary-info"><span class="title">@lang('label.SUBTOTAL')&emsp;&emsp;&emsp;:</span><b class="index"><span id="subTotal">{{ Cart::subtotal()}}&nbsp;@lang('label.TK')</span></b></p>
                    <p class="summary-info"><span class="title">@lang('label.VAT') ({{ $vat }}%)&emsp;:</span><b class="index"><span id="tax">{{Cart::tax()}}&nbsp;@lang('label.TK')</span></b></p>
                    <p class="summary-info total-info "><span class="title">@lang('label.TOTAL')&emsp;&emsp;&emsp;&emsp;:</span><b class="index"><span id="total">{{Cart::total()}}&nbsp;@lang('label.TK')</span></b></p>
                </div>
            </div>
        </div>
        @endif
    </div><!--end main content area-->
</div><!--end container-->
<!--
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>-->
<script type="text/javascript">
$(document).ready(function () {
    $(document).on("click", ".remove-item", function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null
        };
        var id = $(this).data('id');
        if (id) {
            $.ajax({
                url: "{{URL::to('/removeCart')}}/" + id,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    toastr.success(res.data, res.message, options);
                    location.reload();
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

    $(document).on("click", ".qty-change", function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null
        };
        var key = $(this).attr("data-key");
        var qty = $('#qty' + key).val();
        if($(this).hasClass('btn-increase')){
            qty = qty+1;
        } else if($(this).hasClass('btn-reduce')){
            qty = qty-1;
        }
        var price = $('#productPrice_' + key).text();
        var productSubTotal = Number(qty) * Number(price);
        $.ajax({
            url: "{{ URL::to('/updateCart')}}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                key: key,
                qty: qty,
            },
            success: function (res) {
                $('#productSubTotal_' + key).text(productSubTotal);
                $('#subTotal').text(parseFloat(res.subTotal).toFixed(2));
                $('#tax').text(parseFloat(res.vat).toFixed(2));
                $('#total').text(parseFloat(res.total).toFixed(2));
                $('#cartCount').html(res.cartCount);
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            }
        }); //ajax
    });

    $(document).on("click", "#clearCart", function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null
        };
        $.ajax({
            url: "{{URL::to('/clearCart')}}",
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                toastr.success(res.data, res.message, options);
                location.reload();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                if (jqXhr.status == 401) {
                    toastr.error(jqXhr.responseJSON.message, '', options);
                } else {
                    toastr.error('Error', 'Something went wrong', options);
                }
            }
        });
    });

});
</script>

@stop
