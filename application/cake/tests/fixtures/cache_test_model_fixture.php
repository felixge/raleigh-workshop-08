<?php
/* SVN FILE: $Id: cache_test_model_fixture.php 7094 2008-06-02 19:22:55Z AD7six $ */
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
 * @subpackage		cake.tests.fixtures
 * @since			CakePHP(tm) v 1.2.0.4667
 * @version			$Revision: 7094 $
 * @modifiedby		$LastChangedBy: AD7six $
 * @lastmodified	$Date: 2008-06-02 21:22:55 +0200 (Mo, 02 Jun 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * Short description for class.
 *
 * @package		cake.tests
 * @subpackage	cake.tests.fixtures
 */
class CacheTestModelFixture extends CakeTestFixture {
/**
 * name property
 * 
 * @var string 'CacheTestModel'
 * @access public
 */
	var $name = 'CacheTestModel';
/**
 * fields property
 * 
 * @var array
 * @access public
 */
	var $fields = array(
		'id'		=> array('type' => 'string', 'length' => 255, 'key' => 'primary'),
		'data'		=> array('type' => 'string', 'length' => 255, 'default' => ''),
		'expires'	=> array('type' => 'integer', 'length' => 10, 'default' => '0'),
	);
}

?>
