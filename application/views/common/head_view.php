<?php $current_url = $this->uri->segment(1); ?>
<head>
	<base href='<?= asset_url(); ?>'>
	<meta charset='utf-8' />
	<title><mp:Title/> - Shopping 13</title>
	<meta name='description' content='' />
	<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no' />
	<!--begin::Fonts-->
	<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700'/>
	
	<link href='css/pages/login/login-1.css' rel='stylesheet' />
	<link href='plugins/custom/datatables/datatables.bundle.css' rel='stylesheet' />
	<link href='plugins/global/plugins.bundle.css' rel='stylesheet' />
	<link href='plugins/custom/prismjs/prismjs.bundle.css' rel='stylesheet' />
	<link href='css/style.bundle.css' rel='stylesheet' />
	
	<!--custom css-->
	<link href='css/custom.css' rel='stylesheet' />

	<link href="media/favicon/apple-icon-60x60.png" rel="apple-touch-icon" sizes="60x60">
	<link href="media/favicon/apple-icon-57x57.png" rel="apple-touch-icon" sizes="57x57">
	<link href="media/favicon/apple-icon-72x72.png" rel="apple-touch-icon" sizes="72x72">
	<link href="media/favicon/apple-icon-76x76.png" rel="apple-touch-icon" sizes="76x76">
	<link href="media/favicon/apple-icon-114x114.png" rel="apple-touch-icon" sizes="114x114">
	<link href="media/favicon/apple-icon-120x120.png" rel="apple-touch-icon" sizes="120x120">
	<link href="media/favicon/apple-icon-144x144.png" rel="apple-touch-icon" sizes="144x144">
	<link href="media/favicon/apple-icon-152x152.png" rel="apple-touch-icon" sizes="152x152">
	<link href="media/favicon/apple-icon-180x180.png" rel="apple-touch-icon" sizes="180x180">
	<link href="media/favicon/android-icon-192x192.png" rel="icon" type="image/png" sizes="192x192">
	<link href="media/favicon/favicon-32x32.png" rel="icon" type="image/png" sizes="32x32">
	<link href="media/favicon/favicon-96x96.png" rel="icon" type="image/png" sizes="96x96">
	<link href="media/favicon/favicon-16x16.png" rel="icon" type="image/png" sizes="16x16">
	<meta content="media/favicon/ms-icon-144x144.png" name="msapplication-TileImage">

	<script src='js/jquery.js'></script>
</head>
<script>
var base_url = '<?= base_url(); ?>';
<?php if(isset($current_url)) { ?>
	var current_url = '<?= $current_url; ?>';
<?php } ?>
var error_report_mail = '<?= error_report_mail('link'); ?>';
<?php if(isset($sess_expiration_time)) { ?>
	var sess_expiration_time = '<?= $sess_expiration_time; ?>';
<?php } ?>
</script>