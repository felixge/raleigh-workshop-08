<div id="command-cloud">
	<h3>Command Cloud</h3>

	<?php
	$minSize = intval($commandCloud['minSize']);
	$maxSize = intval($commandCloud['maxSize']);
	$minCount = intval($commandCloud['minCount']);
	$maxCount = intval($commandCloud['maxCount']);
	$step = intval($commandCloud['step']) + 5;
	$spread = intval($commandCloud['spread']);

	$out = '';
	foreach ($commandCloud['Command'] as $cmd):
		$size = $minSize + ((intval($cmd['Command']['snippet_command_count']) - $minCount) * $step);
		$size = $maxSize > $size ? $size : $maxSize;
		$style = "font-size:".$size."%;";
		if ($size == $maxSize) {
			$style .= "color:#fff;";
		}
		$out[] = $html->link($cmd['Command']['name'], array('controller' => 'commands', 'action' => 'view', $cmd['Command']['id']), 
			array('style' => $style)
		);
	endforeach;
	echo implode(', ', $out);
	?>
</div>