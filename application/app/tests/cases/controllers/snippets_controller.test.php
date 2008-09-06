<?php
include CONFIGS . 'routes.php';
define('CAKEPHP_UNIT_TEST_EXECUTION', 1);
App::import('Controller', 'Snippets');
App::import('Model', 'Snippet');
class SnippetsControllerTest extends CakeTestCase {
	var $fixtures = array('snippet', 'command', 'commands_snippet');
	var $sut = null; // subject under test

	function setUp() {
		$this->Sut = new SnippetsController();
		$this->Sut->constructClasses();
	}

	function startCase() {
		$this->loadFixtures('Command', 'Snippet', 'CommandsSnippet');
	}

	function testSnippetIndexLoadsSomeSnippets() {
		$this->Sut->index();
		$this->assertFalse(empty($this->Sut->viewVars['snippets']));
	}
	
	function testSnippetDeletionCallsModelDelIfValidSnippetIdPresent() {
		Mock::generate('Snippet');

		$MockSnippet =& new MockSnippet();
		$MockSnippet->setReturnValue('find', array('notEmpty'));
		$this->Sut->Snippet = $MockSnippet;
		$this->Sut->delete('someUuid');
		$MockSnippet->expectCallCount('del', 1);

		$MockSnippet =& new MockSnippet();
		$MockSnippet->setReturnValue('find', array());
		$this->Sut->Snippet = $MockSnippet;
		$this->Sut->delete('someUuid');
		$this->assertEqual($this->Sut->redirectUrl, Router::url(array(
			'controller' => 'snippets', 'action' => 'index'))
		);
		$MockSnippet->expectCallCount('del', 0);
	}

	function testSnippetDeletionRefusedIfNonExistentSnippet() {
		$this->Sut->delete('nonExistantId');
		$this->assertEqual($this->Sut->redirectUrl, Router::url(array(
			'controller' => 'snippets', 'action' => 'index'))
		);
		$flash = $this->Sut->Session->read('Message.flash');
		$this->assertEqual($flash['message'], 'Sorry, this is an invalid snippet');
	}

	function testSnippetDeletionDeletesSnippetIfValidSnippetId() {
		$id = '48c2570e-dfa8-4c32-a35e-0d71cbdd56cb';
		$conditions = array('Snippet.id' => $id);
		$snippet = $this->Sut->Snippet->find('first', compact('conditions'));
		$this->assertFalse(empty($snippet));

		$this->Sut->delete($id);

		$snippet = $this->Sut->Snippet->find('first', compact('conditions'));
		$this->assertTrue(empty($snippet));

		$flash = $this->Sut->Session->read('Message.flash');
		$this->assertEqual($flash['message'], 'The snippet has been deleted.');
		$this->assertEqual($this->Sut->redirectUrl, Router::url(array(
			'controller' => 'snippets', 'action' => 'index'))
		);
	}

	function testDeletingASnippetDeletesAssociatedSnippetCommands() {
		$id = '48c2570e-dfa8-4c32-a35e-0d71cbdd56cb';
		$conditions = array('Snippet.id' => $id);
		$snippet = $this->Sut->Snippet->find('first', compact('conditions'));
		$this->assertFalse(empty($snippet));

		$commands = $this->Sut->Snippet->SnippetCommand->find('all', array(
			'conditions' => array('SnippetCommand.snippet_id' => $id)
		));
		$this->assertFalse(empty($commands));

		$this->Sut->delete($id);

		$snippet = $this->Sut->Snippet->find('first', compact('conditions'));
		$this->assertTrue(empty($snippet));

		$commands = $this->Sut->Snippet->SnippetCommand->find('all', array(
			'conditions' => array('SnippetCommand.snippet_id' => $id)
		));
		$this->assertTrue(empty($commands));
	}

	function testAddingASnippetCreatesACommandIfNonExistent() {
		$testCommands = array('this is a real good test cmd', 'another one test command for us');
		$this->Sut->data = array(
			'Snippet' => array(
				'name' => 'Our Test Snippet',
				'description' => 'This is a test description to pass validation',
				'commands' => implode(', ', $testCommands)
			)
		);
		$conditions = array('Command.name' => $testCommands);
		$this->assertidentical(array(), $this->Sut->Snippet->Command->find('all', compact('conditions')));

		$this->_fakePostRequest();
		$this->Sut->add();

		$conditions = array('Command.name' => $testCommands);
		$this->assertEqual(count($testCommands), count($this->Sut->Snippet->Command->find('all', compact('conditions'))));
	}

	function testAddingASnippetDoesNotCreateACommandIfExistent() {
		$cmd = 'mysqldump';
		$this->Sut->data = array(
			'Snippet' => array(
				'name' => 'Our Test Snippet',
				'description' => 'This is a test description to pass validation',
				'commands' => $cmd . ', atestCommand'
			)
		);
		$conditions = array('Command.name' => $cmd);
		$this->assertEqual(1, count($this->Sut->Snippet->Command->find('all', compact('conditions'))));

		$this->_fakePostRequest();
		$this->Sut->add();

		$this->assertEqual(1, count($this->Sut->Snippet->Command->find('all', compact('conditions'))));
	}

	function testAddingASnippetDoesNotCreateACommandIfExistentWithTestAction() {
		$cmd = 'mysqldump';
		$data = array(
			'Snippet' => array(
				'name' => 'Our Test Snippet',
				'description' => 'This is a test description to pass validation',
				'commands' => $cmd . ', atestCommand'
			)
		);
		$conditions = array('Command.name' => $cmd);
		$this->assertEqual(1, count($this->Sut->Snippet->Command->find('all', compact('conditions'))));

		$results = $this->testAction('/snippets/add', array('return' => 'contents', 'method' => 'post', 'data' => $data));
		$this->assertEqual(1, count($this->Sut->Snippet->Command->find('all', compact('conditions'))));
	}

	function testSnippetViewSetsViewVarContainingCommands() {
		$id = '48c2570e-dfa8-4c32-a35e-0d71cbdd56cb';
		$this->Sut->view($id);
		$this->assertTrue(array_key_exists('Command', $this->Sut->viewVars['snippet']));
	}

	function testSnippetViewRedirectsIfNonExistantSnippetIdGiven() {
		$this->Sut->edit('non-existant-id');
		$this->assertEqual($this->Sut->redirectUrl, Router::url(array('controller' => 'snippets', 'action' => 'index')));
	}

	// function testSnippetsIndex() {
	// 	$results = $this->testAction('/snippets/index', array('return' => 'contents'));
	// 	pr($results);
	// }

	function _fakePostRequest() {
		$_SERVER['REQUEST_METHOD'] = 'POST';
	}
}
?>