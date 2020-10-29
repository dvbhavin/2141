<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<?php $this->load->view('common/head_view'); ?>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed page-loading">
		<?php $this->load->view('common/header_view'); ?>
			<!--begin::Content-->
			<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
				<!--begin::Subheader-->
				<?php if(isset($breadcrumb) && !empty($breadcrumb) && $breadcrumb > 0) { ?>
				<div class="subheader py-2 py-lg-4 subheader-transparent mt-25" id="kt_subheader">
					<div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
						<div class="d-flex align-items-center flex-wrap mr-1">
							<!--begin::Page Heading-->
							<div class="d-flex align-items-baseline mr-5">
								<!--begin::Page Title-->
								<?php if(isset($title) && !empty($title)) { ?>
								<h5 class="text-dark font-weight-bold my-2 mr-5"><?= strtoupper($title); ?></h5>
								<?php } ?>
								<!--end::Page Title-->
								<!--begin::Breadcrumb-->
								<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
									<?php foreach($breadcrumb as $title => $link) {
										echo '<li class="breadcrumb-item">';
										echo $link === NULL ? '<a href="javascript:;" class="text-muted">'.ucwords($title).'</a>' : '<a href='.base_url().$link.' class="text-muted">'.ucwords($title).'</a></li>';
									}?>
								</ul>
								<!--end::Breadcrumb-->
							</div>
							<!--end::Page Heading-->
						</div>
						<?php if(isset($action) && !empty($action)) { ?>
						<div class="d-flex align-items-center">
							<a href="<?= $action; ?>" class="btn btn-light-primary font-weight-bolder btn-sm">
								Add
							</a>
						</div>
						<?php } ?>
					</div>
				</div>
				<?php } ?>
				<!--end::Subheader-->
				<!--begin::Entry-->
				<div class="d-flex flex-column-fluid">
					<!--begin::Container-->
					<div class="container">
						<!--begin::Alert-->
						<div class="alert alert-custom alert-notice shadow-lg d-none" id="alt-box">
						    <div class="alert-icon" id="alt-icon"></div>
						    <div class="alert-text fs-19" id="alt-message"></div>
						</div>
						<!--end::Alert-->

						<!--begin::Dashboard-->
						<mp:Content/>
						<!--end::Dashboard-->
					</div>
					<!--end::Container-->
				</div>
				<!--end::Entry-->
			</div>
			<!--end::Content-->
		<?php $this->load->view('common/footer_view'); ?>
	</body>
	<!--end::Body-->
</html>