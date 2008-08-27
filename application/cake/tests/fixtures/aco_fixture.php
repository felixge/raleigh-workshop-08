<?php
/* SVN FILE: $Id: aco_fixture.php 7222 2008-06-20 20:17:23Z nate $ */
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
 * @version			$Revision: 7222 $
 * @modifiedby		$LastChangedBy: nate $
 * @lastmodified	$Date: 2008-06-20 22:17:23 +0200 (Fr, 20 Jun 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * Short description for class.
 *
 * @package		cake.tests
 * @subpackage	cake.tests.fixtures
 */
class AcoFixture extends CakeTestFixture {
/**
 * name property
 * 
 * @var string 'Aco'
 * @access public
 */
	var $name = 'Aco';
/**
 * fields property
 * 
 * @var array
 * @access public
 */
	var $fields = array(
		'id'		=> array('type' => 'integer', 'key' => 'primary'),
		'parent_id'	=> array('type' => 'integer', 'length' => 10, 'null' => true),
		'model'		=> array('type' => 'string', 'default' => ''),
		'foreign_key' => array('type' => 'integer', 'length' => 10, 'null' => true),
		'alias'		=> array('type' => 'string', 'default' => ''),
		'lft'		=> array('type' => 'integer', 'length' => 10, 'null' => true),
		'rght'		=> array('type' => 'integer', 'length' => 10, 'null' => true)
	);
/**
 * records property
 * 
 * @var array
 * @access public
 */
	var $records = array(
		array('parent_id' => null, 	'model' => null, 'foreign_key' => null, 'alias' => 'ROOT',			'lft' => 1,  'rght' => 18),
		array('parent_id' => 1,		'model' => null, 'foreign_key' => null, 'alias' => 'Controller1',	'lft' => 2,  'rght' => 9),
		array('parent_id' => 2,		'model' => null, 'foreign_key' => null, 'alias' => 'action1',		'lft' => 3,  'rght' => 6),
		array('parent_id' => 3,		'model' => null, 'foreign_key' => null, 'alias' => 'record1',		'lft' => 4,  'rght' => 5),
		array('parent_id' => 2,		'model' => null, 'foreign_key' => null, 'alias' => 'action2',		'lft' => 7,  'rght' => 8),
		array('parent_id' => 1,		'model' => null, 'foreign_key' => null, 'alias' => 'Controller2',	'lft' => 10, 'rght' => 17),
		array('parent_id' => 6,		'model' => null, 'foreign_key' => null, 'alias' => 'action1',		'lft' => 11, 'rght' => 14),
		array('parent_id' => 7,		'model' => null, 'foreign_key' => null, 'alias' => 'record1',		'lft' => 12, 'rght' => 13),
		array('parent_id' => 6,		'model' => null, 'foreign_key' => null, 'alias' => 'action2',		'lft' => 15, 'rght' => 16),
	);
}

?>