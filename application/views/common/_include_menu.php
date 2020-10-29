<?php
$current          = $this->uri->segment(2);
$user_role        = $userdata['user_role'];
$has_admin_access = [User_role::SUPER_ADMIN, User_role::SYSTEM_ADMIN];
?>
<div class="page-header-menu">
    <div class="container-fluid">
        <!-- BEGIN MEGA MENU -->
        <div class="hor-menu">
            <ul class="nav navbar-nav menu flex">
                <?php if(in_array($user_role, $has_admin_access)) { ?>
				<li class="<?= (!empty($current) && $current == 'dashboard' ? 'active' : ''); ?>">
                    <a href="<?= base_url('console/dashboard'); ?>"> Dashboard </a>
                </li>
                <li class="<?= (!empty($current) && $current == 'bill' ? 'active' : ''); ?>">
                    <a href="<?= base_url('console/bill'); ?>"> Bill </a>
                </li>
				<li class="<?= (!empty($current) && $current == 'order' ? 'active' : ''); ?>">
                    <a href="<?= base_url('console/order'); ?>"> Order </a>
                </li>
				<li class="<?= (!empty($current) && $current == 'estimate' ? '' : ''); ?>">
                    <a href="<?= base_url('console/estimate'); ?>"> Estimate </a>
                </li>
				<li class="<?= (!empty($current) && $current == 'employee' ? 'active' : ''); ?>">
                    <a href="<?= base_url('console/employee'); ?>"> Employee </a>
                </li>
				<li class="<?= (!empty($current) && $current == 'settings' ? 'active' : ''); ?>">
                    <a href="<?= base_url('console/settings'); ?>"> Masters </a>
                </li>
				<?php } ?>
            </ul>
        </div>

    </div>
</div>