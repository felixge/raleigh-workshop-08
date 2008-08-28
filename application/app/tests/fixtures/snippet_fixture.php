<?php

class SnippetFixture extends CakeTestFixture {
	var $name = 'Snippet';
	// var $fields = array(
	// 	'id' => array('type' => 'string', 'length' => 36),
	// 	'user_id' => array('type' => 'string', 'length' => 36),
	// 	'name' => array('type' => 'string', 'length' => 255),
	// 	'description' => array('type' => 'text'),
	// 	'created' => array('type' => 'datetime'),
	// 	'modified' => array('type' => 'datetime')
	// );
	var $records = array(
		array(
			'id' => '48b69c67-1244-4426-950b-d26dcbdd56cb',
			'user_id' => '48b69c67-789c-4851-b01c-d26dcbdd56cb',
			'name' => 'MySQL Dump',
			'description' => 'This snippet will make you be able to dump an entire sql file!',
			'created' => '2008-08-25',
			'modified' => '2008-08-25'
		),
		array(
			'id' => '48b69c67-c270-409f-aaa6-d26dcbdd56cb',
			'user_id' => '48b69c67-50a0-4e84-98b8-d26dcbdd56cb',
			'name' => 'TextMate Project Creation',
			'description' => 'This snippet will generate an entire textmate project for you based on a directory.',
			'created' => '2008-08-25',
			'modified' => '2008-08-25'
		),
		array(
			'id' => '48b69c67-09ec-4fe4-b240-d26dcbdd56cb',
			'user_id' => '48b69c67-96f0-4942-826a-d26dcbdd56cb',
			'name' => 'Hash Generator',
			'description' => 'This snippet will generate a random string with the specified length using the specified characters.',
			'created' => '2008-08-25',
			'modified' => '2008-08-25'
		)
	);
}

?>
