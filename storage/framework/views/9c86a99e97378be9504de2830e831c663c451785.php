<?php $__env->startSection('content'); ?>

<div class="container">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="wrap-breadcrumb">
        <ul>
            <li class="item-link"><a href="#" class="link">home</a></li>
            <li class="item-link"><span>detail</span></li>
        </ul>
    </div>
    <div class="row">

        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12 main-content-area">
            <div class="wrap-product-detail row">
                <div class="detail-media col-lg-5 col-md-7 col-xs-12">


                    <div class="exzoom" id="exzoom">
                        <div class="exzoom_img_box">
                            <ul class='exzoom_img_ul'>
                                <?php if(!empty($productImageArr)): ?>
                                <?php $__currentLoopData = $productImageArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <?php if(!empty($productName)): ?>                                    
                                    <img src="<?php echo e(URL::to('/')); ?>/public/uploads/product/thumbImage/<?php echo e($productName); ?>" alt="">
                                    <?php else: ?>
                                    <img src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" alt="">
                                    <?php endif; ?>

                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <img src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" alt="">

                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="exzoom_nav"></div>
                        <p class="exzoom_btn">
                            <a href="javascript:void(0);" class="exzoom_prev_btn"> < </a>
                            <a href="javascript:void(0);" class="exzoom_next_btn"> > </a>
                        </p>
                    </div>




                </div>
                <div class="detail-info col-lg-5 col-md-5 col-xs-12">
                    <h2 class="product-name">
                        <strong><?php echo e($target->productName); ?></strong> <?php echo e($target->productAttribute); ?>

                    </h2>
                    <?php if(auth()->guard()->check()): ?>
                    <?php if(Auth::user()->group_id == 19): ?>
                    <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.PRICE'); ?> : <?php echo e($target->price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                    <?php endif; ?>
                    <?php if(Auth::user()->group_id == 18): ?>
                    <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.PRICE'); ?> : <?php echo e($target->distributor_price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                    <?php endif; ?>
                    <?php if(Auth::user() && !in_array(Auth::user()->group_id,[18,19])): ?>
                    <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.RETAILER_PRICE'); ?> : <?php echo e($target->price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                    <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.DISTRIBUTOR_PRICE'); ?> : <?php echo e($target->distributor_price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                    <?php endif; ?>
                    <?php endif; ?>

                    <?php if(auth()->guard()->guest()): ?>
                    <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.RETAILER_PRICE'); ?> : <?php echo e($target->price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                    <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.DISTRIBUTOR_PRICE'); ?> : <?php echo e($target->distributor_price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                    <?php endif; ?>

                    <?php
                    $availability = !empty($target->available_quantity) && $target->available_quantity > 0 ? __('label.IN_STOCK') : __('label.OUT_OF_STOCK');
                    $availabilityColor = !empty($target->available_quantity) && $target->available_quantity > 0 ? 'green-sharp' : 'red-intense';
                    ?>
                    <div class="stock-info in-stock">
                        <p class="availability"><?php echo app('translator')->get('label.AVAILABILITY'); ?> : <span class="bold text-<?php echo e($availabilityColor); ?>"><?php echo e($availability); ?></span></p>
                    </div>
                    <?php
                    $inDepoProducts = Helper::getInDepoProduct($target->productId, $target->sku_id);
                    ?>

                    <?php if(!empty($inDepoProducts)): ?>
                    <?php if(Auth::check()): ?>
                    <?php if(in_array(Auth::user()->group_id, [14, 18, 19])): ?>
                    <div class="quantity">
                        <span><?php echo app('translator')->get('label.QUANTITY'); ?> :</span>
                        <div class="quantity-input">
                            <input type="text" name="product-quatity" value="1" data-max="120" pattern="[0-9]*" id="qty">

                            <a class="btn btn-reduce" href="#"></a>
                            <a class="btn btn-increase" href="#"></a>
                        </div>
                    </div>

                    <div class="wrap-butons">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <button class="btn add-to-cart" id="addToCart" sku-id="<?php echo e($target->sku_id); ?>" sku-code="<?php echo e($target->sku); ?>" data-id="<?php echo e($target->productId); ?>"><?php echo app('translator')->get('label.ADD_TO_CART'); ?></button>
                            </div>
                            <!--                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <a class="btn add-to-cart show-cart"  href="" type="button"><?php echo app('translator')->get('label.SHOW_CART'); ?></a>
                                                        </div>-->
                            <div class="col-md-6 col-sm-6 margin-top-10">

                                <div class="wrap-btn">
                                    <button class="btn btn-wishlist <?php echo e(!empty($check)? 'hilight':''); ?> add-wish-list"  sku-id="<?php echo e($target->sku_id); ?>" data-id="<?php echo e($target->productId); ?>">Add to Wishlist</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="advance-info col-lg-12">
                    <div class="tab-control normal">
                        <a href="#description" class="tab-control-item active">Description</a>
                        <!-- <a href="#add_infomation" class="tab-control-item">Addtional Infomation</a>
                                                <a href="#review" class="tab-control-item">Reviews</a>-->
                    </div>
                    <div class="tab-contents">
                        <div class="tab-content-item active" id="description">
                            <?php echo $target->productDescription; ?>

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
                                                <img alt="" src="<?php echo e(asset('public/frontend/assets/images/author-avata.jpg')); ?>" height="80" width="80">
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


            <div class="widget mercado-widget widget-product">
                <h2 class="widget-title"><?php echo app('translator')->get('label.LATEST_PRODUCT'); ?></h2>
                <div class="widget-content">
                    <ul class="products">
                        <?php if(!$latestProductInfo->isEmpty()): ?>
                        <?php $__currentLoopData = $latestProductInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="product-item">
                            <div class="product product-widget-style">
                                <div class="thumbnnail">

                                    <a href="<?php echo e(url('/productDetail/'.$product->productId.'/'.$product->sku)); ?>" title="<?php echo e($product->productName); ?> <?php echo e($product->productAttribute); ?>">
                                        <figure><img src="<?php echo e(URL::to('/')); ?>/public/uploads/product/thumbImage/<?php echo e($product->productImage[0] ?? ''); ?>" alt="<?php echo e($product->productName); ?> <?php echo e($product->productAttribute); ?>"></figure>
                                    </a>
                                </div>

                                <div class="product-info">
                                    <a href="<?php echo e(url('/productDetail/'.$product->productId.'/'.$product->sku)); ?>" class="product-name">
                                        <span>
                                            <strong><?php echo e($product->productName); ?></strong> <?php echo e($product->productAttribute); ?>

                                        </span>
                                    </a>
                                    <?php if(auth()->guard()->check()): ?>
                                    <?php if(Auth::user()->group_id == 19): ?>
                                    <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.PRICE'); ?> : <?php echo e($target->price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                    <?php endif; ?>
                                    <?php if(Auth::user()->group_id == 18): ?>
                                    <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.PRICE'); ?> : <?php echo e($target->distributor_price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                    <?php endif; ?>
                                    <?php if(Auth::user() && !in_array(Auth::user()->group_id,[18,19])): ?>
                                    <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.RETAILER_PRICE'); ?> : <?php echo e($target->price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                    <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.DISTRIBUTOR_PRICE'); ?> : <?php echo e($target->distributor_price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                    <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if(auth()->guard()->guest()): ?>
                                    <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.RETAILER_PRICE'); ?> : <?php echo e($target->price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                    <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.DISTRIBUTOR_PRICE'); ?> : <?php echo e($target->distributor_price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

        </div><!--end sitebar-->

        <!-- similar product area -->
        <?php if(!$similarProductInfo->isEmpty()): ?>
        <div class="single-advance-box col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="wrap-show-advance-info-box style-1 box-in-site">
                <h3 class="title-box"><?php echo app('translator')->get('label.SIMILAR_PRODUCTS'); ?></h3>
                <div class="wrap-products">
                    <div class="products slide-carousel owl-carousel style-nav-1 equal-container" data-items="5" data-loop="false" data-nav="true" data-dots="false" data-responsive='{"0":{"items":"1"},"480":{"items":"2"},"768":{"items":"3"},"992":{"items":"3"},"1200":{"items":"5"}}' >

                        <?php $__currentLoopData = $similarProductInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="product product-style-2 equal-elem ">
                            <div class="product-thumnail">

                                <a href="<?php echo e(url('/productDetail/'.$product->productId.'/'.$product->sku)); ?>" title="<?php echo e($product->productName); ?> <?php echo e($product->productAttribute); ?>">
                                    <figure><img src="<?php echo e(URL::to('/')); ?>/public/uploads/product/smallImage/<?php echo e($product->productImage[0] ?? ''); ?>" alt="<?php echo e($product->productName); ?> <?php echo e($product->productAttribute); ?>"></figure>
                                </a>
                                <div class="group-flash">
                                    <span class="flash-item new-label"><?php echo app('translator')->get('label.NEW'); ?></span>
                                </div>
                                <div class="wrap-btn">
                                    <a href="#modalProductQuickView" data-toggle="modal" data-id="<?php echo $product->productId; ?>" data-product-flag="1" sku-code="<?php echo e($product->sku); ?>" class="function-link product-quick-view"><?php echo app('translator')->get('label.QUICK_VIEW'); ?></a>
                                </div>
                            </div>
                            <div class="product-info">
                                <a href="<?php echo e(url('/productDetail/'.$product->productId.'/'.$product->sku)); ?>" class="product-name">
                                    <span>
                                        <strong><?php echo e($product->productName); ?></strong> <?php echo e($product->productAttribute); ?>

                                    </span>
                                </a>
                                <?php if(auth()->guard()->check()): ?>
                                <?php if(Auth::user()->group_id == 19): ?>
                                <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.PRICE'); ?> : <?php echo e($target->price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                <?php endif; ?>
                                <?php if(Auth::user()->group_id == 18): ?>
                                <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.PRICE'); ?> : <?php echo e($target->distributor_price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                <?php endif; ?>
                                <?php if(Auth::user() && !in_array(Auth::user()->group_id,[18,19])): ?>
                                <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.RETAILER_PRICE'); ?> : <?php echo e($target->price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.DISTRIBUTOR_PRICE'); ?> : <?php echo e($target->distributor_price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                <?php endif; ?>
                                <?php endif; ?>

                                <?php if(auth()->guard()->guest()): ?>
                                <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.RETAILER_PRICE'); ?> : <?php echo e($target->price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.DISTRIBUTOR_PRICE'); ?> : <?php echo e($target->distributor_price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>
                </div><!--End wrap-products-->
            </div>
        </div>

        <!-- end similar product area -->
        <?php else: ?>
        <?php if(!$productArr->isEmpty()): ?>
        <div class="single-advance-box col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="wrap-show-advance-info-box style-1 box-in-site">
                <h3 class="title-box"><?php echo app('translator')->get('label.RELATED_PRODUCTS'); ?></h3>
                <div class="wrap-products">
                    <div class="products slide-carousel owl-carousel style-nav-1 equal-container" data-items="5" data-loop="false" data-nav="true" data-dots="false" data-responsive='{"0":{"items":"1"},"480":{"items":"2"},"768":{"items":"3"},"992":{"items":"3"},"1200":{"items":"5"}}' >

                        <?php $__currentLoopData = $productArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="product product-style-2 equal-elem ">
                            <div class="product-thumnail">
                                <a href="<?php echo e(url('/productDetail/'.$product->productId.'/'.$product->sku)); ?>" title="<?php echo e($product->productName); ?> <?php echo e($product->productAttribute); ?>">
                                    <figure><img src="<?php echo e(URL::to('/')); ?>/public/uploads/product/smallImage/<?php echo e(!empty($product->productImage[0]) ? $product->productImage[0] : ''); ?>" alt="<?php echo e($product->productName); ?> <?php echo e($product->productAttribute); ?>"></figure>
                                </a>
                                <div class="group-flash">
                                    <span class="flash-item new-label"><?php echo app('translator')->get('label.NEW'); ?></span>
                                </div>
                                <div class="wrap-btn">
                                    <a href="#modalProductQuickView" data-toggle="modal" data-id="<?php echo $product->productId; ?>" data-product-flag="1" sku-code="<?php echo e($product->sku); ?>" class="function-link product-quick-view"><?php echo app('translator')->get('label.QUICK_VIEW'); ?></a>
                                </div>
                            </div>
                            <div class="product-info">
                                <a href="<?php echo e(url('/productDetail/'.$product->productId.'/'.$product->sku)); ?>" class="product-name">
                                    <span>
                                        <strong><?php echo e($product->productName); ?></strong> <?php echo e($product->productAttribute); ?>

                                    </span>
                                </a>
                                <?php if(auth()->guard()->check()): ?>
                                <?php if(Auth::user()->group_id == 19): ?>
                                <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.PRICE'); ?> : <?php echo e($product->price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                <?php endif; ?>
                                <?php if(Auth::user()->group_id == 18): ?>
                                <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.PRICE'); ?> : <?php echo e($product->distributor_price ?? '00'); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                <?php endif; ?>
                                <?php if(Auth::user() && !in_array(Auth::user()->group_id,[18,19])): ?>
                                <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.RETAILER_PRICE'); ?> : <?php echo e($product->price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.DISTRIBUTOR_PRICE'); ?> : <?php echo e($product->distributor_price ?? '00'); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                <?php endif; ?>
                                <?php endif; ?>

                                <?php if(auth()->guard()->guest()): ?>
                                <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.RETAILER_PRICE'); ?> : <?php echo e($product->price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.DISTRIBUTOR_PRICE'); ?> : <?php echo e($product->distributor_price ?? '00'); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>
                </div><!--End wrap-products-->
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div><!--end row-->

</div><!--end container-->

<!--set product quickview modal-->
<div class="modal fade" id="modalProductQuickView" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div id="showProductQuickView">

        </div>
    </div>
</div>

<script src="<?php echo e(asset('public/assets/global/plugins/jquery.min.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('public/exzoom/jquery.exzoom.js')); ?>"></script>
<link href="<?php echo e(asset('public/exzoom/jquery.exzoom.css')); ?>" rel="stylesheet" type="text/css"/>

<script type="text/javascript">
$("#exzoom").exzoom({
    "navWidth": 70,
    "navHeight": 70,
    "navItemNum": 5,
    "navItemMargin": 7,
    "navBorder": 1,
    "autoPlay": true,
    // autoplay interval in milliseconds
    "autoPlayTimeout": 5000
});

</script>
<script type="text/javascript">
    $(document).ready(function () {

        $(document).on("click", ".add-wish-list", function () {
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null
            };
            var id = $(this).attr('sku-id');
            if (id) {
                $.ajax({
                    url: "<?php echo e(URL::to('/addWishlist/')); ?>/" + id,
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
            var skuCode = $(this).attr('sku-code');
            if (id) {
                $.ajax({
                    url: "<?php echo e(URL::to('/addToCart/')); ?>/" + id,
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        qty: qty,
                        sku_code: skuCode
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

        //product quickview modal
        $(".product-quick-view").on("click", function (e) {
            e.preventDefault();
            var productId = $(this).attr("data-id");
            var skuCode = $(this).attr("sku-code");
            var productFlag = $(this).data("product-flag");
            $.ajax({
                url: "<?php echo e(URL::to('/productQuickView')); ?>",
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


<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/frontend/productDetail.blade.php ENDPATH**/ ?>