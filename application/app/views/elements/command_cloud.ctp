<div id="command-cloud">
	<h3>Command Cloud</h3>
	<?php
	$out = array();
	foreach ($commandCloud as $command) {
		$size = 15 * $command['Command']['scale'];
		$out[] = $html->link(
			$command['Command']['name'],
			array('controller' => 'commands', 'action' => 'view', $command['Command']['id']), 
			array('style' => 'font-size: '.$size.'px;')
		);
	}
	echo join(', ', $out);
	?>
</div>