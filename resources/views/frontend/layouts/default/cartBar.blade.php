
<div class="col-md-12">
    <div class="row padding-10 cart-bar-header">
        <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5 text-center margin-top-10">
            @if (Cart::count()!=0)
            <span class="custom-clear-cart-btn font-size-12" id="clearCart">
                @lang('label.CLEAR_CART')
            </span>
            @else
            &nbsp;
            @endif
        </div>
        <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5 margin-top-12">
            <span class="cart-bar-title bold font-size-16">
                <i class="fa fa-shopping-basket custom-i" aria-hidden="true"></i>&nbsp;@lang('label.CART')
            </span>
        </div>
        <div class="col-md-2 col-lg-2 col-sm-2 col-xs-2 margin-top-10">
            <a class="cart-close btn btn-danger btn-sm tooltips" title="@lang('label.CLOSE')"><i class="fa fa-close"></i></a>
        </div>
    </div>
    <div class=" main-content-area">

        @if (Cart::count()==0)
        <div class="row margin-top-10">
            <div class="col-md-12 text-center">
                <span class="bold font-size-16">@lang('label.CART_EMPTY')</span>
            </div>
            <div class="col-md-12 text-center">
                <a class="btn btn-sm red-kk bold custom-clear-cart-btn font-size-12 margin-top-10" href="{{ url('/inDepoProducts') }}">
                    <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>&nbsp;@lang('label.CONTINUE_SHOPPING')
                </a>
            </div>
        </div>
        @else
        {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal','files' => true,'id'=>'setOrderForm')) !!}
        {!! Form::hidden('warehouse_id',$warehouseInfo->warehouse_id) !!}
        {{csrf_field()}}
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="form-group">
                    @if(Auth::user()->group_id == 14)
                    <label class="text-left control-label col-md-4">@lang('label.RETAILER') :<span class="text-danger"> *</span></label>
                    <div class="col-md-8">
                        {!! Form::select('retailer_id', $retailerList, !empty($retailer_id) ? $retailer_id : null, ['class' => 'form-control js-source-states', 'id' => 'retailerId']) !!}
                    </div>
                    @else
                    {!! Form::hidden('retailer_id', Auth::user()->retailer->id ) !!}
                    @endif
                </div>
            </div>

        </div>
        <div class="row margin-top-10">
            <div class="cart-list-height webkit-scrollbar wrap-iten-in-cart table-responsive">
                <table class="table table-striped products-cart">
                    <thead>
                        <tr class="active">
                            <th class="bold vcenter text-center font-size-14" colspan="5">@lang('label.ITEM_LIST')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$content->isEmpty())
                        @foreach($content as $cartItem)

                        <tr class="pr-cart-item">
                            <td class="vcenter text-center quantity" width="15px">
                                <div class="quantity-input">
                                    <button class="btn btn-increase qty-change" data-key="{{$cartItem->rowId}}" type="button"></button>
                                    <input type="text" name="product-quatity" value="{{ $cartItem->qty }}" data-max="120" pattern="[0-9]*" id="qty{{$cartItem->rowId}}">
                                    <button class="btn btn-reduce qty-change" data-key="{{$cartItem->rowId}}" type="button"></button>
                                </div>
                            </td>
                            <td class="text-center vcenter" width="30px">
                                @if(file_exists('public/uploads/product/smallImage/'.$cartItem->options->image))
                                <img width="30" height="auto" src="{{URL::to('/')}}/public/uploads/product/smallImage/{{$cartItem->options->image}}" alt="{{ $cartItem->name }}">
                                @else
                                <img width="30"  height="auto" src="{{URL::to('/')}}/public/img/no_image.png" alt="{{ $cartItem->name }}">
                                @endif
                            </td>

                            <td class="vcenter" width="130px">
                                <div class="width-inherit">
                                    <span class="font-size-12">{{ $cartItem->name }}</span><br/>
                                    <span class="font-size-11 pull-left" id="productPrice_{{ $cartItem->rowId }}">{{ $cartItem->price }} @lang('label.TK')/{{$cartItem->options->unit}}</span>
                                </div>
                            </td>
                            <td class="text-center vcenter" width="35px">
                                @if( in_array(Auth::user()->group_id ,[18,19]) )
                                {!! Form::hidden('customer_demand['.$cartItem->id.']', $cartItem->qty, ['id'=> 'customerDemand_'.$cartItem->id, 'data-id' => $cartItem->id, 'class' => 'form-control width-inherit text-right integer-decimal-only cart-customer-demand'] ) !!}
                                @else
                                {!! Form::text('customer_demand['.$cartItem->id.']', null, ['id'=> 'customerDemand_'.$cartItem->id, 'data-id' => $cartItem->id, 'class' => 'form-control customer-demand width-inherit text-right integer-decimal-only cart-customer-demand','placeHolder'=>'qty']) !!}
                                @endif

                            </td>
                            <td class="text-right vcenter" width="50px">
                                <div class="width-inherit">
                                    <span class="font-size-11 product-sub-total" id="productSubTotal_{{$cartItem->rowId}}">{{ $cartItem->price * $cartItem->qty }} @lang('label.TK')</span>
                                </div>
                            </td>
                            <td class="text-center vcenter" width="5px">
                                <span class="btn btn-delete width-inherit remove-item font-size-12" title="" data-id="{{ $cartItem->rowId }}">
                                    <i class="fa fa-close" aria-hidden="true"></i>
                                </span>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                <?php
                $vat = !empty($companyInfo->vat) && !empty($companyInfo->include_vat) ? $companyInfo->vat : 0.00;
                ?>
                <table class="table table-borderless">
<!--                    <tr>
                        <td class="bold vcenter text-right" colspan="3">@lang('label.SUBTOTAL'):</td>
                        <td class="bold vcenter text-right">
                            <span id="subTotal">{{ Cart::subtotal()}}&nbsp;@lang('label.TK')</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="bold vcenter text-right" colspan="3">@lang('label.VAT') ({{ $vat }}%):</td>
                        <td class="bold vcenter text-right">
                            <span id="subTotal">{{ Cart::tax()}}&nbsp;@lang('label.TK')</span>
                        </td>
                    </tr>-->
                    <tr>
                        <td class="bold vcenter text-right" colspan="3">@lang('label.TOTAL'):</td>
                        <td class="bold vcenter text-right">
                            <span id="subTotal">{{ Cart::total()}}&nbsp;@lang('label.TK')</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        div class="row margin-top-10">
        <div class="col-md-12">
            <div class="form-group">
                <label class="text-left control-label col-md-4">@lang('label.NOTE_') :<span class="text-danger"> *</span></label>
                <div class="col-md-8">
                    {!! Form::textarea('note', null, ['id'=> 'note', 'class' => 'form-control','rows'=>3,'placeholder'=>__('label.ORDER_NOTE'),'maxlength' => 255]) !!}
                    <span class="text-danger">{{ $errors->first('note') }}</span>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-7 col-lg-7 col-sm-7 col-xs-7 ">
            <a class="btn btn-sm red-kk custom-clear-cart-btn font-size-11" href="{{ url('/inDepoProducts') }}">
                <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>&nbsp;@lang('label.CONTINUE_SHOPPING')
            </a>
        </div>
        <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5 ">
            <!--                <a class="btn btn-sm purple custom-clear-cart-btn font-size-12" href="{{ url('/checkout') }}">
                                <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>&nbsp;@lang('label.CHECK_OUT')
                            </a>-->
            <a class="btn btn-sm purple custom-clear-cart-btn font-size-11 confirm-order">
                <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>&nbsp;@lang('label.CONFIRM_ORDER')
            </a>
        </div>
    </div>
    @endif
</div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null
        };

        $('.cart-close').on('click', function () {
            $(".cart-bar").slideUp();
        });
        // Sr CheckOut Start
        $("#retailerId").on("change", function () {
            var retailer_id = $(this).val();
            if (retailer_id == "0" || !retailer_id) {
                return false;
            }
            $.ajax({
                url: "{{URL::to('/updateSrCart')}}",
                type: 'POST',
                dataType: 'json',
                data: {retailer_id: retailer_id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
//                        $('.cart-bar').html('');
                },
                success: function (res) {
                    $('#cartCount').html(res.cartCount);
                    $('.cart-bar').html(res.cartBar);
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
        //Sr Checkout End

        $(".remove-item").on("click", function () {

            var id = $(this).data('id');
            if (id) {
                $.ajax({
                    url: "{{URL::to('/removeCart')}}/" + id,
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function () {
//                        $('.cart-bar').html('');
                    },
                    success: function (res) {
                        $('#cartCount').html(res.cartCount);
                        $('.cart-bar').html(res.cartBar);
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

        $(".qty-change").on("click", function () {

            var key = $(this).attr("data-key");
            var qty = $('#qty' + key).val();
            if ($(this).hasClass('btn-increase')) {
                qty++;
                $('#qty' + key).val(qty);
            } else if ($(this).hasClass('btn-reduce')) {
                qty--;
                $('#qty' + key).val(qty);
            }
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
                    $('#cartCount').html(res.cartCount);
                    $('.cart-bar').html(res.cartBar);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
        $("#clearCart").on("click", function () {

            $.ajax({
                url: "{{URL::to('/clearCart')}}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
//                    $('.cart-bar').html('');
                },
                success: function (res) {
                    $('#cartCount').html(res.cartCount);
                    $('.cart-bar').html(res.cartBar);
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

        $(".confirm-order").on("click", function (e) {
            e.preventDefault();

            // Serialize the form data
            var retailerId = $("#retailerId").val();
            if (retailerId == '0') {
                toastr.error('Please! Choose a retailer!', 'Validation Error', options);
                return false;
            }
            var formData = new FormData($('#setOrderForm')[0]);
            swal({
                title: 'Are you sure?',
                text: "You want to Confirm this Order?",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{URL::to('/placeOrder')}}",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $(".confirm-order").prop('disabled', true);
                            //App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            swal({
                                title: "Order Confirmation",
                                text: "Your Order is Received!",
                                type: "success",
                                timer: 4000,
                                showConfirmButton: false
                            }, function (isConfirm) {
                                if (isConfirm) {
                                    location.reload();
                                }

                            });
                            //toastr.success(res, '@lang("label.SET_DELIVERY_SUCCESSFULLY")', options);
                            //location.reload();
                            //setTimeout(window.location.replace('{{ route("inDepoProducts")}}'), 3000);
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            $(".confirm-order").prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }

            });
        });
    });
</script>