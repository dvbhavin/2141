<div class="card card-custom gutter-b">
	<div class="card-body">
		<div class="example example-basic">
			<!-- <p></p> -->
			<div class="example-preview">
				<ul class="navi">
					<li class="navi-item">
						<a class="navi-link" href="<?= base_url('console/app/add'); ?>">
							<span class="symbol symbol-50 mr-3">
								<span class="symbol-label">
									<i class="flaticon2-add-1 text-danger"></i>
								</span>
							</span>
							<div class="navi-text">
								<span class="d-block font-weight-bold">Add App</span>
								<!-- <span class="text-muted">Inbox and notifications</span> -->
							</div>
						</a>
					</li>
					
					<li class="navi-item">
						<a class="navi-link" href="<?= base_url('console/app/view'); ?>">
							<span class="symbol symbol-50 mr-3">
								<span class="symbol-label">
									<i class="far fa-window-restore text-success"></i>
								</span>
							</span>
							<div class="navi-text">
								<span class="d-block font-weight-bold">View App</span>
							</div>
							<span class="navi-label">
								<span class="label label-danger label-rounded"><?= $count; ?></span>
							</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>