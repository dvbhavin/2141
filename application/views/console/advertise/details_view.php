<?php // xdebug($_media); ?>
<div class="card card-custom gutter-b">
	<div class="card-body">
		<div class="d-flex">
			<div class="flex-grow-1">
				<div class="d-flex align-items-center justify-content-between flex-wrap">
					<div class="mr-3">
						<span class="d-flex align-items-center text-primary font-size-h5 font-weight-bold mr-3">
							<?= toPropercase($product->title); ?>
						</span>
						<span class="label label-light-info label-inline mr-2"><?= Status::getValue($product->status); ?></span>
					</div>
					<div class="my-lg-0 my-1">
						<a href="javascript:;" class="btn btn-sm btn-light-success font-weight-bolder text-uppercase mr-3"><?= $product->domain; ?></a>
						<a href="javascript:;" class="btn btn-sm btn-info font-weight-bolder text-uppercase">
						<?= $product->name; ?> </a>
					</div>
				</div>
				<div class="d-flex align-items-center flex-wrap justify-content-between">
					<div class="flex-grow-1 font-weight-bold text-dark-50 py-5 py-lg-2 mr-5">
						<?= $product->short_detail; ?>
					</div>
					<div class="flex-grow-1 font-weight-bold text-dark-50 py-5 py-lg-2 mr-5">
						<?= $product->description; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="separator separator-solid my-7"></div>
		<!--begin: Items-->
		<div class="d-flex align-items-center flex-wrap">
			<?php if(isset($product->price) && !empty($product->price)) { ?>
			<div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
				<span class="mr-4">
					<i class="flaticon-piggy-bank icon-2x text-muted font-weight-bold"></i>
				</span>
				<div class="d-flex flex-column text-dark-75">
					<span class="font-weight-bolder font-size-sm">Price</span>
					<span class="font-weight-bolder font-size-h5">
					<span class="text-dark-50 font-weight-bold"></span><?= number_format($product->price, 0); ?></span>
				</div>
			</div>
			<?php } if(isset($product->is_type) && !empty($product->is_type)) { ?>
			<div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
				<span class="mr-4">
					<i class="flaticon-confetti icon-2x text-muted font-weight-bold"></i>
				</span>
				<div class="d-flex flex-column text-dark-75">
					<span class="font-weight-bolder font-size-sm">Product Type</span>
					<span class="font-weight-bolder font-size-h5">
					<span class="text-dark-50 font-weight-bold"></span>
					<?php $xtype = explode(',', $product->is_type);
						foreach($xtype as $type) :
							echo Product_type::getValue($type).', ';
						endforeach; ?>
					</span>
				</div>
			</div>
			<?php } ?>
			
			<div class="d-flex align-items-center flex-lg-fill my-1">
				<span class="mr-4">
					<i class="flaticon-layer icon-2x text-muted font-weight-bold"></i>
				</span>
				<div class="symbol-group symbol-hover">
					<?php foreach($_media as $media) : ?>
					<div class="symbol symbol-80" title="<?= toPropercase($product->title); ?>" id="mr-<?= $media->media_id; ?>">
						<span class="label label-rounded label-danger delete-budget delete_media" data-id="<?= $media->media_id; ?>" data-table="media" data-row="media_id">
							<i class="fas fa-times icon-nm"></i>
						</span>
						<img src="<?= mproduct().$media->media; ?>"/>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<!--begin: Items-->
	</div>
</div>
<script>
$(document).ready(function() {
    
	$('.symbol-hover').on('click', '.delete_media', function () {
        var id    = $(this).data('id');
        var row   = $(this).data('row');
		var table = $(this).data('table');

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
					url: base_url + 'console/product/delete/',
					data:{'table' : table, 'row' : row, 'id' : id},
					success: function(result) {
						if(result == 'success')
						Swal.fire('Deleted!', 'Product image has been deleted.', 'success')

						setTimeout(function() {
							$('#mr-'+id).addClass('d-none');
						}, 1000);
					}
				});
			}
		});

	});

});
</script>