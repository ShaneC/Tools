<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title><?php echo( $output['page']['title'] ); ?></title>
	<!-- META -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="sc solutions, shanechism.com" />
	<!-- CSS & ICONS -->
	<link rel="icon" href="<?php echo( HTTP_ROOT ); ?>/style/global/images/favicon.png" type="image/png" />
	<link rel="stylesheet" type="text/css" href="<?php echo( HTTP_ROOT ); ?>/style/master/css/index.css" />
	<!-- DYANMIC CSS SHEETS -->
	<?php echo( $output['page']['css'] ); ?>	
	<!-- JS LIBRARIES -->
	<script src='<?php echo( HTTP_ROOT ); ?>/includes/js/libs/jquery.js' type='text/javascript'></script>
	<!-- DYANMIC JS SCRIPTS -->
	<?php echo( $output['page']['js'] ); ?>		
	<!-- DYNAMIC SCRIPT INCLUDE -->
	<?php echo( $output['page']['js-script'] ); ?>	
</head>

<body>

