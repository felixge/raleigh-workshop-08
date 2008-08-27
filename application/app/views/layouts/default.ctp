<?php echo $html->docType('html4-strict'); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<?php echo $html->charset('utf-8'); ?>
		<title><?php echo Configure::read('App.title'); ?> : <?php echo $title_for_layout;?></title>
		<link href="/favicon.ico" type="image/x-icon" rel="icon"/>
		<link href="/favicon.ico" type="image/x-icon" rel="shortcut icon"/>
		<base href="<?php echo Router::url('/', true); ?>">
		<?php echo $html->css('screen'); ?>
		<?php echo $scripts_for_layout; ?>
	</head>
	<body>
		<a name="top"></a>
		<?php echo $this->element('header')?>
		<div id="content">
			<?php $session->flash(); ?>
			<?php echo $content_for_layout; ?>
			<div class="clear"></div>
		</div>
	</body>
</html>