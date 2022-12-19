<?php $__env->startSection('content'); ?>

<div class="container">
    <div class="wrap-breadcrumb">
        <ul>
            <li class="item-link"><a href="<?php echo e(url('/')); ?>" class="link">home</a></li>
            <li class="item-link"><span><?php echo $productCategoryArr[$id]??__('label.ALL_CATEGORIES'); ?></span></li>
        </ul>
    </div>
    <div class="row">

        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 sitebar">
            <div class="widget mercado-widget categories-widget">
                <h2 class="widget-title"><?php echo e(__('label.ALL_CATEGORIES')); ?></h2>
                <div class="widget-content">
                    <ul class="list-category">
                        <?php if(!empty($leftCategoryArr)): ?>
                        <?php $__currentLoopData = $leftCategoryArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $catId=>$leftCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('frontend.recursiveCat', [
                            'catId' => $catId,
                            'id' => $id,
                            'leftCategory' => $leftCategory,
                            'categoryList' => $categoryList,
                            'parentIdArr' => $parentIdArr
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div><!-- Categories widget-->

            <div class="widget mercado-widget widget-product">
                <h2 class="widget-title"><?php echo app('translator')->get('label.SPECIAL_PRODUCT'); ?></h2>
                <div class="widget-content">
                    <ul class="products">
                        <?php $__currentLoopData = $specialProductInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                    <div class="wrap-price"><span class="product-price"><?php echo e($product->price); ?></span></div>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div><!-- brand widget-->

        </div><!--end sitebar-->

        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12 main-content-area">
            <?php if(!empty($advertisementInfo)): ?>
            <div class="banner-shop">
                <a href="<?php echo $advertisementInfo->url; ?>" class="banner-link">
                    <figure><img src="<?php echo e(URL::to('/')); ?>/public/uploads/content/advertisement/<?php echo e($advertisementInfo->img_d_x); ?>" alt=""></figure>
                </a>
            </div>
            <?php endif; ?>

            <div class="wrap-shop-control">

                <h1 class="shop-title"><?php echo $productCategoryArr[$id]??__('label.ALL_CATEGORIES'); ?></h1>

                <div class="wrap-right">

                    <!--<div class="sort-item orderby ">
                        <select name="orderby" class="use-chosen" >
                            <option value="menu_order" selected="selected">Default sorting</option>
                            <option value="popularity">Sort by popularity</option>
                            <option value="rating">Sort by average rating</option>
                            <option value="date">Sort by newness</option>
                            <option value="price">Sort by price: low to high</option>
                            <option value="price-desc">Sort by price: high to low</option>
                        </select>
                    </div>

                    <div class="sort-item product-per-page">
                        <select name="post-per-page" class="use-chosen" >
                            <option value="12" selected="selected">12 per page</option>
                            <option value="16">16 per page</option>
                            <option value="18">18 per page</option>
                            <option value="21">21 per page</option>
                            <option value="24">24 per page</option>
                            <option value="30">30 per page</option>
                            <option value="32">32 per page</option>
                        </select>
                    </div>-->

                    <!--<div class="change-display-mode">
                        <a href="#" class="grid-mode display-mode active"><i class="fa fa-th"></i>Grid</a>
                        <a href="list.html" class="list-mode display-mode"><i class="fa fa-th-list"></i>List</a>
                    </div>-->

                </div>

            </div><!--end wrap shop control-->

            <div class="row">
                <?php if(!$target->isEmpty()): ?>
                <ul class="product-list grid-products equal-container">

                    <?php $__currentLoopData = $target; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="col-lg-4 col-md-6 col-sm-6 col-xs-6 ">
                        <div class="product product-style-3 equal-elem ">
                            <div class="product-thumnail">

                                <a href="<?php echo e(url('/productDetail/'.$product->productId.'/'.$product->sku)); ?>" title="<?php echo e($product->productName); ?> <?php echo e($product->productAttribute); ?>">
                                    <figure>
                                        <?php if(!empty($product->productImage[0])): ?>
                                        <img src="<?php echo e(URL::to('/')); ?>/public/uploads/product/smallImage/<?php echo e($product->productImage[0] ?? ''); ?>" alt="<?php echo e($product->productName); ?> <?php echo e($product->productAttribute); ?>">
                                        <?php else: ?>
                                        <img src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" alt="" height="250px" width="250px">
                                        <?php endif; ?>
                                    </figure>
                                </a>
                            </div>
                            <div class="product-info">
                                <a href="<?php echo e(url('/productDetail/'.$product->productId.'/'.$product->sku)); ?>" class="product-name">
                                    <span>
                                        <strong><?php echo e($product->productName); ?></strong> <?php echo e($product->productAttribute); ?>

                                    </span>
                                </a>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="wrap-price">
                                            <span class="product-price"><?php echo e($product->price); ?> <?php echo app('translator')->get('label.TK'); ?></span>
                                        </div>

                                    </div>
                                    <div class="col-md-7">
                                        <input type="hidden" name="product_id" id="product_id">
                                        <div class="cus-quantity depo-product">
                                            <div class="quantity-input">
                                                <input type="text" name="product-quatity" value="1" data-max="120" pattern="[0-9]*" id="depoQty_<?php echo e($product->productId); ?>">
                                                <button class="btn btn-xs btn-reduce minus"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                                <button class="btn btn-xs btn-increase plus"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="#modalProductQuickView" data-toggle="modal" data-id="<?php echo $product->productId; ?>" data-product-flag="2" sku-code="<?php echo e($product->sku); ?>" class="btn add-to-cart product-quick-view"><?php echo app('translator')->get('label.QUICK_VIEW'); ?></a>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn depo-btn btn-default margin-top-14" id="addToCart" sku-code="<?php echo e($product->sku); ?>" data-id="<?php echo e($product->productId); ?>"><?php echo app('translator')->get('label.ADD_TO_CART'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </ul>
                <?php else: ?>
                <div class="aboutus-info style-center">
                    <b class="box-title"><?php echo app('translator')->get('label.PRODUCTS_NOT_AVAILABLE'); ?></b>
                </div>
                <?php endif; ?>
            </div>

            <div class="wrap-pagination-info">
                <div class="col-md-8">
                    <?php echo e($target->appends(Request::all())->links()); ?>

                    <?php
                    $start = empty($target->total()) ? 0 : (($target->currentPage() - 1) * $target->perPage() + 1);
                    $end = ($target->currentPage() * $target->perPage() > $target->total()) ? $target->total() : ($target->currentPage() * $target->perPage());
                    ?> 
                </div>
                <div class="col-md-4">
                    <p class="result-count">
                        <?php echo app('translator')->get('label.SHOWING'); ?> <?php echo e($start); ?> <?php echo app('translator')->get('label.TO'); ?> <?php echo e($end); ?> <?php echo app('translator')->get('label.OF'); ?>  <?php echo e($target->total()); ?> <?php echo app('translator')->get('label.RECORDS'); ?>
                    </p>

                </div>

            </div>
        </div><!--end main products area-->



    </div><!--end row-->

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
	
	
	$(".toggle-control").on('click', function(){
		//if($(this).parent().hasClass('open')){
			$(this).parent().parent().parent().addClass('open');
		//}
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

    $(document).on("click", "#addToCart", function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null
        };
        var id = $(this).data('id');
        var qty = $('#depoQty_' + id).val();
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


    var plus = 0;
    var qty = $('#depoQty').val();
    $(document).on("click", ".plus", function () {
        plus += 1;
        if (plus > 1) {
            $('#depoQty').val(plus);
        } else {
            $('#depoQty').val(1);
        }
    });

    plus = 1;
    $(document).on("click", ".minus", function () {
        var qty = $('#depoQty').val();
        if (qty > 1) {
            plus -= 1;
            if (plus > 1) {
                $('#depoQty').val(plus);
            } else {
                $('#depoQty').val(1);
            }
        }
    });

});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/frontend/inDepoProducts.blade.php ENDPATH**/ ?>