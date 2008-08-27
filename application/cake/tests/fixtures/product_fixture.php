<?php
/* SVN FILE: $Id: product_fixture.php 7222 2008-06-20 20:17:23Z nate $ */
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
 * @version			$Rev: 7222 $
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
class ProductFixture extends CakeTestFixture {
/**
 * name property
 * 
 * @var string 'Product'
 * @access public
 */
	var $name = 'Product';    
/**
 * fields property
 * 
 * @var array
 * @access public
 */
	var $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'name' => array('type' => 'string', 'length' => 255, 'null' => false),
		'type' => array('type' => 'string', 'length' => 255, 'null' => false),
		'price' => array('type' => 'integer', 'null' => false)
	);
/**
 * records property
 * 
 * @var array
 * @access public
 */
	var $records = array(
		array('name' => 'Park\'s Great Hits', 'type' => 'Music', 'price' => 19),
		array('name' => 'Silly Puddy', 'type' => 'Toy', 'price' => 3),
		array('name' => 'Playstation', 'type' => 'Toy', 'price' => 89),
		array('name' => 'Men\'s T-Shirt', 'type' => 'Clothing', 'price' => 32),
		array('name' => 'Blouse', 'type' => 'Clothing', 'price' => 34),
		array('name' => 'Electronica 2002', 'type' => 'Music', 'price' => 4),
		array('name' => 'Country Tunes', 'type' => 'Music', 'price' => 21),
		array('name' => 'Watermelon', 'type' => 'Food', 'price' => 9)
	);
}

?>