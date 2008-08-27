<?php
class Command extends AppModel {
	var $hasAndBelongsToMany = array('Snippet');

	var $hasMany = array('SnippetCommand');

	var $validate = array();
}
?>