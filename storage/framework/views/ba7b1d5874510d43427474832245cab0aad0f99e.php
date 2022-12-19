<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo app('translator')->get('label.SAFE_CARE'); ?></title>
        <link rel="shortcut icon" href="<?php echo e(URL::to('/')); ?>/public/img/favicon.ico" />
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,700,700italic,900,900italic&amp;subset=latin,latin-ext" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open%20Sans:300,400,400italic,600,600italic,700,700italic&amp;subset=latin,latin-ext" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/frontend/assets/css/animate.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/frontend/assets/css/font-awesome.min.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/frontend/assets/css/bootstrap.min.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/frontend/assets/css/flexslider.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/frontend/assets/css/owl.carousel.min.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/frontend/assets/css/chosen.min.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/frontend/assets/css/custom.css')); ?>">

        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/frontend/assets/css/color-01.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/frontend/assets/css/color-01.css')); ?>">

<!--        <script src="<?php echo e(asset('public/frontend/assets/js/sweetalert2.min.js')); ?>"></script>-->
        <link href="<?php echo e(asset('public/assets/global/plugins/sweetalert/lib/sweet-alert.css')); ?>" rel="stylesheet" type="text/css" />

        <!-- BEGIN DROPDOWN SELECT PLUGINS -->
        <link href="<?php echo e(asset('public/assets/global/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/select2/css/select2-bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
        <!-- END DROPDOWN SELECT PLUGINS -->


        <!-- Toaster STYLES -->

        <link href="<?php echo e(asset('public/assets/global/plugins/bootstrap-summernote/summernote.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/css/custom.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/select2/css/select2-bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />

        <!--<link href="<?php echo e(asset('public/assets/layouts/layout/css/custom.css')); ?>" rel="stylesheet" type="text/css" />-->

        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/frontend/assets/css/style.css')); ?>">

        <script src="<?php echo e(asset('public/frontend/assets/js/jquery-1.12.4.minb8ff.js?ver=1.12.4')); ?>" type="text/javascript"></script>
        <script src="<?php echo e(asset('public/frontend/assets/js/jquery-ui-1.12.4.minb8ff.js?ver=1.12.4')); ?>" type="text/javascript"></script>
        <script src="<?php echo e(asset('public/assets/global/plugins/bootstrap-toastr/toastr.min.js')); ?>" type="text/javascript"></script>
        <link href="<?php echo e(asset('public/assets/global/plugins/bootstrap-toastr/toastr.min.css')); ?>" rel="stylesheet" type="text/css" />
        
        <!-- Google Api Script -->
<!--        <script src="https://apis.google.com/js/client:platform.js?onload=start" async defer></script>
        <script>
function start() {
    gapi.load('auth2', function () {
        auth2 = gapi.auth2.init({
            client_id: '39319725791-tckvaaunbp0j62dtmpqbitfhptuhqhm9.apps.googleusercontent.com',
            // Scopes to request in addition to 'profile' and 'email'
            //scope: 'additional_scope'
        });
    });
}
        </script>-->
        <!-- End Google Api Script -->
    </head>
<?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/frontend/layouts/default/header.blade.php ENDPATH**/ ?>