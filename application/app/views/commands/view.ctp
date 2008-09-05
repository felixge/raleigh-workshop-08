<h1><?php echo $this->pageTitle = 'Command: ' . $command['Command']['name']; ?></h1>

<?php echo $html->link('Back to Index', array('controller' => 'snippets', 'action' => 'index'), array('class' => 'btn')); ?>
<div class="clear"></div>

<dl>
	<dt>Name:</dt>
	<dd><?php echo $command['Command']['name']?></dd>
	<dt>Snippets:</dt>
	<dd>
	<?php
	if (empty($command['Snippet'])) {
		echo 'Sorry, there are no snippets where this command is used.';
	} else {
		foreach ($command['Snippet'] as $s) {
			$out[] = $html->link($s['name'], array('controller' => 'snippets', 'action' => 'view', $s['id']));
		}
		echo implode(', ', $out);
	}
	?>
	</dd>
</dl>