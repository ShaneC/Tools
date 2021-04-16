<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title><?php echo( $output['page']['title'] ); ?></title>
	<!-- META -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="SC Solutions Framework" />
	<meta name="viewport" content="width=1024" />
	<!-- CSS & ICONS -->
	<link rel="icon" href="<?php echo( HTTP_ROOT ); ?>/style/global/images/favicon.png" type="image/png" />
	<link rel="stylesheet" type="text/css" href="<?php echo( HTTP_ROOT ); ?>/style/master/css/global.css" />
	<!-- DYANMIC CSS SHEETS -->
	<?php echo( $output['page']['css'] ); ?>	
	<!-- JS LIBRARIES -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
	<!-- DYANMIC JS SCRIPTS -->
	<?php echo( $output['page']['js'] ); ?>		
	<!-- DYNAMIC SCRIPT INCLUDE -->
	<?php echo( $output['page']['js-script'] ); ?>	
</head>

<body>

	