<?php //xdebug($_list); ?>
<div class="card card-custom gutter-b">
	<div class="card-body">
		<table class="table table-bordered table-checkable ctm_dt">
			<thead>
				<tr>
					<th width="1%">No</th>
					<th width="2%">Vendor</th>
					<th width="2%">Category</th>
					<th width="1%">Special</th>
					<th width="1%">Premium</th>
					<th width="1%">Delivery</th>
					<th width="2%">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($_list as $list) : ?>
				<tr id="ad-<?= $list->advertise_id; ?>">
					<td><?= $list->advertise_id; ?></td>
					<td><?= toPropercase($list->shop_name); ?></td>
					<td><?= toPropercase($list->category_name); ?></td>
					<td>
						<?php if(isset($list->is_special) && !empty($list->is_special)) {
							echo '<span class="label label-light-info label-inline mr-2">'.Yes_no::getValue($list->is_special).'</span>';
						} ?>
					</td>
					<td>
						<?php if(isset($list->is_premium) && !empty($list->is_premium)) {
							echo '<span class="label label-light-danger label-inline mr-2">'.Yes_no::getValue($list->is_premium).'</span>';
						} ?>
					</td>
					<td>
						<?php if(isset($list->is_delivery) && !empty($list->is_delivery)) {
							echo '<span class="label label-light-dark label-inline mr-2">'.Yes_no::getValue($list->is_delivery).'</span>';
						} ?>
					</td>
					<td>
						<!-- <a href="<?= base_url().'console/product/details/'.$list->advertise_id; ?>" class="btn btn-sm btn-clean btn-icon mr-2" title="Details">
                            <i class="flaticon-information text-info"></i>
                        </a>

						<a href="<?= base_url().'console/advertise/edit/'.$list->advertise_id; ?>" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit">
                            <i class="flaticon-edit text-success"></i>
                        </a> -->

                        <a href="javascript:;" data-id="<?= $list->advertise_id; ?>" data-table="advertise" data-row="advertise_id" class="btn btn-sm btn-clean btn-icon mr-2 delete_btn" title="Delete">
                            <i class="far fa-trash-alt text-danger"></i>
                        </a>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
<script>
$(document).ready(function() {
    
	$('.ctm_dt').on('click', '.delete_btn', function () {
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
					url: base_url + 'console/advertise/delete/',
					data:{'table' : table, 'row' : row, 'id' : id},
					success: function(result) {
						if(result == 'success')
						Swal.fire('Deleted!', 'Your advertise has been deleted.', 'success')

						$('#ad-'+id).addClass('d-none');
					}
				});
			}
		});

	});

});
</script>