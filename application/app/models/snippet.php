<?php
class Snippet extends AppModel {
	var $hasAndBelongsToMany = array('Command');

	var $hasMany = array(
		'SnippetCommand' => array(
			'dependent' => true
		)
	);

	var $validate = array(
		'name' => array(
			'rule' => 'notEmpty'
		)
		, 'description' => array(
			'rule' => 'notEmpty', 
			'message' => 'Please specify a description.'
		)
		, 'commands' => array(
			'rule' => 'notEmpty', 
			'message' => 'Please specify some commands.'
		)
	);

// var $validate = array(
// 	'name' => array(
// 		'rule' => 'notEmpty', 
// 		'message' => 'Please specify a name.'
// 	)
// 	, 'description' => array(
// 		'rule' => 'notEmpty', 
// 		'message' => 'Please specify a description.'
// 	)
// 	, 'commands' => array(
// 		'rule' => 'notEmpty', 
// 		'message' => 'Please specify some commands.'
// 	)
// );
/**
 * undocumented function
 *
 * @param string $id 
 * @param string $commands 
 * @return void
 * @access public
 */
	function insertCommands($id, $commands) {
		if (empty($commands)) {
			return false;
		}

		$this->SnippetCommand->deleteAll(array('SnippetCommand.snippet_id' => $id));
		$commands = explode(',', $commands);
		foreach ($commands as $c) {
			$c = trim($c);
			$conditions = array('Command.name' => $c);
			$command = $this->Command->find('first', compact('conditions'));

			$commandId = false;
			if (empty($command)) {
				$this->Command->create(array(
					'name' => $c
				));
				$this->Command->save();
				$commandId = $this->Command->getLastInsertId();
			} else {
				$commandId = $command['Command']['id'];
			}

			$this->SnippetCommand->create(array(
				'snippet_id' => $id,
				'command_id' => $commandId
			));
			$this->SnippetCommand->save();
		}

		return true;
	}
}
?>