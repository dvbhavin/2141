<div class="card card-custom gutter-b">
	<div class="card-body">
		<!--begin: Datatable-->
		<table class="table table-bordered table-checkable ctm_dt">
			<thead>
				<tr>
					<th>No</th>
					<th>App Name</th>
					<th>Email</th>
					<th>Mobile No</th>
					<th>Status</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($_list as $list) : ?>
				<tr>
					<td><?= $list->app_id; ?></td>
					<td><b><?= toPropercase($list->name); ?></b></td>
					<td><?= $list->email; ?></td>
					<td><?= $list->mobile; ?></td>
					<td>
					<?php if(isset($list->status) && $list->status == Status::ACTIVE) { ?>
						<span class="btn btn-link-primary font-weight-bold"><?= Status::getValue($list->status); ?></span>
					<?php } else { ?>
						<span class="btn btn-link-danger font-weight-bold"><?= Status::getValue($list->status); ?></span>
					<?php } ?>
					</td>
					<td>
						<a href="<?= base_url().'console/app/edit/'.$list->app_id; ?>" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit">
                            <i class="flaticon-edit text-success"></i>
                        </a>

                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" title="Deactivate">
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