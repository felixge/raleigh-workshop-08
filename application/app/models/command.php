<?php
class Command extends AppModel {
	var $hasAndBelongsToMany = array('Snippet');

	var $hasMany = array('SnippetCommand');

	var $validate = array(
		'name' => array(
			'rule' => 'notEmpty', 'message' => 'Please specify a name.'
		)
	);

/**
 * undocumented function
 *
 * @param string $limit 
 * @return void
 * @access public
 */
	function find($type, $query = array()) {
		switch ($type) {
			case 'cloud':
				$limit = 30;
				$order = array('Command.snippet_command_count' => 'desc');
				$contain = false;
				$commands = $this->find('all', compact('contain', 'order'));

				$maxSize = 200;
				$minSize = 90;

				$resultCount = ($limit > count($commands)) ? count($commands) : $limit;
				$maxCount = @$commands[0]['Command']['snippet_command_count'];
				$minCount = @$commands[$resultCount-1]['Command']['snippet_command_count'];

				$spread = $maxCount - $minCount;
				if ($spread == 0) {
					$spread = 1;
				}
				$step = ($maxCount - $minCount) / $spread;

				srand((float)microtime() * 1000000);
				shuffle($commands);
				$output = array(
					'Command' => $commands,
					'minSize' => $minSize,
					'maxSize' => $maxSize,
					'minCount' => $minCount,
					'maxCount' => $maxCount,
					'spread' => $spread,
					'step' => $step
				);
				return $output;
		}
		$args = func_get_args();
		return call_user_func_array(array('parent', 'find'), $args);
	}
}
?>