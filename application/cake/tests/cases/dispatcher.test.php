<?php
/* SVN FILE: $Id: dispatcher.test.php 7510 2008-08-26 18:33:00Z gwoo $ */
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
 * @subpackage		cake.tests.cases
 * @since			CakePHP(tm) v 1.2.0.4206
 * @version			$Revision: 7510 $
 * @modifiedby		$LastChangedBy: gwoo $
 * @lastmodified	$Date: 2008-08-26 20:33:00 +0200 (Di, 26 Aug 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
require_once CAKE.'dispatcher.php';
App::import('Core', 'AppController');
/**
 * TestDispatcher class
 *
 * @package              cake
 * @subpackage           cake.tests.cases
 */
class TestDispatcher extends Dispatcher {
/**
 * invoke method
 *
 * @param mixed $controller
 * @param mixed $params
 * @param mixed $missingAction
 * @access protected
 * @return void
 */
	function _invoke(&$controller, $params) {
		restore_error_handler();
		if ($result = parent::_invoke($controller, $params)) {
			if ($result === 'missingAction') {
				return $result;
			}
		}
		set_error_handler('simpleTestErrorHandler');

		return $controller;
	}
/**
 * cakeError method
 *
 * @param mixed $filename
 * @access public
 * @return void
 */
	function cakeError($filename) {
		return $filename;
	}
/**
 * _stop method
 *
 * @access protected
 * @return void
 */
	function _stop() {
		return true;
	}

}
/**
 * MyPluginAppController class
 *
 * @package              cake
 * @subpackage           cake.tests.cases
 */
class MyPluginAppController extends AppController {

}
/**
 * MyPluginController class
 *
 * @package              cake
 * @subpackage           cake.tests.cases
 */
class MyPluginController extends MyPluginAppController {
/**
 * name property
 *
 * @var string 'MyPlugin'
 * @access public
 */
	var $name = 'MyPlugin';
/**
 * uses property
 *
 * @var array
 * @access public
 */
	var $uses = array();
/**
 * index method
 *
 * @access public
 * @return void
 */
	function index() {
		return true;
	}
/**
 * add method
 *
 * @access public
 * @return void
 */
	function add() {
		return true;
	}
/**
 * admin_add method
 *
 * @param mixed $id
 * @access public
 * @return void
 */
	function admin_add($id = null) {
		return $id;
	}
}
/**
 * SomePagesController class
 *
 * @package              cake
 * @subpackage           cake.tests.cases
 */
class SomePagesController extends AppController {
/**
 * name property
 *
 * @var string 'SomePages'
 * @access public
 */
	var $name = 'SomePages';
/**
 * uses property
 *
 * @var array
 * @access public
 */
	var $uses = array();
/**
 * display method
 *
 * @param mixed $page
 * @access public
 * @return void
 */
	function display($page = null) {
		return $page;
	}
/**
 * index method
 *
 * @access public
 * @return void
 */
	function index() {
		return true;
	}
/**
 * protected method
 *
 * @access protected
 * @return void
 */
	function _protected() {
		return true;
	}

/**
 * redirect method overriding
 *
 * @access public
 * @return void
 */
	function redirect() {
		echo 'this should not be accessible';
	}
}
/**
 * OtherPagesController class
 *
 * @package              cake
 * @subpackage           cake.tests.cases
 */
class OtherPagesController extends MyPluginAppController {
/**
 * name property
 *
 * @var string 'OtherPages'
 * @access public
 */
	var $name = 'OtherPages';
/**
 * uses property
 *
 * @var array
 * @access public
 */
	var $uses = array();
/**
 * display method
 *
 * @param mixed $page
 * @access public
 * @return void
 */
	function display($page = null) {
		return $page;
	}
/**
 * index method
 *
 * @access public
 * @return void
 */
	function index() {
		return true;
	}
}
/**
 * TestDispatchPagesController class
 *
 * @package              cake
 * @subpackage           cake.tests.cases
 */
class TestDispatchPagesController extends AppController {
/**
 * name property
 *
 * @var string 'TestDispatchPages'
 * @access public
 */
	var $name = 'TestDispatchPages';
/**
 * uses property
 *
 * @var array
 * @access public
 */
	var $uses = array();
/**
 * admin_index method
 *
 * @access public
 * @return void
 */
	function admin_index() {
		return true;
	}

/**
 * camelCased method
 *
 * @access public
 * @return void
 */
	function camelCased() {
		return true;
	}
}
/**
 * ArticlesTestAppController class
 *
 * @package              cake
 * @subpackage           cake.tests.cases
 */
class ArticlesTestAppController extends AppController {

}
/**
 * ArticlesTestController class
 *
 * @package              cake
 * @subpackage           cake.tests.cases
 */
class ArticlesTestController extends ArticlesTestAppController {
/**
 * name property
 *
 * @var string 'ArticlesTest'
 * @access public
 */
	var $name = 'ArticlesTest';
/**
 * uses property
 *
 * @var array
 * @access public
 */
	var $uses = array();
/**
 * admin_index method
 *
 * @access public
 * @return void
 */
	function admin_index() {
		return true;
	}
}
/**
 * SomePostsController class
 *
 * @package              cake
 * @subpackage           cake.tests.cases
 */
class SomePostsController extends AppController {
/**
 * name property
 *
 * @var string 'SomePosts'
 * @access public
 */
	var $name = 'SomePosts';
/**
 * uses property
 *
 * @var array
 * @access public
 */
	var $uses = array();
/**
 * autoRender property
 *
 * @var bool false
 * @access public
 */
	var $autoRender = false;
/**
 * beforeFilter method
 *
 * @access public
 * @return void
 */
	function beforeFilter() {
		if ($this->params['action'] == 'index') {
			$this->params['action'] = 'view';
		} else {
			$this->params['action'] = 'change';
		}
		$this->params['pass'] = array('changed');
	}
/**
 * index method
 *
 * @access public
 * @return void
 */
	function index() {
		return true;
	}
/**
 * change method
 *
 * @access public
 * @return void
 */
	function change() {
		return true;
	}
}
/**
 * TestCachedPagesController class
 *
 * @package              cake
 * @subpackage           cake.tests.cases
 */
class TestCachedPagesController extends AppController {
/**
 * name property
 *
 * @var string 'TestCachedPages'
 * @access public
 */
	var $name = 'TestCachedPages';
/**
 * uses property
 *
 * @var array
 * @access public
 */
	var $uses = array();
/**
 * helpers property
 *
 * @var array
 * @access public
 */
	var $helpers = array('Cache');
/**
 * cacheAction property
 *
 * @var array
 * @access public
 */
	var $cacheAction = array(
		'index'=> '+2 sec', 'test_nocache_tags'=>'+2 sec',
		'view/' => '+2 sec'
	);
/**
 * viewPath property
 *
 * @var string 'posts'
 * @access public
 */
	var $viewPath = 'posts';
/**
 * index method
 *
 * @access public
 * @return void
 */
	function index() {
		$this->render();
	}
/**
 * test_nocache_tags method
 *
 * @access public
 * @return void
 */
	function test_nocache_tags() {
		$this->render();
	}
/**
 * view method
 *
 * @access public
 * @return void
 */
	function view($id = null) {
		$this->render('index');
	}
}
/**
 * TimesheetsController class
 *
 * @package              cake
 * @subpackage           cake.tests.cases
 */
class TimesheetsController extends AppController {
/**
 * name property
 *
 * @var string 'Timesheets'
 * @access public
 */
	var $name = 'Timesheets';
/**
 * uses property
 *
 * @var array
 * @access public
 */
	var $uses = array();
/**
 * index method
 *
 * @access public
 * @return void
 */
	function index() {
		return true;
	}
}
/**
 * Short description for class.
 *
 * @package		cake.tests
 * @subpackage	cake.tests.cases
 */
class DispatcherTest extends CakeTestCase {
/**
 * setUp method
 *
 * @access public
 * @return void
 */
	function setUp() {
		$this->_get = $_GET;
		$_GET = array();
		Configure::write('App.base', false);
		Configure::write('App.baseUrl', false);
		Configure::write('App.dir', 'app');
		Configure::write('App.webroot', 'webroot');
		Configure::write('Cache.disable', true);
	}
/**
 * testParseParamsWithoutZerosAndEmptyPost method
 *
 * @access public
 * @return void
 */
	function testParseParamsWithoutZerosAndEmptyPost() {
		$Dispatcher =& new Dispatcher();
		$test = $Dispatcher->parseParams("/testcontroller/testaction/params1/params2/params3");
		$this->assertIdentical($test['controller'], 'testcontroller');
		$this->assertIdentical($test['action'], 'testaction');
		$this->assertIdentical($test['pass'][0], 'params1');
		$this->assertIdentical($test['pass'][1], 'params2');
		$this->assertIdentical($test['pass'][2], 'params3');
		$this->assertFalse(!empty($test['form']));
	}
/**
 * testParseParamsReturnsPostedData method
 *
 * @access public
 * @return void
 */
	function testParseParamsReturnsPostedData() {
		$_POST['testdata'] = "My Posted Content";
		$Dispatcher =& new Dispatcher();
		$test = $Dispatcher->parseParams("/");
		$this->assertTrue($test['form'], "Parsed URL not returning post data");
		$this->assertIdentical($test['form']['testdata'], "My Posted Content");
	}
/**
 * testParseParamsWithSingleZero method
 *
 * @access public
 * @return void
 */
	function testParseParamsWithSingleZero() {
		$Dispatcher =& new Dispatcher();
		$test = $Dispatcher->parseParams("/testcontroller/testaction/1/0/23");
		$this->assertIdentical($test['controller'], 'testcontroller');
		$this->assertIdentical($test['action'], 'testaction');
		$this->assertIdentical($test['pass'][0], '1');
		$this->assertPattern('/\\A(?:0)\\z/', $test['pass'][1]);
		$this->assertIdentical($test['pass'][2], '23');
	}
/**
 * testParseParamsWithManySingleZeros method
 *
 * @access public
 * @return void
 */
	function testParseParamsWithManySingleZeros() {
		$Dispatcher =& new Dispatcher();
		$test = $Dispatcher->parseParams("/testcontroller/testaction/0/0/0/0/0/0");
		$this->assertPattern('/\\A(?:0)\\z/', $test['pass'][0]);
		$this->assertPattern('/\\A(?:0)\\z/', $test['pass'][1]);
		$this->assertPattern('/\\A(?:0)\\z/', $test['pass'][2]);
		$this->assertPattern('/\\A(?:0)\\z/', $test['pass'][3]);
		$this->assertPattern('/\\A(?:0)\\z/', $test['pass'][4]);
		$this->assertPattern('/\\A(?:0)\\z/', $test['pass'][5]);
	}
/**
 * testParseParamsWithManyZerosInEachSectionOfUrl method
 *
 * @access public
 * @return void
 */
	function testParseParamsWithManyZerosInEachSectionOfUrl() {
		$Dispatcher =& new Dispatcher();
		$test = $Dispatcher->parseParams("/testcontroller/testaction/000/0000/00000/000000/000000/0000000");
		$this->assertPattern('/\\A(?:000)\\z/', $test['pass'][0]);
		$this->assertPattern('/\\A(?:0000)\\z/', $test['pass'][1]);
		$this->assertPattern('/\\A(?:00000)\\z/', $test['pass'][2]);
		$this->assertPattern('/\\A(?:000000)\\z/', $test['pass'][3]);
		$this->assertPattern('/\\A(?:000000)\\z/', $test['pass'][4]);
		$this->assertPattern('/\\A(?:0000000)\\z/', $test['pass'][5]);
	}
/**
 * testParseParamsWithMixedOneToManyZerosInEachSectionOfUrl method
 *
 * @access public
 * @return void
 */
	function testParseParamsWithMixedOneToManyZerosInEachSectionOfUrl() {
		$Dispatcher =& new Dispatcher();
		$test = $Dispatcher->parseParams("/testcontroller/testaction/01/0403/04010/000002/000030/0000400");
		$this->assertPattern('/\\A(?:01)\\z/', $test['pass'][0]);
		$this->assertPattern('/\\A(?:0403)\\z/', $test['pass'][1]);
		$this->assertPattern('/\\A(?:04010)\\z/', $test['pass'][2]);
		$this->assertPattern('/\\A(?:000002)\\z/', $test['pass'][3]);
		$this->assertPattern('/\\A(?:000030)\\z/', $test['pass'][4]);
		$this->assertPattern('/\\A(?:0000400)\\z/', $test['pass'][5]);
	}
/**
 * testQueryStringOnRoot method
 *
 * @access public
 * @return void
 */
	function testQueryStringOnRoot() {
		$_GET = array('coffee' => 'life', 'sleep' => 'sissies');
		$Dispatcher =& new Dispatcher();
		$uri = 'posts/home/?coffee=life&sleep=sissies';
		$result = $Dispatcher->parseParams($uri);
		$this->assertPattern('/posts/', $result['controller']);
		$this->assertPattern('/home/', $result['action']);
		$this->assertTrue(isset($result['url']['sleep']));
		$this->assertTrue(isset($result['url']['coffee']));

		$Dispatcher =& new Dispatcher();
		$uri = '/?coffee=life&sleep=sissy';
		$result = $Dispatcher->parseParams($uri);
		$this->assertPattern('/pages/', $result['controller']);
		$this->assertPattern('/display/', $result['action']);
		$this->assertTrue(isset($result['url']['sleep']));
		$this->assertTrue(isset($result['url']['coffee']));
		$this->assertEqual($result['url']['coffee'], 'life');

		$_GET = $this->_get;
	}
/**
 * testFileUploadArrayStructure method
 *
 * @access public
 * @return void
 */
	function testFileUploadArrayStructure() {
		$_FILES = array('data' => array('name' => array(
			'File' => array(
				array('data' => 'cake_mssql_patch.patch'),
					array('data' => 'controller.diff'),
					array('data' => ''),
					array('data' => ''),
				),
				'Post' => array('attachment' => 'jquery-1.2.1.js'),
			),
			'type' => array(
				'File' => array(
					array('data' => ''),
					array('data' => ''),
					array('data' => ''),
					array('data' => ''),
				),
				'Post' => array('attachment' => 'application/x-javascript'),
			),
			'tmp_name' => array(
				'File' => array(
					array('data' => '/private/var/tmp/phpy05Ywj'),
					array('data' => '/private/var/tmp/php7MBztY'),
					array('data' => ''),
					array('data' => ''),
				),
				'Post' => array('attachment' => '/private/var/tmp/phpEwlrIo'),
			),
			'error' => array(
				'File' => array(
					array('data' => 0),
					array('data' => 0),
					array('data' => 4),
					array('data' => 4)
				),
				'Post' => array('attachment' => 0)
			),
			'size' => array(
				'File' => array(
					array('data' => 6271),
					array('data' => 350),
					array('data' => 0),
					array('data' => 0),
				),
				'Post' => array('attachment' => 80469)
			),
		));

		$Dispatcher =& new Dispatcher();
		$result = $Dispatcher->parseParams('/');

		$expected = array(
			'File' => array(
				array('data' => array(
					'name' => 'cake_mssql_patch.patch',
					'type' => '',
					'tmp_name' => '/private/var/tmp/phpy05Ywj',
					'error' => 0,
					'size' => 6271,
				),
			),
			array('data' => array(
				'name' => 'controller.diff',
				'type' => '',
				'tmp_name' => '/private/var/tmp/php7MBztY',
				'error' => 0,
				'size' => 350,
			)),
			array('data' => array(
				'name' => '',
				'type' => '',
				'tmp_name' => '',
				'error' => 4,
				'size' => 0,
			)),
			array('data' => array(
				'name' => '',
				'type' => '',
				'tmp_name' => '',
				'error' => 4,
				'size' => 0,
			)),
		),
		'Post' => array('attachment' => array(
			'name' => 'jquery-1.2.1.js',
			'type' => 'application/x-javascript',
			'tmp_name' => '/private/var/tmp/phpEwlrIo',
			'error' => 0,
			'size' => 80469,
		)));
		$this->assertEqual($result['data'], $expected);

		$_FILES = array(
			'data' => array(
				'name' => array(
					'Document' => array(
						1 => array(
							'birth_cert' => 'born on.txt',
							'passport' => 'passport.txt',
							'drivers_license' => 'ugly pic.jpg'
						),
						2 => array(
							'birth_cert' => 'aunt betty.txt',
							'passport' => 'betty-passport.txt',
							'drivers_license' => 'betty-photo.jpg'
						),
					),
				),
				'type' => array(
					'Document' => array(
						1 => array(
							'birth_cert' => 'application/octet-stream',
							'passport' => 'application/octet-stream',
							'drivers_license' => 'application/octet-stream',
						),
						2 => array(
							'birth_cert' => 'application/octet-stream',
							'passport' => 'application/octet-stream',
							'drivers_license' => 'application/octet-stream',
						)
					)
				),
				'tmp_name' => array(
					'Document' => array(
						1 => array(
							'birth_cert' => '/private/var/tmp/phpbsUWfH',
							'passport' => '/private/var/tmp/php7f5zLt',
 							'drivers_license' => '/private/var/tmp/phpMXpZgT',
						),
						2 => array(
							'birth_cert' => '/private/var/tmp/php5kHZt0',
 							'passport' => '/private/var/tmp/phpnYkOuM',
 							'drivers_license' => '/private/var/tmp/php9Rq0P3',
						)
					)
				),
				'error' => array(
					'Document' => array(
						1 => array(
							'birth_cert' => 0,
							'passport' => 0,
 							'drivers_license' => 0,
						),
						2 => array(
							'birth_cert' => 0,
 							'passport' => 0,
 							'drivers_license' => 0,
						)
					)
				),
				'size' => array(
					'Document' => array(
						1 => array(
							'birth_cert' => 123,
							'passport' => 458,
 							'drivers_license' => 875,
						),
						2 => array(
							'birth_cert' => 876,
 							'passport' => 976,
 							'drivers_license' => 9783,
						)
					)
				)
			)
		);
		$Dispatcher =& new Dispatcher();
		$result = $Dispatcher->parseParams('/');
		$expected = array(
			'Document' => array(
				1 => array(
					'birth_cert' => array(
						'name' => 'born on.txt',
						'tmp_name' => '/private/var/tmp/phpbsUWfH',
						'error' => 0,
						'size' => 123,
						'type' => 'application/octet-stream',
					),
					'passport' => array(
						'name' => 'passport.txt',
						'tmp_name' => '/private/var/tmp/php7f5zLt',
						'error' => 0,
						'size' => 458,
						'type' => 'application/octet-stream',
					),
					'drivers_license' => array(
						'name' => 'ugly pic.jpg',
						'tmp_name' => '/private/var/tmp/phpMXpZgT',
						'error' => 0,
						'size' => 875,
						'type' => 'application/octet-stream',
					),
				),
				2 => array(
					'birth_cert' => array(
						'name' => 'aunt betty.txt',
						'tmp_name' => '/private/var/tmp/php5kHZt0',
						'error' => 0,
						'size' => 876,
						'type' => 'application/octet-stream',
					),
					'passport' => array(
						'name' => 'betty-passport.txt',
						'tmp_name' => '/private/var/tmp/phpnYkOuM',
						'error' => 0,
						'size' => 976,
						'type' => 'application/octet-stream',
					),
					'drivers_license' => array(
						'name' => 'betty-photo.jpg',
						'tmp_name' => '/private/var/tmp/php9Rq0P3',
						'error' => 0,
						'size' => 9783,
						'type' => 'application/octet-stream',
					),
				),
			)
		);
		$this->assertEqual($result['data'], $expected);

		$_FILES = array();
	}
/**
 * testGetUrl method
 *
 * @access public
 * @return void
 */
	function testGetUrl() {
		$Dispatcher =& new Dispatcher();
		$Dispatcher->base = '/app/webroot/index.php';
		$uri = '/app/webroot/index.php/posts/add';
		$result = $Dispatcher->getUrl($uri);
		$expected = 'posts/add';
		$this->assertEqual($expected, $result);

		Configure::write('App.baseUrl', '/app/webroot/index.php');

		$uri = '/posts/add';
		$result = $Dispatcher->getUrl($uri);
		$expected = 'posts/add';
		$this->assertEqual($expected, $result);

		$_GET['url'] = array();
		Configure::write('App.base', '/control');
		$Dispatcher =& new Dispatcher();
		$Dispatcher->baseUrl();
		$uri = '/control/students/browse';
		$result = $Dispatcher->getUrl($uri);
		$expected = 'students/browse';
		$this->assertEqual($expected, $result);

		$_GET['url'] = array();
		$Dispatcher =& new Dispatcher();
		$Dispatcher->base = '';
		$uri = '/?/home';
		$result = $Dispatcher->getUrl($uri);
		$expected = '?/home';
		$this->assertEqual($expected, $result);

	}
/**
 * testBaseUrlAndWebrootWithModRewrite method
 *
 * @access public
 * @return void
 */
	function testBaseUrlAndWebrootWithModRewrite() {
		$Dispatcher =& new Dispatcher();

		$Dispatcher->base = false;
		$_SERVER['DOCUMENT_ROOT'] = '/cake/repo/branches';
		$_SERVER['SCRIPT_FILENAME'] = '/cake/repo/branches/1.2.x.x/app/webroot/index.php';
		$_SERVER['PHP_SELF'] = '/1.2.x.x/app/webroot/index.php';
		$result = $Dispatcher->baseUrl();
		$expected = '/1.2.x.x';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/1.2.x.x/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);

		$Dispatcher->base = false;
		$_SERVER['DOCUMENT_ROOT'] = '/cake/repo/branches/1.2.x.x/app/webroot';
		$_SERVER['SCRIPT_FILENAME'] = '/cake/repo/branches/1.2.x.x/app/webroot/index.php';
		$_SERVER['PHP_SELF'] = '/index.php';
		$result = $Dispatcher->baseUrl();
		$expected = '';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);

		$Dispatcher->base = false;
		$_SERVER['DOCUMENT_ROOT'] = '/cake/repo/branches/1.2.x.x/test/';
		$_SERVER['SCRIPT_FILENAME'] = '/cake/repo/branches/1.2.x.x/test/webroot/index.php';
		$_SERVER['PHP_SELF'] = '/webroot/index.php';
		$result = $Dispatcher->baseUrl();
		$expected = '';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);

		$Dispatcher->base = false;;
		$_SERVER['DOCUMENT_ROOT'] = '/some/apps/where';
		$_SERVER['SCRIPT_FILENAME'] = '/some/apps/where/app/webroot/index.php';
		$_SERVER['PHP_SELF'] = '/some/apps/where/app/webroot/index.php';
		$result = $Dispatcher->baseUrl();
		$expected = '/some/apps/where';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/some/apps/where/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);


		Configure::write('App.dir', 'auth');

		$Dispatcher->base = false;;
		$_SERVER['DOCUMENT_ROOT'] = '/cake/repo/branches';
		$_SERVER['SCRIPT_FILENAME'] = '/cake/repo/branches/demos/auth/webroot/index.php';
		$_SERVER['PHP_SELF'] = '/demos/auth/webroot/index.php';

		$result = $Dispatcher->baseUrl();
		$expected = '/demos/auth';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/demos/auth/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);

		Configure::write('App.dir', 'code');

		$Dispatcher->base = false;;
		$_SERVER['DOCUMENT_ROOT'] = '/Library/WebServer/Documents';
		$_SERVER['SCRIPT_FILENAME'] = '/Library/WebServer/Documents/clients/PewterReport/code/webroot/index.php';
		$_SERVER['PHP_SELF'] = '/clients/PewterReport/code/webroot/index.php';
		$result = $Dispatcher->baseUrl();
		$expected = '/clients/PewterReport/code';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/clients/PewterReport/code/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);
	}
/**
 * testBaseUrlwithModRewriteAlias method
 *
 * @access public
 * @return void
 */
	function testBaseUrlwithModRewriteAlias() {
		$_SERVER['DOCUMENT_ROOT'] = '/home/aplusnur/public_html';
		$_SERVER['SCRIPT_FILENAME'] = '/home/aplusnur/cake2/app/webroot/index.php';
		$_SERVER['PHP_SELF'] = '/control/index.php';

		Configure::write('App.base', '/control');

		$Dispatcher =& new Dispatcher();
		$result = $Dispatcher->baseUrl();
		$expected = '/control';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/control/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);

		Configure::write('App.base', false);
		Configure::write('App.dir', 'affiliate');
		Configure::write('App.webroot', 'newaffiliate');

		$_SERVER['DOCUMENT_ROOT'] = '/var/www/abtravaff/html';
		$_SERVER['SCRIPT_FILENAME'] = '/var/www/abtravaff/html/newaffiliate/index.php';
		$_SERVER['PHP_SELF'] = '/newaffiliate/index.php';
		$Dispatcher =& new Dispatcher();
		$result = $Dispatcher->baseUrl();
		$expected = '/newaffiliate';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/newaffiliate/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);
	}
/**
 * testBaseUrlAndWebrootWithBaseUrl method
 *
 * @access public
 * @return void
 */
	function testBaseUrlAndWebrootWithBaseUrl() {
		$Dispatcher =& new Dispatcher();

		Configure::write('App.dir', 'app');

		Configure::write('App.baseUrl', '/app/webroot/index.php');
		$result = $Dispatcher->baseUrl();
		$expected = '/app/webroot/index.php';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/app/webroot/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);

		Configure::write('App.baseUrl', '/app/webroot/test.php');
		$result = $Dispatcher->baseUrl();
		$expected = '/app/webroot/test.php';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/app/webroot/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);

		Configure::write('App.baseUrl', '/app/index.php');
		$result = $Dispatcher->baseUrl();
		$expected = '/app/index.php';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/app/webroot/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);

		Configure::write('App.baseUrl', '/index.php');
		$result = $Dispatcher->baseUrl();
		$expected = '/index.php';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/app/webroot/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);

		Configure::write('App.baseUrl', '/CakeBB/app/webroot/index.php');
		$result = $Dispatcher->baseUrl();
		$expected = '/CakeBB/app/webroot/index.php';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/CakeBB/app/webroot/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);

		Configure::write('App.baseUrl', '/CakeBB/app/index.php');
		$result = $Dispatcher->baseUrl();
		$expected = '/CakeBB/app/index.php';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/CakeBB/app/webroot/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);

		Configure::write('App.baseUrl', '/CakeBB/index.php');
		$result = $Dispatcher->baseUrl();
		$expected = '/CakeBB/index.php';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/CakeBB/app/webroot/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);

		Configure::write('App.baseUrl', '/dbhauser/index.php');
		$_SERVER['DOCUMENT_ROOT'] = '/kunden/homepages/4/d181710652/htdocs/joomla';
		$_SERVER['SCRIPT_FILENAME'] = '/kunden/homepages/4/d181710652/htdocs/joomla/dbhauser/index.php';
		$result = $Dispatcher->baseUrl();
		$expected = '/dbhauser/index.php';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/dbhauser/app/webroot/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);
	}
/**
 * testBaseUrlAndWebrootWithBase method
 *
 * @access public
 * @return void
 */
	function testBaseUrlAndWebrootWithBase() {
		$Dispatcher =& new Dispatcher();
		$Dispatcher->base = '/app';
		$result = $Dispatcher->baseUrl();
		$expected = '/app';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/app/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);

		$Dispatcher->base = '';
		$result = $Dispatcher->baseUrl();
		$expected = '';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);

		Configure::write('App.dir', 'testbed');
		$Dispatcher->base = '/cake/testbed/webroot';
		$result = $Dispatcher->baseUrl();
		$expected = '/cake/testbed/webroot';
		$this->assertEqual($expected, $result);
		$expectedWebroot = '/cake/testbed/webroot/';
		$this->assertEqual($expectedWebroot, $Dispatcher->webroot);
	}
/**
 * testMissingController method
 *
 * @access public
 * @return void
 */
	function testMissingController() {
		$Dispatcher =& new TestDispatcher();
		Configure::write('App.baseUrl','/index.php');
		$url = 'some_controller/home/param:value/param2:value2';
		$controller = $Dispatcher->dispatch($url, array('return' => 1));

		$expected = 'missingController';
		$this->assertEqual($expected, $controller);
	}
/**
 * testPrivate method
 *
 * @access public
 * @return void
 */
	function testPrivate() {
		$Dispatcher =& new TestDispatcher();
		Configure::write('App.baseUrl','/index.php');
		$url = 'some_pages/_protected/param:value/param2:value2';
		$controller = $Dispatcher->dispatch($url, array('return' => 1));

		$expected = 'privateAction';
		$this->assertEqual($expected, $controller);
	}
/**
 * testMissingAction method
 *
 * @access public
 * @return void
 */
	function testMissingAction() {
		$Dispatcher =& new TestDispatcher();
		Configure::write('App.baseUrl','/index.php');
		$url = 'some_pages/home/param:value/param2:value2';

		$controller = $Dispatcher->dispatch($url, array('return'=> 1));
		$expected = 'missingAction';
		$this->assertEqual($expected, $controller);

		$Dispatcher =& new TestDispatcher();
		Configure::write('App.baseUrl','/index.php');
		$url = 'some_pages/redirect/param:value/param2:value2';

		$controller = $Dispatcher->dispatch($url, array('return'=> 1));
		$expected = 'missingAction';
		$this->assertEqual($expected, $controller);
	}
/**
 * testDispatch method
 *
 * @access public
 * @return void
 */
	function testDispatch() {
		$Dispatcher =& new TestDispatcher();
		Configure::write('App.baseUrl','/index.php');
		$url = 'pages/home/param:value/param2:value2';

		$controller = $Dispatcher->dispatch($url, array('return' => 1));
		$expected = 'Pages';
		$this->assertEqual($expected, $controller->name);

		$expected = array('0' => 'home', 'param' => 'value', 'param2' => 'value2');
		$this->assertIdentical($expected, $controller->passedArgs);

		Configure::write('App.baseUrl','/pages/index.php');

		$url = 'pages/home';
		$controller = $Dispatcher->dispatch($url, array('return' => 1));

		$expected = 'Pages';
		$this->assertEqual($expected, $controller->name);

		$url = 'pages/home/';
		$controller = $Dispatcher->dispatch($url, array('return' => 1));

		$expected = 'Pages';
		$this->assertEqual($expected, $controller->name);

		unset($Dispatcher);

		$Dispatcher =& new TestDispatcher();
		Configure::write('App.baseUrl','/timesheets/index.php');

		$url = 'timesheets';
		$controller = $Dispatcher->dispatch($url, array('return' => 1));

		$expected = 'Timesheets';
		$this->assertEqual($expected, $controller->name);

		$url = 'timesheets/';
		$controller = $Dispatcher->dispatch($url, array('return' => 1));

		$this->assertEqual('Timesheets', $controller->name);
		$this->assertEqual('/timesheets/index.php', $Dispatcher->base);


		$url = 'test_dispatch_pages/camelCased';
		$controller = $Dispatcher->dispatch($url, array('return' => 1));
		$this->assertEqual('TestDispatchPages', $controller->name);
	}
/**
 * testAdminDispatch method
 *
 * @access public
 * @return void
 */
	function testAdminDispatch() {
		$_POST = array();
		$Dispatcher =& new TestDispatcher();
		Configure::write('Routing.admin', 'admin');
		Configure::write('App.baseUrl','/cake/repo/branches/1.2.x.x/index.php');
		$url = 'admin/test_dispatch_pages/index/param:value/param2:value2';

		Router::reload();
		$Router =& Router::getInstance();
		$controller = $Dispatcher->dispatch($url, array('return' => 1));

		$expected = 'TestDispatchPages';
		$this->assertEqual($expected, $controller->name);

		$expected = array('param' => 'value', 'param2' => 'value2');
		$this->assertIdentical($expected, $controller->passedArgs);
		$this->assertTrue($controller->params['admin']);

		$expected = '/cake/repo/branches/1.2.x.x/index.php/admin/test_dispatch_pages/index/param:value/param2:value2';
		$this->assertIdentical($expected, $controller->here);

		$expected = '/cake/repo/branches/1.2.x.x/index.php';
		$this->assertIdentical($expected, $controller->base);
	}
/**
 * testPluginDispatch method
 *
 * @access public
 * @return void
 */
	function testPluginDispatch() {
		$_POST = array();
		$_SERVER['PHP_SELF'] = '/cake/repo/branches/1.2.x.x/index.php';

		Router::reload();
		$Dispatcher =& new TestDispatcher();
		Router::connect('/my_plugin/:controller/*', array('plugin'=>'my_plugin', 'controller'=>'pages', 'action'=>'display'));

		$Dispatcher->base = false;
		$url = 'my_plugin/some_pages/home/param:value/param2:value2';
		$controller = $Dispatcher->dispatch($url, array('return' => 1));

		$result = $Dispatcher->parseParams($url);
		$expected = array(
			'pass' => array('home'),
			'named' => array('param'=> 'value', 'param2'=> 'value2'), 'plugin'=> 'my_plugin',
			'controller'=> 'some_pages', 'action'=> 'display', 'form'=> null,
			'url'=> array('url'=> 'my_plugin/some_pages/home/param:value/param2:value2'),
		);
		ksort($expected);
		ksort($result);

		$this->assertEqual($expected, $result);

		$expected = 'my_plugin';
		$this->assertIdentical($expected, $controller->plugin);

		$expected = 'SomePages';
		$this->assertIdentical($expected, $controller->name);

		$expected = array('0' => 'home', 'param'=>'value', 'param2'=>'value2');
		$this->assertIdentical($expected, $controller->passedArgs);

		$expected = '/cake/repo/branches/1.2.x.x/my_plugin/some_pages/home/param:value/param2:value2';
		$this->assertIdentical($expected, $controller->here);

		$expected = '/cake/repo/branches/1.2.x.x';
		$this->assertIdentical($expected, $controller->base);
	}
/**
 * testAutomaticPluginDispatch method
 *
 * @access public
 * @return void
 */
	function testAutomaticPluginDispatch() {
		$_POST = array();
		$_SERVER['PHP_SELF'] = '/cake/repo/branches/1.2.x.x/index.php';

		Router::reload();
		$Dispatcher =& new TestDispatcher();
		Router::connect('/my_plugin/:controller/:action/*', array('plugin'=>'my_plugin', 'controller'=>'pages', 'action'=>'display'));

		$Dispatcher->base = false;

		$url = 'my_plugin/other_pages/index/param:value/param2:value2';
		$controller = $Dispatcher->dispatch($url, array('return' => 1));

		$expected = 'my_plugin';
		$this->assertIdentical($expected, $controller->plugin);

		$expected = 'OtherPages';
		$this->assertIdentical($expected, $controller->name);

		$expected = 'index';
		$this->assertIdentical($expected, $controller->action);

		$expected = array('param' => 'value', 'param2' => 'value2');
		$this->assertIdentical($expected, $controller->passedArgs);

		$expected = '/cake/repo/branches/1.2.x.x/my_plugin/other_pages/index/param:value/param2:value2';
		$this->assertIdentical($expected, $controller->here);

		$expected = '/cake/repo/branches/1.2.x.x';
		$this->assertIdentical($expected, $controller->base);
	}
/**
 * testAutomaticPluginControllerDispatch method
 *
 * @access public
 * @return void
 */
	function testAutomaticPluginControllerDispatch() {
		$_POST = array();
		$_SERVER['PHP_SELF'] = '/cake/repo/branches/1.2.x.x/index.php';

		Router::reload();
		$Dispatcher =& new TestDispatcher();
		$Dispatcher->base = false;

		$url = 'my_plugin/add/param:value/param2:value2';

		$controller = $Dispatcher->dispatch($url, array('return' => 1));

		$expected = 'my_plugin';
		$this->assertIdentical($controller->plugin, $expected);

		$expected = 'MyPlugin';
		$this->assertIdentical($controller->name, $expected);

		$expected = 'add';
		$this->assertIdentical($controller->action, $expected);

		$expected = array('param' => 'value', 'param2' => 'value2');
		$this->assertEqual($controller->params['named'], $expected);


		Configure::write('Routing.admin', 'admin');

		Router::reload();
		$Dispatcher =& new TestDispatcher();
		$Dispatcher->base = false;

		$url = 'admin/my_plugin/add/5/param:value/param2:value2';
		$controller = $Dispatcher->dispatch($url, array('return' => 1));

		$expected = 'my_plugin';
		$this->assertIdentical($controller->plugin, $expected);

		$expected = 'MyPlugin';
		$this->assertIdentical($controller->name, $expected);

		$expected = 'admin_add';
		$this->assertIdentical($controller->action, $expected);

		$expected = array(0 => 5, 'param'=>'value', 'param2'=>'value2');
		$this->assertEqual($controller->passedArgs, $expected);

		Router::reload();

		$Dispatcher =& new TestDispatcher();
		$Dispatcher->base = false;

		$controller = $Dispatcher->dispatch('admin/articles_test', array('return' => 1));

		$expected = 'articles_test';
		$this->assertIdentical($controller->plugin, $expected);

		$expected = 'ArticlesTest';
		$this->assertIdentical($controller->name, $expected);

		$expected = 'admin_index';
		$this->assertIdentical($controller->action, $expected);

		$expected = array(
			'pass'=> array(), 'named' => array(), 'controller' => 'articles_test', 'plugin' => 'articles_test', 'action' => 'admin_index',
			'prefix' => 'admin', 'admin' =>  true, 'form' => array(), 'url' => array('url' => 'admin/articles_test'), 'return' => 1
		);
		$this->assertEqual($controller->params, $expected);
	}
/**
 * testAutomaticPluginControllerMissingActionDispatch method
 *
 * @access public
 * @return void
 */
	function testAutomaticPluginControllerMissingActionDispatch() {
		$_POST = array();
		$_SERVER['PHP_SELF'] = '/cake/repo/branches/1.2.x.x/index.php';

		Router::reload();
		$Dispatcher =& new TestDispatcher();
		$Dispatcher->base = false;

		$url = 'my_plugin/param:value/param2:value2';
		$controller = $Dispatcher->dispatch($url, array('return'=> 1));

		$expected = 'missingAction';
		$this->assertIdentical($expected, $controller);
	}
/**
 * testPrefixProtection method
 *
 * @access public
 * @return void
 */
	function testPrefixProtection() {
		$_POST = array();
		$_SERVER['PHP_SELF'] = '/cake/repo/branches/1.2.x.x/index.php';

		Router::reload();
		Router::connect('/admin/:controller/:action/*', array('prefix'=>'admin'), array('controller', 'action'));

		$Dispatcher =& new TestDispatcher();
		$Dispatcher->base = false;

		$url = 'test_dispatch_pages/admin_index/param:value/param2:value2';
		$controller = $Dispatcher->dispatch($url, array('return' => 1));

		$expected = 'privateAction';
		$this->assertIdentical($expected, $controller);
	}
/**
 * undocumented function
 *
 * @return void
 **/
	function testTestPluginDispatch() {
		$Dispatcher =& new TestDispatcher();
		$_back = Configure::read('pluginPaths');
		Configure::write('pluginPaths', array(TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' . DS . 'plugins' . DS));
		$url = '/test_plugin/tests/index';
		$result = $Dispatcher->dispatch($url, array('return' => 1));
		$this->assertTrue(class_exists('TestsController'));
		$this->assertTrue(class_exists('TestPluginAppController'));
		$this->assertTrue(class_exists('OtherComponentComponent'));
		$this->assertTrue(class_exists('PluginsComponentComponent'));

		Configure::write('pluginPaths', $_back);
	}
/**
 * testChangingParamsFromBeforeFilter method
 *
 * @access public
 * @return void
 */
	function testChangingParamsFromBeforeFilter() {
		$Dispatcher =& new TestDispatcher();
		$url = 'some_posts/index/param:value/param2:value2';
		$controller = $Dispatcher->dispatch($url, array('return' => 1));

		$expected = 'missingAction';
		$this->assertEqual($expected, $controller);

		$url = 'some_posts/something_else/param:value/param2:value2';
		$controller = $Dispatcher->dispatch($url, array('return' => 1));

		$expected = 'SomePosts';
		$this->assertEqual($expected, $controller->name);

		$expected = 'change';
		$this->assertEqual($expected, $controller->action);

		$expected = array('changed');
		$this->assertIdentical($expected, $controller->params['pass']);
	}
/**
 * testStaticAssets method
 *
 * @access public
 * @return void
 */
	function testStaticAssets() {
		Router::reload();
		$Configure = Configure::getInstance();
		$Configure->__objects = null;

		Configure::write('pluginPaths', array(TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' . DS . 'plugins' . DS));
		Configure::write('vendorPaths', array(TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' . DS . 'vendors'. DS));

		$Dispatcher =& new TestDispatcher();

		Configure::write('debug', 0);
		ob_start();
		$Dispatcher->dispatch('/img/test.jpg');
		$result = ob_get_clean();
		$file = file_get_contents(TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' . DS . 'vendors' . DS . 'img' . DS . 'test.jpg');
		$this->assertEqual($file, $result);


		Configure::write('debug', 0);
		$Dispatcher->params = $Dispatcher->parseParams('css/test_asset.css');

		ob_start();
		$Dispatcher->cached('css/test_asset.css');
		$result = ob_get_clean();
		$this->assertEqual('this is the test asset css file', $result);


		Configure::write('debug', 0);
		$Dispatcher->params = $Dispatcher->parseParams('test_plugin/css/test_plugin_asset.css');
		ob_start();
		$Dispatcher->cached('test_plugin/css/test_plugin_asset.css');
		$result = ob_get_clean();
		$this->assertEqual('this is the test plugin asset css file', $result);

		Configure::write('debug', 0);
		$Dispatcher->params = $Dispatcher->parseParams('test_plugin/img/cake.icon.gif');
		ob_start();
		$Dispatcher->cached('test_plugin/img/cake.icon.gif');
		$result = ob_get_clean();
		$file = file_get_contents(TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' . DS . 'plugins' . DS . 'test_plugin' .DS . 'vendors' . DS . 'img' . DS . 'cake.icon.gif');
		$this->assertEqual($file, $result);

		header('Content-type: text/html');//reset the header content-type without page can render as plain text.
	}
/**
 * testFullPageCachingDispatch method
 *
 * @access public
 * @return void
 */
	function testFullPageCachingDispatch() {
		Configure::write('Cache.disable', false);
		Configure::write('Cache.check', true);
		Configure::write('debug', 2);

		$_POST = array();
		$_SERVER['PHP_SELF'] = '/';

		Router::reload();
		Router::connect('/', array('controller' => 'test_cached_pages', 'action' => 'index'));

		Configure::write('viewPaths', array(TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' . DS . 'views'. DS));

		$dispatcher =& new Dispatcher();
		$dispatcher->base = false;

		$url = '/';

		ob_start();
		$dispatcher->dispatch($url);
		$out = ob_get_clean();

		ob_start();
		$dispatcher->cached($url);
		$cached = ob_get_clean();

		$result = str_replace(array("\t", "\r\n", "\n"), "", $out);
		$cached = preg_replace('/<!--+[^<>]+-->/', '', $cached);
		$expected =  str_replace(array("\t", "\r\n", "\n"), "", $cached);

		$this->assertEqual($result, $expected);

		$filename = $this->__cachePath($dispatcher->here);
		unlink($filename);

		$dispatcher->base = false;
		$url = 'test_cached_pages/index';

		ob_start();
		$dispatcher->dispatch($url);
		$out = ob_get_clean();

		ob_start();
		$dispatcher->cached($url);
		$cached = ob_get_clean();

		$result = str_replace(array("\t", "\r\n", "\n"), "", $out);
		$cached = preg_replace('/<!--+[^<>]+-->/', '', $cached);
		$expected =  str_replace(array("\t", "\r\n", "\n"), "", $cached);

		$this->assertEqual($result, $expected);
		$filename = $this->__cachePath($dispatcher->here);
		unlink($filename);

		$url = 'TestCachedPages/index';

		ob_start();
		$dispatcher->dispatch($url);
		$out = ob_get_clean();

		ob_start();
		$dispatcher->cached($url);
		$cached = ob_get_clean();

		$result = str_replace(array("\t", "\r\n", "\n"), "", $out);
		$cached = preg_replace('/<!--+[^<>]+-->/', '', $cached);
		$expected =  str_replace(array("\t", "\r\n", "\n"), "", $cached);

		$this->assertEqual($result, $expected);
		$filename = $this->__cachePath($dispatcher->here);
		unlink($filename);

		$url = 'TestCachedPages/test_nocache_tags';

		ob_start();
		$dispatcher->dispatch($url);
		$out = ob_get_clean();

		ob_start();
		$dispatcher->cached($url);
		$cached = ob_get_clean();

		$result = str_replace(array("\t", "\r\n", "\n"), "", $out);
		$cached = preg_replace('/<!--+[^<>]+-->/', '', $cached);
		$expected =  str_replace(array("\t", "\r\n", "\n"), "", $cached);

		$this->assertEqual($result, $expected);
		$filename = $this->__cachePath($dispatcher->here);
		unlink($filename);

		$url = 'test_cached_pages/view/param/param';

		ob_start();
		$dispatcher->dispatch($url);
		$out = ob_get_clean();

		ob_start();
		$dispatcher->cached($url);
		$cached = ob_get_clean();

		$result = str_replace(array("\t", "\r\n", "\n"), "", $out);
		$cached = preg_replace('/<!--+[^<>]+-->/', '', $cached);
		$expected =  str_replace(array("\t", "\r\n", "\n"), "", $cached);

		$this->assertEqual($result, $expected);
		$filename = $this->__cachePath($dispatcher->here);
		unlink($filename);
	}
/**
 * testHttpMethodOverrides method
 *
 * @access public
 * @return void
 */
	function testHttpMethodOverrides() {
		Router::reload();
		Router::mapResources('Posts');

		$_SERVER['REQUEST_METHOD'] = 'POST';
		$dispatcher =& new Dispatcher();
		$dispatcher->base = false;

		$result = $dispatcher->parseParams('/posts');
		$expected = array('pass' => array(), 'named' => array(), 'plugin' => null, 'controller' => 'posts', 'action' => 'add', '[method]' => 'POST', 'form' => array(), 'url' => array());
		$this->assertEqual($result, $expected);

		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] = 'PUT';

		$result = $dispatcher->parseParams('/posts/5');
		$expected = array('pass' => array('5'), 'named' => array(), 'id' => '5', 'plugin' => null, 'controller' => 'posts', 'action' => 'edit', '[method]' => 'PUT', 'form' => array(), 'url' => array());
		$this->assertEqual($result, $expected);

		unset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
		$_SERVER['REQUEST_METHOD'] = 'GET';

		$result = $dispatcher->parseParams('/posts/5');
		$expected = array('pass' => array('5'), 'named' => array(), 'id' => '5', 'plugin' => null, 'controller' => 'posts', 'action' => 'view', '[method]' => 'GET', 'form' => array(), 'url' => array());
		$this->assertEqual($result, $expected);

		$_POST['_method'] = 'PUT';

		$result = $dispatcher->parseParams('/posts/5');
		$expected = array('pass' => array('5'), 'named' => array(), 'id' => '5', 'plugin' => null, 'controller' => 'posts', 'action' => 'edit', '[method]' => 'PUT', 'form' => array(), 'url' => array());
		$this->assertEqual($result, $expected);

		$_POST['_method'] = 'POST';
		$_POST['data'] = array('Post' => array('title' => 'New Post'));
		$_POST['extra'] = 'data';
		$_SERVER = array();

		$result = $dispatcher->parseParams('/posts');
		$expected = array(
			'pass' => array(), 'named' => array(), 'plugin' => null, 'controller' => 'posts', 'action' => 'add',
			'[method]' => 'POST', 'form' => array('extra' => 'data'), 'data' => array('Post' => array('title' => 'New Post')),
			'url' => array()
		);
		$this->assertEqual($result, $expected);

		unset($_POST['_method']);
	}
/**
 * testEnvironmentDetection method
 *
 * @access public
 * @return void
 */
	function testEnvironmentDetection() {
		$dispatcher =& new Dispatcher();

		$environments = array(
			'IIS' => array(
				'No rewrite base path' => array(
					'App' => array('base' => false, 'baseUrl' => '/index.php?', 'server' => 'IIS'),
					'SERVER' => array('HTTPS' => 'off', 'SCRIPT_NAME' => '/index.php', 'PATH_TRANSLATED' => 'C:\\Inetpub\\wwwroot', 'QUERY_STRING' => '', 'REMOTE_ADDR' => '127.0.0.1', 'REMOTE_HOST' => '127.0.0.1', 'REQUEST_METHOD' => 'GET', 'SERVER_NAME' => 'localhost', 'SERVER_PORT' => '80', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'SERVER_SOFTWARE' => 'Microsoft-IIS/5.1', 'APPL_PHYSICAL_PATH' => 'C:\\Inetpub\\wwwroot\\', 'REQUEST_URI' => '/index.php', 'URL' => '/index.php', 'SCRIPT_FILENAME' => 'C:\\Inetpub\\wwwroot\\index.php', 'ORIG_PATH_INFO' => '/index.php', 'PATH_INFO' => '', 'ORIG_PATH_TRANSLATED' => 'C:\\Inetpub\\wwwroot\\index.php', 'DOCUMENT_ROOT' => 'C:\\Inetpub\\wwwroot', 'PHP_SELF' => '/index.php', 'HTTP_ACCEPT' => '*/*', 'HTTP_ACCEPT_LANGUAGE' => 'en-us', 'HTTP_CONNECTION' => 'Keep-Alive', 'HTTP_HOST' => 'localhost', 'HTTP_USER_AGENT' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)', 'HTTP_ACCEPT_ENCODING' => 'gzip, deflate', 'argv' => array(), 'argc' => 0),
					'reload' => true,
					'path' => ''
				),
				'No rewrite with path' => array(
					'SERVER' => array('QUERY_STRING' => '/posts/add', 'REQUEST_URI' => '/index.php?/posts/add', 'URL' => '/index.php?/posts/add', 'argv' => array('/posts/add'), 'argc' => 1),
					'reload' => false,
					'path' => '/posts/add'
				),
				'No rewrite sub dir 1' => array(
					'GET' => array(),
					'SERVER' => array('QUERY_STRING' => '',  'REQUEST_URI' => '/index.php', 'URL' => '/index.php', 'SCRIPT_FILENAME' => 'C:\\Inetpub\\wwwroot\\index.php', 'ORIG_PATH_INFO' => '/index.php', 'PATH_INFO' => '', 'ORIG_PATH_TRANSLATED' => 'C:\\Inetpub\\wwwroot\\index.php', 'DOCUMENT_ROOT' => 'C:\\Inetpub\\wwwroot', 'PHP_SELF' => '/index.php', 'argv' => array(), 'argc' => 0),
					'reload' => false,
					'path' => ''
				),
				'No rewrite sub dir 1 with path' => array(
					'GET' => array('/posts/add' => ''),
					'SERVER' => array('QUERY_STRING' => '/posts/add', 'REQUEST_URI' => '/index.php?/posts/add', 'URL' => '/index.php?/posts/add', 'SCRIPT_FILENAME' => 'C:\\Inetpub\\wwwroot\\index.php', 'argv' => array('/posts/add'), 'argc' => 1),
					'reload' => false,
					'path' => '/posts/add'
				),
				'No rewrite sub dir 2' => array(
					'App' => array('base' => false, 'baseUrl' => '/site/index.php?', 'dir' => 'app', 'webroot' => 'webroot', 'server' => 'IIS'),
					'GET' => array(),
					'POST' => array(),
					'SERVER' => array('SCRIPT_NAME' => '/site/index.php', 'PATH_TRANSLATED' => 'C:\\Inetpub\\wwwroot', 'QUERY_STRING' => '', 'REQUEST_URI' => '/site/index.php', 'URL' => '/site/index.php', 'SCRIPT_FILENAME' => 'C:\\Inetpub\\wwwroot\\site\\index.php', 'DOCUMENT_ROOT' => 'C:\\Inetpub\\wwwroot', 'PHP_SELF' => '/site/index.php', 'argv' => array(), 'argc' => 0),
					'reload' => false,
					'path' => ''
				),
				'No rewrite sub dir 2 with path' => array(
					'GET' => array('/posts/add' => ''),
					'SERVER' => array('SCRIPT_NAME' => '/site/index.php', 'PATH_TRANSLATED' => 'C:\\Inetpub\\wwwroot', 'QUERY_STRING' => '/posts/add', 'REQUEST_URI' => '/site/index.php?/posts/add', 'URL' => '/site/index.php?/posts/add', 'ORIG_PATH_TRANSLATED' => 'C:\\Inetpub\\wwwroot\\site\\index.php', 'DOCUMENT_ROOT' => 'C:\\Inetpub\\wwwroot', 'PHP_SELF' => '/site/index.php', 'argv' => array('/posts/add'), 'argc' => 1),
					'reload' => false,
					'path' => '/posts/add'
				)
			),
			'Apache' => array(
				'No rewrite base path' => array(
					'App' => array('base' => false, 'baseUrl' => '/index.php', 'dir' => 'app', 'webroot' => 'webroot'),
					'SERVER' => array('SERVER_NAME' => 'localhost', 'SERVER_ADDR' => '::1', 'SERVER_PORT' => '80', 'REMOTE_ADDR' => '::1', 'DOCUMENT_ROOT' => '/Library/WebServer/Documents/officespace/app/webroot', 'SCRIPT_FILENAME' => '/Library/WebServer/Documents/site/app/webroot/index.php', 'REQUEST_METHOD' => 'GET', 'QUERY_STRING' => '', 'REQUEST_URI' => '/', 'SCRIPT_NAME' => '/index.php', 'PHP_SELF' => '/index.php', 'argv' => array(), 'argc' => 0),
					'reload' => true,
					'path' => ''
				),
				'No rewrite with path' => array(
					'SERVER' => array('UNIQUE_ID' => 'VardGqn@17IAAAu7LY8AAAAK', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-us) AppleWebKit/523.10.5 (KHTML, like Gecko) Version/3.0.4 Safari/523.10.6', 'HTTP_ACCEPT' => 'text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5', 'HTTP_ACCEPT_LANGUAGE' => 'en-us', 'HTTP_ACCEPT_ENCODING' => 'gzip, deflate', 'HTTP_CONNECTION' => 'keep-alive', 'HTTP_HOST' => 'localhost', 'DOCUMENT_ROOT' => '/Library/WebServer/Documents/officespace/app/webroot', 'SCRIPT_FILENAME' => '/Library/WebServer/Documents/officespace/app/webroot/index.php', 'QUERY_STRING' => '', 'REQUEST_URI' => '/index.php/posts/add', 'SCRIPT_NAME' => '/index.php', 'PATH_INFO' => '/posts/add', 'PHP_SELF' => '/index.php/posts/add', 'argv' => array(), 'argc' => 0),
					'reload' => false,
					'path' => '/posts/add'
				),
				'GET Request at base domain' => array(
					'App' => array('base' => false, 'baseUrl' => null, 'dir' => 'app', 'webroot' => 'webroot'),
					'SERVER'	=> array('UNIQUE_ID' => '2A-v8sCoAQ8AAAc-2xUAAAAB', 'HTTP_ACCEPT_LANGUAGE' => 'en-us', 'HTTP_ACCEPT_ENCODING' => 'gzip, deflate', 'HTTP_COOKIE' => 'CAKEPHP=jcbv51apn84kd9ucv5aj2ln3t3', 'HTTP_CONNECTION' => 'keep-alive', 'HTTP_HOST' => 'cake.1.2', 'SERVER_NAME' => 'cake.1.2', 'SERVER_ADDR' => '127.0.0.1', 'SERVER_PORT' => '80', 'REMOTE_ADDR' => '127.0.0.1', 'DOCUMENT_ROOT' => '/Volumes/Home/htdocs/cake/repo/branches/1.2.x.x/app/webroot', 'SERVER_ADMIN' => 'you@example.com', 'SCRIPT_FILENAME' => '/Volumes/Home/htdocs/cake/repo/branches/1.2.x.x/app/webroot/index.php', 'REMOTE_PORT' => '53550', 'GATEWAY_INTERFACE' => 'CGI/1.1', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'REQUEST_METHOD' => 'GET', 'QUERY_STRING' => 'a=b', 'REQUEST_URI' => '/?a=b', 'SCRIPT_NAME' => '/index.php', 'PHP_SELF' => '/index.php'),
					'GET' => array('a' => 'b'),
					'POST' => array(),
					'reload' => true,
					'path' => '',
					'urlParams' => array('a' => 'b'),
					'environment' => array('CGI_MODE' => false)
				),
				'New CGI no mod_rewrite' => array(
					'App' => array('base' => false, 'baseUrl' => '/limesurvey20/index.php', 'dir' => 'app', 'webroot' => 'webroot'),
					'SERVER' => array('DOCUMENT_ROOT' => '/home/.sites/110/site313/web', 'PATH_INFO' => '/installations', 'PATH_TRANSLATED' => '/home/.sites/110/site313/web/limesurvey20/index.php', 'PHPRC' => '/home/.sites/110/site313', 'QUERY_STRING' => '', 'REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/limesurvey20/index.php/installations', 'SCRIPT_FILENAME' => '/home/.sites/110/site313/web/limesurvey20/index.php', 'SCRIPT_NAME' => '/limesurvey20/index.php', 'SCRIPT_URI' => 'http://www.gisdat-umfragen.at/limesurvey20/index.php/installations', 'PHP_SELF' => '/limesurvey20/index.php/installations', 'CGI_MODE' => true),
					'GET' => array(),
					'POST' => array(),
					'reload' => true,
					'path' => '/installations',
					'urlParams' => array(),
					'environment' => array('CGI_MODE' => true)
				)
			)
		);
		$backup = $this->__backupEnvironment();

		foreach ($environments as $name => $env) {
			foreach ($env as $descrip => $settings) {
				if ($settings['reload']) {
					$this->__reloadEnvironment();
				}
				$this->__loadEnvironment($settings);
				$this->assertEqual($dispatcher->uri(), $settings['path'], "%s on environment: {$name}, on setting: {$descrip}");

				if (isset($settings['urlParams'])) {
					$this->assertEqual($_GET, $settings['urlParams'], "%s on environment: {$name}, on setting: {$descrip}");
				}
				if (isset($settings['environment'])) {
					foreach ($settings['environment'] as $key => $val) {
						$this->assertEqual(env($key), $val, "%s on key {$key} on environment: {$name}, on setting: {$descrip}");
					}
				}
			}
		}
		$this->__loadEnvironment(array_merge(array('reload' => true), $backup));
	}
/**
 * backupEnvironment method
 *
 * @access private
 * @return void
 */
	function __backupEnvironment() {
		return array(
			'App'	=> Configure::read('App'),
			'GET'	=> $_GET,
			'POST'	=> $_POST,
			'SERVER'=> $_SERVER
		);
	}
/**
 * reloadEnvironment method
 *
 * @access private
 * @return void
 */
	function __reloadEnvironment() {
		foreach ($_GET as $key => $val) {
			unset($_GET[$key]);
		}
		foreach ($_POST as $key => $val) {
			unset($_POST[$key]);
		}
		foreach ($_SERVER as $key => $val) {
			unset($_SERVER[$key]);
		}
		Configure::write('App', array());
	}
/**
 * loadEnvironment method
 *
 * @param mixed $env
 * @access private
 * @return void
 */
	function __loadEnvironment($env) {
		if ($env['reload']) {
			$this->__reloadEnvironment();
		}

		if (isset($env['App'])) {
			Configure::write('App', $env['App']);
		}

		if (isset($env['GET'])) {
			foreach ($env['GET'] as $key => $val) {
				$_GET[$key] = $val;
			}
		}

		if (isset($env['POST'])) {
			foreach ($env['POST'] as $key => $val) {
				$_POST[$key] = $val;
			}
		}

		if (isset($env['SERVER'])) {
			foreach ($env['SERVER'] as $key => $val) {
				$_SERVER[$key] = $val;
			}
		}
	}
/**
 * cachePath method
 *
 * @param mixed $her
 * @access private
 * @return string
 */
	function __cachePath($here) {
		$path = $here;
		if ($here == '/') {
			$path = 'home';
		}
		$path = Inflector::slug($path);

		$filename = CACHE . 'views' . DS . $path . '.php';

		if (!file_exists($filename)) {
			$filename = CACHE . 'views' . DS . $path . '_index.php';
		}
		return $filename;
	}
/**
 * tearDown method
 *
 * @access public
 * @return void
 */
	function tearDown() {
		$_GET = $this->_get;
	}
}
?>