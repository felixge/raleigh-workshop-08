<?php
/* SVN FILE: $Id: bid_fixture.php 7126 2008-06-05 15:20:45Z AD7six $ */
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
class BidFixture extends CakeTestFixture {
/**
 * name property
 * 
 * @var string 'Bid'
 * @access public
 */
	var $name = 'Bid';
/**
 * fields property
 * 
 * @var array
 * @access public
 */
	var $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'message_id' => array('type' => 'integer', 'null' => false),
		'name' => array('type' => 'string', 'null' => false)
	);
/**
 * records property
 * 
 * @var array
 * @access public
 */
	var $records = array(
		array('message_id' => 1, 'name' => 'Bid 1.1'),
		array('message_id' => 1, 'name' => 'Bid 1.2'),
		array('message_id' => 3, 'name' => 'Bid 3.1'),
		array('message_id' => 2, 'name' => 'Bid 2.1'),
		array('message_id' => 2, 'name' => 'Bid 2.2')
	);
}
?>
