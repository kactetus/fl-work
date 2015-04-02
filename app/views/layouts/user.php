<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo $template['title']; ?></title>
    	<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="Sviridenko Dmitry">
		<?php echo link_tag(site_url('media/css/bootstrap/css/bootstrap.css'));?>
		<?php echo link_tag(site_url('media/css/default/font-awesome.css'));?>
		<?php echo link_tag(site_url('media/css/default/landing-page.css'));?>	
		<?php echo link_tag(site_url('media/css/user/morris.css'));?>
		<?php echo link_tag(site_url('media/css/user/timeline.css'));?>
		<?php echo link_tag(site_url('media/css/user/admin.css'));?>
		<?php echo link_tag(site_url('media/css/user/dataTables.css'));?>
		<?php echo link_tag(site_url('media/css/user/social-buttons.css'));?>
		<?php echo link_tag(site_url('media/css/user/main.css'));?>
		<?php echo $template['metadata'] . "\n"; ?>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<?php echo $template['body']; ?>
	</body>
</html>