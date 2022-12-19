<div class="row margin-top-20">
    <div class="col-md-12">
        <div class="table-responsive webkit-scrollbar max-height-500">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr class="active">
                        <th class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                        <th class="vcenter" rowspan="2" colspan="2"><?php echo app('translator')->get('label.INVOICE_NO'); ?></th>
                        <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.ORDER_NO'); ?></th>
                        <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.BL_NO'); ?></th>
                        <th class="vcenter text-center" colspan="5"><?php echo app('translator')->get('label.INVOICED_PAYMENT'); ?></th>
                    </tr>
                    <tr class="active">
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.NET_RECEIVABLE'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.RECEIVED'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.DUE'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.TRANSACTION_ID'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.COLLECTION_AMOUNT'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($invoiceDetailsArr)): ?>
                    <?php $sl = 0; ?>
                    <?php $__currentLoopData = $invoiceDetailsArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoiceId => $invoiceDetails): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center vcenter" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>"><?php echo ++$sl; ?></td>
                        <td class="text-center vcenter" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>">
                            <div class="md-checkbox has-success">
                                <?php echo Form::checkbox('full_pay['.$invoiceId.']',1,false, ['id' => 'fullPay_'.$invoiceId, 'data-id' => $invoiceId, 'class'=> 'md-check full-pay-check']); ?>

                                <label for="<?php echo 'fullPay_'.$invoiceId; ?>">
                                    <span class="inc checkbox-text-center tooltips" title="<?php echo app('translator')->get('label.TICK_TO_PAY_IN_FULL'); ?>"></span>
                                    <span class="check mark-caheck checkbox-text-center tooltips" title="<?php echo app('translator')->get('label.TICK_TO_PAY_IN_FULL'); ?>"></span>
                                    <span class="box mark-caheck checkbox-text-center tooltips" title="<?php echo app('translator')->get('label.TICK_TO_PAY_IN_FULL'); ?>"></span>
                                </label>
                            </div>
                        </td>
                        <td class="vcenter" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>"><?php echo $invoiceDetails['invoice_no']; ?></td>
                        <td class="vcenter" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>"><?php echo $invoiceDetails['order_no']; ?></td>
                        <td class="vcenter" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>"><?php echo $invoiceDetails['bl_no']; ?></td>
                        <td class="text-right vcenter" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>"><?php echo Helper::numberFormat2Digit($invoiceDetails['billed']).' '.__('label.TK'); ?></td>
                        <td class="text-right vcenter" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>"><?php echo !empty($invoiceCollection[$invoiceId]['received']) ? Helper::numberFormat2Digit($invoiceCollection[$invoiceId]['received']) : Helper::numberFormat2Digit(0); ?> <?php echo app('translator')->get('label.TK'); ?></td>
                        <td class="text-right vcenter" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>"><?php echo !empty($invoiceCollection[$invoiceId]['due']) ? Helper::numberFormat2Digit($invoiceCollection[$invoiceId]['due']) : Helper::numberFormat2Digit(0); ?> <?php echo app('translator')->get('label.TK'); ?></td>
                        <td class="text-center vcenter width-150" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>">
                            <div class="input-group bootstrap-touchspin width-inherit">
                                <?php echo Form::text('transaction_id['.$invoiceId.']', null, ['id'=> 'transactionId_'.$invoiceId, 'style' => ' min-width: 100px', 'data-id' => $invoiceId,  'class' => 'form-control text-input-width-100-per text-right','autocomplete' => 'off']); ?>

                            </div>
                        </td>
                        <td class="text-center vcenter width-150" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>">
                            <div class="input-group bootstrap-touchspin width-inherit">
                                <?php echo Form::text('collection_amount['.$invoiceId.']', null, ['id'=> 'invoiceCollectionAmount_'.$invoiceId, 'style' => ' min-width: 100px', 'data-id' => $invoiceId, 'data-due-amount' => ($invoiceCollection[$invoiceId]['due'] ?? 0.00), 'class' => 'form-control integer-decimal-only text-input-width-100-per text-right invoice-collection-amount','autocomplete' => 'off']); ?>

                                <span class="input-group-addon bootstrap-touchspin-postfix bold"> <?php echo app('translator')->get('label.TK'); ?></span>
                            </div>
                            <span class="pull-right remaining-amount-<?php echo e($invoiceId); ?>"></span>
                        </td>

                        <?php echo Form::hidden('invoice_no['.$invoiceId.']', $invoiceDetails['invoice_no']); ?>

                        <?php echo Form::hidden('order_no['.$invoiceId.']', $invoiceDetails['order_no']); ?>

                        <?php echo Form::hidden('order_id['.$invoiceId.']', $invoiceDetails['order_id']); ?>

                        <?php echo Form::hidden('payment_mode['.$invoiceId.']', $invoiceDetails['payment_mode']); ?>

                        <?php echo Form::hidden('bl_no['.$invoiceId.']', $invoiceDetails['bl_no']); ?>

                        <?php echo Form::hidden('delivery_id['.$invoiceId.']', $invoiceDetails['delivery_id']); ?>

                        <?php echo Form::hidden('billed['.$invoiceId.']', $invoiceDetails['billed']); ?>

                        <?php echo Form::hidden('invoice_due['.$invoiceId.']', $invoiceCollection[$invoiceId]['due'] ?? 0.00); ?>



                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                    <tr>
                        <td class="text-success" colspan="20"><?php echo app('translator')->get('label.PAYMENT_OF_ALL_INVOICES_HAS_BEEN_COLLECTED'); ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table    >
        </div>
    </div>
</div>

<div class="form-actions">
    <div class="row margin-top-20">
        <div class="col-md-offset-4 col-md-8">
            <button class="btn green btn-submit" id="previewReceive"  href="#modalViewReceivePreview" type="button" data-toggle="modal">
                <i class="fa fa-check"></i> <?php echo app('translator')->get('label.PREVIEW'); ?>
            </button>
            <a href="<?php echo e(URL::to('/admin/receive')); ?>" class="btn btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>

        </div>
    </div>
</div>

<script src="<?php echo e(asset('public/js/custom.js')); ?>" type="text/javascript"></script>
<script>
$(function () {
    $("tooltips").tooltip();
    //when pay in full
    $(".full-pay-check").click(function () {
        //get invoice id
        var invoiceId = $(this).attr("data-id");
        //if pay in full
        if ($(this).prop('checked')) {
            var invElId = "#invoiceCollectionAmount_" + invoiceId;
            //get invoice due
            var invoiceDue = $(invElId).attr("data-due-amount");
            //set span text $0.00
            $('span.remaining-amount-' + invoiceId).text("Due : 0.00" + " <?php echo app('translator')->get('label.TK'); ?>");
            $('span.remaining-amount-' + invoiceId).css("color", "green");
            $(invElId).val(invoiceDue);
            $(invElId).prop('readonly', true);


        } else {
            //clear all number and span text
            $('span.remaining-amount-' + invoiceId).text('');
            $("#invoiceCollectionAmount_" + invoiceId).val('');
            $("#invoiceCollectionAmount_" + invoiceId).prop('readonly', false);
        }

    });
    //end :: when pay in full

    $('.invoice-collection-amount').keyup(function (e) {
        findRemainingAmount(e, this);
    });

    function findRemainingAmount(e, selector) {
        e.preventDefault();
        var amount = $(selector).val();
        var id = $(selector).attr("data-id");
        var due = $(selector).attr("data-due-amount");

        if (amount == '') {
            $('span.remaining-amount-' + id).text('');
            return false;
        }

        var remaining = due - amount;

        if (amount.length > 0) {
            if (remaining >= 0) {
                $('span.remaining-amount-' + id).text("Due : " + remaining.toFixed(2)+ " <?php echo app('translator')->get('label.TK'); ?>");
                $('span.remaining-amount-' + id).css("color", "green");
                return false;
            } else {
                remaining = remaining * (-1);
                $('span.remaining-amount-' + id).text("Surplus : $" + remaining.toFixed(2)+ " <?php echo app('translator')->get('label.TK'); ?>");
                $('span.remaining-amount-' + id).css("color", "red");
                return false;
            }
        }
    }
});

</script>
<?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/receive/showReceiveData.blade.php ENDPATH**/ ?>