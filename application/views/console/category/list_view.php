<?php // xdebug($_list); ?>
<div class="card card-custom gutter-b">
	<div class="card-body">
		<!--begin: Datatable-->
		<table class="table table-bordered table-checkable ctm_dt">
			<thead>
				<tr>
					<th>No</th>
					<th>App</th>
					<th>No of Category</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($_list as $list) : ?>
				<tr>
					<td><?= $list->app_id; ?></td>
					<td><?= toPropercase($list->name); ?></td>
					<td>
						<a href="<?= base_url().'console/category/app_category/'.$list->app_id; ?>"> 
						<span class="btn btn-link-success font-weight-bold"><?= $list->count; ?></span> 
						</a>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<!--end: Datatable-->
	</div>
</div>