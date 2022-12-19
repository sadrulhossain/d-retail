<?php
$currentControllerName = Request::segment(1);
$user = Auth::user();
//echo $currentFullRouteName;
?>
<!-- mobile menu -->
@if (!empty($user))
@if(in_array($user->group_id,[14,18,19]))
<div class="mercado-clone-wrap">
    <div class="mercado-panels-actions-wrap">
        <a class="mercado-close-btn mercado-close-panels" href="#">x</a>
    </div>
    <div class="mercado-panels"></div>
</div>
<div class="row cart-bar" id="cartBar">

</div>
<button type="button" class="link-direction btn-show-cart show-cart" id="cartCount">
    <i class="fa fa-shopping-basket custom-i" aria-hidden="true"></i>
    <div class="left-info">
        <span class="index">{{ Cart::count() }}</span>
        <span class="title">@lang('label.CART')</span>
    </div>
</button>
@endif
@endif
<!--header-->
<header id="header" class="header header-style-1">
    <div class="container-fluid">
        <div class="row">
            <div class="topbar-menu-area">
                <div class="container">
                    <div class="topbar-menu left-menu">
                        <ul>
                            <li class="menu-item" >
                                <a title="Hotline: {!!$konitaInfo->hotline!!}" href="#" ><span class="icon label-before fa fa-mobile"></span>Hotline: {!!$konitaInfo->hotline!!}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="topbar-menu right-menu">
                        <ul>
                            <?php
                            if (!empty($user)) {
                                ?>
                                <li class="dropdown dropdown-user">
                                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                        <span class="username username-hide-on-mobile">@lang('label.WELCOME') {{$user->username}}
                                        </span>
                                        @if($user->checkin_source == 1 && !empty($user->photo) && file_exists('public/frontend/assets/images/userImg/'.$user->photo))
                                        &nbsp;<img class="profile-photo" src="{{ asset('public/frontend/assets/images/userImg/'.$user->photo) }}">
                                        @elseif(($user->checkin_source == 2 || $user->checkin_source == 3) && !empty($user->photo))
                                        &nbsp;<img class="profile-photo" src="{!! $user->photo !!}">
                                        @else
                                        &nbsp;<img class="profile-photo" src="{{ asset('public/frontend/assets/images/avatar/avatar.png') }}">
                                        @endif
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-default">
										<li>
                                            <a href="{{url('/dashboard')}}">
                                                <i class="icon-key"></i>@lang('label.MY_DASHBOARD')</a>
                                        </li>
										<li>
                                            <a href="{{in_array($user->group_id,[14,18,19]) ? url('myProfile') : url('admin/myProfile') }}">
                                                <i class="icon-key"></i>@lang('label.MY_PROFILE')</a>
                                        </li>
                                        <li class="divider"> </li>
                                        <li>
                                            <a class="tooltips" href="{{url('/logoutCustomer')}}" title="Logout">@lang('label.LOGOUT')</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="menu-item" ><a class="tooltips" href="{{url('/logoutCustomer')}}" title="Logout">@lang('label.LOGOUT')</a></li>
                                <?php
                            } else {
                                ?>
                                <li class="menu-item" ><a title="@lang('label.LOGIN')" href="{{url('/login')}}">@lang('label.LOGIN')</a></li>
                                <li class="menu-item" ><a title="@lang('label.REGISTER')" href="{{url('/register')}}">@lang('label.REGISTER')</a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>

                </div>
            </div>


            <div class="container">
                <div class="mid-section main-info-area">

                    <div class="wrap-logo-top left-section">
                        <a href="{{url('/')}}" class="link-to-home"><img src="{{asset('public/frontend/assets/images/'.$konitaInfo->company_logo)}}" alt="mercado"></a>
                    </div>

                    <div class="wrap-search center-section">
                        <div class="wrap-search-form">
                            <form action="{{ url('/shop') }}" id="form-search-top" name="form-search-top">
                                <input type="text" name="search" value="{{Request::get('search')}}" placeholder="Search here...">
                                <button form="form-search-top" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                <div class="wrap-list-cate">
                                    <input type="hidden" name="product-cate" value="0" id="product-cate">
                                    <?php
                                    $productCategoryArr = Common::getAllProductCategory();
                                    ?>
                                    <a href="#" class="link-control">All Category</a>
                                    <ul class="list-cate">
                                        <li class="level-0">All Category</li>
                                        @foreach($productCategoryArr as $key => $category)
                                        <a href="{!! route('category.products.show', $key) !!}">
                                            <li class="level-1">{!!$category!!}</li>
                                        </a>
                                        @endforeach
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="wrap-icon right-section">
                        @guest
                        @else
                        @php

                        $wishItemCount = $myOrderCount = 0;
                        $customerId = DB::table('customer')->select('id')->where('user_id', $userId = Auth::user()->id)->first();
                        if(!empty($customerId->id)){
                        $wishItemCount = DB::table('wishlist')->where('customer_id',$customerId->id)->count();
                        $myOrderCount = DB::table('order')->whereNotIn('status', ['4', '5', '8'])->where('customer_id',$customerId->id)->count();
                        }
                        @endphp
                        @if(Auth::user()->group_id == 9)
                        <div class="wrap-icon-section wishlist" id="wishlistCount">
                            <a href="{{ url('/wishlist') }}" class="link-direction">
                                <i class="fa fa-heart" aria-hidden="true"></i>
                                <div class="left-info">
                                    <span class="index">{{ $wishItemCount }} Items</span>
                                    <span class="title">@lang('label.WISHLIST')</span>
                                </div>
                            </a>
                        </div>
                        <div class="wrap-icon-section minicart">
                            <a href="{{ url('/myOrder') }}" class="link-direction">
                                <i class="fa fa-cart-plus" aria-hidden="true"></i>
                                <div class="left-info">
                                    <span class="index">{{ $myOrderCount }} Orders</span>
                                    <span class="title">@lang('label.MY_ORDER')</span>
                                </div>
                            </a>
                        </div>
                        @endif
                        @endguest
                        <div class="wrap-icon-section minicart" data-spy="affix" data-offset-top="125">

                        </div>
                        <div class="wrap-icon-section show-up-after-1024">
                            <a href="#" class="mobile-navigation">
                                <span></span>
                                <span></span>
                                <span></span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="nav-section header-sticky">
                <div class="primary-nav-section">
                    <div class="container">
                        <ul class="nav primary clone-main-menu" id="mercado_main" data-menuname="Main menu" >
                            <li class="menu-item home-icon">
                                <a href="{{url('/')}}" class="link-term mercado-item-title"><i class="fa fa-home" aria-hidden="true"></i></a>
                            </li>
                            <!--<li class="menu-item">
                                <a href="{{url('/aboutUs')}}" class="link-term mercado-item-title">@lang('label.ABOUT_US')</a>
                            </li>
                            <li class="menu-item">
                                <a href="{{url('/shop')}}" class="link-term mercado-item-title">@lang('label.SHOP')</a>
                            </li>
                            <li class="menu-item">
                                <a href="{{url('/contactUs')}}" class="link-term mercado-item-title">@lang('label.CONTACT_US')</a>
                            </li>-->
                            @if(!$menuArr->isEmpty())
                            @foreach($menuArr as $menu)
                            <li class="menu-item">
                                <a href="{!!url($menu->url)!!}" class="link-term mercado-item-title">{{$menu->title}}</a>
                            </li>
                            @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script type="text/javascript">

    $(document).ready(function () {
        $(".cart-bar").hide();
        
        $(document).on('click', '.show-cart', function () {
            $.ajax({
                url: "{{ URL::to('/cart')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
//                    $(".cart-bar").html('');
                },
                success: function (res) {
                    $('.js-source-states').select2();
                    $(".cart-bar").slideDown();
                    $(".cart-bar").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

    });
</script>