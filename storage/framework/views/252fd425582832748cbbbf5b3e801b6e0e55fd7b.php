<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="<?php echo e(URL::to('/')); ?>">
                <img src="<?php echo e(URL::to('/')); ?>/public/img/logo.png" alt="logo" /> 
            </a>
            <div class="menu-toggler sidebar-toggler">
                <span></span>
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <!--<li class="show-hide-side-menu">
    <a title="" data-container="body" class="btn-show-hide-link">
        <i class="btn red-sunglo" >
            <span id="fullMenu" data-fullMenu="1"><?php echo __('label.FULL_SCREEN'); ?></span>
        </i>
    </a>
</li>-->

                <?php if(!empty($userAccessArr[103][1])): ?>
                <li class="dropdown dropdown-extended dropdown-notification show-tooltip" id="orderNotification" data-container="body"  data-original-title="<?php echo app('translator')->get('label.ORDER_NOTIFICATION'); ?>" data-toggle="tooltip" data-placement="bottom" title="" >
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" >
                        <i class="fa fa-cart-arrow-down"></i>
                        <span class="badge badge-purple"><?php echo $orderCount['total'] ?? 0; ?></span>

                    </a>
                    <ul class="dropdown-menu">
                        <li class="external">
                            <?php
                            $totalOrderCount = __('label.NO');
                            if (!empty($orderCount['total']) && $orderCount['total'] != 0) {
                                $totalOrderCount = '<span class="bold">' . $orderCount['total'] . '</span>';
                            }
                            $sOrderCount = $orderCount['total'] > 1 ? 's' : '';
                            ?>
                            <h3>
                                <?php echo app('translator')->get('label.YOU_HAVE_ORDER_NOTIFICATION', ['n' => $totalOrderCount, 's' => $sOrderCount]); ?>
                            </h3>
                        </li>
                        <?php if(!empty($userAccessArr[103][1])): ?>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a class="notification-padding pending-lc" href="<?php echo e(url('admin/processingOrder')); ?>" data-toggle="modal">
                                        <span class="details">
                                            <span class="badge badge-purple req-number"><?php echo $orderCount['pending'] ?? 0; ?></span>&nbsp;
                                            <?php echo app('translator')->get('label.PENDING_ORDER'); ?>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a class="notification-padding pending-lc" href="<?php echo e(url('admin/processingOrder')); ?>" data-toggle="modal">
                                        <span class="details">
                                            <span class="badge badge-purple req-number"><?php echo $orderCount['partially_delivered'] ?? 0; ?></span>&nbsp;
                                            <?php echo app('translator')->get('label.PARTIALLY_DELIVERED_ORDER'); ?>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
               
                <?php endif; ?>
                <?php if(!empty($userAccessArr[46][1])): ?>
                 <?php if(!empty($pendingTransferCount) && Auth::user()->group_id == 12): ?>
               <li class="dropdown dropdown-extended dropdown-notification show-tooltip" id="orderNotification" data-container="body"  data-original-title="<?php echo app('translator')->get('label.PRODUCT_TRANSFER_NOTIFICATION'); ?>" data-toggle="tooltip" data-placement="bottom" title="" >
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" >
                        <i class="fa fa-truck"></i>
                        <span class="badge badge-purple"><?php echo $pendingTransferCount ?? 0; ?></span>

                    </a>
                    <ul class="dropdown-menu">
                        <li class="external">
                            <h3>
                                <?php echo app('translator')->get('label.YOU_HAVE_PRODUCT_TRANSFER_NOTIFICATION', ['n' => $pendingTransferCount ?? 0]); ?>
                            </h3>
                        </li>
                        <?php if(!empty($userAccessArr[103][1])): ?>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a class="notification-padding pending-lc" href="<?php echo e(url('admin/stockTransferList')); ?>" data-toggle="modal">
                                        <span class="details">
                                            <span class="badge badge-purple req-number"><?php echo $pendingTransferCount ?? 0; ?></span>&nbsp;
                                            <?php echo app('translator')->get('label.STOCK'); ?>&nbsp;<?php echo app('translator')->get('label.TRANSFER'); ?>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>
                <?php endif; ?>

                <li class="dropdown dropdown-user">

                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <?php
                        $user = Auth::user(); //get current user all information
                        if (!empty($user->photo) && file_exists('public/uploads/user/' . $user->photo)) {
                            ?>
                            <img alt="<?php echo e($user['username']); ?>" class="img-circle" src="<?php echo e(URL::to('/')); ?>/public/uploads/user/<?php echo e($user->photo); ?>" />
                        <?php } else { ?>
                            <img alt="<?php echo e($user['username']); ?>" class="img-circle" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" />
                        <?php } ?>
                        <span class="username username-hide-on-mobile"><?php echo app('translator')->get('label.WELCOME'); ?> <?php echo e($user->username); ?> (<?php echo e($user->userGroup->name); ?>)</span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="<?php echo e(url('admin/myProfile')); ?>" class="tooltips" title="My Profile">
                                <i class="icon-user"></i><?php echo app('translator')->get('label.MY_PROFILE'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo e(url('admin/'.Auth::user()->id.'/changePassword')); ?>" class="tooltips" title="Change Password">
                                <i class="icon-key"></i><?php echo app('translator')->get('label.CHANGE_PASSWORD'); ?></a>
                        </li>

                        <li>
                            <a class="tooltips"  title="Logout" href="<?php echo e(route('logout')); ?>"
                               onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                <i class="icon-logout"></i> <?php echo app('translator')->get('label.LOGOUT'); ?>
                            </a>

                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li>
                    <a class="tooltips" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();" title="Logout">
                        <i class="icon-logout"></i>
                    </a>
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                    </form>
                </li>
                <!-- END QUICK SIDEBAR TOGGLER -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>

<!-- Modal start -->

<!--Pending for LC modal-->
<div class="modal fade" id="pendingLcModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="pendingLcViewModal">
        </div>
    </div>
</div>

<!--Pending for Shipment modal-->
<div class="modal fade" id="pendingShipmentModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="pendingShipmentViewModal">
        </div>
    </div>
</div>

<!--Partially Shipped modal-->
<div class="modal fade" id="partiallyShippedModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="partiallyShippedViewModal">
        </div>
    </div>
</div>

<!--Waiting for Tracking NO modal-->
<div class="modal fade" id="trackingNoModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="trackingNoViewModal">
        </div>
    </div>
</div>
<!--ETS ETA Info modal-->
<div class="modal fade" id="modalEtsEtaInfo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showEtsEtaInfo">
        </div>
    </div>
</div>
<!-- Modal end -->
<script type="text/javascript">
    $(document).ready(function () {
        
        $('.show-tooltip').tooltip();
        $('.tooltips').tooltip();
        
        //PENDING FOR LC  Details MODAL
        $(document).on("click", ".pending-lc", function (e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo e(URL::to('admin/dashboard/pendingForLc')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                },
                beforeSend: function () {
                    $("#pendingLcViewModal").html('');
                },
                success: function (res) {
                    $("#pendingLcViewModal").html(res.html);
                    $('.tooltips').tooltip();
                    //table header fix
                    $(".table-head-fixer-color").tableHeadFixer();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
//END of PENDING FOR LC DETAILS MODAL
        
//PENDING FOR Shipment Details MODAL
        $(document).on("click", ".pending-shipment", function (e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo e(URL::to('admin/dashboard/pendingForShipment')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                },
                beforeSend: function () {
                    $("#pendingShipmentViewModal").html('');
                },
                success: function (res) {
                    $("#pendingShipmentViewModal").html(res.html);
                    $('.tooltips').tooltip();
                    //table header fix
                    $(".table-head-fixer-color").tableHeadFixer();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
//END of PENDING FOR SHIPMENT DETAILS MODAL
        
//PARTIALLY SHIPPED Details MODAL
        $(document).on("click", ".partially-shipped", function (e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo e(URL::to('admin/dashboard/getPartiallyShipped')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                },
                beforeSend: function () {
                    $("#partiallyShippedViewModal").html('');
                },
                success: function (res) {
                    $("#partiallyShippedViewModal").html(res.html);
                    $('.tooltips').tooltip();
                    //table header fix
                    $(".table-head-fixer-color").tableHeadFixer();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
//END of PARTIALLY SHIPPED DETAILS MODAL
        
//Waiting For Tracking No Details MODAL
        $(document).on("click", ".waiting-for-tracking", function (e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo e(URL::to('admin/dashboard/waitingTrackingNo')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                },
                beforeSend: function () {
                    $("#trackingNoViewModal").html('');
                },
                success: function (res) {
                    $("#trackingNoViewModal").html(res.html);
                    $('.tooltips').tooltip();
                    //table header fix
                    $(".table-head-fixer-color").tableHeadFixer();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
        
        $('.ets-eta-info').on('click', function (e) {
            e.preventDefault();
            var formData = {
                ref: $(this).attr("data-ref"),
            };
            $.ajax({
                url: "<?php echo e(URL::to('/admin/dashboard/getEtsEtaInfo')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                beforeSend: function () {
                    $("#showEtsEtaInfo").html('');
                },
                success: function (res) {
                    $("#showEtsEtaInfo").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
    });
</script>
<?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/layouts/default/topNavbar.blade.php ENDPATH**/ ?>