<?php
/* SVN FILE: $Id: join_a_b_fixture.php 7126 2008-06-05 15:20:45Z AD7six $ */
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
 * @since			CakePHP(tm) v 1.2.0.6317
 * @version			$Revision: 7126 $
 * @modifiedby		$LastChangedBy: AD7six $
 * @lastmodified	$Date: 2008-06-05 17:20:45 +0200 (Do, 05 Jun 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * Short description for class.
 *
 * @package		cake.tests
 * @subpackage	cake.tests.fixtures
 */
class JoinABFixture extends CakeTestFixture {
/**
 * name property
 * 
 * @var string 'JoinAsJoinB'
 * @access public
 */
	var $name = 'JoinAsJoinB';
/**
 * fields property
 * 
 * @var array
 * @access public
 */
	var $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'join_a_id' => array('type' => 'integer', 'length' => 10, 'null' => true),
		'join_b_id' => array('type' => 'integer', 'default' => null),
		'other' => array('type' => 'string', 'default' => ''),
		'created' => array('type' => 'datetime', 'null' => true),
		'updated' => array('type' => 'datetime', 'null' => true)
	);
/**
 * records property
 * 
 * @var array
 * @access public
 */
	var $records = array(
		array('join_a_id' => 1, 'join_b_id' => 2, 'other' => 'Data for Join A 1 Join B 2', 'created' => '2008-01-03 10:56:33', 'updated' => '2008-01-03 10:56:33'),
		array('join_a_id' => 2, 'join_b_id' => 3, 'other' => 'Data for Join A 2 Join B 3', 'created' => '2008-01-03 10:56:34', 'updated' => '2008-01-03 10:56:34'),
		array('join_a_id' => 3, 'join_b_id' => 1, 'other' => 'Data for Join A 3 Join B 1', 'created' => '2008-01-03 10:56:35', 'updated' => '2008-01-03 10:56:35')
	);
}

?>
