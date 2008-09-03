<?php
include CONFIGS . 'routes.php';
define('CAKEPHP_UNIT_TEST_EXECUTION', 1);
App::import('Controller', 'Snippets');
App::import('Model', 'Snippet');
class SnippetsControllerTest extends CakeTestCase {
	var $fixtures = array('snippet', 'command', 'snippet_command');
	function setUp() {
		$this->sut = new SnippetsController();
		$this->sut->constructClasses();
	}

	function startCase() {
		$this->loadFixtures('Command', 'Snippet', 'SnippetCommand');
	}

	function testSnippetIndexLoadsSomeSnippets() {
		$this->sut->index();
		$this->assertFalse(empty($this->sut->viewVars['snippets']));
	}
	
	function testSnippetDeletionCallsModelDelIfValidSnippetIdPresent() {
		Mock::generate('Snippet');

		$MockSnippet =& new MockSnippet();
		$MockSnippet->setReturnValue('find', array('notEmpty'));
		$this->sut->Snippet = $MockSnippet;
		$this->sut->delete('someUuid');
		$MockSnippet->expectCallCount('del', 1);

		$MockSnippet =& new MockSnippet();
		$MockSnippet->setReturnValue('find', array());
		$this->sut->Snippet = $MockSnippet;
		$this->sut->delete('someUuid');
		$this->assertEqual($this->sut->redirectUrl, Router::url(array(
			'controller' => 'snippets', 'action' => 'index'))
		);
		$MockSnippet->expectCallCount('del', 0);
	}

	function testSnippetDeletionRefusedIfNonExistentSnippet() {
		$this->sut->delete('nonExistantId');
		$this->assertEqual($this->sut->redirectUrl, Router::url(array(
			'controller' => 'snippets', 'action' => 'index'))
		);
		$flash = $this->sut->Session->read('Message.flash');
		$this->assertEqual($flash['message'], 'Sorry, this is an invalid snippet');
	}

	function testSnippetDeletionDeletesSnippetIfValidSnippetId() {
		$id = '48b69c67-1244-4426-950b-d26dcbdd56cb';
		$conditions = array('Snippet.id' => $id);
		$snippet = $this->sut->Snippet->find('first', compact('conditions'));
		$this->assertFalse(empty($snippet));

		$this->sut->delete($id);

		$snippet = $this->sut->Snippet->find('first', compact('conditions'));
		$this->assertTrue(empty($snippet));

		$flash = $this->sut->Session->read('Message.flash');
		$this->assertEqual($flash['message'], 'The snippet has been deleted.');
		$this->assertEqual($this->sut->redirectUrl, Router::url(array(
			'controller' => 'snippets', 'action' => 'index'))
		);
	}

	function testDeletingASnippetDeletesAssociatedSnippetCommands() {
		$id = '48b69c67-1244-4426-950b-d26dcbdd56cb';
		$conditions = array('Snippet.id' => $id);
		$snippet = $this->sut->Snippet->find('first', compact('conditions'));
		$this->assertFalse(empty($snippet));

		$commands = $this->sut->Snippet->SnippetCommand->find('all', array(
			'conditions' => array('SnippetCommand.snippet_id' => $id)
		));
		$this->assertFalse(empty($commands));

		$this->sut->delete($id);

		$snippet = $this->sut->Snippet->find('first', compact('conditions'));
		$this->assertTrue(empty($snippet));

		$commands = $this->sut->Snippet->SnippetCommand->find('all', array(
			'conditions' => array('SnippetCommand.snippet_id' => $id)
		));
		$this->assertTrue(empty($commands));
	}

	function testAddingASnippetCreatesACommandIfNonExistent() {
		$testCommands = array('this is a test cmd', 'another one');
		$this->sut->data = array(
			'Snippet' => array(
				'name' => 'Our Test Snippet',
				'description' => 'This is a test description to pass validation',
				'commands' => implode(', ', $testCommands)
			)
		);
		$conditions = array('Command.name' => $testCommands);
		$this->assertidentical(array(), $this->sut->Snippet->Command->find('all', compact('conditions')));

		$this->_fakePostRequest();
		$this->sut->add();

		$conditions = array('Command.name' => $testCommands);
		$this->assertEqual(count($testCommands), count($this->sut->Snippet->Command->find('all', compact('conditions'))));
	}

	function testAddingASnippetDoesNotCreateACommandIfExistent() {
		$cmd = 'mysqldump';
		$this->sut->data = array(
			'Snippet' => array(
				'name' => 'Our Test Snippet',
				'description' => 'This is a test description to pass validation',
				'commands' => $cmd . ', atestCommand'
			)
		);
		$conditions = array('Command.name' => $cmd);
		$this->assertEqual(1, count($this->sut->Snippet->Command->find('all', compact('conditions'))));

		$this->_fakePostRequest();
		$this->sut->add();

		$this->assertEqual(1, count($this->sut->Snippet->Command->find('all', compact('conditions'))));
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
		$this->assertEqual(1, count($this->sut->Snippet->Command->find('all', compact('conditions'))));

		$results = $this->testAction('/snippets/add', array('return' => 'contents', 'method' => 'post', 'data' => $data));
		$this->assertEqual(1, count($this->sut->Snippet->Command->find('all', compact('conditions'))));
	}

	function testSnippetViewSetsViewVarContainingCommands() {
		$id = '48b69c67-1244-4426-950b-d26dcbdd56cb';
		$this->sut->view($id);
		$this->assertTrue(array_key_exists('Command', $this->sut->viewVars['snippet']));
	}

	function testSnippetViewRedirectsIfNonExistantSnippetIdGiven() {
		$this->sut->edit('non-existant-id');
		$this->assertEqual($this->sut->redirectUrl, Router::url(array('controller' => 'snippets', 'action' => 'index')));
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