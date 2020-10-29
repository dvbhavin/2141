<?php $badge = '<i class="flaticon2-correct text-success icon-md"></i>'; ?>
<div class="card card-custom gutter-b">
	<div class="card-body">
		<table class="table table-bordered table-checkable ctm_dt">
			<thead>
				<tr>
					<th>No</th>
					<th>
					<?php if(isset($list->role) && !empty($list->role) && $list->role == User_role::VENDOR) { 
							echo 'Shop Name';
						} else if(isset($list->role) && !empty($list->role) && $list->role == User_role::MEMBER) {
							echo 'Name';
						} else {
							echo 'Name';
						} ?>
					</th>
					<th>Email</th>
					<th>Mobile No</th>
					<th>Type</th>
					<!-- <th>Device</th> -->
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($_list as $list) : ?>
				<tr id="ur-<?= $list->user_id; ?>">
					<td><?= $list->user_id; ?></td>
					<td><b>
						<?php if(isset($list->role) && !empty($list->role) && $list->role == User_role::VENDOR) { 
							echo toPropercase($list->shop_name);
						} else if(isset($list->role) && !empty($list->role) && $list->role == User_role::MEMBER) {
							echo toPropercase($list->first_name.' '.$list->last_name);
						} else {
							echo toPropercase($list->first_name.' '.$list->last_name);
						} ?>
						</b></td>
					<td><?= $list->email.' '.($list->email_verified ? $badge : '' ); ?></td>
					<td><?= $list->mobile.' '.($list->mobile_verified ? $badge : '' ); ?></td>
					<td>
						<span class="btn btn-link-primary font-weight-bold"><?= User_role::getValue($list->role); ?></span>
					</td>
					<!-- <td> </td> -->
					<td>
						<!-- <a href="<?= base_url().'console/user/edit/'.$list->user_id; ?>" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit"> <i class="flaticon-edit text-success"></i> </a>

						<a href="<?= base_url().'console/user/detail/'.$list->user_id; ?>" class="btn btn-sm btn-clean btn-icon mr-2" title="Detail"> <i class="flaticon2-user text-primary"></i> </a> -->

                        <a href="javascript:;" data-id="<?= $list->user_id; ?>" data-table="user_master" data-row="user_id" class="btn btn-sm btn-clean btn-icon mr-2 delete_btn" title="Delete"> <i class="far fa-trash-alt text-danger"></i> </a>
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
					url: base_url + 'console/user/delete/',
					data:{'table' : table, 'row' : row, 'id' : id},
					success: function(result) {
						if(result == 'success')
						Swal.fire('Deleted!', 'User has been deleted.', 'success')

						$('#ur-'+id).addClass('d-none');
					}
				});
			}
		});

	});

});
</script>