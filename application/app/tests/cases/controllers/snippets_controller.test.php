<?php
include CONFIGS . 'routes.php';
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
		$this->sut->redirectUrl;
	}

	function testSnippetIndexLoadsSomeSnippets() {
		$this->sut->index();
		$this->assertFalse(empty($this->sut->viewVars['snippets']));
	}
	
	function testSnippetDeletionRefusedIfExistentSnippet() {
		Mock::generate('Snippet');
		$this->sut->doRedirect = false;

		$MockSnippet =& new MockSnippet();
		$MockSnippet->setReturnValue('find', array('notEmpty'));
		$this->sut->Snippet = $MockSnippet;
		$this->sut->delete('someUuid');
		$MockSnippet->expectCallCount('del', 1);

		$MockSnippet =& new MockSnippet();
		$MockSnippet->setReturnValue('find', array());
		$this->sut->Snippet = $MockSnippet;
		$this->sut->delete('someUuid');
		$MockSnippet->expectCallCount('del', 0);
	}

	function testDeletingASnippetDeletesAssociatedSnippetCommands() {
		$this->sut->doRedirect = false;

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
		$this->sut->doRedirect = false;

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
		$this->sut->doRedirect = false;

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

	function testSnippetViewSetsViewVarContainingCommands() {
		$id = '48b69c67-1244-4426-950b-d26dcbdd56cb';

		$this->sut->doRedirect = false;
		$this->sut->view($id);

		$this->assertTrue(array_key_exists('Command', $this->sut->viewVars['snippet']));
	}

	function testSnippetViewRedirectsIfNonExistantSnippetIdGiven() {
		$this->sut->doRedirect = false;
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