<?php //xdebug($edit); ?>
<div class="card card-custom gutter-b example example-compact">
	<form id="advertise-form" class="form" method="post" enctype="multipart/form-data">
		<div class="card-body">
			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-4 form-group">
					<label>Choose Category*</label>
					<select class="form-control select2 ctm_s2" id="category" name="category_id" data-placeholder="Choose category">
						<option></option>
						<?php if(isset($_category) && !empty($_category)) {
						foreach($_category as $category) : 
						$selected = (isset($edit) && isset($edit->category) && $edit->category == $category->category_id ? 'selected' : ''); ?>
							<option value="<?= $category->category_id; ?>" <?= $selected; ?>><?= toPropercase($category->name); ?></option>
						<?php endforeach;
						} ?>
					</select>
					<span class="form-text text-danger" id="error_category"></span>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-4 form-group">
					<label>Choose Vendor*</label>
					<select class="form-control select2 ctm_s2" id="user_id" name="user_id" data-placeholder="Choose vendor">
						<option></option>
						<?php if(isset($_category) && !empty($_category)) {
						foreach($_category as $category) : 
						$selected = (isset($edit) && isset($edit->category) && $edit->category == $category->category_id ? 'selected' : ''); ?>
							<option value="<?= $category->category_id; ?>" <?= $selected; ?>><?= toPropercase($category->name); ?></option>
						<?php endforeach;
						} ?>
					</select>
					<span class="form-text text-danger" id="error_user_id"></span>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-4 form-group">
					<label>Discount*</label>
					<input type="text" class="form-control numbers_only" name="discount" id="discount" placeholder="Enter discount" maxlength="2" value="<?= isset($edit) && isset($edit->discount) ? $edit->discount : set_value('discount'); ?>" />
					<span class="form-text text-danger" id="error_discount"></span>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-4 form-group">
					<label>Banner</label>
					<div class="custom-file">
						<input type="file" class="custom-file-input" id="banner" name="banner" accept="image/x-png, image/jpeg" />
						<label class="custom-file-label">Choose file</label>
					</div>
					<span class="form-text text-danger" id="error_banner"></span>
					<span class="form-text text-danger" id="error_banner_type"></span>
					<span class="form-text text-danger" id="error_banner_size"></span>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-4 form-group">
					<label>Short Video</label>
					<div class="custom-file">
						<input type="file" class="custom-file-input" id="short_video" name="short_video" accept="image/x-png, image/jpeg" />
						<label class="custom-file-label">Choose file</label>
					</div>
					<span class="form-text text-danger" id="error_short_video"></span>
					<span class="form-text text-danger" id="error_short_video_type"></span>
					<span class="form-text text-danger" id="error_short_video_size"></span>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-4 form-group">
					<label>Images</label>
					<div class="custom-file">
						<input type="file" class="custom-file-input" id="media" name="medias[]" accept="image/x-png, image/jpeg" multiple />
						<label class="custom-file-label">Choose file</label>
					</div>
					<span class="form-text text-danger" id="error_media"></span>
					<span class="form-text text-danger" id="error_type"></span>
					<span class="form-text text-danger" id="error_size"></span>
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-2 form-group">
					<label>Special</label>
                    <div class="radio-inline">
                    <?php foreach($type as $key => $value): 
                    $checked = (isset($edit) && isset($edit->is_special) && $edit->is_special == $key ? 'checked' : ''); ?>
                        <label class="radio radio-rounded radio-brand">
                            <input type="radio" name="is_special" class="is_special" value="<?= $key; ?>" <?= $checked; ?> /> <?= $value; ?>
                            <span></span>
                        </label>
                    <?php endforeach; ?>
                    </div>
					<span class="form-text text-danger" id="error_is_special"></span>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-2 form-group">
					<label>Premium</label>
                    <div class="radio-inline">
                    <?php foreach($type as $key => $value): 
                    $checked = (isset($edit) && isset($edit->is_premium) && $edit->is_premium == $key ? 'checked' : ''); ?>
                        <label class="radio radio-rounded radio-brand">
                            <input type="radio" name="is_premium" class="is_premium" value="<?= $key; ?>" <?= $checked; ?> /> <?= $value; ?>
                            <span></span>
                        </label>
                    <?php endforeach; ?>
                    </div>
					<span class="form-text text-danger" id="error_is_premium"></span>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-2 form-group">
					<label>Delivery</label>
                    <div class="radio-inline">
                    <?php foreach($type as $key => $value): 
                    $checked = (isset($edit) && isset($edit->is_delivery) && $edit->is_delivery == $key ? 'checked' : ''); ?>
                        <label class="radio radio-rounded radio-brand">
                            <input type="radio" name="is_delivery" class="is_delivery" value="<?= $key; ?>" <?= $checked; ?> /> <?= $value; ?>
                            <span></span>
                        </label>
                    <?php endforeach; ?>
                    </div>
					<span class="form-text text-danger" id="error_is_delivery"></span>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-6 form-group">
					<label>Advertise Duration</label>
                    <div class="input-daterange input-group ctm_datepicker">
						<input type="text" class="form-control" name="start_on" />
						<div class="input-group-append">
							<span class="input-group-text">
								<i class="la la-ellipsis-h"></i>
							</span>
						</div>
						<input type="text" class="form-control" name="end_on" />
					</div>
					<span class="form-text text-danger" id="error_duration"></span>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12 form-group">
	                <textarea class="ctm_sn" name="description" id="description"><?= isset($edit) && isset($edit->description) ? $edit->description : set_value('description'); ?></textarea>
			    </div>
			</div>
		</div>
		<div class="card-footer bg-gray-100 border-top-0">
			<div class="row align-items-center">
				<div class="col text-left"></div>
				<div class="col text-right">
					<input type="hidden" name="advertise_id" value="<?= isset($edit) && isset($edit->advertise_id) ? $edit->advertise_id : set_value('advertise_id'); ?>" />
					<a href="<?= base_url('console/advertise'); ?>" class="btn btn-light-primary font-weight-bolder mr-2"> <i class="ki ki-long-arrow-back icon-sm"></i>Back </a>
					<button type="submit" data-url="console/advertise/view" class="btn btn-primary font-weight-bolder submit_btn bt-group"> <i class="ki ki-check icon-sm"></i>Save advertise </button>
				</div>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
$('.back_btn').click(function() {
	window.location.href = base_url + 'console/advertise';
});

$(document).ready(function() {
	var continue_to = base_url + 'console/advertise';
	
	$('body').on('change blur', 'input, select', function() {
		$(this).closest('.form-group').removeClass('is-invalid');
	});

	$('body').on('change blur', '#category', function() {
		$('#error_category').html('').hide();
		if($(this).val() == '') {
			$('#error_category').html('Choose category').show();
		}
	});

	$('body').on('change blur', '#user_id', function() {
		$('#error_user_id').html('').hide();
		if($(this).val() == '') {
			$('#error_user_id').html('Choose vendor').show();
		}
	});

	$('body').on('change blur', '#discount', function() {
		$('#error_discount').html('').hide();
		if($('#discount').val().trim() == '') {
			$('#error_discount').html('Enter discount').show();
		} else if(!validateNumber($(this).val().trim())) {
			$('#error_discount').html('Enter valid discount').show();
		}
	});

	$('#media').change(function() {	
		$('#error_media').html('').hide();
		var fp            = $("#media");
		var lg            = fp[0].files.length;
		var items         = fp[0].files;
		var fileSize      = 0;
		var fileExtension = ['jpeg', 'jpg', 'png'];

		if (lg > 0) {
			for (var i = 0; i < lg; i++) {
				fileSize = fileSize+items[i].size; // get file size
			}
			if(fileSize > 52428800) {
				var msg = 'Upload file size up to 10 MB';
				$('#error_size').html(msg).show();
			}
		}
       
		if($(this).val() == '') {
			$('#error_media').html('Select file').show();
		} else if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
			var msg = 'Only these formats are allowed : jpeg, jpg, png';
			$('#error_type').html(msg).show();
        }
	});
	
	$('#advertise-form').submit(function(e) {
		var redirect = base_url + $(this).data('url');
		var isValid  = 1;

		if($('#category').val() == '') {
			isValid = 0;
			$('#category').parents('.form-group').addClass('is-invalid');
			$('#error_category').html('Choose category').show();
		}

		if($('#user_id').val() == '') {
			isValid = 0;
			$('#user_id').parents('.form-group').addClass('is-invalid');
			$('#error_user_id').html('Choose vendor').show();
		}

		if($('#discount').val().trim() == '') {
			isValid = 0;
			$('#discount').parents('.form-group').addClass('is-invalid');
			$('#error_discount').html('Enter discount').show();
		} else if(!validateNumber($('#discount').val().trim())) {
			isValid = 0;
			$('#discount').parents('.form-group').addClass('is-invalid');
			$('#error_discount').html('Enter valid discount').show();
		}

		if($('#media').val() != '') {
			var fileExtension = ['jpeg', 'jpg', 'png'];
			var fileSize = 0;
			fileSize = $("#media")[0].files[0].size;

			if(fileSize > 52428800) {
				isValid = 0;
				var msg = 'Upload file size up to 10 MB';
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
});
</script>