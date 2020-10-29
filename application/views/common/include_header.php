<?php 
$uname   = strtoupper($userdata['user_name']);
$current = $this->uri->segment(2);
//$user_role        = $userdata['user_role'];
//$has_admin_access = [User_role::SUPER_ADMIN, User_role::SYSTEM_ADMIN];
?>
<div id='kt_header' class='header flex-column header-fixed'>
	<!--begin::Top-->
	<div class='header-top'>
		<!--begin::Container-->
		<div class='container'>
			<!--begin::Left-->
			<div class='d-none d-lg-flex align-items-center mr-3'>
				<!--begin::Logo-->
				<a href='<?= base_url(); ?>' class='mr-20'>
					<img alt='shopping-13' src='media/logos/white-logo.png' class='max-h-60px' />
				</a>
				<!--end::Logo-->
			</div>
			<!--end::Left-->
			<!--begin::Topbar-->
			<div class='topbar'>
				<!--begin::User-->
				<div class='topbar-item'>
					<div class='btn btn-icon btn-hover-transparent-white w-auto d-flex align-items-center btn-lg px-2' id='kt_quick_user_toggle'>
						<div class='d-flex flex-column text-right pr-3'>
							<span class='opacity-50 font-weight-bold font-size-sm d-none d-md-inline'>User</span>
							<span class='font-weight-bolder font-size-sm d-none d-md-inline'><?= toPropercase($userdata['user_name']); ?></span>
						</div>
						<span class='symbol symbol-35'>
							<span class='symbol-label font-size-h5 font-weight-bold text-white bg-white-o-30'><?= $uname[0]; ?></span>
						</span>
					</div>
				</div>
				<!--end::User-->
			</div>
			<!--end::Topbar-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Top-->
	<!--begin::Bottom-->
	<div class='header-bottom'>
		<!--begin::Container-->
		<div class='container'>
			<!--begin::Header Menu Wrapper-->
			<div class='header-menu-wrapper header-menu-wrapper-left' id='kt_header_menu_wrapper'>
				<!--begin::Header Menu-->
				<div id='kt_header_menu' class='header-menu header-menu-left header-menu-mobile header-menu-layout-default'>
					<!--begin::Header Nav-->
					<ul class='menu-nav'>
						<li class='menu-item' data-menu-toggle='hover' aria-haspopup='true'>
							<a href='<?= base_url('console/category'); ?>' class='menu-link'>
								<span class='menu-text <?= (!empty($current) && $current == 'category' ? 'current' : ''); ?>'>Category</span>
							</a>
						</li>
						<li class='menu-item' data-menu-toggle='hover' aria-haspopup='true'>
							<a href='<?= base_url('console/advertise'); ?>' class='menu-link'>
								<span class='menu-text <?= (!empty($current) && $current == 'advertise' ? 'current' : ''); ?>'>Advertise</span>
							</a>
						</li>
						<li class='menu-item' data-menu-toggle='hover' aria-haspopup='true'>
							<a href='<?= base_url('console/user'); ?>' class='menu-link'>
								<span class='menu-text <?= (!empty($current) && $current == 'user' ? 'current' : ''); ?>'>User</span>
							</a>
						</li>
						<li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="hover" aria-haspopup="true">
							<a href='<?= base_url('console/master'); ?>' class='menu-link'>
								<span class='menu-text <?= (!empty($current) && $current == 'master' ? 'current' : ''); ?>'>Master</span>
							</a>
							<!-- <div class="menu-submenu menu-submenu-classic menu-submenu-left">
								<ul class="menu-subnav">
									<li class="menu-item menu-item-submenu">
										<a href="javascript:;" class="menu-link menu-toggle">
											<span class="menu-text">Tax</span>
										</a>
									</li>
									<li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
										<a href="javascript:;" class="menu-link menu-toggle">
											<span class="menu-text">Category</span>
											<i class="menu-arrow"></i>
										</a>
										<div class="menu-submenu menu-submenu-classic menu-submenu-right">
											<ul class="menu-subnav">
												<li class="menu-item" aria-haspopup="true">
													<a href="crud/file-upload/image-input.html" class="menu-link">
														<i class="menu-bullet menu-bullet-dot">
															<span></span>
														</i>
														<span class="menu-text">Image Input</span>
													</a>
												</li>
												<li class="menu-item" aria-haspopup="true">
													<a href="crud/file-upload/dropzonejs.html" class="menu-link">
														<i class="menu-bullet menu-bullet-dot">
															<span></span>
														</i>
														<span class="menu-text">DropzoneJS</span>
													</a>
												</li>
												<li class="menu-item" aria-haspopup="true">
													<a href="crud/file-upload/uppy.html" class="menu-link">
														<i class="menu-bullet menu-bullet-dot">
															<span></span>
														</i>
														<span class="menu-text">Uppy</span>
													</a>
												</li>
											</ul>
										</div>
									</li>
								</ul>
							</div> -->
						</li>
					</ul>
					<!--end::Header Nav-->
				</div>
				<!--end::Header Menu-->
			</div>
			<!--end::Header Menu Wrapper-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Bottom-->
</div>