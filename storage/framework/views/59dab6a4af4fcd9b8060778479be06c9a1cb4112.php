<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i><?php echo app('translator')->get('label.TRANSFERRED_PRODUCT_LIST'); ?>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                <?php echo Form::open(array('group' => 'form', 'url' => 'admin/stockTransferList/filter','class' => 'form-horizontal')); ?>

                <div class="row">
                    <div class="col-md-12 margin-bottom-20">
                        <div class="col-md-3">
                            <div class="form">
                                <label class="control-label" for="refNo"><?php echo app('translator')->get('label.REFERENCE_NO'); ?></label>
                                <div>
                                    <?php echo Form::text('ref_no',  Request::get('ref_no'), ['class' => 'form-control tooltips', 'title' => 'Reference', 'placeholder' => 'Reference','list'=>'refNo', 'autocomplete'=>'off']); ?>

                                    <datalist id="refNo">
                                        <?php if(!empty($refNoArr)): ?>
                                        <?php $__currentLoopData = $refNoArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $refNo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($refNo->reference_no); ?>"></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </datalist>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form">
                                <label class="control-label"><?php echo app('translator')->get('label.TRANSFER_DATE'); ?> :</label>
                                <div class="input-group date datepicker2">
                                    <?php echo Form::text('transfer_date', Request::get('Transfer_date'), ['id'=> 'checkoutDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']); ?> 
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="checkoutDate">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form">
                                <label class="control-label" for="warehouse"><?php echo app('translator')->get('label.WAREHOUSE'); ?>:</label>
                                <?php echo Form::select('warehouse_id', $warehouseList, Request::get('warehouse_id'), ['class' => 'form-control js-source-states', 'id' => 'warehouse']); ?>

                                <!--<div id="displayProductHints"></div>-->
                            </div>
                        </div>

                        <div class="col-md-3 margin-top-20">
                            <div class="form">
                                <label class="control-label">&nbsp;</label>
                                <button type="submit" class="btn btn-md green btn-outline filter-submit">
                                    <i class="fa fa-search"></i> <?php echo app('translator')->get('label.FILTER'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo Form::close(); ?>

                <!-- End Filter -->
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th class="text-center"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th><?php echo app('translator')->get('label.REFERENCE_NO'); ?></th>
                            <th><?php echo app('translator')->get('label.TRANSFER_DATE'); ?></th>
                            <th class="text-center"><?php echo app('translator')->get('label.WAREHOUSE'); ?></th>
                            <th><?php echo app('translator')->get('label.TRANSFER_BY'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.TRANSFER_AT'); ?></th>
                            <th class="text-center"><?php echo app('translator')->get('label.STATUS'); ?></th>
                            <th class="text-center"><?php echo app('translator')->get('label.ACTION'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!$targetArr->isEmpty()): ?>
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center"><?php echo ++$sl; ?></td>
                            <td><?php echo $target->reference_no ?? ''; ?></td>
                            <td><?php echo !empty($target->transfer_date) ? Helper::formatDate($target->transfer_date) : ''; ?></td>
                            <td class="text-center"><?php echo $target->warehouse_name ?? ''; ?></td>
                            <td>
                                <?php echo $target->name ?? ''; ?>

                            </td>
                            <td>
                                <?php echo !empty($target->created_at) ? Helper::formatDateTime($target->created_at) : ''; ?>

                            </td>
                            <td class="vcenter text-center">
                                <?php if($target->approval_status == '0'): ?>
                                <span  class="label label-sm label-warning"><?php echo app('translator')->get('label.PENDING'); ?></span>
                                <?php elseif($target->approval_status == '1'): ?>
                                <span class="label label-sm label-success"><?php echo app('translator')->get('label.APPROVED'); ?></span>
                                <?php else: ?>
                                <span class="label label-sm label-danger"><?php echo app('translator')->get('label.DENIED'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">

                                <?php if(in_array(Auth::user()->group_id, [1, 11]) || (Auth::user()->group_id == 12 && !empty($warehouseIdList[$target->warehouse_id]))): ?>
                                <?php if($target->approval_status == '0'): ?>
                                <?php if(!empty($userAccessArr[46][25])): ?>
                                <button type="button" class="btn green-sharp btn-xs tooltips approve" data-status="1" title="<?php echo e(__('label.CLICK_HERE_TO_APPROVE')); ?>"  data-id="<?php echo e($target->id); ?>">
                                    <i class="fa fa-check"></i>
                                </button>
                                <?php endif; ?>
                                <?php if(!empty($userAccessArr[46][26])): ?>
                                <button type="button" class="btn red-mint btn-xs tooltips deny" data-status="0" title="<?php echo e(__('label.CLICK_HERE_TO_DENY')); ?>" data-id="<?php echo e($target->id); ?>">
                                    <i class="fa fa-ban"></i>
                                </button>
                                <?php endif; ?>
                                <?php endif; ?>
                                <?php endif; ?>
                                <?php if(!empty($userAccessArr[46][5])): ?>
                                <button type="button" class="btn yellow btn-xs tooltips details-btn" title="<?php echo app('translator')->get('label.VIEW_PRODUCT_DETAILS'); ?>" id="detailsBtn-<?php echo e($target->reference_no); ?>" data-target="#productDetails" data-toggle="modal" data-id="<?php echo e($target->id); ?>">
                                    <i class="fa fa-navicon text-white"></i>
                                </button>
                                <?php endif; ?>
                            </td>

                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="8"><?php echo app('translator')->get('label.NO_TRANSFERED_ITEM_FOUND'); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo $__env->make('layouts.paginator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</div>

<!-- details modal -->

<div class="modal fade" id="productDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showProductDetails">

        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).on('click', '.details-btn', function () {

        var transferId = $(this).attr("data-id");
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        $.ajax({
            url: "<?php echo e(URL::to('admin/stockTransferList/getProductDetails')); ?>",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                transfer_id: transferId
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#showProductDetails').html(res.html);
                App.unblockUI();
            },
        });
    });
    $(document).on("click", ".approve", function (e) {
        e.preventDefault();
        var productTransferMasterId = $(this).attr("data-id");
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        swal({
            title: 'Are you sure you want to approve this transfer?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, Approve',
            cancelButtonText: 'No, Cancel',
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "<?php echo e(URL::to('admin/stockTransferList/approve')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        product_transfer_master_Id: productTransferMasterId,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
                        location.reload();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {

                        if (jqXhr.status == 400) {
                            var errorsHtml = '';
                            var errors = jqXhr.responseJSON.message;
                            $.each(errors, function (key, value) {
                                errorsHtml += '<li>' + value + '</li>';
                            });
                            toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                        } else if (jqXhr.status == 401) {
                            toastr.error(jqXhr.responseJSON.message, '', options);
                        } else {
                            toastr.error('Error', 'Something went wrong', options);
                        }
                        App.unblockUI();
                    }
                });
            }
        });
    });
    $(document).on("click", ".deny", function (e) {
        e.preventDefault();
        var productTransferMasterId = $(this).attr("data-id");
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        swal({
            title: 'Are you sure you want to deny this transfer?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, Deny',
            cancelButtonText: 'No, Cancel',
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "<?php echo e(URL::to('admin/stockTransferList/deny')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        product_transfer_master_Id: productTransferMasterId,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
                        location.reload();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {

                        if (jqXhr.status == 400) {
                            var errorsHtml = '';
                            var errors = jqXhr.responseJSON.message;
                            $.each(errors, function (key, value) {
                                errorsHtml += '<li>' + value + '</li>';
                            });
                            toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                        } else if (jqXhr.status == 401) {
                            toastr.error(jqXhr.responseJSON.message, '', options);
                        } else {
                            toastr.error('Error', 'Something went wrong', options);
                        }
                        App.unblockUI();
                    }
                });
            }
        });
    });

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/productTransfer/transferList.blade.php ENDPATH**/ ?>