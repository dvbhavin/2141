<?php //xdebug($edit); ?>
<div class="card card-custom gutter-b example example-compact">
	<!--begin::Form-->
	<form id="category-form" class="form" method="post" enctype="multipart/form-data">
		<div class="card-footer bg-gray-100 border-top-0">
			<div class="row align-items-center">
				<div class="col text-left"></div>
				<div class="col text-right">
					<input type="hidden" name="category_id" value="<?= isset($edit) && isset($edit->category_id) ? $edit->category_id : set_value('category_id'); ?>" />
					<input type="hidden" name="previous" value="<?= isset($edit) && isset($edit->name) ? $edit->name : set_value('name'); ?>" />
					<a href="<?= base_url('console/category'); ?>" class="btn btn-light-primary font-weight-bolder mr-2"> <i class="ki ki-long-arrow-back icon-sm"></i>Back </a>

					<div class="btn-group">
						<button type="submit" data-url="console/category/view" class="btn btn-primary font-weight-bolder submit_btn bt-group"> <i class="ki ki-check icon-sm"></i>Save category </button>
						<!-- <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split bt-group" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
						<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
							<ul class="nav nav-hover flex-column">
								<li class="nav-item">
									<a href="javascript:;" class="nav-link submit_btn" data-url="console/category/view">
										<i class="nav-icon flaticon2-add-1"></i>
										<span class="nav-text">Save &amp; add more category</span>
									</a>
								</li>
							</ul>
						</div> -->
					</div>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-5 form-group">
					<label>Choose Parent Category</label>
					<select class="form-control select2 ctm_s2 ctm_s2_ar" id="parent" name="parent_id" data-placeholder="Choose parent category">
						<option></option>
						<?php if(isset($_parent) && !empty($_parent)) {
						foreach($_parent as $parent) : 
						$selected = (isset($edit) && isset($edit->parent_id) && $edit->parent_id == $parent->category_id ? 'selected' : ''); ?>
							<option value="<?= $parent->category_id; ?>" <?= $selected; ?>><?= toPropercase($parent->name); ?></option>
						<?php endforeach;
						} ?>
					</select>
					<span class="form-text text-danger" id="error_parent"></span>
				</div>
			
				<div class="col-sm-12 col-md-6 col-lg-5 form-group">
					<label>Category Name*</label>
					<input type="text" class="form-control" name="name" id="name" placeholder="Enter category name" value="<?= isset($edit) && isset($edit->name) ? $edit->name : set_value('name'); ?>" />
					<span class="form-text text-danger" id="error_name"></span>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-2 form-group">
					<label>Order</label>
					<input type="text" class="form-control numbers_only" name="is_order" id="is_order" placeholder="Enter category order" maxlength="2" value="<?= isset($edit) && isset($edit->is_order) ? $edit->is_order : set_value('is_order'); ?>" />
					<span class="form-text text-danger" id="error_is_order"></span>
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-9 form-group">
					<label>Description</label>
					<textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter description"><?= isset($edit) && isset($edit->description) ? $edit->description : set_value('description'); ?></textarea>
					<span class="form-text text-danger" id="error_description"></span>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-3 form-group">
		            <?php 
						$media    = (isset($edit) && isset($edit->media) && !empty($edit->media) ? mcat().$edit->media : ''); 
						$media_id = (isset($edit) && isset($edit->media_id) ? $edit->media_id : '');
		            ?>
		            <div class="image-input image-input-outline" id="ctm_media" style="background-image: url(media/users/upload.png)">
						<div class="image-input-wrapper" style="background-image: url(<?= $media; ?>)"></div>
						<label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
							<i class="fa fa-pen icon-sm text-muted"></i>
							<input type="file" id="media" name="media" accept=".png, .jpg, .jpeg" />
							<input type="hidden" name="profile_avatar_remove" value="1" />
						</label>
						<span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
							<i class="ki ki-bold-close icon-xs text-muted"></i>
						</span>
						<span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow delete" data-action="remove" data-id="<?= $media_id; ?>" data-table="media" data-row="media_id" data-toggle="tooltip" title="Remove avatar">
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
	window.location.href = base_url + 'console/category';
});

$(document).ready(function() {
	var continue_to = base_url + 'console/category';
	
	$('body').on('change blur', 'input, select', function() {
		$(this).closest('.form-group').removeClass('is-invalid');
	});

	$('body').on('change blur', '#name', function() {
		$('#error_name').html('').hide();
		if($(this).val().trim() == '') {
			$('#error_name').html('Enter category name').show();
		} else if(!validateName($(this).val().trim())) {
			$('#error_name').html('Numbers are not allowed').show();
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
	
	$('body').on('change blur', '#is_order', function() {
		$('#error_is_order').html('').hide();
		if($('#is_order').val().trim() == '') {
			$('#error_is_order').html('Enter category order').show();
		} else if(!validateNumber($(this).val().trim())) {
			$('#error_is_order').html('Enter valid order no').show();
		}
	});
	
	$('#category-form').submit(function(e) {
		var redirect = base_url + $(this).data('url');
		var isValid  = 1;

		if($('#name').val().trim() == '') {
			isValid = 0;
			$('#name').parents('.form-group').addClass('is-invalid');
			$('#error_name').html('Enter category name').show();
		} else if(!validateName($('#name').val().trim())) {
			isValid = 0;
			$('#name').parents('.form-group').addClass('is-invalid');
			$('#error_name').html('Numbers are not allowed').show();
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

		if($('#is_order').val() != '' && !validateNumber($('#is_order').val().trim())) {
			isValid = 0;
			$('#is_order').parents('.form-group').addClass('is-invalid');
			$('#error_is_order').html('Enter valid order no').show();
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
					url: base_url + 'console/category/delete/',
					data:{'id' : id, 'table' : table, 'row' : row},
					success: function(result) {
						if(result == 'success')
						Swal.fire('Deleted!', 'Image successfully removed !', 'success')
					}
				});
			}
		});

    });
    // end file upload

});
</script>