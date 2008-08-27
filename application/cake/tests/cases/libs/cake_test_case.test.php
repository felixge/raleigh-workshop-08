<?php
/* SVN FILE: $Id: cake_test_case.test.php 7490 2008-08-23 17:28:08Z mark_story $ */
/**
 * CakeTestCase TestCase
 *
 * Test Case for CakeTestCase Class
 *
 * PHP versions 4 and 5
 *
 * CakePHP :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2006-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2006-2008, Cake Software Foundation, Inc.
 * @link			http://www.cakefoundation.org/projects/info/cakephp CakePHP Project
 * @package			cake
 * @subpackage		cake.cake.libs.
 * @since			CakePHP v 1.2.0.4487
 * @version			$Revision: 7490 $
 * @modifiedby		$LastChangedBy: mark_story $
 * @lastmodified	$Date: 2008-08-23 19:28:08 +0200 (Sa, 23 Aug 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
App::import('Core', 'CakeTestCase');
App::import('Controller', 'App');

Mock::generate('CakeHtmlReporter');
Mock::generate('CakeTestCase', 'CakeDispatcherMockTestCase');

SimpleTest::ignore('SubjectCakeTestCase');
SimpleTest::ignore('CakeDispatcherMockTestCase');

/**
* SubjectCakeTestCase
*
* @package    cake.tests
* @subpackage cake.tests.cases.libs
*/
class SubjectCakeTestCase extends CakeTestCase {
/**
 * Feed a Mocked Reporter to the subject case
 * prevents its pass/fails from affecting the real test
 *
 * @param string $reporter 
 * @access public
 * @return void
 */
	function setReporter(&$reporter) {
		$this->_reporter = &$reporter;
	} 
	
	function testDummy() {
		
	}
}

/**
 * CakeTestCaseTest
 *
 * @package cake.tests
 * @subpackage cake.tests.cases.libs
 **/
class CakeTestCaseTest extends CakeTestCase {
/**
 * setUp
 * 
 * @access public
 * @return void
 */	
	function setUp() {
		$this->Case =& new SubjectCakeTestCase();
		$reporter =& new MockCakeHtmlReporter();
		$this->Case->setReporter($reporter);
		$this->Reporter = $reporter;
	}
/**
 * testAssertGoodTags 
 *
 * @access public
 * @return void
 */	
	function testAssertGoodTags() {
		$this->Reporter->expectAtLeastOnce('paintPass');
		$this->Reporter->expectNever('paintFail');
		
		$input = '<p>Text</p>';
		$pattern = array(
			'<p',
			'Text',
			'/p',
		);
		$this->assertTrue($this->Case->assertTags($input, $pattern));
		
		$input = '<a href="/test.html" class="active">My link</a>';
		$pattern = array(
			'a' => array('href' => '/test.html', 'class' => 'active'),
			'My link',
			'/a'
		);
		$this->assertTrue($this->Case->assertTags($input, $pattern));

		$pattern = array(
			'a' => array('class' => 'active', 'href' => '/test.html'),
			'My link',
			'/a'
		);
		$this->assertTrue($this->Case->assertTags($input, $pattern));
		
		$input = "<a    href=\"/test.html\"\t\n\tclass=\"active\"\tid=\"primary\">\t<span>My link</span></a>";
		$pattern = array(
			'a' => array('id' => 'primary', 'href' => '/test.html', 'class' => 'active'),
			'<span',
			'My link',
			'/span',
			'/a'
		);
		$this->assertTrue($this->Case->assertTags($input, $pattern));
		
		$input = '<p class="info"><a href="/test.html" class="active"><strong onClick="alert(\'hey\');">My link</strong></a></p>';
		$pattern = array(
			'p' => array('class' => 'info'),
			'a' => array('class' => 'active', 'href' => '/test.html' ),
			'strong' => array('onClick' => 'alert(\'hey\');'),
			'My link',
			'/strong',
			'/a',
			'/p'
		);
		$this->assertTrue($this->Case->assertTags($input, $pattern));
	}
/**
 * testBadAssertTags
 * 			
 * @access public
 * @return void
 */
	function testBadAssertTags() {
		$this->Reporter->expectAtLeastOnce('paintFail');
		$this->Reporter->expectNever('paintPass');
		
		$input = '<a href="/test.html" class="active">My link</a>';
		$pattern = array(
			'a' => array('hRef' => '/test.html', 'clAss' => 'active'),
			'My link',
			'/a'
		);
		$this->assertFalse($this->Case->assertTags($input, $pattern));
		
		$input = '<a href="/test.html" class="active">My link</a>';
		$pattern = array(
			'<a' => array('href' => '/test.html', 'class' => 'active'),
			'My link',
			'/a'
		);
		$this->assertFalse($this->Case->assertTags($input, $pattern));
	}
/**
 * testBefore
 * 	
 * @access public
 * @return void
 */	
	function testBefore() {
		$this->Case->before('testDummy');
		$this->assertFalse(isset($this->Case->db));
		
		$this->Case->fixtures = array('core.post');
		$this->Case->before('start');
		$this->assertTrue(isset($this->Case->db));
		$this->assertTrue(isset($this->Case->_fixtures['core.post']));
		$this->assertTrue(is_a($this->Case->_fixtures['core.post'], 'CakeTestFixture'));
		$this->assertEqual($this->Case->_fixtureClassMap['Post'], 'core.post');	
	}
/**
 * testAfter
 * 
 * @access public
 * @return void
 */
	function testAfter() {
		$this->Case->after('testDummy');
		$this->assertFalse($this->Case->__truncated);

		$this->Case->fixtures = array('core.post');
		$this->Case->before('start');
		$this->Case->start();
		$this->Case->after('testDummy');
		$this->assertTrue($this->Case->__truncated);
	}
/**
 * testLoadFixtures
 *
 * @access public
 * @return void
 */
	function testLoadFixtures() {
		$this->Case->fixtures = array('core.post');
		$this->Case->autoFixtures = false;
		$this->Case->before('start');
		$this->expectError();
		$this->Case->loadFixtures('Wrong!');
	}
/**
 * testGetTests Method
 *
 * @return void
 * @access public
 */
	function testGetTests() {
		$result = $this->Case->getTests();
		$this->assertEqual(array_slice($result, 0, 2), array('start', 'startCase'));
		$this->assertEqual(array_slice($result, -2), array('endCase', 'end'));
	}
	
/**
 * TestTestAction
 *
 * @access public
 * @return void
 **/
	function testTestAction() {
		$_back = array(
			'controller' => Configure::read('controllerPaths'),
			'view' => Configure::read('viewPaths'),
			'plugin' => Configure::read('pluginPaths'),
		);
		Configure::write('controllerPaths', array(TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' . DS . 'controllers' . DS));
		Configure::write('viewPaths', array(TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' . DS . 'views' . DS));
		Configure::write('pluginPaths', array(TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' . DS . 'plugins' . DS));
		
		$result = $this->Case->testAction('/tests_apps/index', array('return' => 'view'));
		$this->assertPattern('/This is the TestsAppsController index view/', $result);
		
		$result = $this->Case->testAction('/tests_apps/index', array('return' => 'contents'));
		$this->assertPattern('/This is the TestsAppsController index view/', $result);
		$this->assertPattern('/<html/', $result);
		$this->assertPattern('/<\/html>/', $result);
		
		$result = $this->Case->testAction('/tests_apps/some_method', array('return' => 'result'));
		$this->assertEqual($result, 5);
		
		$result = $this->Case->testAction('/tests_apps/set_action', array('return' => 'vars'));
		$this->assertEqual($result, array('var' => 'string'));
		
		$result = $this->Case->testAction('/tests_apps_posts/add', array('fixturize' => true, 'return' => 'vars'));
		$this->assertTrue(isset($result['posts']));
		$this->assertEqual(sizeof($result['posts']), 1);
		
		Configure::write('controllerPaths', $_back['controller']);
		Configure::write('viewPaths', $_back['view']);
		Configure::write('pluginPaths', $_back['plugin']);
	}	
/**
 * testSkipIf
 *
 * @return void
 **/
	function testSkipIf() {
		$this->assertTrue($this->Case->skipIf(true));
		$this->assertFalse($this->Case->skipIf(false));
	}
/**
 * testTestDispatcher
 *
 * @access public
 * @return void
 */		
	function testTestDispatcher() {
		$_back = array(
			'controller' => Configure::read('controllerPaths'),
			'view' => Configure::read('viewPaths'),
			'plugin' => Configure::read('pluginPaths')
		);
		Configure::write('controllerPaths', array(TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' . DS . 'controllers' . DS));
		Configure::write('viewPaths', array(TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' . DS . 'views' . DS));
		Configure::write('pluginPaths', array(TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' . DS . 'plugins' . DS));
		
		$Dispatcher =& new CakeTestDispatcher();
		$Case =& new CakeDispatcherMockTestCase();
		
		$Case->expectOnce('startController');
		$Case->expectOnce('endController');
		
		$Dispatcher->testCase($Case);
		$this->assertTrue(isset($Dispatcher->testCase));
		
		$return = $Dispatcher->dispatch('/tests_apps/index', array('autoRender' => 0, 'return' => 1, 'requested' => 1));
		
		Configure::write('controllerPaths', $_back['controller']);
		Configure::write('viewPaths', $_back['view']);
		Configure::write('pluginPaths', $_back['plugin']);
	}
/**
 * tearDown
 *
 * @access public
 * @return void
 */		
	function tearDown() {
		unset($this->Case);
		unset($this->Reporter);
	}
}
?>