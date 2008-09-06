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

	function insertCommands($id, $commands) {
		if (empty($commands)) {
			return false;
		}

		$this->SnippetCommand->deleteAll(array('SnippetCommand.snippet_id' => $id));
		$commands = preg_split('/[\s]*,[\s]*/', $commands);
		foreach ($commands as $command) {
			$command = trim($command);
			$conditions = array('Command.name' => $command);
			$command = $this->Command->find('first', compact('conditions'));

			$command_id = false;
			if (empty($command)) {
				$this->Command->create(array(
					'name' => $command
				));
				$this->Command->save();
				$command_id = $this->Command->id;
			} else {
				$command_id = $command['Command']['id'];
			}

			$this->SnippetCommand->create(array(
				'snippet_id' => $id,
				'command_id' => $command_id
			));
			$this->SnippetCommand->save();
		}

		return true;
	}
}
?>