
<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i><?php echo app('translator')->get('label.RETAILER_DISTRIBUTOR_PAYMENT_DUE_REPORT'); ?>
            </div>
            <div class="actions">
                <span class="text-right">
                    <?php if(!empty($request->generate) && $request->generate == 'true'): ?>
                    <?php if(!empty($retailerList)): ?>
                    <?php if(!empty($userAccessArr[140][6])): ?>
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="<?php echo e(URL::to($request->fullUrl().'&view=print')); ?>"  title="<?php echo app('translator')->get('label.PRINT'); ?>">
                        <i class="fa fa-print"></i>
                    </a>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[140][9])): ?>
                    <a class="btn btn-sm btn-inline green-seagreen tooltips vcenter" target="_blank" href="<?php echo e(URL::to($request->fullUrl().'&view=pdf')); ?>"  title="<?php echo app('translator')->get('label.DOWNLOAD'); ?>">
                        <i class="fa fa-file-pdf-o"></i>
                    </a>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php endif; ?>
                </span>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            <?php echo Form::open(array('group' => 'form', 'url' => 'admin/paymentDueByRetailerReport/filter','class' => 'form-horizontal')); ?>

            <?php echo Form::hidden('page', Helper::queryPageStr($qpArr)); ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="fromDate"><?php echo app('translator')->get('label.FROM_DATE'); ?> :</label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                <?php echo Form::text('from_date', Request::get('from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off', 'style' => 'min-width:150px']); ?> 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="fromDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger"><?php echo e($errors->first('from_date')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="toDate"><?php echo app('translator')->get('label.TO_DATE'); ?> :</label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                <?php echo Form::text('to_date', Request::get('to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off', 'style' => 'min-width:150px']); ?> 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="toDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger"><?php echo e($errors->first('to_date')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="form">
                            <button type="submit" class="btn btn-md green btn-outline filter-submit">
                                <?php echo app('translator')->get('label.GENERATE'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <?php echo Form::close(); ?>

            <!-- End Filter -->
            <?php if(Request::get('generate') == 'true'): ?>
            <div class="row margin-top-20">

                <div class="col-md-12">
                    <div style="max-height: 500px;" class="tableFixHead sample webkit-scrollbar">
                        <table class="table table-bordered table-striped table-head-fixer-color " id="dataTable">
                            <thead>
                                <tr class="blue-light">

                                    <th class="text-center vcenter bold"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                    <th class="vcenter text-center bold"><?php echo app('translator')->get('label.RETAILER'); ?></th>
                                    <th class="text-center vcenter bold"><?php echo app('translator')->get('label.TOTAL_INVOICED_AMOUNT'); ?></th>
                                    <th class="text-center vcenter bold"><?php echo app('translator')->get('label.TOTAL_RECEIVED'); ?></th>
                                    <th class="text-center vcenter bold"><?php echo app('translator')->get('label.TOTAL_DUE'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($retailerList)): ?>
                                <?php
                                $sl = 0;
                                ?>
                                <?php $__currentLoopData = $retailerList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rtlId => $rtlName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $totalReceived = $receivedArr[$rtlId] ?? 0;
                                $totalInvoice = $invoiceArr[$rtlId] ?? 0;
                                $dueAmount = $totalInvoice - $totalReceived;
                                ?>
                                <tr>
                                    <td class="vcenter text-center"><?php echo e(++$sl); ?></td>
                                    <td class="vcenter text-left "><?php echo e($rtlName); ?></td>
                                    <td class="vcenter text-right"> <?php echo e(Helper::numberFormat2Digit($invoiceArr[$rtlId] ?? '')); ?>&nbsp;<?php echo app('translator')->get('label.TK'); ?> </td>
                                    <td class="vcenter text-right"> <?php echo e(Helper::numberFormat2Digit($receivedArr[$rtlId] ?? '')); ?>&nbsp;<?php echo app('translator')->get('label.TK'); ?> </td>
                                    <td class="vcenter text-right"> <?php echo e(Helper::numberFormat2Digit( $dueAmount )); ?>&nbsp;<?php echo app('translator')->get('label.TK'); ?> </td>

                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="8"><?php echo app('translator')->get('label.NO_PRODUCT_TYPE_FOUND'); ?></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>	
    </div>
</div>
<!-- Modal start -->
<script type="text/javascript">
    $(function () {
        //table header fix
        $("#dataTable").tableHeadFixer();

//        $('.sample').floatingScrollbar();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/report/paymentDueByRetailer/index.blade.php ENDPATH**/ ?>