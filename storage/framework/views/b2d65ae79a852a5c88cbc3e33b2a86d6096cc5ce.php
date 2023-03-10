<!DOCTYPE html>
<html lang="en">
    <head>
        <!--        <meta charset="utf-8" />-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo app('translator')->get('label.SAFE_CARE'); ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="KTI" name="description" />
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <!--        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />-->
        <link href="<?php echo e(asset('public/fonts/css.css?family=Open Sans')); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo e(asset('public/assets/global/plugins/font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/simple-line-icons/simple-line-icons.min.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')); ?>" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?php echo e(asset('public/assets/global/plugins/morris/morris.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/fullcalendar/fullcalendar.min.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/jqvmap/jqvmap/jqvmap.css')); ?>" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->

        <!-- BEGIN DROPDOWN SELECT PLUGINS -->
        <link href="<?php echo e(asset('public/assets/global/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/select2/css/select2-bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
        <!-- END DROPDOWN SELECT PLUGINS -->

        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo e(asset('public/assets/global/css/components.min.css')); ?>" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/css/plugins.min.css')); ?>" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?php echo e(asset('public/assets/layouts/layout/css/layout.min.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/layouts/layout/css/themes/darkblue.min.css')); ?>" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?php echo e(asset('public/assets/layouts/layout/css/custom.css')); ?>" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <!-- SWEETALERT STYLES -->
        <link href="<?php echo e(asset('public/assets/global/plugins/sweetalert/lib/sweet-alert.css')); ?>" rel="stylesheet" type="text/css" />

        <!-- DATEPICKER STYLES -->
        <link href="<?php echo e(asset('public/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/clockface/css/clockface.css')); ?>" rel="stylesheet" type="text/css" />
        <!-- Toaster STYLES -->
        <link href="<?php echo e(asset('public/assets/global/plugins/bootstrap-toastr/toastr.min.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/bootstrap-summernote/summernote.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/css/custom.css')); ?>" rel="stylesheet" type="text/css" />

        <!-- DATA TABLE STYLES -->
        <link href="<?php echo e(asset('public/assets/global/plugins/datatables/datatables.min.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')); ?>" rel="stylesheet" type="text/css" />
        <!-- MultiSelect STYLES -->
        <link href="<?php echo e(asset('public/assets/global/plugins/bootstrap-multiselect/css/bootstrap-multiselect.css')); ?>" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="<?php echo e(URL::to('/')); ?>/public/img/favicon.ico" />
        <script src="<?php echo e(asset('public/assets/global/plugins/jquery.min.js')); ?>" type="text/javascript"></script>
        <script src="<?php echo e(asset('public/assets/global/plugins/jquery-ui/jquery-ui.min.js')); ?>" type="text/javascript"></script>
        <link href="<?php echo e(asset('public/assets/global/plugins/jquery-ui/jquery-ui.min.css')); ?>" rel="stylesheet" type="text/css"/>

        
        <!-- Start:: Cropper --->
        <link href="<?php echo e(asset('public/css/cropper.css')); ?>" rel="stylesheet" type="text/css" />
        <!-- End:: Cropper -->

        <!-- Start :: Treant JS -->
        <link href="<?php echo e(asset('public/js/treant-js-master/Treant.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/js/treant-js-master/vendor/perfect-scrollbar/perfect-scrollbar.css')); ?>" rel="stylesheet" type="text/css" />
        <!-- End :: Treant JS -->
    </head>
    <!-- END HEAD --><?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/layouts/default/header.blade.php ENDPATH**/ ?>