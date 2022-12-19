<?php
$currentControllerName = Request::segment(1);
$user = Auth::user();
//echo $currentFullRouteName;
?>
<!-- mobile menu -->
<?php if(!empty($user)): ?>
<?php if(in_array($user->group_id,[14,18,19])): ?>
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
        <span class="index"><?php echo e(Cart::count()); ?></span>
        <span class="title"><?php echo app('translator')->get('label.CART'); ?></span>
    </div>
</button>
<?php endif; ?>
<?php endif; ?>
<!--header-->
<header id="header" class="header header-style-1">
    <div class="container-fluid">
        <div class="row">
            <div class="topbar-menu-area">
                <div class="container">
                    <div class="topbar-menu left-menu">
                        <ul>
                            <li class="menu-item" >
                                <a title="Hotline: <?php echo $konitaInfo->hotline; ?>" href="#" ><span class="icon label-before fa fa-mobile"></span>Hotline: <?php echo $konitaInfo->hotline; ?></a>
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
                                        <span class="username username-hide-on-mobile"><?php echo app('translator')->get('label.WELCOME'); ?> <?php echo e($user->username); ?>

                                        </span>
                                        <?php if($user->checkin_source == 1 && !empty($user->photo) && file_exists('public/frontend/assets/images/userImg/'.$user->photo)): ?>
                                        &nbsp;<img class="profile-photo" src="<?php echo e(asset('public/frontend/assets/images/userImg/'.$user->photo)); ?>">
                                        <?php elseif(($user->checkin_source == 2 || $user->checkin_source == 3) && !empty($user->photo)): ?>
                                        &nbsp;<img class="profile-photo" src="<?php echo $user->photo; ?>">
                                        <?php else: ?>
                                        &nbsp;<img class="profile-photo" src="<?php echo e(asset('public/frontend/assets/images/avatar/avatar.png')); ?>">
                                        <?php endif; ?>
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-default frontend-dropdown">
                                        <li>
                                            <a href="<?php echo e(url('/dashboard')); ?>">
                                                <i class="icon-key"></i><?php echo app('translator')->get('label.MY_DASHBOARD'); ?></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo e(in_array($user->group_id,[14,18,19]) ? url('admin/myProfile') : url('admin/myProfile')); ?>">
                                                <i class="icon-key"></i><?php echo app('translator')->get('label.MY_PROFILE'); ?></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo e(in_array($user->group_id,[18,19]) ? url('admin/retailerDistributorOrder') : url('admin/retailerDistributorOrder')); ?>">
                                                <i class="icon-key"></i><?php echo app('translator')->get('label.MY_ORDER'); ?></a>
                                        </li>
                                        <li class="divider"> </li>
                                        <li>
                                            <a class="tooltips" href="<?php echo e(url('/logoutCustomer')); ?>" title="Logout"><?php echo app('translator')->get('label.LOGOUT'); ?></a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="menu-item" ><a class="tooltips" href="<?php echo e(url('/logoutCustomer')); ?>" title="Logout"><?php echo app('translator')->get('label.LOGOUT'); ?></a></li>
                                <?php
                            } else {
                                ?>
                                <li class="menu-item" ><a title="<?php echo app('translator')->get('label.LOGIN'); ?>" href="<?php echo e(url('/login')); ?>"><?php echo app('translator')->get('label.LOGIN'); ?></a></li>
                                <li class="menu-item" ><a class="text-transform-none" title="<?php echo app('translator')->get('label.REGISTER'); ?>" href="<?php echo e(url('/register')); ?>"><?php echo app('translator')->get('label.REGISTER'); ?></a></li>
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
                        <a href="<?php echo e(url('/')); ?>" class="link-to-home"><img src="<?php echo e(asset('public/frontend/assets/images/'.$konitaInfo->company_logo)); ?>" alt="mercado"></a>
                    </div>

                    <div class="wrap-search center-section">
                        <div class="wrap-search-form">
                            <form action="<?php echo e(url('/search/product')); ?>" id="form-search-top" name="form-search-top">
                                <input type="text" name="search" value="<?php echo e(Request::get('search')); ?>" placeholder="Search here..." list="productSearch" autocomplete="false">
                                <datalist id="productSearch" class="proSearch">
                                    <?php if(!empty($frontProductSearchList)): ?>
                                    <?php $__currentLoopData = $frontProductSearchList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name=>$name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($name); ?>"></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </datalist>
                                <button form="form-search-top" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </form>
                        </div>
                    </div>

                    <div class="wrap-icon right-section">
                        <?php if(auth()->guard()->guest()): ?>
                        <?php else: ?>
                        <?php

                        $wishItemCount = $myOrderCount = 0;
                        $customerId = DB::table('customer')->select('id')->where('user_id', $userId = Auth::user()->id)->first();
                        if(!empty($customerId->id)){
                        $wishItemCount = DB::table('wishlist')->where('customer_id',$customerId->id)->count();
                        $myOrderCount = DB::table('order')->whereNotIn('status', ['4', '5', '8'])->where('customer_id',$customerId->id)->count();
                        }
                        ?>
                        <?php if(Auth::user()->group_id == 9): ?>
                        <div class="wrap-icon-section wishlist" id="wishlistCount">
                            <a href="<?php echo e(url('/wishlist')); ?>" class="link-direction">
                                <i class="fa fa-heart" aria-hidden="true"></i>
                                <div class="left-info">
                                    <span class="index"><?php echo e($wishItemCount); ?> Items</span>
                                    <span class="title"><?php echo app('translator')->get('label.WISHLIST'); ?></span>
                                </div>
                            </a>
                        </div>
                        <div class="wrap-icon-section minicart">
                            <a href="<?php echo e(url('/myOrder')); ?>" class="link-direction">
                                <i class="fa fa-cart-plus" aria-hidden="true"></i>
                                <div class="left-info">
                                    <span class="index"><?php echo e($myOrderCount); ?> Orders</span>
                                    <span class="title"><?php echo app('translator')->get('label.MY_ORDER'); ?></span>
                                </div>
                            </a>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
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
                                <a href="<?php echo e(url('/')); ?>" class="link-term mercado-item-title"><i class="fa fa-home" aria-hidden="true"></i></a>
                            </li>
                            <!--<li class="menu-item">
                                <a href="<?php echo e(url('/aboutUs')); ?>" class="link-term mercado-item-title"><?php echo app('translator')->get('label.ABOUT_US'); ?></a>
                            </li>
                            <li class="menu-item">
                                <a href="<?php echo e(url('/shop')); ?>" class="link-term mercado-item-title"><?php echo app('translator')->get('label.SHOP'); ?></a>
                            </li>
                            <li class="menu-item">
                                <a href="<?php echo e(url('/contactUs')); ?>" class="link-term mercado-item-title"><?php echo app('translator')->get('label.CONTACT_US'); ?></a>
                            </li>-->
                            <?php if(!$menuArr->isEmpty()): ?>
                            <?php $__currentLoopData = $menuArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="menu-item">
                                <a href="<?php echo url($menu->url); ?>" class="link-term mercado-item-title"><?php echo e($menu->title); ?></a>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
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
                url: "<?php echo e(URL::to('/cart')); ?>",
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
</script><?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/frontend/layouts/default/topNavbar.blade.php ENDPATH**/ ?>