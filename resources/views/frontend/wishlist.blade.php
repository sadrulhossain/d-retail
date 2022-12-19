@extends('frontend.layouts.default.master')
@section('content')

<div class="container">

    <div class="wrap-breadcrumb">
        <ul>
            <li class="item-link"><a href="#" class="link">home</a></li>
            <li class="item-link"><span>Wishlist</span></li>
        </ul>
    </div>
    <div class="wishlist-box style-1">
        <h3 class="title-box">@lang('label.WISHLIST')</h3>
        <div class=" main-content-area">

            <div class="wrap-iten-in-cart">
                <h2 class="box-title">@lang('label.PRODUCT_NAME')</h2>
                <ul class="products-cart">
                    @if(!empty($target))
                    @foreach($target as $product)
                    <li class="pr-cart-item">
                        <div class="product-image">
                            <a href="{{ url('/productDetail/'.$product->productId) }}" title="T-Shirt Raw Hem Organic Boro Constrast Denim">
                                <figure><img src="{{URL::to('/')}}/public/uploads/product/smallImage/{{$product->productImage[0] ?? ''}}" alt="T-Shirt Raw Hem Organic Boro Constrast Denim"></figure>
                            </a>
                        </div>
                        <div class="product-name">
                            <a class="link-to-product" href="{{ url('/productDetail/'.$product->productId.'/'.$product->sku) }}"><strong>{{$product->productName}}</strong> {{ $product->productAttribute }}</a>
                        </div>

                        <div class="price-field sub-total"><p class="price">{{ $product->price }}</p></div>
                        <div class="delete">
                            <button class="btn btn-delete remove-item" title="" data-id="{{ $product->wishItemId }}">
                                <i class="fa fa-times-circle" aria-hidden="true"></i>
                            </button>
                        </div>
                    </li>
                    @endforeach
                    @else
                    <li class="pr-cart-item">
                        <div class="product-name">
                            <p>@lang('label.EMPTY_WISHLIST')</p>
                        </div>
                    </li>
                    @endif


                </ul>
            </div>
        </div><!--end main content area-->
    </div>


</div><!--end container-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
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
                    url: "{{URL::to('/wishlist/removeItem')}}/" + id,
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
    });
</script>
@stop
