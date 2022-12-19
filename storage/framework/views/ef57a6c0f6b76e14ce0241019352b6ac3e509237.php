

<?php $__env->startSection('data_count'); ?>
<?php if(session('status')): ?>
<div class="alert alert-success">
    <?php echo e(session('status')); ?>


</div>
<?php endif; ?>


<div class="portlet-body">
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="<?php echo e(url('dashboard')); ?>">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Dashboard</span>
            </li>
        </ul>
        <div class="page-toolbar">
            <h5 class="dashboard-date font-blue-madison"><span class="icon-calendar"></span> Today is <span class="font-blue-madison"><?php echo date('l, d F Y'); ?></span> </h5>   
        </div>
    </div>


    <div class="row margin-top-20">
        <?php if(in_array(Auth::user()->group_id, [18,19])): ?>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat-v2  dashboard-stat yellow-casablanca tooltips" href="<?php echo e(url('/admin/myProfile')); ?>" title="<?php echo app('translator')->get('label.MY_PROFILE'); ?>">
                <div class="visual">
                    <i class="icon-user"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <i class="icon-user"></i>
                    </div>
                    <div class="desc"><?php echo app('translator')->get('label.MY_PROFILE'); ?></div>
                </div>
            </a>
        </div> 

        <?php endif; ?>
        <?php if(!in_array(Auth::user()->group_id, [18,19])): ?>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a id="productPricing" data-target="#productPricingModal" data-toggle="modal" class="dashboard-stat-v2  dashboard-stat yellow-casablanca tooltips" href="#productPricingModal" title="<?php echo app('translator')->get('label.PRODUCT_PRICING'); ?>">
                <div class="visual">
                    <i class="fa fa-sliders"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <i class="fa fa-sliders"></i>
                    </div>
                    <div class="desc"><?php echo app('translator')->get('label.PRODUCT_PRICING'); ?></div>
                </div>
            </a>
        </div> 
        <?php endif; ?>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a id="pendingOrder" data-target="#pendingOrderModal" data-toggle="modal" class="dashboard-stat-v2  dashboard-stat blue tooltips" href="#pendingOrderModal" title="<?php echo app('translator')->get('label.PENDING_ORDER'); ?>">
                <div class="visual">
                    <i class="fa fa-sliders"></i>
                </div>
                <div class="details">
                    <div class="number" data-counter="counterup" data-value="<?php echo e(!empty($pendingOrder) ? $pendingOrder : 0); ?>">
                        <?php echo !empty($pendingOrder) ? $pendingOrder : 0; ?>

                    </div>
                    <div class="desc"><?php echo app('translator')->get('label.PENDING_ORDER'); ?></div>
                </div>
            </a>
        </div> 

        <?php if(in_array(Auth::user()->group_id, [1, 11, 12])): ?>
        <!--        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a id="waitingProcessing" data-target="#waitingProcessingingModal" data-toggle="modal" href="#waitingProcessingingModal" class="dashboard-stat-v2  dashboard-stat purple-soft tooltips" title="<?php echo app('translator')->get('label.WAITING_FOR_PROCESSING'); ?>">
                        <div class="visual">
                            <i class="fa fa-cubes"></i>
                        </div>
                        <div class="details">
                            <div class="number" data-counter="counterup" data-value="<?php echo e(!empty($waitingForProcessing)?$waitingForProcessing : 0); ?>">
                                <?php echo !empty($waitingForProcessing) ? $waitingForProcessing : 0; ?>

                            </div>
                            <div class="desc"><?php echo app('translator')->get('label.WAITING_FOR_PROCESSING'); ?></div>
                        </div>
                    </a>
                </div> 
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a id="placedInDelivery" data-target="#placedInDeliveryModal" data-toggle="modal" href="#placedInDeliveryModal" class="dashboard-stat-v2  dashboard-stat green-sharp tooltips" title="<?php echo app('translator')->get('label.TO_BE_PLACED_IN_DELIVERY'); ?>">
                        <div class="visual">
                            <i class="fa fa-puzzle-piece"></i>
                        </div>
                        <div class="details">
                            <div class="number"  data-counter="counterup" data-value="<?php echo e(!empty($tobePalcedInDelivery)?$tobePalcedInDelivery : 0); ?>">
                                <?php echo !empty($tobePalcedInDelivery) ? $tobePalcedInDelivery : 0; ?>

                            </div>
                            <div class="desc"><?php echo app('translator')->get('label.TO_BE_PLACED_IN_DELIVERY'); ?></div>
                        </div>
                    </a>
                </div> -->
        <?php elseif(in_array(Auth::user()->group_id, [14])): ?>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a id="todaysOrder" data-target="#todaysOrderModal" data-toggle="modal" href="#todaysOrderModal" class="dashboard-stat-v2  dashboard-stat purple-soft tooltips" title="<?php echo app('translator')->get('label.TODAYS_ORDERS'); ?>">
                <div class="visual">
                    <i class="fa fa-cubes"></i>
                </div>
                <div class="details">
                    <div class="number" data-counter="counterup" data-value="<?php echo e(!empty($todaysOrder)?$todaysOrder : 0); ?>">
                        <?php echo !empty($todaysOrder) ? $todaysOrder : 0; ?>

                    </div>
                    <div class="desc"><?php echo app('translator')->get('label.TODAYS_ORDERS'); ?></div>
                </div>
            </a>
        </div> 
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a id="totalRetailers" data-target="#totalRetailersModal" data-toggle="modal" href="#totalRetailersModal" class="dashboard-stat-v2  dashboard-stat green-sharp tooltips" title="<?php echo app('translator')->get('label.TOTAL_RETAILERS'); ?>">
                <div class="visual">
                    <i class="fa fa-puzzle-piece"></i>
                </div>
                <div class="details">
                    <div class="number"  data-counter="counterup" data-value="<?php echo e(!empty($totalRetailers)?$totalRetailers : 0); ?>">
                        <?php echo !empty($totalRetailers) ? $totalRetailers : 0; ?>

                    </div>
                    <div class="desc"><?php echo app('translator')->get('label.TOTAL_RETAILERS'); ?></div>
                </div>
            </a>
        </div> 

        <?php endif; ?>
        <!--        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a id="showMyProfile"  href="<?php echo e(url("admin/myProfile")); ?>" class="dashboard-stat-v2  dashboard-stat blue-soft tooltips" title="<?php echo app('translator')->get('label.MY_PROFILE'); ?>">
                        <div class="visual">
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="details">
                            <div class="number">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="desc"><?php echo app('translator')->get('label.MY_PROFILE'); ?></div>
                        </div>
                    </a>
                </div> -->

        <?php if(in_array(Auth::user()->group_id, [1, 11, 12])): ?>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a id="lowSKU" data-target="#lowQuantityProductsModal" data-toggle="modal" href="#lowQuantityProductsModal"  class="dashboard-stat-v2  dashboard-stat red-soft tooltips" title="<?php echo app('translator')->get('label.LOW_QUANTITY_SKU'); ?>">
                <div class="visual">
                    <i class="fa fa-sitemap"></i>
                </div>
                <div class="details">
                    <div class="number" data-counter="counterup" data-value="<?php echo e(!empty($lowQuantityProductList)?$lowQuantityProductList : 0); ?>">
                        <?php echo !empty($lowQuantityProductList) ? $lowQuantityProductList : 0; ?>

                    </div>
                    <div class="desc"><?php echo app('translator')->get('label.LOW_QUANTITY_SKU'); ?></div>
                </div>
            </a>
        </div> 
        <?php endif; ?>
    </div>
    <div class="row margin-top-20">

    </div>



    <?php if(in_array(Auth::user()->group_id, [1, 11])): ?>
    <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark font-size-14">
                            <?php echo app('translator')->get('label.WAREHOUSE_WISE_TODAYS_SALES_QUANTITY'); ?>
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="todayBestSellingWarehouse" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark font-size-14">
                            <?php echo app('translator')->get('label.WAREHOUSE_WISE_TODAYS_SALES_PRICE'); ?>
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="pmModeWiseTodaysCollection" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>

    </div>
    <?php endif; ?>

    <!--Last 30 Days (Monthly Order state)-->
    <?php if(in_array(Auth::user()->group_id, [18, 19])): ?>
    <div class="col-md-12 col-sm-12 col-xs-12 margin-top-10">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">
                        <?php echo app('translator')->get('label.MONTHLY_ORDER_STATE'); ?>
                    </span>
                    <span class="caption-helper"></span>
                </div>
                <div class="actions">
                    <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                </div>
            </div>
            <div class="portlet-body">
                <div id="monthlyOrderState" style="width: 100%; height: 400px; margin: 0 auto;"></div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!--End of Last 30 Days (Monthly Order state) --> 


    <!--Start :: Top 10 Most Selling Products of the Year-->
    <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark font-size-14">
                            <?php echo app('translator')->get('label.TOP_TEN_MOST_SELLING_PRODUCTS_QTY_WISE'); ?>
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="topSellingQuantityWise" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark font-size-14">
                            <?php echo app('translator')->get('label.TOP_TEN_MOST_SELLING_PRODUCTS_AMOUNT_WISE'); ?>
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="topSellingAmountWise" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
    </div>
    <!--End :: Top 10 Most Selling Products of the Year-->

    <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            <?php echo app('translator')->get('label.LAST_THIRTY_DAYS_BEST_WIREHOUSE_PERFORMANCE'); ?>
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="last30DaysBestWh" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            <?php echo app('translator')->get('label.LAST_THIRTY_DAYS_WORST_WIREHOUSE_PERFORMANCE'); ?>
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="last30DaysWorstWh" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
    </div>
    <?php if(in_array(Auth::user()->group_id, [12])): ?>
    <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark font-size-14">
                            <?php echo app('translator')->get('label.LAST_QUARTER_BEST_PERFORMANCE_MDO_QUANTITY_WISE'); ?>
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="lasteQuarterBestPerformerMDOByQuantity" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark font-size-14">
                            <?php echo app('translator')->get('label.LAST_QUARTER_BEST_PERFORMANCE_MDO_AMOUNT_WISE'); ?>
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="lasteQuarterBestPerformerMDOByAmount" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 margin-top-10">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">
                        <?php echo app('translator')->get('label.LAST_30_DAYS_SALES_PERFORMANCE'); ?>
                    </span>
                    <span class="caption-helper"></span>
                </div>
                <div class="actions">
                    <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                </div>
            </div>
            <div class="portlet-body">
                <div id="lastThirtyDaysSalesPerformance" style="width: 100%; height: 400px; margin: 0 auto;"></div>
            </div>
        </div>
    </div>
    <?php endif; ?>






</div>


<!--product pricing modal-->
<div class="modal fade" id="productPricingModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showProductPricing">
        </div>
    </div>
</div>
<div class="modal fade" id="pendingOrderModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showPendingOrder">
        </div>
    </div>
</div>
<!--end:: product pricing modal-->

<!--waiting for processing modal-->
<div class="modal fade" id="waitingProcessingingModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div  id="showWaitingForProcessing">
        </div>
    </div>
</div>
<!--end:: waiting for processing modal-->

<!--waiting for processing modal-->
<div class="modal fade" id="placedInDeliveryModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div  id="showPlacedInDelivery">
        </div>
    </div>
</div>
<!--end:: waiting for processing modal-->

<!--waiting for processing modal-->
<div class="modal fade" id="todaysOrderModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div  id="showTodaysOrder">
        </div>
    </div>
</div>
<!--end:: waiting for processing modal-->

<!--waiting for processing modal-->
<div class="modal fade" id="totalRetailersModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div  id="showTotalRetailer">
        </div>
    </div>
</div>
<!--end:: waiting for processing modal-->

<!--low quantity sku modal-->
<div class="modal fade" id="lowQuantityProductsModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showLowQuanSKU">
        </div>
    </div>
</div>
<!--end:: low quantity sku modal-->

<!--inquiry Summary modal-->
<div class="modal fade" id="inquirySummaryModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showInquirySummary">
        </div>
    </div>
</div>


<script src="<?php echo e(asset('public/js/apexcharts.min.js')); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
//Start :: Product pricing
$("#productPricing").on("click", function (e) {
e.preventDefault();
$.ajax({
url: "<?php echo e(URL::to('/dashboard/getProductPricing')); ?>",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
        $("#showProductPricing").html('');
        App.blockUI({
        boxed: true
        });
        },
        success: function (res) {
        $("#showProductPricing").html(res.html);
        App.unblockUI();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        App.unblockUI();
        }
}); //ajax
});
//End :: Product Pricing
//Start :: Product pricing
$("#pendingOrder").on("click", function (e) {
e.preventDefault();
$.ajax({
url: "<?php echo e(URL::to('/dashboard/getPendingOrder')); ?>",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
        $("#showPendingOrder").html('');
        App.blockUI({
        boxed: true
        });
        },
        success: function (res) {
        $("#showPendingOrder").html(res.html);
        App.unblockUI();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        App.unblockUI();
        }
}); //ajax
});
//End :: Product Pricing
//
//Start :: Waiting for processing
//    $("#waitingProcessing").on("click", function (e) {
//    e.preventDefault();
//    $.ajax({
//    url: "<?php echo e(URL::to('/dashboard/getWaitingForProcessing')); ?>",
//            type: "POST",
//            dataType: "json",
//            headers: {
//            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//            },
//            beforeSend: function () {
//            $("#showWaitingForProcessing").html('');
//            App.blockUI({
//            boxed: true
//            });
//            },
//            success: function (res) {
//            $("#showWaitingForProcessing").html(res.html);
//            App.unblockUI();
//            },
//            error: function (jqXhr, ajaxOptions, thrownError) {
//            App.unblockUI();
//            }
//    }); //ajax
//    });
//End :: Waiting for processing
//
//Start :: placed In Delivery
//    $("#placedInDelivery").on("click", function (e) {
//    e.preventDefault();
//    $.ajax({
//    url: "<?php echo e(URL::to('/dashboard/getPlacedInDelivery')); ?>",
//            type: "POST",
//            dataType: "json",
//            headers: {
//            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//            },
//            beforeSend: function () {
//            $("#showPlacedInDelivery").html('');
//            App.blockUI({
//            boxed: true
//            });
//            },
//            success: function (res) {
//            $("#showPlacedInDelivery").html(res.html);
//            App.unblockUI();
//            },
//            error: function (jqXhr, ajaxOptions, thrownError) {
//            App.unblockUI();
//            }
//    }); //ajax
//    });
//End :: placed In Delivery
//
//Start :: placed In Delivery
$("#lowSKU").on("click", function (e) {
e.preventDefault();
$.ajax({
url: "<?php echo e(URL::to('/dashboard/getLowQuantitySKU')); ?>",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
        $("#showLowQuanSKU").html('');
        App.blockUI({
        boxed: true
        });
        },
        success: function (res) {
        $("#showLowQuanSKU").html(res.html);
        App.unblockUI();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        App.unblockUI();
        }
}); //ajax
});
//End :: placed In Delivery

//Start :: Waiting for processing
$("#todaysOrder").on("click", function (e) {
e.preventDefault();
$.ajax({
url: "<?php echo e(URL::to('/dashboard/getTodaysOrder')); ?>",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
        $("#showTodaysOrder").html('');
        App.blockUI({
        boxed: true
        });
        },
        success: function (res) {
        $("#showTodaysOrder").html(res.html);
        App.unblockUI();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        App.unblockUI();
        }
}); //ajax
});
//End :: Waiting for processing
//Start :: Waiting for processing
$("#totalRetailers").on("click", function (e) {
e.preventDefault();
$.ajax({
url: "<?php echo e(URL::to('/dashboard/getTotalRetailer')); ?>",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
        $("#showTotalRetailer").html('');
        App.blockUI({
        boxed: true
        });
        },
        success: function (res) {
        $("#showTotalRetailer").html(res.html);
        App.unblockUI();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        App.unblockUI();
        }
}); //ajax
});
//End :: Waiting for processing

//START :: Last Year Growth Chart
var lastYearGrowthOptions = {
chart: {
height: 400,
        type: 'line',
        shadow: {
        enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 1
        },
        toolbar: {
        show: false
        }
},
        colors: ['#295939', '#0f3057', '#3390ff'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return val
                },
                textAnchor: 'middle',
                distributed: false,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#295939', '#0f3057', '#3390ff'],
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        stroke: {
        curve: 'smooth'
        },
        series: [
        {
        name: "<?php echo app('translator')->get('label.PROFIT'); ?> (<?php echo app('translator')->get('label.TK'); ?>)",
                data: [
<?php
if (!empty($lastOneYearMonth)) {
    foreach ($lastOneYearMonth as $monthName => $profit) {
        $growth = !empty($profit) ? $profit : 0;
        echo $growth . ',';
    }
}
?>
                ]
        },
        ],
        grid: {
        borderColor: '#e7e7e7',
                row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                },
        },
        markers: {

        size: 6
        },
        xaxis: {
        categories: [
<?php
if (!empty($lastOneYearMonth)) {
    foreach ($lastOneYearMonth as $monthName => $profit) {
        echo '"' . $monthName . '", ';
    }
}
?>
        ],
                title: {
                text: "<?php echo app('translator')->get('label.MONTHS'); ?>",
                        style: {
                        color: undefined,
                                fontSize: '11px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                }
        },
        yaxis: {
        title: {
        text: "<?php echo app('translator')->get('label.PROFIT'); ?> (<?php echo app('translator')->get('label.TK'); ?>)",
        },
                min: <?php echo!empty($minProfit) ? $minProfit - 10 : 0; ?>,
                max: <?php echo!empty($maxProfit) ? $maxProfit + 10 : 0; ?>,
                forceNiceScale: true,
                labels: {
                show: true,
                        align: 'right',
                        minWidth: 0,
                        maxWidth: 160,
                        style: {
                        color: undefined,
                                fontSize: '11px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 400,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                        offsetX: 0,
                        offsetY: 0,
                        rotate: 0,
                        formatter: (val) => {
                var ftVal = parseFloat(val).toFixed(2);
                var valLength = ftVal.toString().length;
                var fnlVal = parseInt(val);
                var valT = '';
                if (valLength > 6){
                fnlVal = parseInt(val / 1000);
                valT = 'K';
                }
                if (valLength > 9){
                fnlVal = parseInt(val / 1000000);
                valT = 'M';
                }
                if (valLength > 12){
                fnlVal = parseInt(val / 1000000000);
                valT = 'B';
                }
                return fnlVal + '' + valT
                },
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return parseFloat(val).toFixed(2)
        }
        }
        },
        legend: {
        position: 'bottom',
                horizontalAlign: 'center',
                floating: false,
                offsetY: 0,
                offsetX: - 5
        }
}

var lastYearGrowthChart = new ApexCharts(document.querySelector("#lastYearGrowthChart"), lastYearGrowthOptions);
lastYearGrowthChart.render();
//END :: Last Year Growth Chart

//Start:: Expense vs Earning
var lastYearExpenseEarningOptions = {
series: [ {
name: "<?php echo app('translator')->get('label.EARNING'); ?> (<?php echo app('translator')->get('label.TK'); ?>)",
        data: [
<?php
if (!empty($lastOneYearEarning)) {
    foreach ($lastOneYearEarning as $month => $earning) {
        ?>
                "<?php echo e($earning); ?>",
        <?php
    }
}
?>
        ]
        }, {
name: "<?php echo app('translator')->get('label.EXPENSE'); ?> (<?php echo app('translator')->get('label.TK'); ?>)",
        data: [
<?php
if (!empty($lastOneYearExpense)) {
    foreach ($lastOneYearExpense as $month => $expense) {
        ?>
                "<?php echo e($expense); ?>",
        <?php
    }
}
?>
        ]
}],
        chart: {
        type: 'bar',
                height: 350
        },
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '25%',
                borderRadius: 0,
                endingShape: 'rounded',
                startingShape: 'rounded',
        },
        },
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return val
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#1f441e', '#ff0000', '#440a67', '#C62700', '#ABC400', '#26001b', '#ff005c', '#21209c', '#04BC06', '#013C38', '#8f4f4f', '#435560', '#025955', '#8c0000', '#763857', '#28527a', '#413c69', '#484018', '#1687a7', '#41584b', '#dd9866', '#16a596', '#649d66', '#7a4d1d', '#630B0B', '#FF5600', '#AF00A0', '#000000', '#290262', '#9D0233'],
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        categories: [
<?php
if (!empty($lastOneYearEarning)) {
    foreach ($lastOneYearEarning as $month => $earning) {
        echo '"' . $month . '", ';
    }
}
?>
        ],
        },
        yaxis: {
        title: {
        text: "<?php echo app('translator')->get('label.AMOUNT'); ?> (<?php echo app('translator')->get('label.TK'); ?>)"
        },
                labels: {
                show: true,
                        align: 'right',
                        minWidth: 0,
                        maxWidth: 160,
                        style: {
                        color: undefined,
                                fontSize: '11px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 400,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                        offsetX: 0,
                        offsetY: 0,
                        rotate: 0,
                        formatter: (val) => {
                var ftVal = parseFloat(val).toFixed(2);
                var valLength = ftVal.toString().length;
                var fnlVal = parseInt(val);
                var valT = '';
                if (valLength > 6){
                fnlVal = parseInt(val / 1000);
                valT = 'K';
                }
                if (valLength > 9){
                fnlVal = parseInt(val / 1000000);
                valT = 'M';
                }
                if (valLength > 12){
                fnlVal = parseInt(val / 1000000000);
                valT = 'B';
                }
                return fnlVal + '' + valT
                },
                },
        },
        fill: {
        opacity: 1
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return parseFloat(val).toFixed(2)
        }
        }
        }
};
var lastYearExpenseEarning = new ApexCharts(document.querySelector("#lastYearExpenseEarning"), lastYearExpenseEarningOptions);
lastYearExpenseEarning.render();
//Start:: Expense vs Earning


//START :: Category Wise Profit Chart
var categoryProfitOptions = {
chart: {
height: 400,
        type: 'line',
        shadow: {
        enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 1
        },
        toolbar: {
        show: false
        }
},
        colors: ['#295939', '#0f3057', '#3390ff'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return val
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#295939', '#0f3057', '#3390ff'],
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        stroke: {
        curve: 'smooth'
        },
        series: [
<?php
if (!empty($categoryInfo)) {
    foreach ($categoryInfo as $info) {
        if ($info->parent_id == 0) {
            ?>
                    {
                    name: "<?php echo e($info->name); ?>",
                            data: [
            <?php
            if (!empty($lastSixMonths)) {
                foreach ($lastSixMonths as $monthName => $categories) {
                    $growth = !empty($categories[$info->id]) ? $categories[$info->id] : 0;
                    echo $growth . ',';
                }
            }
            ?>
                            ]
                    },
            <?php
        }
    }
}
?>
        ],
        grid: {
        borderColor: '#e7e7e7',
                row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                },
        },
        markers: {

        size: 6
        },
        xaxis: {
        categories: [
<?php
if (!empty($lastSixMonths)) {
    foreach ($lastSixMonths as $monthName => $profit) {
        echo '"' . $monthName . '", ';
    }
}
?>
        ],
                title: {
                text: "<?php echo app('translator')->get('label.MONTHS'); ?>",
                        style: {
                        color: undefined,
                                fontSize: '11px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                }
        },
        yaxis: {
        title: {
        text: " <?php echo app('translator')->get('label.PROFIT'); ?>   (<?php echo app('translator')->get('label.TK'); ?>)",
                style: {
                color: undefined,
                        fontSize: '11px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 700,
                        cssClass: 'apexcharts-xaxis-title',
                },
        },
                min: <?php echo!empty($categoryWiseMinProfit) ? $categoryWiseMinProfit - 10 : 0; ?>,
                max: <?php echo!empty($categoryWiseMaxProfit) ? $categoryWiseMaxProfit + 10 : 0; ?>,
                forceNiceScale: true,
                labels: {
                show: true,
                        align: 'right',
                        minWidth: 0,
                        maxWidth: 160,
                        style: {
                        color: undefined,
                                fontSize: '11px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 400,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                        offsetX: 0,
                        offsetY: 0,
                        rotate: 0,
                        formatter: (val) => {
                var ftVal = parseFloat(val).toFixed(2);
                var valLength = ftVal.toString().length;
                var fnlVal = parseInt(val);
                var valT = '';
                if (valLength > 6){
                fnlVal = parseInt(val / 1000);
                valT = 'K';
                }
                if (valLength > 9){
                fnlVal = parseInt(val / 1000000);
                valT = 'M';
                }
                if (valLength > 12){
                fnlVal = parseInt(val / 1000000000);
                valT = 'B';
                }
                return fnlVal + '' + valT
                },
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return parseFloat(val).toFixed(2)
        }
        }
        },
        legend: {
        position: 'bottom',
                horizontalAlign: 'center',
                floating: false,
                offsetY: 0,
                offsetX: - 5
        }
}

var categoryProfitChart = new ApexCharts(document.querySelector("#categoryProfitChart"), categoryProfitOptions);
categoryProfitChart.render();
//END :: Last Year Growth Chart


//START :: Top 10 Most Selling Products of the Year

var topSellingQuantityWiseOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        }
},
        series: [{
        name: "<?php echo app('translator')->get('label.QUANTITY'); ?>",
                data: [
<?php
if (!empty($totalSellQuantityArr)) {
    foreach ($totalSellQuantityArr as $productName => $productQuantity) {
        ?>
                        "<?php echo e($productQuantity); ?>",
        <?php
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: ['#1f441e', '#ff0000', '#440a67', '#C62700', '#ABC400', '#26001b', '#ff005c', '#21209c', '#04BC06', '#013C38', '#8f4f4f', '#435560', '#025955', '#8c0000', '#763857', '#28527a', '#413c69', '#484018', '#1687a7', '#41584b', '#dd9866', '#16a596', '#649d66', '#7a4d1d', '#630B0B', '#FF5600', '#AF00A0', '#000000', '#290262', '#9D0233'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return parseFloat(val).toFixed(2)
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#1f441e', '#ff0000', '#440a67', '#C62700', '#ABC400', '#26001b', '#ff005c', '#21209c', '#04BC06', '#013C38', '#8f4f4f', '#435560', '#025955', '#8c0000', '#763857', '#28527a', '#413c69', '#484018', '#1687a7', '#41584b', '#dd9866', '#16a596', '#649d66', '#7a4d1d', '#630B0B', '#FF5600', '#AF00A0', '#000000', '#290262', '#9D0233']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        labels: {
        show: true,
                //                rotate: - 60,
                //                rotateAlways: true,
                hideOverlappingLabels: false,
                showDuplicates: true,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                return val;
                },
                format: undefined,
        },
                categories: [
<?php
if (!empty($totalReturnQuantityArr)) {
    foreach ($totalReturnQuantityArr as $productId => $productQuantity) {
        $productName = !empty($productId) && !empty($productList[$productId]) ? $productList[$productId] : '';
        echo '"' . $productName . '", ';
    }
}
?>
                ],
                title: {
                text: "<?php echo app('translator')->get('label.PRODUCT'); ?>",
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                        color: undefined,
                                fontSize: '11px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                },
        },
        yaxis: {
        title: {
        text: "<?php echo app('translator')->get('label.QUANTITY'); ?>",
                offsetX: 0,
                offsetY: 0,
                style: {
                color: undefined,
                        fontSize: '11px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 700,
                        cssClass: 'apexcharts-xaxis-title',
                },
        }
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return parseFloat(val).toFixed(2)
        }
        }
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        };
var topSellingQuantityWise = new ApexCharts(document.querySelector("#topSellingQuantityWise"), topSellingQuantityWiseOptions);
topSellingQuantityWise.render();
var todayBestSellingWarehouseOptions = {

chart: {
height: 400,
        type: 'line',
        shadow: {
        enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 1
        },
        toolbar: {
        show: false
        }
},
        colors:["#4C87B9", "#8E44AD", "#F36A5A", "#1BA39C", "#D91E18"],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return parseFloat(val).toFixed(2)
                },
                textAnchor: 'middle',
                distributed: false,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ["#4C87B9", "#8E44AD", "#F36A5A", "#1BA39C", "#D91E18"]
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        stroke: {
        curve: 'smooth'
        },
        series: [

        {
        name: "<?php echo app('translator')->get('label.UPCOMING'); ?>",
                data: [
<?php
if (!empty($warehouseList)) {
    foreach ($warehouseList as $id => $name) {
        $quantity = !empty($warehouseWiseQuantityTodayArr[$id]) ? $warehouseWiseQuantityTodayArr[$id] : 0;
        ?>
                        "<?php echo e($quantity); ?>",
        <?php
    }
}
?>
                ]
        },
        ],
        grid: {
        borderColor: '#e7e7e7',
                row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                },
        },
        markers: {

        size: 6
        },
        xaxis: {
        labels: {
        show: true,
                rotate: - 60,
                rotateAlways: true,
                hideOverlappingLabels: false,
                showDuplicates: true,
                trim: true,
                minHeight: undefined,
                maxHeight: 100,
                offsetX: 0,
                offsetY: 0,
                format: undefined,
                formatter: undefined,
        },
                categories: [
<?php
if (!empty($warehouseList)) {
    foreach ($warehouseList as $wrId => $wrName) {
        //        echo "'$wrName',";
        echo '"' . $wrName . '", ';
    }
}
?>
                ],
                title: {
                text: "<?php echo app('translator')->get('label.BRAND'); ?>",
                        offsetX: - 40,
                        offsetY: 50,
                        style: {
                        color: undefined,
                                fontSize: '12px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                },
        },
        yaxis: {
        title: {
        text: "<?php echo app('translator')->get('label.QUANTITY'); ?>"
        }
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return parseFloat(val).toFixed(2) + " Unit"
        }
        }
        },
        legend: {
        position: 'bottom',
                horizontalAlign: 'center',
                offsetY: 10,
                offsetX: 0,
                width: undefined,
                height: 100,
        }

};
var todayBestSellingWarehouse = new ApexCharts(document.querySelector("#todayBestSellingWarehouse"), todayBestSellingWarehouseOptions);
todayBestSellingWarehouse.render();
var pmModeWiseTodaysCollectionOptions = {
series: [
<?php
if (!empty($paymentModeList)) {
    foreach ($paymentModeList as $modeId => $modeName) {
        $noOfCm = !empty($pmModeWiseTodaysCollectionArr[$modeId]) ? $pmModeWiseTodaysCollectionArr[$modeId] : 0.00;
        echo $noOfCm . ',';
    }
}
?>
],
        labels: [
<?php
if (!empty($paymentModeList)) {
    foreach ($paymentModeList as $modeId => $modeName) {
        echo "'$modeName', ";
    }
}
?>
        ],
        chart: {
        width: 415,
                type: 'donut',
        },
        plotOptions: {
        pie: {
        startAngle: - 90,
                endAngle: 270
        }
        },
        colors: ["#4C87B9", "#8E44AD", "#F36A5A", "#1BA39C", "#D91E18"],
        dataLabels: {
        enabled: true
        },
        fill: {
        type: 'gradient',
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return val
        }
        }
        },
        legend: {
        position: 'bottom',
                formatter: function(val, opts) {
                var amount = parseFloat(opts.w.globals.series[opts.seriesIndex]).toFixed(2);
                return val + ": " + amount + " <?php echo app('translator')->get('label.TK'); ?>"
                }
        },
        title: {
        text: ''
        },
        responsive: [{
        breakpoint: 480,
                options: {
                chart: {
                width: 200
                },
                        legend: {
                        position: 'bottom'
                        }
                }
        }]
        };
var pmModeWiseTodaysCollection = new ApexCharts(document.querySelector("#pmModeWiseTodaysCollection"), pmModeWiseTodaysCollectionOptions);
pmModeWiseTodaysCollection.render();
var topSellingAmountWiseOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        }
},
        series: [{
        name: "<?php echo app('translator')->get('label.AMOUNT'); ?>",
                data: [
<?php
if (!empty($totalSellAmountArr)) {
    foreach ($totalSellAmountArr as $productId => $productAmount) {
        ?>
                        "<?php echo e($productAmount); ?>",
        <?php
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: ['#1f441e', '#ff0000', '#440a67', '#C62700', '#ABC400', '#26001b', '#ff005c', '#21209c', '#04BC06', '#013C38', '#8f4f4f', '#435560', '#025955', '#8c0000', '#763857', '#28527a', '#413c69', '#484018', '#1687a7', '#41584b', '#dd9866', '#16a596', '#649d66', '#7a4d1d', '#630B0B', '#FF5600', '#AF00A0', '#000000', '#290262', '#9D0233'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return parseFloat(val).toFixed(2) + " <?php echo app('translator')->get('label.TK'); ?>"
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#1f441e', '#ff0000', '#440a67', '#C62700', '#ABC400', '#26001b', '#ff005c', '#21209c', '#04BC06', '#013C38', '#8f4f4f', '#435560', '#025955', '#8c0000', '#763857', '#28527a', '#413c69', '#484018', '#1687a7', '#41584b', '#dd9866', '#16a596', '#649d66', '#7a4d1d', '#630B0B', '#FF5600', '#AF00A0', '#000000', '#290262', '#9D0233']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        labels: {
        show: true,
                //                rotate: - 60,
                //                rotateAlways: true,
                hideOverlappingLabels: false,
                showDuplicates: true,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                return val;
                },
                format: undefined,
        },
                categories: [
<?php
if (!empty($totalSellAmountArr)) {
    foreach ($totalSellAmountArr as $productId => $productAmount) {
        $productName = !empty($productId) && !empty($productList[$productId]) ? $productList[$productId] : '';
        echo '"' . $productName . '", ';
    }
}
?>
                ],
                title: {
                text: "<?php echo app('translator')->get('label.PRODUCT'); ?>",
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                        color: undefined,
                                fontSize: '11px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                },
        },
        yaxis: {
        title: {
        text: "<?php echo app('translator')->get('label.AMOUNT'); ?> (<?php echo app('translator')->get('label.TK'); ?>)",
                offsetX: 0,
                offsetY: 0,
                style: {
                color: undefined,
                        fontSize: '11px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 700,
                        cssClass: 'apexcharts-xaxis-title',
                },
        },
                labels: {
                show: true,
                        align: 'right',
                        minWidth: 0,
                        maxWidth: 160,
                        style: {
                        color: undefined,
                                fontSize: '11px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 400,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                        offsetX: 0,
                        offsetY: 0,
                        rotate: 0,
                        formatter: (val) => {
                var ftVal = parseFloat(val).toFixed(2);
                var valLength = ftVal.toString().length;
                var fnlVal = parseInt(val);
                var valT = '';
                if (valLength > 6){
                fnlVal = parseInt(val / 1000);
                valT = 'K';
                }
                if (valLength > 9){
                fnlVal = parseInt(val / 1000000);
                valT = 'M';
                }
                if (valLength > 12){
                fnlVal = parseInt(val / 1000000000);
                valT = 'B';
                }
                return fnlVal + '' + valT
                },
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return parseFloat(val).toFixed(2) + " <?php echo app('translator')->get('label.TK'); ?>"
        }
        }
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        };
var topSellingAmountWise = new ApexCharts(document.querySelector("#topSellingAmountWise"), topSellingAmountWiseOptions);
topSellingAmountWise.render();
var lasteQuarterBestPerformerMDOByQuantityOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        }
},
        series: [{
        name: "<?php echo app('translator')->get('label.QUANTITY'); ?>",
                data: [
<?php
if (!empty($srArr)) {
    foreach ($srArr as $srId => $srName) {
        $quantity = !empty($threeMnthsOrderByQtyFrSrArr[$srId]) ? $threeMnthsOrderByQtyFrSrArr[$srId] : 0;
        ?>
                        "<?php echo e($quantity); ?>",
        <?php
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: ['#1f441e', '#ff0000', '#440a67', '#C62700', '#ABC400', '#26001b', '#ff005c', '#21209c', '#04BC06', '#013C38', '#8f4f4f', '#435560', '#025955', '#8c0000', '#763857', '#28527a', '#413c69', '#484018', '#1687a7', '#41584b', '#dd9866', '#16a596', '#649d66', '#7a4d1d', '#630B0B', '#FF5600', '#AF00A0', '#000000', '#290262', '#9D0233'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return parseFloat(val).toFixed(2) + " <?php echo app('translator')->get('label.UNIT'); ?>"
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#1f441e', '#ff0000', '#440a67', '#C62700', '#ABC400', '#26001b', '#ff005c', '#21209c', '#04BC06', '#013C38', '#8f4f4f', '#435560', '#025955', '#8c0000', '#763857', '#28527a', '#413c69', '#484018', '#1687a7', '#41584b', '#dd9866', '#16a596', '#649d66', '#7a4d1d', '#630B0B', '#FF5600', '#AF00A0', '#000000', '#290262', '#9D0233']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        labels: {
        show: true,
                //                rotate: - 60,
                //                rotateAlways: true,
                hideOverlappingLabels: false,
                showDuplicates: true,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                return val;
                },
                format: undefined,
        },
                categories: [
<?php
if (!empty($srArr)) {
    foreach ($srArr as $srId => $srName) {
        echo '"' . $srName . '", ';
    }
}
?>
                ],
                title: {
                text: "<?php echo app('translator')->get('label.SR'); ?>",
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                        color: undefined,
                                fontSize: '11px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                },
        },
        yaxis: {
        title: {
        text: "<?php echo app('translator')->get('label.QUANTITY'); ?>",
                offsetX: 0,
                offsetY: 0,
                style: {
                color: undefined,
                        fontSize: '11px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 700,
                        cssClass: 'apexcharts-xaxis-title',
                },
        }
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return parseFloat(val).toFixed(2) + " <?php echo app('translator')->get('label.UNIT'); ?>"
        }
        }
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        };
var lasteQuarterBestPerformerMDOByQuantity = new ApexCharts(document.querySelector("#lasteQuarterBestPerformerMDOByQuantity"), lasteQuarterBestPerformerMDOByQuantityOptions);
lasteQuarterBestPerformerMDOByQuantity.render();
var lasteQuarterBestPerformerMDOByAmountOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        }
},
        series: [{
        name: "<?php echo app('translator')->get('label.AMOUNT'); ?>",
                data: [
<?php
if (!empty($srArr)) {
    foreach ($srArr as $srId => $srName) {
        $quantity = !empty($threeMnthsOrderByAmountForSrArr[$srId]) ? $threeMnthsOrderByAmountForSrArr[$srId] : 0;
        ?>
                        "<?php echo e($quantity); ?>",
        <?php
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: ['#04BC06', '#013C38', '#8f4f4f', '#435560', '#025955', '#8c0000', '#763857', '#28527a', '#413c69', '#484018', '#1687a7', '#41584b', '#dd9866', '#16a596', '#649d66', '#7a4d1d', '#630B0B', '#FF5600', '#AF00A0', '#000000', '#290262', '#9D0233', '#C62700', '#ABC400', '#26001b', '#ff005c', '#21209c'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return parseFloat(val).toFixed(2) + " <?php echo app('translator')->get('label.TK'); ?>"
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#1f441e', '#ff0000', '#440a67', '#C62700', '#ABC400', '#26001b', '#ff005c', '#21209c', '#04BC06', '#013C38', '#8f4f4f', '#435560', '#025955', '#8c0000', '#763857', '#28527a', '#413c69', '#484018', '#1687a7', '#41584b', '#dd9866', '#16a596', '#649d66', '#7a4d1d', '#630B0B', '#FF5600', '#AF00A0', '#000000', '#290262', '#9D0233']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        labels: {
        show: true,
                //                rotate: - 60,
                //                rotateAlways: true,
                hideOverlappingLabels: false,
                showDuplicates: true,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                return val;
                },
                format: undefined,
        },
                categories: [
<?php
if (!empty($srArr)) {
    foreach ($srArr as $srId => $srName) {
        echo '"' . $srName . '", ';
    }
}
?>
                ],
                title: {
                text: "<?php echo app('translator')->get('label.SR'); ?>",
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                        color: undefined,
                                fontSize: '11px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                },
        },
        yaxis: {
        title: {
        text: "<?php echo app('translator')->get('label.AMOUNT'); ?> (<?php echo app('translator')->get('label.TK'); ?>)",
                offsetX: 0,
                offsetY: 0,
                style: {
                color: undefined,
                        fontSize: '11px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 700,
                        cssClass: 'apexcharts-xaxis-title',
                },
        },
                labels: {
                show: true,
                        align: 'right',
                        minWidth: 0,
                        maxWidth: 160,
                        style: {
                        color: undefined,
                                fontSize: '11px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 400,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                        offsetX: 0,
                        offsetY: 0,
                        rotate: 0,
                        formatter: (val) => {
                var ftVal = parseFloat(val).toFixed(2);
                var valLength = ftVal.toString().length;
                var fnlVal = parseInt(val);
                var valT = '';
                if (valLength > 6){
                fnlVal = parseInt(val / 1000);
                valT = 'K';
                }
                if (valLength > 9){
                fnlVal = parseInt(val / 1000000);
                valT = 'M';
                }
                if (valLength > 12){
                fnlVal = parseInt(val / 1000000000);
                valT = 'B';
                }
                return fnlVal + '' + valT
                },
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return parseFloat(val).toFixed(2) + " <?php echo app('translator')->get('label.TK'); ?>"
        }
        }
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        };
var lasteQuarterBestPerformerMDOByAmount = new ApexCharts(document.querySelector("#lasteQuarterBestPerformerMDOByAmount"), lasteQuarterBestPerformerMDOByAmountOptions);
lasteQuarterBestPerformerMDOByAmount.render();
//End :: Top 10 Most Selling Products of the Year

//Start:: last 30 days Best WIrehouse Performance
var last30DaysBestWhOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        },
        },
        series: [{
        name: "<?php echo app('translator')->get('label.QUANTITY'); ?>",
                data: [
<?php
if (!empty($last30DaysBestWhArr)) {
    foreach ($last30DaysBestWhArr as $whId => $qty) {
        echo "'$qty',";
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: [ '#E08283', '#C49F47', '#C8D046', '#7F6084', '#4B77BE', '#E35B5A', '#3598DC', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8877A9', '#555555'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return parseFloat(val).toFixed(2);
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        title: {
        text: '',
        },
                labels: {
                show: true,
                        rotate: - 60,
                        rotateAlways: true,
                },
                categories: [
<?php
if (!empty($last30DaysBestWhArr)) {
    foreach ($last30DaysBestWhArr as $whId => $qty) {
        $warehouseName = !empty($whId) && !empty($warehouseList[$whId]) ? $warehouseList[$whId] : '';
        echo '"' . $warehouseName . '", ';
    }
}
?>

                ],
        },
        yaxis: {
        title: {
        text: "<?php echo app('translator')->get('label.QUANTITY'); ?>"
        },
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return  parseFloat(val).toFixed(2) + " <?php echo app('translator')->get('label.UNIT'); ?>"
        }
        }
        }
};
var last30DaysBestWh = new ApexCharts(document.querySelector("#last30DaysBestWh"), last30DaysBestWhOptions);
last30DaysBestWh.render();
//End:: last 30 days Best WIrehouse Performance
//Start:: last 30 days Worst WIrehouse Performance
var last30DaysWorstWhOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        },
        },
        series: [{
        name: "<?php echo app('translator')->get('label.QUANTITY'); ?>",
                data: [
<?php
if (!empty($last30DaysWorstWhArr)) {
    foreach ($last30DaysWorstWhArr as $whId => $qty) {
        echo "'$qty',";
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: [ '#E08283', '#C49F47', '#C8D046', '#7F6084', '#4B77BE', '#E35B5A', '#3598DC', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8877A9', '#555555'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return parseFloat(val).toFixed(2);
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        title: {
        text: '',
        },
                labels: {
                show: true,
                        rotate: - 60,
                        rotateAlways: true,
                },
                categories: [
<?php
if (!empty($last30DaysWorstWhArr)) {
    foreach ($last30DaysWorstWhArr as $whId => $qty) {
        $warehouseName = !empty($whId) && !empty($warehouseList[$whId]) ? $warehouseList[$whId] : '';
        echo '"' . $warehouseName . '", ';
    }
}
?>

                ],
        },
        yaxis: {
        title: {
        text: "<?php echo app('translator')->get('label.QUANTITY'); ?>"
        },
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return  parseFloat(val).toFixed(2) + " <?php echo app('translator')->get('label.UNIT'); ?>"
        }
        }
        }
};
var last30DaysWorstWh = new ApexCharts(document.querySelector("#last30DaysWorstWh"), last30DaysWorstWhOptions);
last30DaysWorstWh.render();
//End:: last 30 days Worst WIrehouse Performance

//START :: Top 10 Most Returned & Damaged Products of the Year

var topReturnedOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        }
},
        series: [{
        name: "<?php echo app('translator')->get('label.QUANTITY'); ?>",
                data: [
<?php
if (!empty($totalReturnQuantityArr)) {
    foreach ($totalReturnQuantityArr as $productName => $productQuantity) {
        ?>
                        "<?php echo e($productQuantity); ?>",
        <?php
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: ['#1f441e', '#ff0000', '#440a67', '#C62700', '#ABC400', '#26001b', '#ff005c', '#21209c', '#04BC06', '#013C38', '#8f4f4f', '#435560', '#025955', '#8c0000', '#763857', '#28527a', '#413c69', '#484018', '#1687a7', '#41584b', '#dd9866', '#16a596', '#649d66', '#7a4d1d', '#630B0B', '#FF5600', '#AF00A0', '#000000', '#290262', '#9D0233'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return val
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#1f441e', '#ff0000', '#440a67', '#C62700', '#ABC400', '#26001b', '#ff005c', '#21209c', '#04BC06', '#013C38', '#8f4f4f', '#435560', '#025955', '#8c0000', '#763857', '#28527a', '#413c69', '#484018', '#1687a7', '#41584b', '#dd9866', '#16a596', '#649d66', '#7a4d1d', '#630B0B', '#FF5600', '#AF00A0', '#000000', '#290262', '#9D0233']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        labels: {
        show: true,
                //                rotate: - 60,
                //                rotateAlways: true,
                hideOverlappingLabels: false,
                showDuplicates: true,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                return val;
                },
                format: undefined,
        },
                categories: [
<?php
if (!empty($totalReturnQuantityArr)) {
    foreach ($totalReturnQuantityArr as $productId => $productQuantity) {
        $productName = !empty($productId) && !empty($productList[$productId]) ? $productList[$productId] : '';
        echo '"' . $productName . '", ';
    }
}
?>
                ],
                title: {
                text: "<?php echo app('translator')->get('label.PRODUCT'); ?>",
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                        color: undefined,
                                fontSize: '11px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                },
        },
        yaxis: {
        title: {
        text: "<?php echo app('translator')->get('label.QUANTITY'); ?>",
                offsetX: 0,
                offsetY: 0,
                style: {
                color: undefined,
                        fontSize: '11px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 700,
                        cssClass: 'apexcharts-xaxis-title',
                },
        }
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        };
var topReturned = new ApexCharts(document.querySelector("#topReturned"), topReturnedOptions);
topReturned.render();
var topDamagedOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        }
},
        series: [{
        name: "<?php echo app('translator')->get('label.QUANTITY'); ?>",
                data: [
<?php
if (!empty($totalDamegeQuantityArr)) {
    foreach ($totalDamegeQuantityArr as $productId => $productQuantity) {
        ?>
                        "<?php echo e($productQuantity); ?>",
        <?php
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: ['#1f441e', '#ff0000', '#440a67', '#C62700', '#ABC400', '#26001b', '#ff005c', '#21209c', '#04BC06', '#013C38', '#8f4f4f', '#435560', '#025955', '#8c0000', '#763857', '#28527a', '#413c69', '#484018', '#1687a7', '#41584b', '#dd9866', '#16a596', '#649d66', '#7a4d1d', '#630B0B', '#FF5600', '#AF00A0', '#000000', '#290262', '#9D0233'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return val
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#1f441e', '#ff0000', '#440a67', '#C62700', '#ABC400', '#26001b', '#ff005c', '#21209c', '#04BC06', '#013C38', '#8f4f4f', '#435560', '#025955', '#8c0000', '#763857', '#28527a', '#413c69', '#484018', '#1687a7', '#41584b', '#dd9866', '#16a596', '#649d66', '#7a4d1d', '#630B0B', '#FF5600', '#AF00A0', '#000000', '#290262', '#9D0233']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        labels: {
        show: true,
                //                rotate: - 60,
                //                rotateAlways: true,
                hideOverlappingLabels: false,
                showDuplicates: true,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                return val;
                },
                format: undefined,
        },
                categories: [
<?php
if (!empty($totalDamegeQuantityArr)) {
    foreach ($totalDamegeQuantityArr as $productId => $productQuantity) {
        $productName = !empty($productId) && !empty($productList[$productId]) ? $productList[$productId] : '';
        echo '"' . $productName . '", ';
    }
}
?>
                ],
                title: {
                text: "<?php echo app('translator')->get('label.PRODUCT'); ?>",
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                        color: undefined,
                                fontSize: '11px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                },
        },
        yaxis: {
        title: {
        text: "<?php echo app('translator')->get('label.QUANTITY'); ?>",
                offsetX: 0,
                offsetY: 0,
                style: {
                color: undefined,
                        fontSize: '11px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 700,
                        cssClass: 'apexcharts-xaxis-title',
                },
        }
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        };
var topDamaged = new ApexCharts(document.querySelector("#topDamaged"), topDamagedOptions);
topDamaged.render();
//End :: Top 10 Most Returned & Damaged Products of the Year

var lastThirtyDaysSalesPerformanceOptions = {
chart: {
height: 400,
        type: 'line',
        shadow: {
        enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 1
        },
        toolbar: {
        show: false
        }
},
        colors:["#4C87B9", "#1BA39C", "#D91E18", "#8E44AD", "#F36A5A"],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return parseFloat(val).toFixed(2)
                },
                textAnchor: 'middle',
                distributed: false,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ["#4C87B9", "#1BA39C", "#D91E18", "#8E44AD", "#F36A5A"]
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        stroke: {
        curve: 'smooth'
        },
        series: [

        {
        name: "<?php echo app('translator')->get('label.PENDING'); ?>",
                data: [
<?php
if (!empty($productArr)) {
    foreach ($productArr as $productId => $productName) {
        $pendingAmount = !empty($last30DaysSalesArr['pending'][$productId]) ? $last30DaysSalesArr['pending'][$productId] : 0;
        ?>
                        "<?php echo e($pendingAmount); ?>",
        <?php
    }
}
?>
                ]
        },
        {
        name: "<?php echo app('translator')->get('label.DELIVERED'); ?>",
                data: [
<?php
if (!empty($productArr)) {
    foreach ($productArr as $productId => $productName) {
        $deliveredAmount = !empty($last30DaysSalesArr['delivered'][$productId]) ? $last30DaysSalesArr['delivered'][$productId] : 0;
        ?>
                        "<?php echo e($deliveredAmount); ?>",
        <?php
    }
}
?>
                ]
        },
        {
        name: "<?php echo app('translator')->get('label.CANCELLED'); ?>",
                data: [
<?php
if (!empty($productArr)) {
    foreach ($productArr as $productId => $productName) {
        $cancelledAmount = !empty($last30DaysSalesArr['cancelled'][$productId]) ? $last30DaysSalesArr['cancelled'][$productId] : 0;
        ?>
                        "<?php echo e($cancelledAmount); ?>",
        <?php
    }
}
?>
                ]
        }

        ],
        grid: {
        borderColor: '#e7e7e7',
                row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                },
        },
        markers: {

        size: 6
        },
        xaxis: {
        labels: {
        show: true,
                rotate: - 60,
                rotateAlways: true,
                hideOverlappingLabels: false,
                showDuplicates: true,
                trim: true,
                minHeight: 60,
                maxHeight: 120,
                format: undefined,
                formatter: undefined,
        },
                categories: [
<?php
if (!empty($productArr)) {
    foreach ($productArr as $productId => $productName) {
        echo '"' . $productName . '", ';
    }
}
?>
                ],
                title: {
                text: "<?php echo app('translator')->get('label.PRODUCTS'); ?>",
                        offsetX: 0,
                        offsetY: 20,
                        style: {
                        color: undefined,
                                fontSize: '12px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                },
        },
        yaxis: {
        title: {
        text: "<?php echo app('translator')->get('label.VOLUME'); ?> (<?php echo app('translator')->get('label.UNIT'); ?>)"
        }
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return parseFloat(val).toFixed(2) + " Unit"
        }
        }
        },
        legend: {
        position: 'bottom',
                horizontalAlign: 'center',
                offsetY: 0,
                offsetX: 0,
                width: undefined,
                height: 100,
        }
};
var lastThirtyDaysSalesPerformance = new ApexCharts(document.querySelector("#lastThirtyDaysSalesPerformance"), lastThirtyDaysSalesPerformanceOptions);
lastThirtyDaysSalesPerformance.render();
});
//** ** ** ** ** ** ** * MONTHLY ORDER STATE ** ** ** ** ** ** ** ** **
var monthlyOrderStateOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        },
        events: {
        click:function(event, chartContext, config) {
        var dateIndex = config.dataPointIndex;
//        $.ajax({
//        url: "<?php echo e(URL::to('dashboard/getMonthlyOrderState')); ?>",
//                type: "POST",
//                dataType: "json",
//                headers: {
//                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                },
//                data: {
//                date_index: dateIndex,
//                },
//                beforeSend: function () {
//                //                        App.blockUI({boxed: true});
//                },
//                success: function (res) {
//                $("#inquirySummaryModal").modal("show");
//                $("#showInquirySummary").html(res.html);
//                $('.tooltips').tooltip();
//                //table header fix
//                $(".table-head-fixer-color").tableHeadFixer();
//                },
//                error: function (jqXhr, ajaxOptions, thrownError) {
//                if (jqXhr.status == 400) {
//                var errorsHtml = '';
//                var errors = jqXhr.responseJSON.message;
//                $.each(errors, function (key, value) {
//                errorsHtml += '<li>' + value[0] + '</li>';
//                });
//                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
//                } else if (jqXhr.status == 401) {
//                toastr.error(jqXhr.responseJSON.message, '', options);
//                } else {
//                toastr.error('Error', 'Something went wrong', options);
//                }
//                //                        App.unblockUI();
//                }
//        }); //ajax
        }
        },
        },
        series: [{
        name: "<?php echo app('translator')->get('label.NUMBER_OF_ORDER'); ?>",
                data: [
<?php
if (!empty($monthlyOrderStateArr)) {
    foreach ($monthlyOrderStateArr as $item) {
        echo "'$item',";
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: [ '#E08283', '#C49F47', '#C8D046', '#7F6084', '#4B77BE', '#E35B5A', '#3598DC', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8877A9', '#555555'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return val;
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        title: {
        text: 'Date',
        },
                labels: {
                show: true,
                        rotate: - 60,
                        rotateAlways: true,
                },
                categories: [
<?php
if (!empty($monthlyOrderStateArr)) {
    foreach ($monthlyOrderStateArr as $date => $item) {
        $date = date("d M Y", strtotime($date));
        echo "'$date', ";
    }
}
?>

                ],
        },
        yaxis: {
        title: {
        text: "<?php echo app('translator')->get('label.NUMBER_OF_ORDER'); ?>"
        },
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return  val;
        }
        }
        }
};
var monthlyOrderState = new ApexCharts(document.querySelector("#monthlyOrderState"), monthlyOrderStateOptions);
monthlyOrderState.render();
//****************** END OF MONTHLY ORDER STATE ************
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>