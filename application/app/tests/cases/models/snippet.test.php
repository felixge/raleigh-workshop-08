<?php
class SnippetTest extends CakeTestCase {
	var $fixtures = array('snippet');
	function setUp() {
		$this->sut = ClassRegistry::init('Snippet');
	}

	function testSnippetDeletionRefusedIfNotExistentSnippet() {
		$this->loadFixtures('Snippet');
	}
}
?>