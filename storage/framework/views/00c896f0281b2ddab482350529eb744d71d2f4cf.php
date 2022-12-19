<?php $__env->startSection('content'); ?>

<div class="container">
    <?php echo $__env->make('frontend.layouts.default.carosel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('frontend.layouts.default.banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if(!$highlightedCategoryInfo->isEmpty()): ?>
    <div class="wrap-show-advance-info-box style-1">
        <h3 class="title-box"><?php echo app('translator')->get('label.PRODUCT_CATEGORIES'); ?></h3>

        <div class="wrap-products">
            <div class="wrap-product-tab tab-style-1">
                <div class="tab-contents">
                    <div class="tab-content-item active" id="digital_1a">
                        <div class="wrap-products slide-carousel owl-carousel style-nav-1 equal-container" data-items="5" data-loop="false" data-nav="true" data-dots="false" data-responsive='{"0":{"items":"1"},"480":{"items":"2"},"768":{"items":"3"},"992":{"items":"4"},"1200":{"items":"5"}}' >
                            <?php $__currentLoopData = $highlightedCategoryInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <div class="product product-style-2 equal-elem ">
                                <div class="product-thumnail width-inherit">

                                    <a href="<?php echo route('category.products.show', $category->id); ?>" class="width-inherit">
                                        <figure>
                                            <?php if(!empty($category->image) && file_exists('public/uploads/category/'.$category->image)): ?>
                                            <img  src="<?php echo e(URL::to('/')); ?>/public/uploads/category/<?php echo e($category->image); ?>" alt="<?php echo e($category->name); ?>"style="width:226px; height: 216px;">
                                            <?php else: ?>
                                            <img src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" alt="<?php echo e($category->name); ?>" style="width:226px;" >
                                            <?php endif; ?>
                                        </figure>
                                    </a>
                                </div>
                                <div class="product-info">
                                    <a href="<?php echo route('category.products.show', $category->id); ?>" class="product-name">
                                        <span class="category-text-center">
                                            <strong><?php echo e($category->name); ?></strong>
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!--On Sale-->
    <?php if(!$featuredProductInfo->isEmpty()): ?>
    <div class="wrap-show-advance-info-box style-1">
        <h3 class="title-box"><?php echo app('translator')->get('label.FEATURED_PRODUCTS'); ?></h3>

        <div class="wrap-products">
            <div class="wrap-product-tab tab-style-1">
                <div class="tab-contents">
                    <div class="tab-content-item active" id="digital_1a">
                        <div class="wrap-products slide-carousel owl-carousel style-nav-1 equal-container" data-items="5" data-loop="false" data-nav="true" data-dots="false" data-responsive='{"0":{"items":"1"},"480":{"items":"2"},"768":{"items":"3"},"992":{"items":"4"},"1200":{"items":"5"}}' >
                            <?php $__currentLoopData = $featuredProductInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <div class="product product-style-2 equal-elem ">
                                <div class="product-thumnail">

                                    <a href="<?php echo e(url('/productDetail/'.$product->productId.'/'.$product->sku)); ?>" title="<?php echo e($product->productName); ?> <?php echo e($product->productAttribute); ?>">
                                        <figure>
                                            <?php if(!empty($product->productImage[0]) && file_exists('public/uploads/product/smallImage/'.$product->productImage[0])): ?>
                                            <img src="<?php echo e(URL::to('/')); ?>/public/uploads/product/smallImage/<?php echo e($product->productImage[0]); ?>" alt="<?php echo e($product->productName); ?> <?php echo e($product->productAttribute); ?>">
                                            <?php else: ?>
                                            <img src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" alt="<?php echo e($product->productName); ?> <?php echo e($product->productAttribute); ?>">
                                            <?php endif; ?>
                                        </figure>
                                    </a>
                                    <div class="group-flash">
                                        <span class="flash-item sale-label"><?php echo app('translator')->get('label.FEATURED'); ?></span>
                                    </div>
                                    <div class="wrap-btn">
                                        <a href="#modalProductQuickView" data-toggle="modal" data-id="<?php echo $product->productId; ?>" sku-code="<?php echo e($product->sku); ?>" class="function-link product-quick-view"><?php echo app('translator')->get('label.QUICK_VIEW'); ?></a>
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

                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!--Latest Products-->
    <?php if(!$latestProductInfo->isEmpty()): ?>
    <div class="wrap-show-advance-info-box style-1">
        <h3 class="title-box"><?php echo app('translator')->get('label.LATEST_PRODUCTS'); ?></h3>
        <!--<div class="wrap-top-banner">
            <a href="#" class="link-banner banner-effect-2">
                <figure><img src="<?php echo e(asset('public/frontend/assets/images/digital-electronic-banner.jpg')); ?>" width="1170" height="240" alt=""></figure>
            </a>
        </div>!-->
        <div class="wrap-products">
            <div class="wrap-product-tab tab-style-1">
                <div class="tab-contents">
                    <div class="tab-content-item active" id="digital_1a">
                        <div class="wrap-products slide-carousel owl-carousel style-nav-1 equal-container" data-items="5" data-loop="false" data-nav="true" data-dots="false" data-responsive='{"0":{"items":"1"},"480":{"items":"2"},"768":{"items":"3"},"992":{"items":"4"},"1200":{"items":"5"}}' >
                            <?php $__currentLoopData = $latestProductInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="product product-style-2 equal-elem ">
                                <div class="product-thumnail">

                                    <a href="<?php echo e(url('/productDetail/'.$product->productId.'/'.$product->sku)); ?>" title="<?php echo e($product->productName); ?> <?php echo e($product->productAttribute); ?>">
                                        <figure>
                                            <?php if(!empty($product->productImage[0]) && file_exists('public/uploads/product/smallImage/'.$product->productImage[0])): ?>
                                            <img src="<?php echo e(URL::to('/')); ?>/public/uploads/product/smallImage/<?php echo e($product->productImage[0]); ?>" alt="<?php echo e($product->productName); ?> <?php echo e($product->productAttribute); ?>">
                                            <?php else: ?>
                                            <img src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" alt="<?php echo e($product->productName); ?> <?php echo e($product->productAttribute); ?>">
                                            <?php endif; ?>
                                        </figure>
                                    </a>
                                    <div class="group-flash">
                                        <span class="flash-item new-label"><?php echo app('translator')->get('label.NEW'); ?></span>
                                    </div>
                                    <div class="wrap-btn">
                                        <a href="#modalProductQuickView" data-toggle="modal" data-id="<?php echo $product->productId; ?>" sku-code="<?php echo e($product->sku); ?>" class="function-link product-quick-view"><?php echo app('translator')->get('label.QUICK_VIEW'); ?></a>
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
                                    <?php if(!in_array(Auth::user()->group_id,[18,19])): ?>
                                    <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.RETAILER_PRICE'); ?> : <?php echo e($product->price); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                    <div class="wrap-price"><span class="product-price"><?php echo app('translator')->get('label.DISTRIBUTOR_PRICE'); ?> : <?php echo e($product->distributor_price ?? '00'); ?> <?php echo app('translator')->get('label.TK'); ?></span></div>
                                    <?php endif; ?>
                                    <?php endif; ?>

                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Special Product!-->
    <!--Latest Products-->
    <?php if(!$specialProductInfo->isEmpty()): ?>
    <div class="wrap-show-advance-info-box style-1">
        <h3 class="title-box"><?php echo app('translator')->get('label.SPECIAL_PRODUCTS'); ?></h3>
        <div class="wrap-products">
            <div class="wrap-product-tab tab-style-1">
                <div class="tab-contents">
                    <div class="tab-content-item active" id="digital_1a">
                        <div class="wrap-products slide-carousel owl-carousel style-nav-1 equal-container" data-items="5" data-loop="false" data-nav="true" data-dots="false" data-responsive='{"0":{"items":"1"},"480":{"items":"2"},"768":{"items":"3"},"992":{"items":"4"},"1200":{"items":"5"}}' >
                            <?php $__currentLoopData = $specialProductInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="product product-style-2 equal-elem ">
                                <div class="product-thumnail">

                                    <a href="<?php echo e(url('/productDetail/'.$product->productId.'/'.$product->sku)); ?>" title="<?php echo e($product->productName); ?> <?php echo e($product->productAttribute); ?>">
                                        <figure>
                                            <?php if(!empty($product->productImage[0]) && file_exists('public/uploads/product/smallImage/'.$product->productImage[0])): ?>
                                            <img src="<?php echo e(URL::to('/')); ?>/public/uploads/product/smallImage/<?php echo e($product->productImage[0]); ?>" alt="<?php echo e($product->productName); ?> <?php echo e($product->productAttribute); ?>">
                                            <?php else: ?>
                                            <img src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" alt="<?php echo e($product->productName); ?> <?php echo e($product->productAttribute); ?>">
                                            <?php endif; ?>
                                        </figure>
                                    </a>
                                    <div class="group-flash">
                                        <span class="flash-item new-label"><?php echo app('translator')->get('label.SPECIAL'); ?></span>
                                    </div>
                                    <div class="wrap-btn">
                                        <a href="#modalProductQuickView" data-toggle="modal" data-id="<?php echo $product->productId; ?>" sku-code="<?php echo e($product->sku); ?>" class="function-link product-quick-view"><?php echo app('translator')->get('label.QUICK_VIEW'); ?></a>
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

                                    
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <h3 class="title-box news"><?php echo app('translator')->get('label.NEWS'); ?></h3>
    <div class="news-block">
        <div class="row">
            <?php if(!$newsAndEvents->isEmpty()): ?>
            <?php $__currentLoopData = $newsAndEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="item  col-xs-12 col-lg-4 grid-group-item">
                <div class="post-thumbnail image-cover">
                    <div class="featured-image">
                        <a  href="<?php echo e(URL::to('/news-and-events').'/'.$post->slug); ?>" class="post-featured-img image-cover">
                            <?php if(!empty($post->featured_image) && file_exists('public/uploads/NewsAndEvents/'.$post->featured_image)): ?>
                            <img class="group list-group-image " src="<?php echo e(asset('public/uploads/NewsAndEvents/'. $post->featured_image)); ?>" alt="featured Image" />
                            <?php else: ?>
                            <img class="group list-group-image" src="<?php echo e(asset('public/uploads/img/no-image.png')); ?>" alt="" />
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="post-caption news-title">
                        <a href="<?php echo e(URL::to('/').'/news-and-events/'.$post->slug); ?>" class="group inner list-group-item-heading"><?php echo $post->title ?? ''; ?></a>
                    </div>

                    <h3 class="post-date group inner">
                        <?php if(!empty($post->publish_date)): ?>
                        <i class="fa fa-calendar"></i>
                        <?php echo e(!empty($post->publish_date)? Helper::formatDateTimeForPost($post->publish_date):''); ?>

                        <?php endif; ?>
                        &nbsp;
                        <?php if(!empty($post->location)): ?>
                        <i class="fa fa-map-marker"></i>
                        <?php echo e(!empty($post->location) ? $post->location : ''); ?>

                        <?php endif; ?>
                    </h3>
                    <?php if(!empty($post->featured_image)): ?>
                    <div class="post-content group inner list-group-item-text text-justify">
                        <?php echo Helper::limitTextWords($post->content, 60, (URL::to('/').'/news-and-events/'.$post->slug)); ?>

                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
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


<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/frontend/index.blade.php ENDPATH**/ ?>