<?php
class SnippetCommand extends AppModel {
	var $useTable = 'commands_snippets';

	var $belongsTo = array(
		'Snippet',
		'Command'
	);

	var $validate = array();
}
?>