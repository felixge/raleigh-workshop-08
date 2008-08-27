<?php
/* SVN FILE: $Id: item_fixture.php 7126 2008-06-05 15:20:45Z AD7six $ */
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
class ItemFixture extends CakeTestFixture {
/**
 * name property
 * 
 * @var string 'Item'
 * @access public
 */
	var $name = 'Item';
/**
 * fields property
 * 
 * @var array
 * @access public
 */
	var $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'syfile_id' => array('type' => 'integer', 'null' => false),
		'published' => array('type' => 'boolean', 'null' => false),
		'name' => array('type' => 'string', 'null' => false)
	);
/**
 * records property
 * 
 * @var array
 * @access public
 */
	var $records = array(
		array('syfile_id' => 1, 'published' => 0, 'name' => 'Item 1'),
		array('syfile_id' => 2, 'published' => 0, 'name' => 'Item 2'),
		array('syfile_id' => 3, 'published' => 0, 'name' => 'Item 3'),
		array('syfile_id' => 4, 'published' => 0, 'name' => 'Item 4'),
		array('syfile_id' => 5, 'published' => 0, 'name' => 'Item 5'),
		array('syfile_id' => 6, 'published' => 0, 'name' => 'Item 6')
	);
}
?>
