<?php echo $html->docType('html4-strict'); ?>
<html>
	<head>
		<?php echo $html->charset('utf-8'); ?>
		<title><?php echo Configure::read('App.title'); ?> : <?php echo $title_for_layout;?></title>
		<base href="<?php echo Router::url('/', true); ?>">
		<?php echo $html->css('screen'); ?>
		<?php echo $scripts_for_layout; ?>
	</head>
	<body>
		<div id="content">
			<div id="header">
				<span>No more bashing your head</span>
				<h2 id="headline">cli.licio.us</h2>
			</div>
			<?php $session->flash(); ?>
			<?php echo $content_for_layout; ?>
			<div class="clear"></div>
		</div>
	</body>
</html>