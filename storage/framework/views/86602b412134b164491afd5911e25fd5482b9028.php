<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-th-list"></i><?php echo app('translator')->get('label.PENDING_ORDER_LIST'); ?>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            <?php echo Form::open(array('group' => 'form', 'url' => '/admin/processingOrder/filter','class' => 'form-horizontal')); ?>

            <?php echo Form::hidden('page', Helper::queryPageStr($qpArr)); ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="fromDate"><?php echo app('translator')->get('label.FROM_DATE'); ?> :</label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                <?php echo Form::text('from_date', Request::get('from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']); ?> 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="fromDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="toDate"><?php echo app('translator')->get('label.TO_DATE'); ?> </label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                <?php echo Form::text('to_date', Request::get('to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']); ?> 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="toDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="orderNo"><?php echo app('translator')->get('label.ORDER_NO'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('order_no', $orderNoList, Request::get('order_no'), ['class' => 'form-control js-source-states','id'=>'orderNo']); ?>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="orderNo"><?php echo app('translator')->get('label.RETAILER'); ?>:</label>
                        <div class="col-md-8">
                            <?php echo Form::select('retailer_id', $retailerList, Request::get('retailer_id'), ['class' => 'form-control js-source-states','id'=>'retailerId']); ?>

                        </div>
                    </div>
                </div>
                <?php if(Auth::user()->group_id != 14): ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="srId"><?php echo app('translator')->get('label.SR'); ?>:</label>
                        <div class="col-md-8">
                            <?php echo Form::select('sr_id', $srList, Request::get('sr_id'), ['class' => 'form-control js-source-states','id'=>'srId']); ?>

                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <div class="col-md-4 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> <?php echo app('translator')->get('label.FILTER'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

            <!-- End Filter -->


            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.ORDER_NO'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.RETAILER'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.SR'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.PRODUCT'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.BRAND'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.SKU'); ?></th>
                            <th class="vcenter text-center"><?php echo app('translator')->get('label.ORDER_QTY'); ?></th>
                            <th class="vcenter text-center"><?php echo app('translator')->get('label.PRICE'); ?></th>
                            <th class="vcenter text-center"><?php echo app('translator')->get('label.STOCK'); ?></th>
                            <th class="vcenter text-center"><?php echo app('translator')->get('label.DELIVERED_QUANTITY'); ?></th>
                            <th class="vcenter text-center"><?php echo app('translator')->get('label.DUE_QTY'); ?></th>
                            <th class="vcenter text-center"><?php echo app('translator')->get('label.AVAILABLE_QTY'); ?></th>
                            <th class="vcenter text-center"><?php echo app('translator')->get('label.TOTAL_PRICE'); ?></th>
                            <th class="vcenter text-center"><?php echo app('translator')->get('label.TOTAL_PAYING_AMOUNT'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.CREATION_DATE'); ?></th>
                            <?php if(!empty($userAccessArr[103][5])): ?>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.DELIVERY_DETAILS'); ?></th>
                            <?php endif; ?>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.ACTION'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!$targetArr->isEmpty()): ?>
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>

                        <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <?php
                            $order = !empty($data->order_id) && !empty($orderArr[$data->order_id]) ? $orderArr[$data->order_id] : 0;
                            $freezeStockArr = Common::getFreezeStock($data->warehouse_id, $data->order_id);
                            ?>
                            <td class="text-center vcenter" rowspan="<?php echo e(!empty($order['products']) ? sizeof($order['products']) : 1); ?>"><?php echo ++$sl; ?></td>
                            <td class="vcenter" rowspan="<?php echo e(!empty($order['products']) ? sizeof($order['products']) : 1); ?>"><?php echo e($order['order_no']); ?></td>
                            <td class="vcenter" rowspan="<?php echo e(!empty($order['products']) ? sizeof($order['products']) : 1); ?>"><?php echo e($order['retailer_name']); ?></td>
                            <td class="vcenter" rowspan="<?php echo e(!empty($order['products']) ? sizeof($order['products']) : 1); ?>"><?php echo e($order['user_name']); ?></td>

                            <?php if(!empty($order['products'])): ?>
                            <?php $i = 0; ?>
                            <?php $__currentLoopData = $order['products']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detailsId => $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            if ($i > 0) {
                                echo '<tr>';
                            }

//                            $deliveryDetailsArr = Common::getDeliveredSku($data->order_id);
//                            $dueQty = 0;
//                            $deliveredQty = 0;
//                            if (!empty($deliveryDetailsArr[$details['sku_id']])) {
//                                $deliveredQty = $deliveryDetailsArr[$details['sku_id']];
//                                $dueQty = $details['available_quantity'] - $deliveryDetailsArr[$details['sku_id']];
//                            }
                            ?>

                            <td class="vcenter"> <?php echo e($details['product_name']); ?> </td>
                            <td class="vcenter"> <?php echo e($details['brand_name']); ?> </td>
                            <td class="vcenter"> <?php echo e($details['sku']); ?> </td>
                            <td class="vcenter text-right"> <?php echo e($details['quantity']); ?> </td>
                            <td class="vcenter text-right"> <?php echo e($details['unit_price']); ?>&nbsp;<?php echo app('translator')->get('label.TK'); ?> </td>
                            <?php
                            $freezeStock = !empty($freezeStockArr[$details['sku_id']]) ? $freezeStockArr[$details['sku_id']] : 0;
                            $availableQty = !empty($details['available_quantity']) ? $details['available_quantity'] : 0;
                            $availabelStock = $availableQty - $freezeStock;
                            
                            $text = 'red-thunderbird';
                            if (!empty($details['quantity']) && !empty($details['available_quantity'])) {
                                if ($details['quantity'] <= $details['available_quantity']) {
                                    $text = 'green-steel';
                                }
                            }
                            $dueQty = 0;
                            $deliveredQty = 0;
                            if ($data->order_id == isset($deliveryDetailsArr[$data->order_id])) {
                                (int) $deliveredQty = $deliveryDetailsArr[$data->order_id][$details['sku_id']];
                                (int) $dueQty = $details['quantity'] - $deliveryDetailsArr[$data->order_id][$details['sku_id']];
                            } else {
                                $dueQty = $details['quantity'] - $dueQty;
                            }
                            
                            $text2 = 'red-thunderbird';
                            if (!empty($details['quantity']) && !empty($availabelStock)) {
                                if ($details['quantity'] <= $availabelStock) {
                                    $text2 = 'green-steel';
                                }
                                if ($availabelStock <= $dueQty) {
                                    $text2 = 'green-steel';
                                }
                            }
                            ?>
                            <td class="vcenter text-right text-<?php echo e($text); ?>">
                                <?php echo e(!empty($details['available_quantity']) ? number_format($details['available_quantity'], 0) : 0); ?> 
                            </td>
                            <td class="vcenter text-center"> 
                                <?php echo e(number_format($deliveredQty)); ?> 
                            </td>
                            <td class="vcenter text-center"> 
                                <?php echo e(number_format($dueQty)); ?>

                            </td>
                            <td class="vcenter text-right text-<?php echo e($text2); ?>"> 
                                <?php echo e(!empty($availabelStock) ? number_format($availabelStock, 0) : 0); ?> 
                            </td>
                            <td class="vcenter text-right"> <?php echo e($details['total_price']); ?>&nbsp;<?php echo app('translator')->get('label.TK'); ?> </td>

                            <?php if($i == 0): ?>
                            <td class="vcenter text-right" rowspan="<?php echo e(!empty($order['products']) ? sizeof($order['products']) : 1); ?>"> <?php echo e($order['grand_total']); ?>&nbsp;<?php echo app('translator')->get('label.TK'); ?> </td>

                            <td class="text-center vcenter" rowspan="<?php echo e(!empty($order['products']) ? sizeof($order['products']) : 1); ?>">
                                <?php echo e(!empty($order['created_at']) ? Helper::formatDate($order['created_at']) : ''); ?>

                            </td>
                            <?php if(!empty($userAccessArr[103][5])): ?>
                            <td class="text-center vcenter" rowspan="<?php echo e(!empty($order['products']) ? sizeof($order['products']) : 1); ?>">

                                <?php if(!empty($deliveryArr[$order['order_id']])): ?>
                                <?php $__currentLoopData = $deliveryArr[$order['order_id']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliveryId => $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button  type="button" class="btn btn-xs green-seagreen btn-circle btn-rounded tooltips vcenter delivery-details" 
                                         href="#deliveryDetails"  data-toggle="modal" data-orderId ="<?php echo e($delivery['order_id']); ?>" 
                                         data-deliveryId="<?php echo e($delivery['delivery_id']); ?>" data-html="true" 
                                         title="
                                         <div class='text-left'>
                                         <?php echo app('translator')->get('label.BL_NO'); ?>: &nbsp;<?php echo $delivery['bl_no']; ?><br/>
                                         <?php echo app('translator')->get('label.PAYMENT_STATUS'); ?>: &nbsp;<?php echo $delivery['payment_status']; ?><br/>
                                         <?php echo app('translator')->get('label.PAYMENT_MODE'); ?>: &nbsp;<?php echo $delivery['payment_mode']; ?><br/>
                                         <!--<?php echo app('translator')->get('label.CLICK_TO_SEE_DETAILS'); ?>-->
                                         </div>
                                         " 
                                         >
                                    <i class="fa fa-truck"></i>
                                </button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <button type="button" class="btn btn-xs cursor-default btn-circle btn-rounded-flat red-soft tooltips vcenter" title="<?php echo app('translator')->get('label.NO_SHIPMENT_YET'); ?>">
                                    <i class="fa fa-minus"></i>
                                </button>
                                <?php endif; ?>

                            </td>
                            <?php endif; ?>
                            <td class="td-actions text-center vcenter " rowspan="<?php echo e(!empty($order['products']) ? sizeof($order['products']) : 1); ?>">
                                <div class="width-inherit">

                                    <?php if(!empty($userAccessArr[103][14])): ?>
                                    <?php if(!empty($deliveryArr)): ?>
                                    <?php if( array_key_exists($order['order_id'],$deliveryArr) ): ?>
                                    <button class="btn btn-xs tooltips green-jungle vcenter mark-delivered-btn" data-id="<?php echo $order['order_id']; ?>"  title="<?php echo app('translator')->get('label.MARK_AS_DELIVERED'); ?>">
                                        <i class="fa fa-check"></i>
                                    </button>

                                    <?php endif; ?>
                                    <?php endif; ?>
                                    <?php endif; ?>


                                    <?php if(!empty($userAccessArr[103][5])): ?>


                                    <button class="btn btn-xs blue-sharp tooltips vcenter view-order-details" data-id="<?php echo $order['order_id']; ?>" href="#modalViewStockDemand"  data-toggle="modal" title="<?php echo app('translator')->get('label.VIEW_STOCK_DEMAND'); ?>">
                                        <!--<i class="fa fa-file-text-o"></i>-->
                                        <i class="fa fa-info-circle"></i>
                                    </button>
                                    <?php endif; ?>
                                    <?php if(in_array(Auth::user()->group_id, [12])): ?>
                                    <?php if(!empty($userAccessArr[103][16])): ?>
                                    <!-- <a class="btn btn-xs purple-soft tooltips vcenter" href="<?php echo e(URL::to('admin/processingOrder/' . $order['order_id'] . '/getSetDelivery')); ?>"  title="<?php echo app('translator')->get('label.SET_DELIVERY'); ?>">
                                        <i class="fa fa-cart-plus"></i>
                                    </a>-->


                                    <?php
                                    $btnClass = 'cursor-default grey-mint';
                                    $btnTitle = __('label.UNABLE_TO_SET_DELIVERY_DUE_TO_INSUFFICIENT_STOCK');
                                    $btnHref = '';
                                    $btnClass = 'purple-soft delivery-information';
                                    $btnTitle = __('label.SET_DELIVERY');
                                    $btnHref = 'href=#modalDeliveryInformation';
                                    if (!empty($details['quantity']) && !empty($details['available_quantity'])) {
                                        if ($details['quantity'] <= $details['available_quantity']) {
                                            
                                        }
                                    }
                                    ?>



                                    <button class="btn btn-xs <?php echo e($btnClass); ?> tooltips vcenter" data-id="<?php echo $order['order_id']; ?>" <?php echo e($btnHref); ?>  data-toggle="modal"  title="<?php echo e($btnTitle); ?>">
                                        <i class="fa fa-cart-plus"></i>
                                    </button>
                                    <?php endif; ?>
                                    <?php if(!empty($userAccessArr[103][13])): ?>
                                    <button class="btn btn-xs tooltips vcenter red-soft cancel-order" data-id="<?php echo $order['order_id']; ?>" data-flag='8' data-placement="top" data-rel="tooltip" title="<?php echo app('translator')->get('label.CANCEL_ORDER'); ?>">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </div>

                            </td>
                            <?php endif; ?>

                            <?php
                            if ($i < (sizeof($order['products']) - 1)) {
                                echo '</tr>';
                            }
                            $i++;
                            ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="18" class="vcenter"><?php echo app('translator')->get('label.NO_PROCESSING_ORDER_FOUND'); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo $__env->make('layouts.paginator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        </div>

    </div>
</div>
<div class="modal fade" id="modalProductReturn" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showProductReturn">

        </div>
    </div>
</div>
<!--view stock and demand modal-->
<div class="modal fade" id="modalViewStockDemand" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showViewStockDemand">
        </div>
    </div>
</div>
<div class="modal fade" id="deliveryDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showDeliveryDetails">
        </div>
    </div>
</div>

<!-- START:: Show Order Information Form-->
<div class="modal fade" id="modalDeliveryInformation" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showDeliveryInformation"></div>
    </div>
</div>
<!-- END:: Show Order Information Form -->

<script type="text/javascript">

    $(document).ready(function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null
        };


        $(document).on('click', '.product-return', function () {

            var orderId = $(this).attr("data-id");
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "<?php echo e(URL::to('admin/processingOrder/getProductReturn')); ?>",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    order_id: orderId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showProductReturn').html(res.html);
                    App.unblockUI();
                },
            });
        });

        $(document).on("click", "#submitReturnSave", function () {
            swal({
                title: "Are you sure?",
                text: "<?php echo app('translator')->get('label.DO_YOU_WANT_TO_CONTINUE_IT'); ?>",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<?php echo app('translator')->get('label.YES_CONTINUE_IT'); ?>",
                closeOnConfirm: true,
                closeOnCancel: true,
            }, function (isConfirm) {
                if (isConfirm) {
                    var options = {
                        closeButton: true,
                        debug: false,
                        positionClass: "toast-bottom-right",
                        onclick: null,
                    };

                    var formData = new FormData($("#productReturnForm")[0]);
                    $.ajax({
                        url: "<?php echo e(URL::to('/admin/processingOrder/setProductReturn')); ?>",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function () {
                            App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            setTimeout(window.location.replace('<?php echo e(URL::to("/admin/processingOrder")); ?>'), 1000);
                            App.unblockUI();
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value[0] + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', "<?php echo app('translator')->get('label.SOMETHING_WENT_WRONG'); ?>", options);
                            }
                            App.unblockUI();
                        }
                    }); //ajax
                }
            });
        });

        $(document).on("click", ".cancel-order", function () {
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null
            };
            var id = $(this).attr("data-id");
            var flag = $(this).attr("data-flag");
            swal({
                title: "Are you sure, you want cancel this order?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, cancel It",
                closeOnConfirm: true,
                closeOnCancel: true,
            }, function (isConfirm) {
                if (isConfirm) {
                    if (id) {
                        $.ajax({
                            url: "<?php echo e(URL::to('admin/processingOrder/cancel')); ?>",
                            type: 'POST',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                id: id,
                                status: flag,
                            },
                            success: function (res) {
                                toastr.success(res.data, res.message, options);
                                location.reload();
                            },
                            error: function (jqXhr, ajaxOptions, thrownError) {

                                if (jqXhr.status == 401) {
                                    toastr.error(jqXhr.responseJSON.message, '', options);
                                } else {
                                    toastr.error('Error', 'Something went wrong', options);
                                }
                            }
                        });
                    }
                }
            });
        });
        $(document).on("click", ".mark-as-delivered", function () {
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null
            };
            var id = $(this).attr("data-id");
            var flag = $(this).attr("data-flag");
            if (id) {
                $.ajax({
                    url: "<?php echo e(URL::to('admin/processingOrder/confirmDelivery')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id,
                        status: flag,
                    },
                    success: function (res) {
                        toastr.success(res.data, res.message, options);
                        location.reload();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {

                        if (jqXhr.status == 401) {
                            toastr.error(jqXhr.responseJSON.message, '', options);
                        } else {
                            toastr.error('Error', 'Something went wrong', options);
                        }
                    }
                });
            }
        });

        $(document).on("click", ".view-order-details", function (e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo e(URL::to('/admin/processingOrder/viewStockDemand')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id
                },
                beforeSend: function () {
                    $("#showViewStockDemand").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showViewStockDemand").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        // *************** START:: Show Delivery Information Modal ***************//
        $(document).on("click", ".delivery-information", function (e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo e(URL::to('/admin/processingOrder/getSetDelivery')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id
                },
                beforeSend: function () {
                    $("#showDeliveryInformation").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showDeliveryInformation").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax


        });
        // *************** END:: Show Delivery Information Modal ***************//

        // ***************** START:: Show Payment Information ******************//

        $(document).on("click", "#confirmProceed", function (e) {
            e.preventDefault();
            var bilNo = $(".bl-no").val();
            var grandTotalPrice = $("grandTotalPrice").val();
            if (typeof bilNo === 'undefined' || bilNo === '') {
                toastr.error("Please, Enter Delivery Challan No!", "Error", options);
                return false;
            }
            if (grandTotalPrice === "" || grandTotalPrice === 0) {
                toastr.error("Invalid Delivery Challan quantity!", "Error", options);
                return false;
            }

            var deliveryFromData = new FormData($("#setDeliveryForm")[0]);

            $.ajax({
                url: "<?php echo e(URL::to('admin/showPaymentInfo')); ?>",
                type: "POST",
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: deliveryFromData,
                beforeSend: function () {
                    $("#showDeliveryInformation").html('<strong><?php echo app('translator')->get("label.LOADING_PLEASE_WAIT"); ?></strong>');
                    $("#confirmProceed").prop('disabled', true);
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $("#showDeliveryInformation").fadeIn('slow');
                    $("#showDeliveryInformation").html(res.html);
                    $("#confirmProceed").prop('disabled', false);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $("#confirmProceed").prop('disabled', true);
                    App.unblockUI();
                }
            }); //ajax
        });

        $(document).on("click", "#confirmPayment", function (e) {
            e.preventDefault();
            var orderId = $(this).attr("data-id");
            var paymentMode = $('input[name=payment_mode]:checked').attr('data-val');
            var deliveryFromData = new FormData($("#setInformationForm")[0]);
            if (typeof paymentMode === 'undefined' || paymentMode === '') {
                toastr.error("Please, Select Payment Mode", "Error", options);
                return false;
            }
            $.ajax({
                url: "<?php echo e(URL::to('admin/confirmDelivery')); ?>",
                type: "POST",
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: deliveryFromData,
                beforeSend: function () {
                    $("#showDeliveryInformation").html('<strong><?php echo app('translator')->get("label.LOADING_PLEASE_WAIT"); ?></strong>');
                    /*App.blockUI({
                     boxed: true
                     });*/
                },
                success: function (res) {
                    $("#showDeliveryInformation").fadeIn('slow');
                    $("#showDeliveryInformation").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax


        });

        $(document).on("click", "#confirmDelivery", function (e) {
            swal({
                title: "Are you sure?",
                text: "<?php echo app('translator')->get('label.DO_YOU_WANT_TO_CONTINUE_IT'); ?>",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<?php echo app('translator')->get('label.YES_CONTINUE_IT'); ?>",
                closeOnConfirm: true,
                closeOnCancel: true,
            }, function (isConfirm) {
                if (isConfirm) {
                    var options = {
                        closeButton: true,
                        debug: false,
                        positionClass: "toast-bottom-right",
                        onclick: null,
                    };

                    var formData = new FormData($("#saveDeliveryForm")[0]);
                    $.ajax({
                        url: "<?php echo e(URL::to('/admin/processingOrder/saveSetDelivery')); ?>",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function () {
                            $('#confirmDeliveryLoading').append('<strong><?php echo app('translator')->get("label.ORDER_STATUS_UPDATE_IN_PROGRESS"); ?></strong>');
                            App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            setTimeout(window.location.replace('<?php echo e(URL::to("/admin/processingOrder")); ?>'), 1000);
                            App.unblockUI();
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value[0] + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', "<?php echo app('translator')->get('label.SOMETHING_WENT_WRONG'); ?>", options);
                            }
                            $('#confirmDeliveryLoading').html('');
                            App.unblockUI();
                        }

                    }); //ajax
                }
            });
        });

        // START:: Mark as Delivered 
        $(document).on('click', ".mark-delivered-btn", function (e) {
            e.preventDefault();
            var dataId = $(this).attr("data-id");
            swal({
                title: "Are you sure?",
                text: "<?php echo app('translator')->get('label.DO_YOU_WANT_TO_CONTINUE_IT'); ?>",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e73310",
                confirmButtonText: "<?php echo app('translator')->get('label.MARK_AS_DELIVERED'); ?>",
                closeOnConfirm: true,
                closeOnCancel: true,
            }, function (isConfirm) {
                if (isConfirm) {
                    var options = {
                        closeButton: true,
                        debug: false,
                        positionClass: "toast-bottom-right",
                        onclick: null,
                    };

                    $.ajax({
                        url: "<?php echo e(URL::to('admin/processingOrder/markAsDelivered')); ?>",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        data: {id: dataId},
                        beforeSend: function () {
                            $('#confirmDeliveryLoading').append('<strong><?php echo app('translator')->get("label.ORDER_STATUS_UPDATE_IN_PROGRESS"); ?></strong>');
                            App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            setTimeout(window.location.replace('<?php echo e(URL::to("/admin/processingOrder")); ?>'), 1000);
                            App.unblockUI();
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value[0] + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', "<?php echo app('translator')->get('label.SOMETHING_WENT_WRONG'); ?>", options);
                            }
                            $('#confirmDeliveryLoading').html('');
                            App.unblockUI();
                        }

                    }); //ajax
                }
            });
        });
        // END:: Mark as Delivered 

        // START:: Delivery Modal
        $(document).on('click', ".delivery-details", function (e) {
            e.preventDefault();
            var orderId = $(this).attr("data-orderId");
            var deliveryId = $(this).attr("data-deliveryId");

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            $.ajax({
                url: "<?php echo e(URL::to('admin/processingOrder/getDeliveryDetails')); ?>",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json', // what to expect back from the PHP script, if anything
                data: {
                    delivery_id: deliveryId,
                    order_id: orderId,
                },
                beforeSend: function () {
                    $('#showDeliveryDetails').html();
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showDeliveryDetails').html(res.html)
//                    setTimeout(window.location.replace('<?php echo e(URL::to("/admin/processingOrder")); ?>'), 1000);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', "<?php echo app('translator')->get('label.SOMETHING_WENT_WRONG'); ?>", options);
                    }
                    $('#confirmDeliveryLoading').html('');
                    App.unblockUI();
                }

            }); //ajax
        });
        // END:: Delivery Modal

    });



    // ***************** END:: Show Payment Information ******************//
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/processingOrder/index.blade.php ENDPATH**/ ?>