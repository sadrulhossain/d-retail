<div class="modal-content margin-top-60">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center">
            <h4 class="modal-title" id="exampleModalLavel">@lang('label.PRODUCT_QUICK_VIEW')</h4>
        </h4>
    </div>
    <div class="modal-body">

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    @if(!empty($target->productImage[0]))
                    <img src="{{URL::to('/')}}/public/uploads/product/smallImage/{{$target->productImage[0] ?? ''}}" id="pimage" style="height: 220px; width: 200px;">
                    @else
                    <img src="{{URL::to('/')}}/public/img/no_image.png" alt="">
                    @endif
                    <div class="card-body">
                        <a href="{{ url('/productDetail/'.$target->productId.'/'.$target->sku) }}">

                            <h4 class="card-title text-center" id="pname"> <strong>{{ $target->productName }}</strong> {{ $target->productAttribute }}</h4>
                        </a>
                    </div>

                </div>

            </div>


            <div class="col-md-4">
                <ul class="list-group">
                    <li class="list-group-item">@lang('label.PRODUCT_SKU'): <span id="pCode">{{ $target->sku }}</span> </li>
                    <li class="list-group-item">@lang('label.CATEGORY'): <span id="pCat">{{ $target->categoryName }}</span></li>
                    <li class="list-group-item">@lang('label.BRAND'): <span id="pBrand">{{ $target->brandName }}</span> </li>
					@auth
                    @if(Auth::user()->group_id == 19)
					<li class="list-group-item">@lang('label.PRICE'): <span id="pPrice">{{ $target->price }}&nbsp;@lang('label.TK')</span> </li>
                    @endif
                    @if(Auth::user()->group_id == 18)
					<li class="list-group-item">@lang('label.PRICE'): <span id="pPrice">{{ $target->distributor_price }}&nbsp;@lang('label.TK')</span> </li>
                    @endif
                    @if(Auth::user() && !in_array(Auth::user()->group_id,[18,19]))
					<li class="list-group-item">@lang('label.RETAILER_PRICE'): <span id="pPrice">{{ $target->price }}&nbsp;@lang('label.TK')</span> </li>
					<li class="list-group-item">@lang('label.DISTRIBUTOR_PRICE'): <span id="pPrice">{{ $target->distributor_price }}&nbsp;@lang('label.TK')</span> </li>
                    @endif
                    @endauth
                    <?php
                    $availability = !empty($target->available_quantity) && $target->available_quantity > 0 ? __('label.IN_STOCK') : __('label.OUT_OF_STOCK');
                    $availabilityColor = !empty($target->available_quantity) && $target->available_quantity > 0 ? 'green-sharp' : 'red-intense';
                    ?>
                    <li class="list-group-item">@lang('label.STOCK'): <span class="badge badge-{{$availabilityColor}}"> {{$availability}}</span> </li>
                </ul>

            </div>

            <!--<div class="col-md-4">

                <input type="hidden" name="product_id" id="product_id">
                <div class="cus-quantity">
                    <div class="quantity-input">
                        <input type="text" name="product-quatity" value="1" data-max="120" pattern="[0-9]*" id="qty">
                        <button class="btn btn-reduce minus"><i class="fa fa-minus" aria-hidden="true"></i></button>
                        <button class="btn btn-increase plus"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </div>
                </div>


                <button class="btn btn-primary" id="addToCart" sku-code="{{$target->sku}}" data-id="{{ $target->productId }}">@lang('label.ADD_TO_CART')</button>


            </div>-->
            <div class="col-md-4">
                <?php
                $inDepoProducts = Helper::getInDepoProduct($target->productId, $target->sku_id);
                ?>
                @if(Auth::check())
                @if(!empty($inDepoProducts))
                @if(Auth::user()->group_id == 14)
                <input type="hidden" name="product_id" id="product_id">
                <div class="cus-quantity">
                    <div class="quantity-input">
                        <input type="text" name="product-quatity" value="1" data-max="120" pattern="[0-9]*" id="qty">
                        <button class="btn btn-reduce minus"><i class="fa fa-minus" aria-hidden="true"></i></button>
                        <button class="btn btn-increase plus"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </div>
                </div>
                <button class="btn btn-primary" id="addToCart" sku-code="{{$target->sku}}" data-id="{{ $target->productId }}">@lang('label.ADD_TO_CART')</button>
                @endif
                @else
				
                <table class="table table-responsive table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center vcenter" colspan="2">@lang('label.AVAILABLE_STOCK')</th>
                        </tr>
                        <tr>
                            <th class="vcenter">@lang('label.CENTRAL_WAREHOUSE')</th>
                            <th class="text-center vcenter">{{ $target->available_quantity }}</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th class="vcenter" colspan="2">@lang('label.LOCAL_WAREHOUSE')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$localProductQtys->isEmpty())
                        @foreach($localProductQtys as $localInfo)
                        <tr>
                            <td class="text-center vcenter">{{ !empty($localInfo->wh_name) ? $localInfo->wh_name : ''}}</td>
                            <td class="text-center vcenter">{{ !empty($localInfo->local_quantity)? Helper::numberFormat2Digit($localInfo->local_quantity):'' }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="2" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                @endif
                @endif

            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on("click", "#addToCart", function () {
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null
            };
            var id = $(this).data('id');
            var qty = $('#qty').val();
            var skuCode = $(this).attr('sku-code');
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
                        sku_code: skuCode
                    },
                    success: function (res) {
//                            toastr.success(res.data, res.message, options);
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
        var plus = 0;
        var qty = $('#qty').val();
        $(document).on("click", ".plus", function () {
            plus += 1;
            if (plus > 1) {
                $('#qty').val(plus);
            } else {
                $('#qty').val(1);
            }
        });

        plus = 1;
        $(document).on("click", ".minus", function () {
            var qty = $('#qty').val();
            if (qty > 1) {
                plus -= 1;
                if (plus > 1) {
                    $('#qty').val(plus);
                } else {
                    $('#qty').val(1);
                }
            }
        });
    });
</script>
