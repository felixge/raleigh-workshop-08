<?php
class SnippetSystemGroupTest extends GroupTest {
	var $label = 'Snippet System Tests';
	function __construct() {
		$controllers = array('snippets');
		foreach ($controllers as $c) {
			TestManager::addTestFile($this, APP . 'tests' . DS . 'cases' . DS . 'controllers' . DS . $c . '_controller');
		}

		$models = array('snippet', 'command');
		foreach ($models as $m) {
			TestManager::addTestFile($this, APP . 'tests' . DS . 'cases' . DS . 'models' . DS . $m);
		}
	}
}
?>