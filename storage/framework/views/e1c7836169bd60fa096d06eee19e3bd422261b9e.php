<footer id="footer">
    <div class="wrap-footer-content footer-style-1">

        <div class="wrap-function-info">
            <div class="container">
                <ul>
                    <?php if(!$specialityArr->isEmpty()): ?>
                    <?php $__currentLoopData = $specialityArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $speciality): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="fc-info-item">
                        <i class="<?php echo e($speciality->icon); ?>" aria-hidden="true"></i>
                        <div class="wrap-left-info">
                            <h4 class="fc-name"><?php echo e($speciality->title); ?></h4>
                            <p class="fc-desc"><?php echo e($speciality->subtitle); ?></p>
                        </div>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>



                </ul>
            </div>
        </div>
        <!--End function info-->

        <div class="main-footer-content">

            <div class="container">

                <div class="row">

                    <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        <div class="wrap-footer-item">
                            <h3 class="item-header">Contact Details</h3>
                            <div class="item-content">
                                <div class="wrap-contact-detail">
                                    <ul>
                                        <li>
                                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                                            <p class="contact-txt"><?php echo !empty($konitaInfo->address)?$konitaInfo->address:''; ?></p>
                                        </li>
                                        <li>
                                            <i class="fa fa-phone" aria-hidden="true"></i>
                                            <p class="contact-txt"><?php echo e(!empty($phoneNumber)?$phoneNumber:''); ?></p>
                                        </li>
                                        <li>
                                            <i class="fa fa-envelope" aria-hidden="true"></i>
                                            <p class="contact-txt"><?php echo e(!empty($konitaInfo->email)?$konitaInfo->email:''); ?></p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">

                        <div class="wrap-footer-item">
                            <h3 class="item-header"><?php echo app('translator')->get('label.HOTLINE'); ?></h3>
                            <div class="item-content">
                                <div class="wrap-hotline-footer">
                                    <span class="desc">Call Us toll Free</span>
                                    <b class="phone-number"><?php echo !empty($konitaInfo->hotline)?$konitaInfo->hotline:''; ?></b>
                                </div>
                            </div>
                        </div>

<!--                        <div class="wrap-footer-item footer-item-second">
                            <h3 class="item-header">Sign up for newsletter</h3>

                            <button type="button" class="btn-submit" id="subscribe">Subscribe</button>

                            <div class="item-content">
                                <div class="wrap-newletter-footer">
                                    <input type="email" class="input-email" name="email" id="email" value="" placeholder="Enter your email address">

                                    <button class="btn-submit" href="#modalCaptcha"  data-toggle="modal" id="openCaptcha">
                                        Subscribe
                                    </button>
                                </div>
                            </div>

                        </div>-->

                    </div>

                    <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12 box-twin-content ">
                        <div class="wrap-footer-item">
                            <h3 class="item-header">We Using Safe Payments:</h3>
                            <div class="item-content">
                                <div class="wrap-list-item wrap-gallery">
                                    <img src="<?php echo e(asset('public/frontend/assets/images/payment.png')); ?>" style="max-width: 260px;">
                                </div>
                            </div>
                        </div>
                        <div class="wrap-footer-item">
                            <h3 class="item-header">Social network</h3>
                            <div class="item-content">
                                <div class="wrap-list-item social-network">
                                    <ul>
                                        <li>
                                            <?php if(!$socialArr->isEmpty()): ?>
                                            <?php $__currentLoopData = $socialArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo $social->url; ?>" class="link-to-item" title="<?php echo $social->title; ?>"><i class="<?php echo $social->icon; ?>" aria-hidden="true"></i></a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />
        </div>
        <div class="coppy-right-box">
            <div class="container">
                <div class="coppy-right-item item-left">
                    <div class="page-footer-inner"><?php echo app('translator')->get('label.COPYRIGHT'); ?> &copy; <?php echo date('Y'); ?>  <a target="_blank" href="<?php echo e($konitaInfo->website); ?>"><?php echo app('translator')->get('label.SAFE_CARE'); ?></a>  | <?php echo app('translator')->get('label.POWERED_BY'); ?>
                        <a target="_blank" href="http://www.swapnoloke.com/"><?php echo app('translator')->get('label.SWAPNOLOKE'); ?></a>
                    </div>
                </div>
                <div class="coppy-right-item item-right">
                    <div class="wrap-nav horizontal-nav">
                        <ul>
                            <!--<li class="menu-item"><a href="about-us.html" class="link-term">About us</a></li>								
                            <li class="menu-item"><a href="privacy-policy.html" class="link-term">Privacy Policy</a></li>
                            <li class="menu-item"><a href="terms-conditions.html" class="link-term">Terms & Conditions</a></li>
                            <li class="menu-item"><a href="return-policy.html" class="link-term">Return Policy</a></li>-->	
                            <?php if(!$footerMenuArr->isEmpty()): ?>
                            <?php $__currentLoopData = $footerMenuArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $footerMenu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="menu-item"><a href="<?php echo e(url('footer-menu/'.$footerMenu->slug)); ?>" class="link-term"><?php echo $footerMenu->title; ?></a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</footer>
<!--captcha modal-->
<div class="modal fade" id="modalCaptcha" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-sm ">
        <div id="showCaptcha">

        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function () {
    var options = {
        closeButton: true,
        debug: false,
        positionClass: "toast-bottom-right",
        onclick: null
    };
    $(document).on("click", "#openCaptcha", function () {
        var email = $("#email").val();
        $.ajax({
            url: "<?php echo e(URL::to('subscribe/openCaptcha')); ?>",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                email: email
            },
            beforeSend: function () {
                $("#showCaptcha").html('');
            },
            success: function (res) {
                $("#showCaptcha").html(res.html);
            },
            error: function (jqXhr, ajaxOptions, thrownError) {

                $('.modal').modal('hide');
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
            }
        });
    });


    $(document).ready(function () {
        $(document).on("click", "#submitCaptcha", function () {
            var formData = new FormData($('#submitSubsciberForm')[0]);
            var sum = formData.get('sum');
            var sumVal = formData.get('sum_val');
            if (Number(sum) != Number(sumVal)) {
                toastr.error('Error', "<?php echo app('translator')->get('label.YOUR_CAPCHA_INPUT_IS_WRONG'); ?>", options);
                $('.modal').modal('hide');
                return false;
            }
            $.ajax({
                url: "<?php echo e(URL::to('subscribe')); ?>",
                type: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function (res) {
                    toastr.success(res.data, res.message, options);
                    $('.modal').modal('hide');
                    $("#email").val('');
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('.modal').modal('hide');
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
                }
            });
        });
    });

});
</script>
<?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/frontend/layouts/default/footer.blade.php ENDPATH**/ ?>