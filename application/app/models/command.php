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
				$options = array_merge(array(
					'scaleMin' => 0.5,
					'scaleMax' => 2,
					'limit' => 50
				), $query);

				$commands = $this->find('all', array(
					'contain' => false,
					'order' => array('Command.snippet_command_count' => 'desc'),
				));
				if (empty($commands)) {
					return array();
				}

				$max = $commands[0]['Command']['snippet_command_count'];
				$min = $commands[count($commands)-1]['Command']['snippet_command_count'];
				$range = $max - $min;

				foreach ($commands as &$command) {
					$command['Command']['scale'] = 
						(($command['Command']['snippet_command_count'] - $min) / $range)
						* ($options['scaleMax'] - $options['scaleMin'])
						+ $options['scaleMin'];
				}
				if ($options['limit']) {
					$commands = array_slice($commands, 0, $options['limit']);
				}
				srand();
				shuffle($commands);
				return $commands;
		}
		$args = func_get_args();
		return call_user_func_array(array('parent', 'find'), $args);
	}
}
?>