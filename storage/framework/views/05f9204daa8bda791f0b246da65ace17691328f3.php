<!--MAIN SLIDE-->
<div class="wrap-main-slide">
    <div class="slide-carousel owl-carousel style-nav-1" data-items="1" data-loop="1" data-nav="true" data-dots="false">
        <?php if(!$bannerArr->isEmpty()): ?>
        <?php $__currentLoopData = $bannerArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="item-slide">
            <img src="<?php echo e(URL::to('/')); ?>/public/uploads/content/banner/<?php echo e($banner->img_d_x); ?>" alt="" class="img-slide">
            <div class="slide-info <?php echo $banner->position??''; ?>">
                <h2 class="f-title"><?php echo $banner->title??''; ?></h2>
                <span class="f-subtitle"><?php echo $banner->subtitle??''; ?></span>
                <p class="sale-info"><?php echo $banner->price_info??''; ?><b class="price"><?php echo $banner->price??''; ?></b></p>
                <a href="<?php echo $banner->url??''; ?>" class="btn-link">Shop Now</a>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

    </div>
</div><?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/frontend/layouts/default/carosel.blade.php ENDPATH**/ ?>