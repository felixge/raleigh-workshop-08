<?php
/* SVN FILE: $Id: session.test.php 7222 2008-06-20 20:17:23Z nate $ */
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
 * @subpackage		cake.tests.cases.libs.controller.components
 * @since			CakePHP(tm) v 1.2.0.5436
 * @version			$Revision: 7222 $
 * @modifiedby		$LastChangedBy: nate $
 * @lastmodified	$Date: 2008-06-20 22:17:23 +0200 (Fr, 20 Jun 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Core', 'Controller');
App::import('Component', 'Session');
/**
 * SessionTestController class
 *
 * @package              cake
 * @subpackage           cake.tests.cases.libs.controller.components
 */
class SessionTestController extends Controller {}
/**
 * Short description for class.
 *
 * @package    cake.tests
 * @subpackage cake.tests.cases.libs.controller.components
 */
class SessionComponentTest extends CakeTestCase {
/**
 * testSessionAutoStart method
 *
 * @access public
 * @return void
 */
	function testSessionAutoStart() {
		Configure::write('Session.start', false);
		$Session =& new SessionComponent();
		$this->assertFalse($Session->__active);
		$this->assertFalse($Session->__started);
		$Session->startup(new SessionTestController());

		Configure::write('Session.start', true);
		$Session =& new SessionComponent();
		$this->assertTrue($Session->__active);
		$this->assertFalse($Session->__started);
		$Session->startup(new SessionTestController());
		$this->assertTrue(isset($_SESSION));
	}
/**
 * testSessionInitialize method
 *
 * @access public
 * @return void
 */
	function testSessionInitialize() {
		$Session =& new SessionComponent();

		$this->assertEqual($Session->__bare, 0);

		$Session->initialize(new SessionTestController());
		$this->assertEqual($Session->__bare, 0);

		$sessionController =& new SessionTestController();
		$sessionController->params['bare'] = 1;
		$Session->initialize($sessionController);
		$this->assertEqual($Session->__bare, 1);
	}
/**
 * testSessionActivate method
 *
 * @access public
 * @return void
 */
	function testSessionActivate() {
		$Session =& new SessionComponent();

		$this->assertTrue($Session->__active);
		$this->assertNull($Session->activate());
		$this->assertTrue($Session->__active);

		Configure::write('Session.start', false);
		$Session =& new SessionComponent();
		$this->assertFalse($Session->__active);
		$this->assertNull($Session->activate());
		$this->assertTrue($Session->__active);
		Configure::write('Session.start', true);
		$Session->destroy();
	}
/**
 * testSessionValid method
 *
 * @access public
 * @return void
 */
	function testSessionValid() {
		$Session =& new SessionComponent();

		$this->assertTrue($Session->valid());

		$Session->_userAgent = 'rweerw';
		$this->assertFalse($Session->valid());

		Configure::write('Session.start', false);
		$Session =& new SessionComponent();
		$this->assertFalse($Session->__active);
		$this->assertFalse($Session->valid());
		Configure::write('Session.start', true);
	}
/**
 * testSessionError method
 *
 * @access public
 * @return void
 */
	function testSessionError() {
		$Session =& new SessionComponent();

		$this->assertFalse($Session->error());

		Configure::write('Session.start', false);
		$Session =& new SessionComponent();
		$this->assertFalse($Session->__active);
		$this->assertFalse($Session->error());
		Configure::write('Session.start', true);
	}
/**
 * testSessionReadWrite method
 *
 * @access public
 * @return void
 */
	function testSessionReadWrite() {
		$Session =& new SessionComponent();

		$this->assertFalse($Session->read('Test'));

		$this->assertTrue($Session->write('Test', 'some value'));
		$this->assertEqual($Session->read('Test'), 'some value');
		$this->assertFalse($Session->write('Test.key', 'some value'));
		$Session->del('Test');

		$this->assertTrue($Session->write('Test.key.path', 'some value'));
		$this->assertEqual($Session->read('Test.key.path'), 'some value');
		$this->assertEqual($Session->read('Test.key'), array('path' => 'some value'));
		$this->assertTrue($Session->write('Test.key.path2', 'another value'));
		$this->assertEqual($Session->read('Test.key'), array('path' => 'some value', 'path2' => 'another value'));
		$Session->del('Test');

		$array = array('key1' => 'val1', 'key2' => 'val2', 'key3' => 'val3');
		$this->assertTrue($Session->write('Test', $array));
		$this->assertEqual($Session->read('Test'), $array);
		$Session->del('Test');

		$this->assertFalse($Session->write(array('Test'), 'some value'));
		$this->assertTrue($Session->write(array('Test' => 'some value')));
		$this->assertEqual($Session->read('Test'), 'some value');
		$Session->del('Test');

		Configure::write('Session.start', false);
		$Session =& new SessionComponent();
		$this->assertFalse($Session->write('Test', 'some value'));
		$Session->write('Test', 'some value');
		$this->assertFalse($Session->read('Test'));
		Configure::write('Session.start', true);
	}
/**
 * testSessionDel method
 *
 * @access public
 * @return void
 */
	function testSessionDel() {
		$Session =& new SessionComponent();

		$this->assertFalse($Session->del('Test'));

		$Session->write('Test', 'some value');
		$this->assertTrue($Session->del('Test'));

		Configure::write('Session.start', false);
		$Session =& new SessionComponent();
		$Session->write('Test', 'some value');
		$this->assertFalse($Session->del('Test'));
		Configure::write('Session.start', true);
	}
/**
 * testSessionDelete method
 *
 * @access public
 * @return void
 */
	function testSessionDelete() {
		$Session =& new SessionComponent();

		$this->assertFalse($Session->delete('Test'));

		$Session->write('Test', 'some value');
		$this->assertTrue($Session->delete('Test'));

		Configure::write('Session.start', false);
		$Session =& new SessionComponent();
		$Session->write('Test', 'some value');
		$this->assertFalse($Session->delete('Test'));
		Configure::write('Session.start', true);
	}
/**
 * testSessionCheck method
 *
 * @access public
 * @return void
 */
	function testSessionCheck() {
		$Session =& new SessionComponent();

		$this->assertFalse($Session->check('Test'));

		$Session->write('Test', 'some value');
		$this->assertTrue($Session->check('Test'));
		$Session->delete('Test');

		Configure::write('Session.start', false);
		$Session =& new SessionComponent();
		$Session->write('Test', 'some value');
		$this->assertFalse($Session->check('Test'));
		Configure::write('Session.start', true);
	}
/**
 * testSessionFlash method
 *
 * @access public
 * @return void
 */
	function testSessionFlash() {
		$Session =& new SessionComponent();

		$this->assertNull($Session->read('Message.flash'));

		$Session->setFlash('This is a test message');
		$this->assertEqual($Session->read('Message.flash'), array('message' => 'This is a test message', 'layout' => 'default', 'params' => array()));

		$Session->setFlash('This is a test message', 'test', array('name' => 'Joel Moss'));
		$this->assertEqual($Session->read('Message.flash'), array('message' => 'This is a test message', 'layout' => 'test', 'params' => array('name' => 'Joel Moss')));

		$Session->setFlash('This is a test message', 'default', array(), 'myFlash');
		$this->assertEqual($Session->read('Message.myFlash'), array('message' => 'This is a test message', 'layout' => 'default', 'params' => array()));

		$Session->setFlash('This is a test message', 'non_existing_layout');
		$this->assertEqual($Session->read('Message.myFlash'), array('message' => 'This is a test message', 'layout' => 'default', 'params' => array()));
	}
/**
 * testSessionId method
 *
 * @access public
 * @return void
 */
	function testSessionId() {
		unset($_SESSION);
		$Session =& new SessionComponent();
		$this->assertNull($Session->id());
	}
/**
 * testSessionDestroy method
 *
 * @access public
 * @return void
 */
	function testSessionDestroy() {
		$Session =& new SessionComponent();

		$Session->write('Test', 'some value');
		$this->assertEqual($Session->read('Test'), 'some value');
		$Session->destroy('Test');
		$this->assertNull($Session->read('Test'));
	}
}
?>