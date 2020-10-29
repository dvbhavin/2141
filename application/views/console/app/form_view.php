<div class="card card-custom gutter-b example example-compact">
	<!--begin::Form-->
	<form id="app-form" class="form" method="post" enctype="multipart/form-data">
		<div class="card-footer bg-gray-100 border-top-0">
			<div class="row align-items-center">
				<div class="col text-left"></div>
				<div class="col text-right">
					<input type="hidden" name="app_id" value="<?= isset($edit) && isset($edit->app_id) ? $edit->app_id : set_value('app_id'); ?>" />
					<input type="hidden" name="previous" id="previous" value="<?= isset($edit) && isset($edit->name) ? $edit->name : set_value('name'); ?>" />
					<a href="<?= base_url('console/app'); ?>" class="btn btn-light-primary font-weight-bolder mr-2"> <i class="ki ki-long-arrow-back icon-sm"></i>Back </a>

					<button type="submit" data-url="console/app/view" class="btn btn-primary font-weight-bolder submit_btn"> <i class="ki ki-check icon-sm"></i>Save app </button>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-4 form-group">
					<label>App Name*</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text"> <i class="flaticon2-website"></i> </span>
						</div>
						<input type="text" class="form-control" name="name" id="name" placeholder="Enter app name"  value="<?= isset($edit) && isset($edit->name) ? $edit->name : set_value('name'); ?>" />
					</div>
					<span id="name_loader" class="form-text text-danger d-none"><i class="fa fa-spinner fa-spin"></i> Checking...</span>
					<span class="form-text text-danger" id="error_name"></span>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-8 form-group">
					<label>App Description*</label>
					<input type="text" class="form-control" name="description" id="description" placeholder="Enter description" value="<?= isset($edit) && isset($edit->description) ? $edit->description : set_value('description'); ?>" />
					<span class="form-text text-danger" id="error_description"></span>
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
					<label>Status*</label>
                    <div class="radio-inline">
                    <?php foreach($status as $key => $value):
                    	$checked = (isset($edit) && isset($edit->status) && $edit->status == $key ? 'checked' : '');
                    ?>
                        <label class="radio radio-rounded radio-brand">
                            <input type="radio" name="status" class="status" value="<?= $key; ?>" <?= $checked; ?>> <?= $value; ?>
                            <span></span>
                        </label>
                    <?php endforeach; ?>
                    </div>
					<span class="form-text text-danger" id="error_status"></span>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-6 form-group">
		            <?php 
						$media    = (isset($edit) && isset($edit->media) && !empty($edit->media) ? mapp().$edit->media : ''); 
						$media_id = (isset($edit) && isset($edit->media_id) ? $edit->media_id : '');
		            ?>
		            <div class="image-input image-input-outline" id="ctm_media" style="background-image: url(media/users/upload.png)">
						<div class="image-input-wrapper" style="background-image: url(<?= $media; ?>)"></div>
						<label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
							<i class="fa fa-pen icon-sm text-muted"></i>
							<input type="file" id="media" name="media" name="profile_avatar" accept=".png, .jpg, .jpeg" />
							<input type="hidden" name="profile_avatar_remove" value="1" />
						</label>
						<span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
							<i class="ki ki-bold-close icon-xs text-muted"></i>
						</span>
						<span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow delete" data-action="remove" data-id="<?= $media_id; ?>" data-table="media" data-row="media_id" data-file="<?= $media; ?>" data-toggle="tooltip" title="Remove image">
							<i class="ki ki-bold-close icon-xs text-muted"></i>
						</span>
					</div>
				</div>
			</div>
		</div>
	</form>
	<!--end::Form-->
</div>
<script type="text/javascript">
$('.back_btn').click(function() {
	window.location.href = base_url + 'console/app';
});

$(document).ready(function() {
	var continue_to = base_url + 'console/app';
	
	$('body').on('change blur', 'input, select', function() {
		$(this).closest('.form-group').removeClass('is-invalid');
	});

	$('body').on('change blur', '#name', function() {
		$('#error_name').html('').hide();
		if($(this).val().trim() == '') {
			$('#error_name').html('Enter app name').show();
		} else if(!validateName($(this).val().trim())) {
			$('#error_name').html('Numbers are not allowed').show();
		} else {
			var previous = $('#previous').val().trim();
			var name     = $(this).val().trim();
			if((previous != '' && previous != name) || (previous == '')) {
				app_exist = false;
				$('#name_loader').removeClass('d-none');
				$.ajax({
					type: 'POST',
					url:  base_url + 'common/ajax_check_app_exist',
					data: {'name':name},
					dataType: 'json',
					success: function(data) {
						setTimeout(function() {
							$('#name_loader').addClass('d-none');
							if(data.app_exist == true) {
								app_exist = true;
								$('#error_name').html('This app name is already exist').show();
								$('.submit_btn').attr('disabled', true);
							} else {
								$('.submit_btn').attr('disabled', false);
							}
						}, 1000);
					}, error: function(data) {
						ajax_error_swal(data.status);
					}
				});
			} else {
				$('.bt-group').attr('disabled', false);
			}
		}
	});

	$('body').on('change blur', '#description', function() {
		$('#error_description').html('').hide();
		if($(this).val().trim() == '') {
			$('#error_description').html('Enter description').show();
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
	
	$(':radio[name=status]').change(function() {
		$('#error_status').html('').hide();
		if($(this).val().trim() == '') {
			$('#error_status').html('Please choose status').show();
		}
	});

	$('#media').change(function() {	
		$('#error_media').html('').hide();
		var fileSize      = $('#media')[0].files[0].size;
		var fileExtension = ['jpeg', 'jpg', 'png'];
       
		if(fileSize > 2097152) {
			var msg = 'Upload file size up to 2 MB';
			$('#error_size').html(msg).show();
		}
		
		if($(this).val() == '') {
			$('#error_media').html('Select file').show();
		} else if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
			var msg = 'Only these formats are allowed : jpeg, jpg, png';
			$('#error_type').html(msg).show();
        }
	});
	
	$('#app-form').submit(function(e) {
		// e.preventDefault();
		var redirect = base_url + $(this).data('url');
		var isValid  = 1;

		if($('#name').val().trim() == '') {
			isValid = 0;
			$('#name').parents('.form-group').addClass('is-invalid');
			$('#error_name').html('Enter app name').show();
		} else if(!validateName($('#name').val().trim())) {
			isValid = 0;
			$('#name').parents('.form-group').addClass('is-invalid');
			$('#error_name').html('Numbers are not allowed').show();
		}

		if($('#description').val().trim() == '') {
			isValid = 0;
			$('#description').parents('.form-group').addClass('is-invalid');
			$('#error_description').html('Enter description').show();
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

		if($('input[type=radio][name=status]:checked').length == 0) {
			isValid = 0;
			$('.status').parents('.form-group').addClass('is-invalid');
			$('#error_status').html('Please choose status').show();
		}

		if($('#media').val() != '') {
			var fileExtension = ['jpeg', 'jpg', 'png'];
			var fileSize = 0;
			fileSize = $("#media")[0].files[0].size;

			if(fileSize > 2097152) {
				isValid = 0;
				var msg = 'Upload file size up to 2 MB';
				$('#error_size').html(msg).show();
			} else if ($.inArray($('#media').val().split('.').pop().toLowerCase(), fileExtension) == -1) {
				isValid = 0;
				var msg = 'Only these formats are allowed : jpeg, jpg, png';
				$('#error_type').html(msg).show();
			}
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

	// start file upload
    var media = new KTImageInput('ctm_media');

    media.on('cancel', function(imageInput) {
        swal.fire({
            title: 'Image successfully canceled !',
            type: 'success',
            buttonsStyling: false,
            confirmButtonText: 'Awesome!',
            confirmButtonClass: 'btn btn-primary font-weight-bold'
        });
    });

    media.on('change', function(imageInput) {
        swal.fire({
            title: 'Image successfully changed !',
            type: 'success',
            buttonsStyling: false,
            confirmButtonText: 'Awesome!',
            confirmButtonClass: 'btn btn-primary font-weight-bold'
        });
    });

    media.on('remove', function(imageInput) {
		var id    = $('.delete').data('id');
		var row   = $('.delete').data('row');
		var table = $('.delete').data('table');
		var table = $('.delete').data('table');
		var file  = $('.delete').data('file');

        Swal.fire({
			title: 'Are you sure?',
			text: 'You won\'t be able to revert this!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes, delete it!'
		}).then(function (result) {
			if (result.value) {
				$.ajax({
					type: 'POST',
					url: base_url + 'console/app/delete/',
					data:{'id' : id, 'table' : table, 'row' : row, 'file' : file},
					success: function(result) {
						if(result == 'success')
						Swal.fire('Deleted!', 'Image successfully removed !', 'success')
					}
				});
			}
		});

    });

});
</script>