<!--BANNER-->
<div class="wrap-banner style-twin-default">
     <?php if(!$advertisementArr->isEmpty()): ?>
    <?php $__currentLoopData = $advertisementArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $advertisement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="banner-item">
        <a href="<?php echo $advertisement->url; ?>" class="link-banner banner-effect-1">
            <figure><img src="<?php echo e(URL::to('/')); ?>/public/uploads/content/advertisement/<?php echo e($advertisement->img_d_x); ?>" alt="" width="580" height="190"></figure>
        </a>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
   
</div><?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/frontend/layouts/default/banner.blade.php ENDPATH**/ ?>