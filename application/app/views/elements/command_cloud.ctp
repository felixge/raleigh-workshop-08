<ul class="cloud">
	<?php
	$out = array();
	foreach ($commandCloud as $command) {
		$size = 10 * $command['Command']['scale'];
		$out[] = '<li>'.$html->link(
			$command['Command']['name'],
			array('controller' => 'commands', 'action' => 'view', $command['Command']['id']), 
			array('style' => 'font-size: '.$size.'px;')
		).'</li>';
	}
	echo join("\n", $out);
	?>
</ul>