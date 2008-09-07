<?php echo $html->docType('html4-strict'); ?>
<html>
	<head>
		<?php echo $html->charset('utf-8'); ?>
		<title><?php echo $title_for_layout.' | '.Configure::read('App.name');?></title>
		<base href="<?php echo Router::url('/', true); ?>">
		<?php echo $html->css('screen'); ?>
		<?php echo $javascript->link('jquery'); ?>
		<?php echo $scripts_for_layout; ?>
	</head>
	<body>
		<div id="content">
			<div id="header">
				<span>No more bashing your head</span>
				<?php echo $html->link('<h2 id="headline">' . Configure::read('App.name') . '</h2>', '/', array(), false, false); ?>
			</div>
			<div class="clear"></div>
			<?php $session->flash(); ?>
			<?php echo $content_for_layout; ?>
			<div class="clear"></div>
		</div>
	</body>
</html>