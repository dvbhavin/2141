<div class="d-flex flex-column flex-root">
	<!--begin::Login-->
	<div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
		<!--begin::Aside-->
		<div class="login-aside d-flex flex-column flex-row-auto" style="background-color: #F2C98A;">
			<!--begin::Aside Top-->
			<div class="d-flex flex-column-auto flex-column pt-15">
				<!--begin::Aside header-->
				<a href="<?= base_url(); ?>" class="text-center mb-10">
					<img src="<?= asset_url(); ?>media/logos/logo.png" class="max-h-90px" alt="shopping-13" />
				</a>
				<!--end::Aside header-->
				<!--begin::Aside title-->
				<!-- <h3 class="font-weight-bolder text-center font-size-h4 font-size-h1-lg" style="color: #986923;">Discover Amazing Metronic
				<br />with great build tools</h3> -->
				<!--end::Aside title-->
			</div>
			<!--end::Aside Top-->
			<!--begin::Aside Bottom-->
			<div class="aside-img d-flex flex-row-fluid bgi-no-repeat bgi-position-y-bottom bgi-position-x-center" style="background-image: url(<?= asset_url(); ?>media/svg/illustrations/login-visual-1.svg)"></div>
			<!--end::Aside Bottom-->
		</div>
		<!--begin::Aside-->
		<!--begin::Content-->
		<div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
			<!--begin::Content body-->
			<div class="d-flex flex-column-fluid flex-center">
				<!--begin::Signin-->
				<div class="login-form login-signin">
					<!--begin::Form-->
					<form action="javascript:;" id="login_form" class="form" novalidate="novalidate" method="post">
						<div class="pb-13 pt-lg-0 pt-5">
							<h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Welcome to Shopping 13</h3>
						</div>
						
						<div id="error_form"></div>
						<div class="form-group">
							<label class="font-size-h6 font-weight-bolder text-dark">Email</label>
							<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" type="text" name="email_id" id="email_id" autocomplete="off" />
							<span id="error_email_id" class="text-danger"></span>
						</div>
						<div class="form-group">
							<div class="d-flex justify-content-between mt-n5">
								<label class="font-size-h6 font-weight-bolder text-dark pt-5">Password</label>
								<!-- <a href="javascript:;" class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5" id="kt_login_forgot">Forgot Password ?</a> -->
							</div>
							<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" type="password" name="password" id="password" autocomplete="off" />
							<span id="error_password" class="text-danger"></span>
						</div>
						<div class="pb-lg-0 pb-5">
							<button type="submit" id="login_btn" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Sign In</button>
						</div>
					</form>
					<!--end::Form-->
				</div>
				<!--end::Signin-->
				<!--begin::Forgot-->
				<div class="login-form login-forgot">
					<!--begin::Form-->
					<form class="form" novalidate="novalidate" id="kt_login_forgot_form">
						<div class="pb-13 pt-lg-0 pt-5">
							<h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Forgotten Password ?</h3>
							<p class="text-muted font-weight-bold font-size-h4">Enter your email to reset your password</p>
						</div>
						<div class="form-group">
							<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="email" placeholder="Email" name="email" autocomplete="off" />
						</div>
						<div class="form-group d-flex flex-wrap pb-lg-0">
							<button type="button" id="kt_login_forgot_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4">Submit</button>
							<button type="button" id="kt_login_forgot_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3">Cancel</button>
						</div>
					</form>
					<!--end::Form-->
				</div>
				<!--end::Forgot-->
			</div>
			<!--end::Content body-->
		</div>
		<!--end::Content-->
	</div>
	<!--end::Login-->
</div>
<script type="text/javascript">
$(document).ready(function() {
   	var continue_to = base_url + 'account';

   	$(document).bind('keypress', function(e) {
      	if(e.keyCode==13) {
        	$('#login_btn, #forgot_btn').trigger('click');
      	}
   	});

    $('#email_id').change(function() {
		$('#error_email_id').hide();
		$(this).parents('.form-group').removeClass('has-error');
		if($(this).val().trim() == '') {
			$(this).parents('.form-group').addClass('has-error');
			$('#error_email_id').html('Enter email address').show();
		}
	});

	$('#password').change(function() {
		$('#error_password').hide();
		$(this).parents('.form-group').removeClass('has-error');
		if($(this).val() == '') {
			$(this).parents('.form-group').addClass('has-error');
			$('#error_password').html('Enter password').show();
		}
	});

    $('#login_btn').click(function(event) {
		event.preventDefault();
		var is_valid = 1;
		if($('#email_id').val() == '') {
			is_valid = 0;
			$('#email_id').parents('.form-group').addClass('has-error');
			$('#error_email_id').html('Enter email address').show();
		}

		if($('#password').val() == '') {
			is_valid = 0;
			$('#password').parents('.form-group').addClass('has-error');
			$('#error_password').html('Enter password').show();
		}

		if(is_valid) {
			submit_login_form();
		}
	});

	function submit_login_form() {
		$('#login_btn').html('<span class="fa fa-spinner fa-spin"></span> Connecting...');
		$.ajax({
			type: 'POST',
			url: base_url + 'account/ajax_login',
			data: $('#login_form').serialize(),
			dataType: 'json',
			success: function(data) {
				//console.log(data);
				$('#error_form').html('').hide();
				if(data.status == 'success') {
					$('#login_btn').html('<span class="fa fa-check"></span> Sign In Successful...');
					setTimeout(function() {
						$('#login_btn').html('<span class="fa fa-spinner fa-spin"></span> Redirecting...');
					}, 700);

					setTimeout(function() {
						window.location = continue_to;
					}, 1000)
				}

				if(data.status == 'error') {
					$('#login_btn').html('Sign In');
					var error_msg;
					if(data.error_type == 'login') {
						$('#email_id').parents('.form-group').addClass('has-error');
						error_msg = 'Invalid email address !';
					}
					if(data.error_type == 'password') {
						$('#password').parents('.form-group').addClass('has-error');
						error_msg = 'Invalid password !';
					}
					$('#error_form').html('<div class="alert alert-custom alert-white alert-shadow gutter-b" role="alert">\
							<div class="alert-icon">\
								<i class="flaticon-information text-danger"></i>\
							</div>\
							<div class="alert-text text-danger font-size-h4">'+ error_msg +'</div>\
						</div>').show();
				}

				if(data.status == 'fail') {
					$('#login_btn').html('Sign In');
					if(data.error_type == 'email_verification') {
						quick_swal('info','email address verification pending!<br/>Check your mailbox for email varification link.');
					}
					if(data.error_type == 'inactive') {
						quick_swal('info',"Your account is inactive.<br/> Contact site administrator for further assistance.");
					}
					if(data.error_type == 'deactivated') {
						quick_swal('error','Your account has been deactivated.<br/>Contact site administrator for further assistance.');
					}
					if(data.error_type == 'deleted') {
						quick_swal('error','Your account has been blocked.<br/>Contact site administrator for further assistance.');
					}
					if(data.error_type == 'no_login_rights') {
						quick_swal('warning','Your login has been restricted by admin.<br/>Contact site administrator for further assistance.');
					}
					if(data.error_type == 'database') {
						quick_swal();
					}
				}
			}, error: function(data) {
				$('#login_btn').html('Sign In');
				quick_swal(data.status);
			}
		});
	}
	// end login form

	// start forgot password form
});
</script>