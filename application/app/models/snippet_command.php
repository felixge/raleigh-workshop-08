<?php
class SnippetCommand extends AppModel {
	var $useTable = 'commands_snippets';

	var $belongsTo = array(
		'Snippet',
		'Command' => array(
			'counterCache' => true
		)
	);

	var $validate = array();
}
?>