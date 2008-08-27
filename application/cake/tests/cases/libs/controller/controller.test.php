<?php
/* SVN FILE: $Id: controller.test.php 7515 2008-08-27 01:30:09Z gwoo $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package			cake.tests
 * @subpackage		cake.tests.cases.libs.controller
 * @since			CakePHP(tm) v 1.2.0.5436
 * @version			$Revision: 7515 $
 * @modifiedby		$LastChangedBy: gwoo $
 * @lastmodified	$Date: 2008-08-27 03:30:09 +0200 (Mi, 27 Aug 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Core', 'Controller');
App::import('Component', 'Security');
App::import('Component', 'Cookie');
/**
 * ControllerPost class
 *
 * @package              cake
 * @subpackage           cake.tests.cases.libs.controller
 */
class ControllerPost extends CakeTestModel {
/**
 * name property
 *
 * @var string 'ControllerPost'
 * @access public
 */
	var $name = 'ControllerPost';
/**
 * useTable property
 *
 * @var string 'posts'
 * @access public
 */
	var $useTable = 'posts';
/**
 * invalidFields property
 *
 * @var array
 * @access public
 */
	var $invalidFields = array('name' => 'error_msg');
/**
 * lastQuery property
 *
 * @var mixed null
 * @access public
 */
	var $lastQuery = null;
/**
 * beforeFind method
 *
 * @param mixed $query
 * @access public
 * @return void
 */
	function beforeFind($query) {
		$this->lastQuery = $query;
	}
/**
 * find method
 *
 * @param mixed $type
 * @param array $options
 * @access public
 * @return void
 */
	function find($type, $options = array()) {
		if ($type == 'popular') {
			$conditions = array($this->name . '.' . $this->primaryKey .' > ' => '1');
			$options = Set::merge($options, compact('conditions'));
			return parent::find('all', $options);
		}
		return parent::find($type, $options);
	}
}
/**
 * ControllerComment class
 *
 * @package              cake
 * @subpackage           cake.tests.cases.libs.controller
 */
class ControllerComment extends CakeTestModel {
/**
 * name property
 *
 * @var string 'ControllerComment'
 * @access public
 */
	var $name = 'Comment';
/**
 * useTable property
 *
 * @var string 'comments'
 * @access public
 */
	var $useTable = 'comments';
/**
 * data property
 *
 * @var array
 * @access public
 */
	var $data = array('name' => 'Some Name');
/**
 * alias property
 *
 * @var string 'ControllerComment'
 * @access public
 */
	var $alias = 'ControllerComment';
}
/**
 * NameTest class
 *
 * @package              cake
 * @subpackage           cake.tests.cases.libs.controller
 */
class NameTest extends CakeTestModel {
/**
 * name property
 * @var string 'Name'
 * @access public
 */
	var $name = 'Name';
/**
 * useTable property
 * @var string 'names'
 * @access public
 */
	var $useTable = 'comments';
/**
 * alias property
 *
 * @var string 'ControllerComment'
 * @access public
 */
	var $alias = 'Name';
}
if (!class_exists('AppController')) {
/**
 * AppController class
 *
 * @package              cake
 * @subpackage           cake.tests.cases.libs.controller
 */
	class AppController extends Controller {
/**
 * helpers property
 *
 * @var array
 * @access public
 */
		var $helpers = array('Html', 'Javascript');
/**
 * uses property
 *
 * @var array
 * @access public
 */
		var $uses = array('ControllerPost');
/**
 * components property
 *
 * @var array
 * @access public
 */
		var $components = array('Cookie');
	}
} else if (!defined('APP_CONTROLLER_EXISTS')) {
	define('APP_CONTROLLER_EXISTS', true);
}
/**
 * TestController class
 *
 * @package              cake
 * @subpackage           cake.tests.cases.libs.controller
 */
class TestController extends AppController {
/**
 * helpers property
 *
 * @var array
 * @access public
 */
	var $helpers = array('Xml');
/**
 * components property
 *
 * @var array
 * @access public
 */
	var $components = array('Security');
/**
 * uses property
 *
 * @var array
 * @access public
 */
	var $uses = array('ControllerComment');
/**
 * index method
 *
 * @param mixed $testId
 * @param mixed $test2Id
 * @access public
 * @return void
 */
	function index($testId, $test2Id) {
		$this->data['testId'] = $testId;
		$this->data['test2Id'] = $test2Id;
	}
}
/**
 * TestComponent class
 *
 * @package              cake
 * @subpackage           cake.tests.cases.libs.controller
 */
class TestComponent extends Object {
/**
 * beforeRedirect method
 *
 * @access public
 * @return void
 */
	function beforeRedirect() {
		return true;
	}
}
/**
 * Short description for class.
 *
 * @package    cake.tests
 * @subpackage cake.tests.cases.libs.controller
 */
class ControllerTest extends CakeTestCase {
/**
 * fixtures property
 *
 * @var array
 * @access public
 */
	var $fixtures = array('core.post', 'core.comment', 'core.name');
/**
 * testConstructClasses method
 *
 * @access public
 * @return void
 */
	function testConstructClasses() {
		$Controller =& new Controller();
		$Controller->modelClass = 'ControllerPost';
		$Controller->passedArgs[] = '1';
		$Controller->constructClasses();
		$this->assertEqual($Controller->ControllerPost->id, 1);

		unset($Controller);

		$Controller =& new Controller();
		$Controller->uses = array('ControllerPost', 'ControllerComment');
		$Controller->passedArgs[] = '1';
		$Controller->constructClasses();
		$this->assertTrue(is_a($Controller->ControllerPost, 'ControllerPost'));
		$this->assertTrue(is_a($Controller->ControllerComment, 'ControllerComment'));
		
		$this->assertEqual($Controller->ControllerComment->name, 'Comment');

		unset($Controller);
	}

	function testAliasName() {
		$Controller =& new Controller();
		$Controller->uses = array('NameTest');
		$Controller->constructClasses();

		$this->assertEqual($Controller->NameTest->name, 'Name');
		$this->assertEqual($Controller->NameTest->alias, 'Name');

		unset($Controller);
	}

/**
 * testPersistent method
 *
 * @access public
 * @return void
 */
	function testPersistent() {
		Configure::write('Cache.disable', false);
		$Controller =& new Controller();
		$Controller->modelClass = 'ControllerPost';
		$Controller->persistModel = true;
		$Controller->constructClasses();
		$this->assertTrue(file_exists(CACHE . 'persistent' . DS .'controllerpost.php'));
		$this->assertTrue(is_a($Controller->ControllerPost, 'ControllerPost'));
		@unlink(CACHE . 'persistent' . DS . 'controllerpost.php');
		@unlink(CACHE . 'persistent' . DS . 'controllerpostregistry.php');

		unset($Controller);
		Configure::write('Cache.disable', true);
	}
/**
 * testPaginate method
 *
 * @access public
 * @return void
 */
	function testPaginate() {
		$Controller =& new Controller();
		$Controller->uses = array('ControllerPost', 'ControllerComment');
		$Controller->passedArgs[] = '1';
		$Controller->params['url'] = array();
		$Controller->constructClasses();

		$results = Set::extract($Controller->paginate('ControllerPost'), '{n}.ControllerPost.id');
		$this->assertEqual($results, array(1, 2, 3));

		$results = Set::extract($Controller->paginate('ControllerComment'), '{n}.ControllerComment.id');
		$this->assertEqual($results, array(1, 2, 3, 4, 5, 6));

		$Controller->modelClass = null;

		$Controller->uses[0] = 'Plugin.ControllerPost';
		$results = Set::extract($Controller->paginate(), '{n}.ControllerPost.id');
		$this->assertEqual($results, array(1, 2, 3));

		$Controller->passedArgs = array('page' => '-1');
		$results = Set::extract($Controller->paginate('ControllerPost'), '{n}.ControllerPost.id');
		$this->assertEqual($Controller->params['paging']['ControllerPost']['page'], 1);
		$this->assertEqual($results, array(1, 2, 3));

		$Controller->passedArgs = array('sort' => 'ControllerPost.id', 'direction' => 'asc');
		$results = Set::extract($Controller->paginate('ControllerPost'), '{n}.ControllerPost.id');
		$this->assertEqual($Controller->params['paging']['ControllerPost']['page'], 1);
		$this->assertEqual($results, array(1, 2, 3));

		$Controller->passedArgs = array('sort' => 'ControllerPost.id', 'direction' => 'desc');
		$results = Set::extract($Controller->paginate('ControllerPost'), '{n}.ControllerPost.id');
		$this->assertEqual($Controller->params['paging']['ControllerPost']['page'], 1);
		$this->assertEqual($results, array(3, 2, 1));

		$Controller->passedArgs = array('sort' => 'id', 'direction' => 'desc');
		$results = Set::extract($Controller->paginate('ControllerPost'), '{n}.ControllerPost.id');
		$this->assertEqual($Controller->params['paging']['ControllerPost']['page'], 1);
		$this->assertEqual($results, array(3, 2, 1));

		$Controller->passedArgs = array('sort' => 'NotExisting.field', 'direction' => 'desc');
		$results = Set::extract($Controller->paginate('ControllerPost'), '{n}.ControllerPost.id');
		$this->assertEqual($Controller->params['paging']['ControllerPost']['page'], 1);
		$this->assertEqual($results, array(1, 2, 3));

		$Controller->passedArgs = array('sort' => 'ControllerPost.author_id', 'direction' => 'allYourBase');
		$results = Set::extract($Controller->paginate('ControllerPost'), '{n}.ControllerPost.id');
		$this->assertEqual($Controller->ControllerPost->lastQuery['order'][0], array('ControllerPost.author_id' => 'asc'));
		$this->assertEqual($results, array(1, 3, 2));
	}
/**
 * testPaginateExtraParams method
 *
 * @access public
 * @return void
 */
	function testPaginateExtraParams() {
		$Controller =& new Controller();
		$Controller->uses = array('ControllerPost', 'ControllerComment');
		$Controller->passedArgs[] = '1';
		$Controller->params['url'] = array();
		$Controller->constructClasses();

		$Controller->passedArgs = array('page' => '-1', 'contain' => array('ControllerComment'));
		$result = $Controller->paginate('ControllerPost');
		$this->assertEqual($Controller->params['paging']['ControllerPost']['page'], 1);
		$this->assertEqual(Set::extract($result, '{n}.ControllerPost.id'), array(1, 2, 3));
		$this->assertTrue(!isset($Controller->ControllerPost->lastQuery['contain']));

		$Controller->passedArgs = array('page' => '-1');
		$Controller->paginate = array('ControllerPost' => array('contain' => array('ControllerComment')));
		$result = $Controller->paginate('ControllerPost');
		$this->assertEqual($Controller->params['paging']['ControllerPost']['page'], 1);
		$this->assertEqual(Set::extract($result, '{n}.ControllerPost.id'), array(1, 2, 3));
		$this->assertTrue(isset($Controller->ControllerPost->lastQuery['contain']));

		$Controller->paginate = array('ControllerPost' => array('popular', 'fields' => array('id', 'title')));
		$result = $Controller->paginate('ControllerPost');
		$this->assertEqual(Set::extract($result, '{n}.ControllerPost.id'), array(2, 3));
		$this->assertEqual($Controller->ControllerPost->lastQuery['conditions'], array('ControllerPost.id > ' => '1'));
	}
/**
 * testDefaultPaginateParams method
 *
 * @access public
 * @return void
 */
	function testDefaultPaginateParams() {
		$Controller =& new Controller();
		$Controller->modelClass = 'ControllerPost';
		$Controller->params['url'] = array();
		$Controller->paginate = array('order' => 'ControllerPost.id DESC');
		$Controller->constructClasses();
		$results = Set::extract($Controller->paginate('ControllerPost'), '{n}.ControllerPost.id');
		$this->assertEqual($Controller->params['paging']['ControllerPost']['defaults']['order'], 'ControllerPost.id DESC');
		$this->assertEqual($Controller->params['paging']['ControllerPost']['options']['order'], 'ControllerPost.id DESC');
		$this->assertEqual($results, array(3, 2, 1));
	}
/**
 * testFlash method
 *
 * @access public
 * @return void
 */
	function testFlash() {
		$Controller =& new Controller();
		$Controller->flash('this should work', '/flash');
		$result = $Controller->output;

		$expected = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>this should work</title>
		<style><!--
		P { text-align:center; font:bold 1.1em sans-serif }
		A { color:#444; text-decoration:none }
		A:HOVER { text-decoration: underline; color:#44E }
		--></style>
		</head>
		<body>
		<p><a href="/flash">this should work</a></p>
		</body>
		</html>';
		$result = str_replace(array("\t", "\r\n", "\n"), "", $result);
		$expected =  str_replace(array("\t", "\r\n", "\n"), "", $expected);
		$this->assertEqual($result, $expected);
	}
/**
 * testControllerSet method
 *
 * @access public
 * @return void
 */
	function testControllerSet() {
		$Controller =& new Controller();
		$Controller->set('variable_with_underscores', null);
		$this->assertTrue(array_key_exists('variable_with_underscores', $Controller->viewVars));

		$Controller->viewVars = array();
		$viewVars = array('ModelName' => array('id' => 1, 'name' => 'value'));
		$Controller->set($viewVars);
		$this->assertTrue(array_key_exists('modelName', $Controller->viewVars));

		$Controller->viewVars = array();
		$Controller->set('variable_with_underscores', 'value');
		$this->assertTrue(array_key_exists('variable_with_underscores', $Controller->viewVars));

		$Controller->viewVars = array();
		$viewVars = array('ModelName' => 'name');
		$Controller->set($viewVars);
		$this->assertTrue(array_key_exists('modelName', $Controller->viewVars));

		$Controller->set('title', 'someTitle');
		$this->assertIdentical($Controller->pageTitle, 'someTitle');

		$Controller->viewVars = array();
		$expected = array('ModelName' => 'name', 'ModelName2' => 'name2');
		$Controller->set(array('ModelName', 'ModelName2'), array('name', 'name2'));
		$this->assertIdentical($Controller->viewVars, $expected);
	}
/**
 * testRender method
 *
 * @access public
 * @return void
 */
	function testRender() {
		Configure::write('viewPaths', array(TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' . DS . 'views'. DS, TEST_CAKE_CORE_INCLUDE_PATH . 'libs' . DS . 'view' . DS));

		$Controller =& new Controller();
		$Controller->viewPath = 'posts';

		$result = $Controller->render('index');
		$this->assertPattern('/posts index/', $result);

		$result = $Controller->render('/elements/test_element');
		$this->assertPattern('/this is the test element/', $result);

		$Controller = new TestController();
		$Controller->constructClasses();
		$Controller->ControllerComment->validationErrors = array('title' => 'tooShort');
		$expected = $Controller->ControllerComment->validationErrors;

		ClassRegistry::flush();
		$Controller->viewPath = 'posts';
		$result = $Controller->render('index');
		$View = ClassRegistry::getObject('view');
		$this->assertTrue(isset($View->validationErrors['ControllerComment']));
		$this->assertEqual($expected, $View->validationErrors['ControllerComment']);

		$Controller->ControllerComment->validationErrors = array();
		ClassRegistry::flush();
	}
/**
 * testToBeInheritedGuardmethods method
 *
 * @access public
 * @return void
 */
	function testToBeInheritedGuardmethods() {
		$Controller =& new Controller();
		$this->assertTrue($Controller->_beforeScaffold(''));
		$this->assertTrue($Controller->_afterScaffoldSave(''));
		$this->assertTrue($Controller->_afterScaffoldSaveError(''));
		$this->assertFalse($Controller->_scaffoldError(''));
	}
/**
 * testRedirect method
 *
 * @access public
 * @return void
 */
	function testRedirect() {
		$url = 'cakephp.org';
		$codes = array(
			100 => "Continue",
			101 => "Switching Protocols",
			200 => "OK",
			201 => "Created",
			202 => "Accepted",
			203 => "Non-Authoritative Information",
			204 => "No Content",
			205 => "Reset Content",
			206 => "Partial Content",
			300 => "Multiple Choices",
			301 => "Moved Permanently",
			302 => "Found",
			303 => "See Other",
			304 => "Not Modified",
			305 => "Use Proxy",
			307 => "Temporary Redirect",
			400 => "Bad Request",
			401 => "Unauthorized",
			402 => "Payment Required",
			403 => "Forbidden",
			404 => "Not Found",
			405 => "Method Not Allowed",
			406 => "Not Acceptable",
			407 => "Proxy Authentication Required",
			408 => "Request Time-out",
			409 => "Conflict",
			410 => "Gone",
			411 => "Length Required",
			412 => "Precondition Failed",
			413 => "Request Entity Too Large",
			414 => "Request-URI Too Large",
			415 => "Unsupported Media Type",
			416 => "Requested range not satisfiable",
			417 => "Expectation Failed",
			500 => "Internal Server Error",
			501 => "Not Implemented",
			502 => "Bad Gateway",
			503 => "Service Unavailable",
			504 => "Gateway Time-out"
		);

		Mock::generatePartial('Controller', 'MockController', array('header'));
		App::import('Helper', 'Cache');

//		$codes = array_merge($codes, array_flip($codes));
		foreach ($codes as $code => $msg) {
			$MockController =& new MockController();
			$MockController->components = array('Test');
			$MockController->Component =& new Component();
			$MockController->Component->init($MockController);
			$MockController->expectCallCount('header', 2);
			$MockController->redirect($url, (int) $code, false);
		}
		$codes = array_flip($codes);
		foreach ($codes as $code => $msg) {
			$MockController =& new MockController();
			$MockController->components = array('Test');
			$MockController->Component =& new Component();
			$MockController->Component->init($MockController);
			$MockController->expectCallCount('header', 2);
			$MockController->redirect($url, $code, false);
		}
	}
/**
 * testMergeVars method
 *
 * @access public
 * @return void
 */
	function testMergeVars() {
		$this->skipIf(defined('APP_CONTROLLER_EXISTS'), 'MergeVars will be skipped as it needs a non-existent AppController. As the an AppController class exists, this cannot be run.');

		$TestController =& new TestController();
		$TestController->constructClasses();

		$testVars = get_class_vars('TestController');
		$appVars = get_class_vars('AppController');

		$components = is_array($appVars['components'])
						? array_merge($appVars['components'], $testVars['components'])
						: $testVars['components'];
		if (!in_array('Session', $components)) {
			$components[] = 'Session';
		}
		$helpers = is_array($appVars['helpers'])
					? array_merge($appVars['helpers'], $testVars['helpers'])
					: $testVars['helpers'];
		$uses = is_array($appVars['uses'])
					? array_merge($appVars['uses'], $testVars['uses'])
					: $testVars['uses'];

		$this->assertEqual(count(array_diff($TestController->helpers, $helpers)), 0);
		$this->assertEqual(count(array_diff($TestController->uses, $uses)), 0);
		$this->assertEqual(count(array_diff_assoc(Set::normalize($TestController->components), Set::normalize($components))), 0);
	}
/**
 * testReferer method
 *
 * @access public
 * @return void
 */
	function testReferer() {
		$Controller =& new Controller();
		$_SERVER['HTTP_REFERER'] = 'http://cakephp.org';
		$result = $Controller->referer(null, false);
		$expected = 'http://cakephp.org';
		$this->assertIdentical($result, $expected);

		$_SERVER['HTTP_REFERER'] = '';
		$result = $Controller->referer('http://cakephp.org', false);
		$expected = 'http://cakephp.org';
		$this->assertIdentical($result, $expected);

		$_SERVER['HTTP_REFERER'] = '';
		$result = $Controller->referer(null, false);
		$expected = '/';
		$this->assertIdentical($result, $expected);

		$_SERVER['HTTP_REFERER'] = FULL_BASE_URL.$Controller->webroot.'/some/path';
		$result = $Controller->referer(null, false);
		$expected = '/some/path';
		$this->assertIdentical($result, $expected);

		$Controller->webroot .= '/';
		$_SERVER['HTTP_REFERER'] = FULL_BASE_URL.$Controller->webroot.'/some/path';
		$result = $Controller->referer(null, false);
		$expected = '/some/path';
		$this->assertIdentical($result, $expected);

		$_SERVER['HTTP_REFERER'] = FULL_BASE_URL.$Controller->webroot.'some/path';
		$result = $Controller->referer(null, false);
		$expected = '/some/path';
		$this->assertIdentical($result, $expected);

		$Controller->webroot = '/recipe/';

		$_SERVER['HTTP_REFERER'] = FULL_BASE_URL.$Controller->webroot.'recipes/add';
		$result = $Controller->referer();
		$expected = '/recipes/add';
		$this->assertIdentical($result, $expected);
	}
/**
 * testSetAction method
 *
 * @access public
 * @return void
 */
	function testSetAction() {
		$TestController =& new TestController();
		$TestController->setAction('index', 1, 2);
		$expected = array('testId' => 1, 'test2Id' => 2);
		$this->assertidentical($TestController->data, $expected);
	}
/**
 * testUnimplementedIsAuthorized method
 *
 * @access public
 * @return void
 */
	function testUnimplementedIsAuthorized() {
		$TestController =& new TestController();
		$TestController->isAuthorized();
		$this->assertError();
	}
/**
 * testValidateErrors method
 *
 * @access public
 * @return void
 */
	function testValidateErrors() {
		$TestController =& new TestController();
		$TestController->constructClasses();
		$this->assertFalse($TestController->validateErrors());
		$this->assertEqual($TestController->validate(), 0);

		$TestController->ControllerComment->invalidate('some_field', 'error_message');
		$TestController->ControllerComment->invalidate('some_field2', 'error_message2');
		$comment = new ControllerComment;
		$comment->set('someVar', 'data');
		$result = $TestController->validateErrors($comment);
		$expected = array('some_field' => 'error_message', 'some_field2' => 'error_message2');
		$this->assertIdentical($result, $expected);
		$this->assertEqual($TestController->validate($comment), 2);
	}
/**
 * testPostConditions method
 *
 * @access public
 * @return void
 */
	function testPostConditions() {
		$Controller =& new Controller();


		$data = array(
			'Model1' => array('field1' => '23'),
			'Model2' => array('field2' => 'string'),
			'Model3' => array('field3' => '23'),
		);
		$expected = array(
			'Model1.field1' => '23',
			'Model2.field2' => 'string',
			'Model3.field3' => '23',
		);
		$result = $Controller->postConditions($data);
		$this->assertIdentical($result, $expected);


		$data = array();
		$Controller->data = array(
			'Model1' => array('field1' => '23'),
			'Model2' => array('field2' => 'string'),
			'Model3' => array('field3' => '23'),
		);
		$expected = array(
			'Model1.field1' => '23',
			'Model2.field2' => 'string',
			'Model3.field3' => '23',
		);
		$result = $Controller->postConditions($data);
		$this->assertIdentical($result, $expected);


		$data = array();
		$Controller->data = array();
		$result = $Controller->postConditions($data);
		$this->assertNull($result);


		$data = array();
		$Controller->data = array(
			'Model1' => array('field1' => '23'),
			'Model2' => array('field2' => 'string'),
			'Model3' => array('field3' => '23'),
		);
		$ops = array(
			'Model1.field1' => '>',
			'Model2.field2' => 'LIKE',
			'Model3.field3' => '<=',
		);
		$expected = array(
			'Model1.field1 >' => '23',
			'Model2.field2 LIKE' => "%string%",
			'Model3.field3 <=' => '23',
		);
		$result = $Controller->postConditions($data, $ops);
		$this->assertIdentical($result, $expected);
	}
/**
 * testRequestHandlerPrefers method
 *
 * @access public
 * @return void
 */
	function testRequestHandlerPrefers(){
		Configure::write('debug', 2);
		$Controller =& new Controller();
		$Controller->components = array("RequestHandler");
		$Controller->modelClass='ControllerPost';
		$Controller->params['url']['ext'] = 'rss';
		$Controller->constructClasses();
		$Controller->Component->initialize($Controller);
		$Controller->beforeFilter();
		$Controller->Component->startup($Controller);

		$this->assertEqual($Controller->RequestHandler->prefers(), 'rss');
		unset($Controller);
	}
}
?>