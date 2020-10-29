<div class="card card-custom gutter-b example example-compact">
	<!--begin::Form-->
	<form id="member-form" class="form" method="post" enctype="multipart/form-data">
		<div class="card-footer bg-gray-100 border-top-0">
			<div class="row align-items-center">
				<div class="col text-left"></div>
				<div class="col text-right">
					<input type="hidden" name="user_id" value="<?= isset($edit) && isset($edit->user_id) ? $edit->user_id : set_value('user_id'); ?>" />
					<a href="<?= base_url('console/user'); ?>" class="btn btn-light-primary font-weight-bolder mr-2"> <i class="ki ki-long-arrow-back icon-sm"></i>Back </a>

					<button type="submit" data-url="console/user/view" class="btn btn-primary font-weight-bolder submit_btn"> <i class="ki ki-check icon-sm"></i>Save </button>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-4 form-group">
					<label>First Name*</label>
					<input type="text" class="form-control" name="first_name" id="first_name" placeholder="Enter first name"  value="<?= isset($edit) && isset($edit->first_name) ? $edit->first_name : set_value('first_name'); ?>" />
					<span class="form-text text-danger" id="error_first_name"></span>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-4 form-group">
					<label>Last Name*</label>
					<input type="text" class="form-control" name="last_name" id="last_name" placeholder="Enter last name"  value="<?= isset($edit) && isset($edit->last_name) ? $edit->last_name : set_value('last_name'); ?>" />
					<span class="form-text text-danger" id="error_last_name"></span>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-4 form-group">
					<label>Password</label>
					<input type="password" class="form-control" name="password" id="password" placeholder="Enter password"  value="<?= isset($edit) && isset($edit->password) ? $edit->password : set_value('password'); ?>" />
					<span class="form-text text-danger" id="error_password"></span>
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-4 form-group">
					<label>Email*</label>
					<input type="text" class="form-control" name="email" id="email" placeholder="Enter email" value="<?= isset($edit) && isset($edit->email) ? $edit->email : set_value('email'); ?>" />
					<span class="form-text text-danger" id="error_email"></span>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-4 form-group">
					<label>Mobile No*</label>
					<input type="text" class="form-control numbers_only" name="mobile" id="mobile" placeholder="Enter mobile no" maxlength="10" value="<?= isset($edit) && isset($edit->mobile) ? $edit->mobile : set_value('mobile'); ?>" />
					<span class="form-text text-danger" id="error_mobile"></span>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-4 form-group">
					<label>Age</label>
					<input type="text" class="form-control numbers_only" name="age" id="age" placeholder="Enter age" maxlength="2" value="<?= isset($edit) && isset($edit->age) ? $edit->age : set_value('age'); ?>" />
					<span class="form-text text-danger" id="error_age"></span>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-3 form-group">
					<label>Address</label>
					<input type="text" class="form-control" name="address" id="address" placeholder="Enter address" value="<?= isset($edit) && isset($edit->address) ? $edit->address : set_value('address'); ?>" />
					<span class="form-text text-danger" id="error_address"></span>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-3 form-group">
					<label>City</label>
					<input type="text" class="form-control" name="city" id="city" placeholder="Enter city"  value="<?= isset($edit) && isset($edit->city) ? $edit->city : set_value('city'); ?>" />
					<span class="form-text text-danger" id="error_city"></span>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-3 form-group">
					<label>State</label>
					<input type="text" class="form-control" name="state" id="state" placeholder="Enter state" value="<?= isset($edit) && isset($edit->state) ? $edit->state : set_value('state'); ?>" />
					<span class="form-text text-danger" id="error_state"></span>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-3 form-group">
					<label>Pincode</label>
					<input type="text" class="form-control numbers_only" name="pincode" id="pincode" placeholder="Enter pincode" maxlength="6" value="<?= isset($edit) && isset($edit->pincode) ? $edit->pincode : set_value('pincode'); ?>" />
					<span class="form-text text-danger" id="error_pincode"></span>
				</div>
			</div>
			
		</div>
	</form>
	<!--end::Form-->
</div>
<script type="text/javascript">
$('.back_btn').click(function() {
	window.location.href = base_url + 'console/user';
});

$(document).ready(function() {
	var continue_to = base_url + 'console/user';
	
	$('body').on('change blur', 'input, select', function() {
		$(this).closest('.form-group').removeClass('is-invalid');
	});

	$('body').on('change blur', '#first_name', function() {
		$('#error_first_name').html('').hide();
		if($(this).val().trim() == '') {
			$('#error_first_name').html('Enter first name').show();
		} else if(!validateName($(this).val().trim())) {
			$('#error_first_name').html('Numbers are not allowed').show();
		}
	});
	
	$('body').on('change blur', '#last_name', function() {
		$('#error_last_name').html('').hide();
		if($(this).val().trim() == '') {
			$('#error_last_name').html('Enter last name').show();
		} else if(!validateName($(this).val().trim())) {
			$('#error_last_name').html('Numbers are not allowed').show();
		}
	});

	$('body').on('change blur', '#email', function() {
		$('#error_email').html('').hide();
		if($(this).val().trim() == '') {
			$('#error_email').html('Enter email address').show();
		} else if( ! validateEmail($(this).val().trim())) {
			$('#error_email').html('Enter valid email address').show();
		}
	});

	$('body').on('change blur', '#mobile', function() {
		$('#error_mobile').html('').hide();
		if($('#mobile').val().trim() == '') {
			$('#error_mobile').html('Enter mobile no').show();
		} else if(!validateNumber($(this).val().trim())) {
			$('#error_mobile').html('Enter valid mobile no').show();
		} else if(!validateMobile($(this).val().trim())) {
			$('#error_mobile').html('Enter 10 digit mobile no').show();
		}
	});
	
	$('#member-form').submit(function(e) {
		// e.preventDefault();
		var redirect = base_url + $(this).data('url');
		var isValid  = 1;

		if($('#first_name').val().trim() == '') {
			isValid = 0;
			$('#first_name').parents('.form-group').addClass('is-invalid');
			$('#error_first_name').html('Enter first name').show();
		} else if(!validateName($('#first_name').val().trim())) {
			isValid = 0;
			$('#first_name').parents('.form-group').addClass('is-invalid');
			$('#error_first_name').html('Numbers are not allowed').show();
		}

		if($('#last_name').val().trim() == '') {
			isValid = 0;
			$('#last_name').parents('.form-group').addClass('is-invalid');
			$('#error_last_name').html('Enter last name').show();
		} else if(!validateName($('#first_name').val().trim())) {
			isValid = 0;
			$('#last_name').parents('.form-group').addClass('is-invalid');
			$('#error_last_name').html('Numbers are not allowed').show();
		}

		if($('#email').val().trim() == '') {
			isValid = 0;
			$('#email').parents('.form-group').addClass('is-invalid');
			$('#error_email').html('Enter email address').show();
		} else if( ! validateEmail($('#email').val().trim())) {
			isValid = 0;
			$('#email').parents('.form-group').addClass('is-invalid');
			$('#error_email').html('Enter valid email address').show();
		}
		
		if($('#mobile').val().trim() == '') {
			isValid = 0;
			$('#mobile').parents('.form-group').addClass('is-invalid');
			$('#error_mobile').html('Enter mobile no').show();
		} else if(!validateNumber($('#mobile').val().trim())) {
			isValid = 0;
			$('#mobile').parents('.form-group').addClass('is-invalid');
			$('#error_mobile').html('Enter valid mobile no').show();
		} else if(!validateMobile($('#mobile').val().trim())) {
			isValid = 0;
			$('#mobile').parents('.form-group').addClass('is-invalid');
			$('#error_mobile').html('Enter 10 digit mobile no').show();
		}

		if(!isValid) {
			e.preventDefault();
		} else {
			// Start Block UI
		    KTApp.blockPage({
		        overlayColor: 'red',
		        opacity: 0.1,
		        state: 'primary' // a bootstrap color
		    });

		    setTimeout(function() {
		        KTApp.unblockPage();
		    }, 2000);
		    // End Block UI
		}
	});

	$(window).bind('pageshow', function() {
		var form = $('form'); 
		form[0].reset();
	});

});
</script>