<div class="card card-custom gutter-b example example-compact">
	<!--begin::Form-->
	<form id="tax-form" class="form">
		<div class="card-footer bg-gray-100 border-top-0">
			<div class="row align-items-center">
				<div class="col text-left"></div>
				<div class="col text-right">
					<input type="hidden" name="tax_id" value="<?= isset($edit) && isset($edit->tax_id) ? $edit->tax_id : set_value('tax_id'); ?>" />
					<a href="<?= base_url('console/master/tax'); ?>" class="btn btn-light-primary font-weight-bolder mr-2"> <i class="ki ki-long-arrow-back icon-sm"></i>Back </a>

					<div class="btn-group">
						<button type="button" class="btn btn-primary font-weight-bolder submit_btn"> <i class="ki ki-check icon-sm"></i>Save Tax </button>
					</div>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-6 form-group">
					<label>Name*</label>
					<input type="text" class="form-control" name="name" id="name" placeholder="Enter name" value="<?= isset($edit) && isset($edit->name) ? $edit->name : set_value('name'); ?>" />
					<span class="form-text text-danger" id="error_name"></span>
				</div>

				<div class="col-md-4 form-group">
					<label>Percentage*</label>
					<input type="text" class="form-control numbers_only" name="percentage" id="percentage" placeholder="Enter percentage" maxlength="2" value="<?= isset($edit) && isset($edit->percentage) ? $edit->percentage : set_value('percentage'); ?>" />
					<span class="form-text text-danger" id="error_percentage"></span>
				</div>
			</div>
		</div>
	</form>
	<!--end::Form-->
</div>
<script type="text/javascript">
$('.back_btn').click(function() {
	window.location.href = base_url + 'console/master/tax';
});

$(document).ready(function() {
	var continue_to = base_url + 'console/master/tax';
	
	$('body').on('change blur', 'input, select', function() {
		$(this).closest('.form-group').removeClass('is-invalid');
	});

	$('body').on('change blur', '#name', function() {
		$('#error_name').html('').hide();
		if($(this).val().trim() == '') {
			$('#error_name').html('Enter name').show();
		}
	});

	$('body').on('change blur', '#percentage', function() {
		$('#error_percentage').html('').hide();
		if($('#percentage').val().trim() == '') {
			$('#error_percentage').html('Enter percentage').show();
		} else if(!validateNumber($(this).val().trim())) {
			$('#error_percentage').html('Enter valid percentage').show();
		}
	});
	
	$('.submit_btn').click(function(e) {
		e.preventDefault();
		var isValid  = 1;

		if($('#name').val().trim() == '') {
			isValid = 0;
			$('#name').parents('.form-group').addClass('is-invalid');
			$('#error_name').html('Enter name').show();
		}

		if($('#percentage').val().trim() == '') {
			isValid = 0;
			$('#percentage').parents('.form-group').addClass('is-invalid');
			$('#error_percentage').html('Enter percentage').show();
		} else if(!validateNumber($('#percentage').val().trim())) {
			isValid = 0;
			$('#percentage').parents('.form-group').addClass('is-invalid');
			$('#error_percentage').html('Enter valid percentage').show();
		}

		if(isValid) {
			submit_form();
		}
	});

	function submit_form() {
    $('.bt-group').attr('disabled', true);
		$.ajax({
			type: 'POST',
			url: base_url + 'console/master/ajaxAddTax',
			data: $('#tax-form').serialize(),
			dataType: 'json',
			success: function(data) {
				//console.log(data);
				$('.bt-group').removeAttr('disabled', false);
				setTimeout(function() {
					if(data.status == 'success') {
						$('#tax-form')[0].reset();
						quick_alert('success', data.message);
					} else {
						quick_alert('warning', 'Error! Unable to complete process.');
					}
				}, 500);
				setTimeout(function() {
					window.location = continue_to;
				}, 2000);
			},
			error: function(data) {
				$('.bt-group').removeAttr('disabled', false);
				quick_swal('warning','Error! Unable to complete process.');
			}
		});
	}

});
</script>