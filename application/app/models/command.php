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
					'scale' => 2,
					'limit' => 50,
					'shuffle' => true,
					'query' => array(),
				), $query);

				$commands = $this->find('all', array_merge(array(
					'contain' => false,
					'order' => array('Command.snippet_command_count' => 'desc'),
					'limit' => $options['limit'],
				)), $options['query']);
				if (empty($commands)) {
					return array();
				}

				$max = $commands[0]['Command']['snippet_command_count'];
				$min = $commands[count($commands)-1]['Command']['snippet_command_count'];
				$range = $max - $min;
				if (!$range) {
					$range = 1;
				}

				foreach ($commands as &$command) {
					$command['Command']['scale'] = 
						(($command['Command']['snippet_command_count'] - $min) / $range)
						* $options['scale']
						+ 1;
				}
				if ($options['shuffle']) {
					srand();
					shuffle($commands);
				}
				return $commands;
		}
		$args = func_get_args();
		return call_user_func_array(array('parent', 'find'), $args);
	}
}
?>