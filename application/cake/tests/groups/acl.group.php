<?php
/* SVN FILE: $Id: acl.group.php 7094 2008-06-02 19:22:55Z AD7six $ */
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
 * @subpackage		cake.tests.groups
 * @since			CakePHP(tm) v 1.2.0.4206
 * @version			$Revision: 7094 $
 * @modifiedby		$LastChangedBy: AD7six $
 * @lastmodified	$Date: 2008-06-02 21:22:55 +0200 (Mo, 02 Jun 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/** AclAndAuthGroupTest
 *
 * This test group will run the Acl and Auth tests
 *
 * @package    cake.tests
 * @subpackage cake.tests.groups
 */
/**
 * AclAndAuthGroupTest class
 * 
 * @package              cake
 * @subpackage           cake.tests.groups
 */
class AclAndAuthGroupTest extends GroupTest {
/**
 * label property
 * 
 * @var string 'Acl and Auth Tests'
 * @access public
 */
	var $label = 'Acl and Auth Tests';
/**
 * AclAndAuthGroupTest method
 * 
 * @access public
 * @return void
 */
	function AclAndAuthGroupTest() {
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'model' . DS . 'db_acl');
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'controller' . DS . 'components' . DS . 'acl');
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'controller' . DS . 'components' . DS . 'auth');
	}
}
?>
