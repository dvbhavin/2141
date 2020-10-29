<div class="error_page">
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="row">
					<div class="col-md-6">
						<a class="logo" href="<?php echo base_url(); ?>">
							<img class="img-responsive" src="<?= asset_url(); ?>layouts/img/logo.png" style="max-width:50%">
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<!-- <h3><strong>Oops! An Error Occurred</strong></h3> -->
						<h4><strong><?php echo $heading; ?></strong></h4>
						<?php echo $message; ?>
						<strong>Possible causes:</strong>
						<ul>
							<?php echo $reason; ?>
						</ul>
						<strong>What you can try:</strong>
						<ul>
							<?php echo $action; ?>
						</ul>
					</div>
					<div class="col-md-4 col-md-offset-0 col-xs-6 col-xs-offset-3">
						<img class="img-responsive" src="<?php echo asset_url(); ?>layouts/error_icons/<?= $status_code; ?>.png">
					</div>
				</div>
				<br>
				<div class="text-center">
					<a class="btn btn-default" href="<?php echo base_url(); ?>">GO BACK HOME</a>
					<a class="btn btn-danger" target="_blank" href="<?php echo error_report_mail('link'); ?>">REPORT THIS ERROR</a>
				</div>
				<p><strong>Error code: <?php echo $status_code; ?></strong></p>
				<strong>More information about this error:</strong>
				<p><?php echo $error_info; ?></p>
			</div>
		</div>
	</div>
</div>