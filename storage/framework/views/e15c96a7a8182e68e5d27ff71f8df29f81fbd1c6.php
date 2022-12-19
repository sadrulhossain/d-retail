<?php if(!is_array($leftCategory)): ?>
<li class="category-item "  style="<?php echo e(($catId==$id)?'color:red;':''); ?>">
    <a href="<?php echo route('category.products.show', $catId); ?>" class="cate-link"><?php echo $categoryList[$catId]??''; ?></a>
</li>
<?php else: ?>
<li class="category-item has-child-cate <?php echo e((($catId==$id) || (!empty($parentIdArr[$catId])))?'open':''); ?>">
    <a href="<?php echo route('category.products.show', $catId); ?>" class="cate-link" style="<?php echo e(($catId==$id)?'color:red;':''); ?>"><?php echo $categoryList[$catId]??''; ?></a>
    <span class="toggle-control">+</span>
    <ul class="sub-cate">
        <?php $__currentLoopData = $leftCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cId=>$left): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('frontend.recursiveCat', [
        'catId' => $cId,
        'id' => $id,
        'leftCategory' => $left,
        'categoryList' => $categoryList,
       'parentIdArr' => $parentIdArr,
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</li>
<?php endif; ?><?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/frontend/recursiveCat.blade.php ENDPATH**/ ?>