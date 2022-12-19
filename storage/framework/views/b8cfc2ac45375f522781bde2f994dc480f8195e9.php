<div class="modal-content margin-top-60">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
        <h4 class="modal-title text-center">
            <h4 class="modal-title" id="exampleModalLavel"><?php echo app('translator')->get('label.PRODUCT_QUICK_VIEW'); ?></h4>
        </h4>
    </div>
    <div class="modal-body">

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <?php if(!empty($target->productImage[0])): ?>
                    <img src="<?php echo e(URL::to('/')); ?>/public/uploads/product/smallImage/<?php echo e($target->productImage[0] ?? ''); ?>" id="pimage" style="height: 220px; width: 200px;">
                    <?php else: ?>
                    <img src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" alt="">
                    <?php endif; ?>
                    <div class="card-body">
                        <a href="<?php echo e(url('/productDetail/'.$target->productId.'/'.$target->sku)); ?>">

                            <h4 class="card-title text-center" id="pname"> <strong><?php echo e($target->productName); ?></strong> <?php echo e($target->productAttribute); ?></h4>
                        </a>
                    </div>

                </div>

            </div>


            <div class="col-md-4">
                <ul class="list-group">
                    <li class="list-group-item"><?php echo app('translator')->get('label.PRODUCT_SKU'); ?>: <span id="pCode"><?php echo e($target->sku); ?></span> </li>
                    <li class="list-group-item"><?php echo app('translator')->get('label.CATEGORY'); ?>: <span id="pCat"><?php echo e($target->categoryName); ?></span></li>
                    <li class="list-group-item"><?php echo app('translator')->get('label.BRAND'); ?>: <span id="pBrand"><?php echo e($target->brandName); ?></span> </li>
					<?php if(auth()->guard()->check()): ?>
                    <?php if(Auth::user()->group_id == 19): ?>
					<li class="list-group-item"><?php echo app('translator')->get('label.PRICE'); ?>: <span id="pPrice"><?php echo e($target->price); ?>&nbsp;<?php echo app('translator')->get('label.TK'); ?></span> </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->group_id == 18): ?>
					<li class="list-group-item"><?php echo app('translator')->get('label.PRICE'); ?>: <span id="pPrice"><?php echo e($target->distributor_price); ?>&nbsp;<?php echo app('translator')->get('label.TK'); ?></span> </li>
                    <?php endif; ?>
                    <?php if(Auth::user() && !in_array(Auth::user()->group_id,[18,19])): ?>
					<li class="list-group-item"><?php echo app('translator')->get('label.RETAILER_PRICE'); ?>: <span id="pPrice"><?php echo e($target->price); ?>&nbsp;<?php echo app('translator')->get('label.TK'); ?></span> </li>
					<li class="list-group-item"><?php echo app('translator')->get('label.DISTRIBUTOR_PRICE'); ?>: <span id="pPrice"><?php echo e($target->distributor_price); ?>&nbsp;<?php echo app('translator')->get('label.TK'); ?></span> </li>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php
                    $availability = !empty($target->available_quantity) && $target->available_quantity > 0 ? __('label.IN_STOCK') : __('label.OUT_OF_STOCK');
                    $availabilityColor = !empty($target->available_quantity) && $target->available_quantity > 0 ? 'green-sharp' : 'red-intense';
                    ?>
                    <li class="list-group-item"><?php echo app('translator')->get('label.STOCK'); ?>: <span class="badge badge-<?php echo e($availabilityColor); ?>"> <?php echo e($availability); ?></span> </li>
                </ul>

            </div>

            <!--<div class="col-md-4">

                <input type="hidden" name="product_id" id="product_id">
                <div class="cus-quantity">
                    <div class="quantity-input">
                        <input type="text" name="product-quatity" value="1" data-max="120" pattern="[0-9]*" id="qty">
                        <button class="btn btn-reduce minus"><i class="fa fa-minus" aria-hidden="true"></i></button>
                        <button class="btn btn-increase plus"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </div>
                </div>


                <button class="btn btn-primary" id="addToCart" sku-code="<?php echo e($target->sku); ?>" data-id="<?php echo e($target->productId); ?>"><?php echo app('translator')->get('label.ADD_TO_CART'); ?></button>


            </div>-->
            <div class="col-md-4">
                <?php
                $inDepoProducts = Helper::getInDepoProduct($target->productId, $target->sku_id);
                ?>
                <?php if(Auth::check()): ?>
                <?php if(!empty($inDepoProducts)): ?>
                <?php if(Auth::user()->group_id == 14): ?>
                <input type="hidden" name="product_id" id="product_id">
                <div class="cus-quantity">
                    <div class="quantity-input">
                        <input type="text" name="product-quatity" value="1" data-max="120" pattern="[0-9]*" id="qty">
                        <button class="btn btn-reduce minus"><i class="fa fa-minus" aria-hidden="true"></i></button>
                        <button class="btn btn-increase plus"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </div>
                </div>
                <button class="btn btn-primary" id="addToCart" sku-code="<?php echo e($target->sku); ?>" data-id="<?php echo e($target->productId); ?>"><?php echo app('translator')->get('label.ADD_TO_CART'); ?></button>
                <?php endif; ?>
                <?php else: ?>
				
                <table class="table table-responsive table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center vcenter" colspan="2"><?php echo app('translator')->get('label.AVAILABLE_STOCK'); ?></th>
                        </tr>
                        <tr>
                            <th class="vcenter"><?php echo app('translator')->get('label.CENTRAL_WAREHOUSE'); ?></th>
                            <th class="text-center vcenter"><?php echo e($target->available_quantity); ?></th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th class="vcenter" colspan="2"><?php echo app('translator')->get('label.LOCAL_WAREHOUSE'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!$localProductQtys->isEmpty()): ?>
                        <?php $__currentLoopData = $localProductQtys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $localInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center vcenter"><?php echo e(!empty($localInfo->wh_name) ? $localInfo->wh_name : ''); ?></td>
                            <td class="text-center vcenter"><?php echo e(!empty($localInfo->local_quantity)? Helper::numberFormat2Digit($localInfo->local_quantity):''); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="2" class="vcenter"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php endif; ?>
                <?php endif; ?>

            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on("click", "#addToCart", function () {
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null
            };
            var id = $(this).data('id');
            var qty = $('#qty').val();
            var skuCode = $(this).attr('sku-code');
            if (id) {
                $.ajax({
                    url: "<?php echo e(URL::to('/addToCart/')); ?>/" + id,
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        qty: qty,
                        sku_code: skuCode
                    },
                    success: function (res) {
//                            toastr.success(res.data, res.message, options);
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
            } else {
                alert('danger');
            }
        });
        var plus = 0;
        var qty = $('#qty').val();
        $(document).on("click", ".plus", function () {
            plus += 1;
            if (plus > 1) {
                $('#qty').val(plus);
            } else {
                $('#qty').val(1);
            }
        });

        plus = 1;
        $(document).on("click", ".minus", function () {
            var qty = $('#qty').val();
            if (qty > 1) {
                plus -= 1;
                if (plus > 1) {
                    $('#qty').val(plus);
                } else {
                    $('#qty').val(1);
                }
            }
        });
    });
</script>
<?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/frontend/showProductQuickView.blade.php ENDPATH**/ ?>