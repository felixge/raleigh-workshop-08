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
			'rule' => 'notEmpty', 'message' => 'Please specify a name.'
		)
		, 'description' => array(
			'rule' => 'notEmpty', 'message' => 'Please specify a description.'
		)
		, 'commands' => array(
			'rule' => 'notEmpty', 'message' => 'Please specify some commands.'
		)
	);
}
?>