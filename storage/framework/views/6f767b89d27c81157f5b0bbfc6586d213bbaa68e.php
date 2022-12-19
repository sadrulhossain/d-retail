<?php echo $__env->make('frontend.layouts.default.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<body class="home-page home-01 ">
    <?php echo $__env->make('frontend.layouts.default.topNavbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <main id="main" class="main-site">
        <div>
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>
    <?php echo $__env->make('frontend.layouts.default.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('frontend.layouts.default.footerScript', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html><?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/frontend/layouts/default/master.blade.php ENDPATH**/ ?>