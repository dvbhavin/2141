<div class="card card-custom gutter-b">
	<div class="card-body">
		<!--begin: Datatable-->
		<table class="table table-bordered table-checkable" id="ctm_dt">
			<thead>
				<tr>
					<th>No</th>
					<th>Name</th>
					<th>Percentage</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($_list as $list) : ?>
				<tr>
					<td><?= $list->tax_id; ?></td>
					<td><?= toPropercase($list->name); ?></td>
					<td><?= $list->percentage; ?>%</td>
					<td>
						<a href="<?= base_url().'console/master/edit_tax/'.$list->tax_id; ?>" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit">
                            <i class="flaticon-edit text-success"></i>
                        </a>

                        <a href="javascript:;" data-id="<?= $list->tax_id; ?>" data-table="tax" data-row="tax_id" class="btn btn-sm btn-clean btn-icon mr-2 delete_btn" title="Deactivate">
                            <i class="fas fa-unlink text-danger"></i>
                        </a>
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
    
	$('#ctm_dt').on('click', '.delete_btn', function () {
        var table = $(this).data('table');
        var row   = $(this).data('row');
        var id    = $(this).data('id');

		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes, delete it!'
		}).then(function (result) {
			if (result.value) {

				$.ajax({
					type: 'POST',
					url: base_url + 'console/master/delete/',
					data:{'table' : table, 'row' : row, 'id' : id},
					success: function(result) {
						if(result == 'success')
						Swal.fire('Deleted!', 'Your '+ table +' has been deleted.', 'success')

						setTimeout(function() {
							window.location.href = base_url + 'console/master';
						}, 700);
					}
				});

			}
		});

	});

});
</script>