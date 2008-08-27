<h1><?php echo $this->pageTitle = $snippet['Snippet']['name']; ?></h1>

<?php echo $html->link('back to Index', array('controller' => 'snippets', 'action' => 'index')); ?>

<dl>
	<dt>Name:</dt>
	<dd><?php echo $snippet['Snippet']['name']?></dd>
	<dt>Description:</dt>
	<dd><?php echo $snippet['Snippet']['description']?></dd>
	<dt>Commands:</dt>
	<dd>
	<?php
	if (empty($snippet['Command'])) {
		echo 'Sorry, there are no commands assigned to this snippet';
	} else {
		foreach ($snippet['Command'] as $c) {
			$out[] = $html->link($c['name'], array('controller' => 'commands', 'action' => 'view', $c['id']));
		}
		echo implode(', ', $out);
	}
	?>
	</dd>
</dl>