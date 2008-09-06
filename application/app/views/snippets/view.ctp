<h1>View Snippet</h1>
<?php $this->pageTitle = $snippet['Snippet']['name']; ?>
<?php echo $html->link('Back to Index', array('controller' => 'snippets', 'action' => 'index'), array('class' => 'button')); ?>
<?php echo $html->link('Edit', array('controller' => 'snippets', 'action' => 'edit', $snippet['Snippet']['id']), array('class' => 'button')); ?>
<dl>
	<dt>Name:</dt>
	<dd><?php echo $snippet['Snippet']['name']?></dd>
	<dt>Description:</dt>
	<dd><?php echo nl2br($snippet['Snippet']['description']); ?></dd>
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