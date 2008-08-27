<?php 
/* SVN FILE: $Id$ */
/* App schema generated on: 2008-08-27 15:08:25 : 1219845265*/
class AppSchema extends CakeSchema {
	var $name = 'App';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $commands = array(
			'id' => array('type' => 'string', 'null' => false, 'length' => 36, 'key' => 'primary'),
			'name' => array('type' => 'string', 'null' => false),
			'snippet_command_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'user_id' => array('type' => 'string', 'null' => false, 'length' => 36),
			'created' => array('type' => 'datetime', 'null' => false),
			'update' => array('type' => 'datetime', 'null' => false),
			'indexes' => array()
		);
	var $commands_snippets = array(
			'id' => array('type' => 'string', 'null' => false, 'length' => 36, 'key' => 'primary'),
			'snippet_id' => array('type' => 'string', 'null' => false, 'length' => 36),
			'command_id' => array('type' => 'string', 'null' => false, 'length' => 36),
			'indexes' => array()
		);
	var $snippets = array(
			'id' => array('type' => 'string', 'null' => false, 'length' => 36, 'key' => 'primary'),
			'name' => array('type' => 'string', 'null' => false),
			'description' => array('type' => 'text', 'null' => false),
			'user_id' => array('type' => 'string', 'null' => false, 'length' => 36),
			'created' => array('type' => 'datetime', 'null' => false),
			'modified' => array('type' => 'datetime', 'null' => false),
			'indexes' => array()
		);
}
?>