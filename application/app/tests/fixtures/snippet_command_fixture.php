<?php
class SnippetCommandFixture extends CakeTestFixture {
	var $name = 'SnippetCommand';
	var $table = 'commands_snippets';
	var $records = array(
		array(
			'id' => '48b69dc9-d7ec-4f44-8c70-d42ecbdd56cb',
			'snippet_id' => '48b6d16c-d7e0-43c5-93c4-dd9acbdd56cb',
			'command_id' => '48b69c67-dcdc-42f5-9fcf-d26dcbdd56cb',
		),
		array(
			'id' => '48b69dc9-1608-46b9-b647-d42ecbdd56cb',
			'snippet_id' => '48b69c67-1244-4426-950b-d26dcbdd56cb',
			'command_id' => '48b69c67-232c-432b-ba55-d26dcbdd56cb'
		),
		array(
			'id' => '48b69dc9-48d0-42e6-b6eb-d42ecbdd56cb',
			'snippet_id' => '48b6d16c-a314-49c5-8cc9-dd9acbdd56cb',
			'command_id' => '48b69c67-697c-4906-b20a-d26dcbdd56cb'
		)
	);
}

?>