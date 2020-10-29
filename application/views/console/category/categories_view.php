<?php //xdebug($_list); ?>
<div class="card card-custom gutter-b">
	<div class="card-body">
		<!--begin: Datatable-->
		<table class="table table-bordered table-checkable ctm_dt">
			<thead>
				<tr>
					<th width="1%">No</th>
					<th width="2%">Category Name</th>
					<th width="2%">Parent Category</th>
					<th width="3%">Description</th>
					<th width="1%">Order</th>
					<th width="1%">Image</th>
					<th width="2%">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($_list as $list) : ?>
				<tr id="cr-<?= $list->category_id; ?>">
					<td><?= $list->category_id; ?></td>
					<td><?= toPropercase($list->name); ?></td>
					<td><?= toPropercase($list->parent); ?></td>
					<td><?= toPropercase($list->description); ?></td>
					<td><?= $list->is_order; ?></td>
					<td>
						<?php if(isset($list->media) && !empty($list->media)) { ?>
						<img src="<?= mcat().$list->media; ?>" height="70" width="70">
						<?php } ?>
					</td>
					<td>
						<a href="<?= base_url().'console/category/edit/'.$list->category_id; ?>" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit"> <i class="flaticon-edit text-success"></i> </a>

                        <a href="javascript:;" data-id="<?= $list->category_id; ?>" data-table="category" data-row="category_id" class="btn btn-sm btn-clean btn-icon mr-2 delete_btn" title="Delete"> <i class="far fa-trash-alt text-danger"></i> </a>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<!--end: Datatable-->
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
					url: base_url + 'console/category/delete/',
					data:{'table' : table, 'row' : row, 'id' : id},
					success: function(result) {
						if(result == 'success')
						Swal.fire('Deleted!', 'Your category has been deleted.', 'success')

						$('#cr-'+id).addClass('d-none');
					}
				});
			}
		});

	});

});
</script>