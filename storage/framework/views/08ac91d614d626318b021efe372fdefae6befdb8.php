
<?php $__env->startSection('content'); ?>
<div class="container">

    <div class="wrap-breadcrumb">
        <ul>
            <li class="item-link"><a href="#" class="link">home</a></li>
            <li class="item-link"><span>login</span></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12 col-md-offset-3">							
            <div class=" main-content-area">

                <div class="wrap-login-item ">
                    <div class="login-form form-item form-stl">
                        <?php echo Form::open(['route' => 'customer.authenticate' , 'id' => 'buyerForm' ,'group' => 'form', 'class' => 'form-horizontal']); ?>

                        <?php echo csrf_field(); ?>
                        <fieldset class="wrap-title">
                            <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <h3 class="form-title text-center"><?php echo app('translator')->get('label.LOGIN_IN'); ?></h3>										
                        </fieldset>
                        <fieldset class="wrap-input">
                            <label for="frm-reg-lname"><?php echo app('translator')->get('label.USERNAME'); ?><span class="required"> *</span></label>
                            <?php echo Form::text('username', null, ['id'=> 'username', 'class' => 'form-control','autocomplete' => 'off','placeholder' => '']); ?>

                        </fieldset>
                        <fieldset class="wrap-input">
                            <label for="frm-reg-pass"><?php echo app('translator')->get('label.PASSWORD'); ?><span class="required"> *</span></label>
                            <?php echo Form::password('password', ['id'=> 'password', 'class' => 'form-control','autocomplete' => 'off','placeholder' => 'Enter Password']); ?> 
                        </fieldset>

                        <fieldset class="wrap-input">
                            <input type="submit" class="btn btn-submit login-btn" value="Login" name="submit">
                            <!--<a class="link-function left-position" href="<?php echo e(url('requestForgotPassword')); ?>" title="Forgotten password?"><?php echo app('translator')->get('label.FORGOT_PASSWORD'); ?></a> 
                              <div class="social-login row">
                                 <div class="col-md-6">
                                     <div id="googleButton"></div>
                                 </div>
                                 <div class="col-md-6">
                                     <a onclick="fbLogin();" class="fb-button"><i class="fa fa-facebook fa-fw"></i> <?php echo app('translator')->get('label.LOGIN_WITH_FACEBOOK'); ?></a>
                                 </div>
                             </div>-->
                        </fieldset>

                        <?php echo Form::close(); ?>

                    </div>
                </div>
            </div><!--end main products area-->		
        </div>
    </div><!--end row-->

</div>
<script src="<?php echo e(asset('public/js/fbLogin.js')); ?>"></script>
<script type="text/javascript">
// Facebook Login Area
// Facebook login with JavaScript SDK
function fbLogin() {
    FB.login(function (response) {
        if (response.authResponse) {
            // Get and display the user profile data
            getFbUserData();

        } else {
            // There was an error.
        }
    }, {scope: 'email'});
}
//START::show pass
$(document).on('click', '#showPass', function () {
    $('#passIcon').toggleClass("fa-eye fa-eye-slash");
    var input = $('#password');
    var confirmPass = $('#confPassword');
    if (input.attr("type") == "password") {
        input.attr("type", "text");
        confirmPass.attr("type", "text");
    } else {
        input.attr("type", "password");
        confirmPass.attr("type", "password");
    }
});
//END::show pass

// Fetch the user profile data from facebook
function getFbUserData() {
    FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,name,email,picture'},
            function (response) {
                var fbId = response.id;
                var email = response.email;
                var fullName = response.name;
                var photo = response.picture.data.url;
                var options = {
                    closeButton: true,
                    debug: false,
                    positionClass: "toast-bottom-right",
                    onclick: null
                };
                $.ajax({
                    url: "<?php echo e(route('customer.facebookLogin')); ?>",
                    type: "POST",
                    datatype: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        fb_id: fbId,
                        full_name: fullName,
                        email: email,
                        photo: photo,
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
                        window.location.href = "<?php echo e(url('/')); ?>";
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
                            toastr.error(jqXhr.responseJSON.message, 'Error', options);
                        } else {
                            toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                        }
                    }

                });
            });
}
// End Facebook Login Area


// Google Login Area
function renderButton() {
    gapi.signin2.render('googleButton', {
        'scope': 'profile email',
        'width': 240,
        'height': 50,
        'longtitle': true,
        'theme': 'dark'
    });
}
$('#googleButton').click(function () {
    auth2.grantOfflineAccess().then(signInCallback);
});

function signInCallback(authResult) {
    if (authResult['code']) {
        setTimeout(function () {
            if (auth2.isSignedIn.get()) {
                var profile = auth2.currentUser.get().getBasicProfile();
                var googleId = profile.getId();
                var fullName = profile.getName();
                var email = profile.getEmail();
                var photo = profile.getImageUrl();
                var options = {
                    closeButton: true,
                    debug: false,
                    positionClass: "toast-bottom-right",
                    onclick: null
                };
                $.ajax({
                    url: "<?php echo e(route('customer.googleLogin')); ?>",
                    type: "POST",
                    datatype: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        google_id: googleId,
                        full_name: fullName,
                        email: email,
                        photo: photo,
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
                        window.location.href = "<?php echo e(url('/')); ?>";
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
                            toastr.error(jqXhr.responseJSON.message, 'Error', options);
                        } else {
                            toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                        }
                    }

                });
            }
        }, 1000);

    } else {
        // There was an error.
    }
}
// End Google Login Area
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\arroz\resources\views/frontend/login.blade.php ENDPATH**/ ?>