<?php
/* SVN FILE: $Id: items_portfolio_fixture.php 7126 2008-06-05 15:20:45Z AD7six $ */
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
class ItemsPortfolioFixture extends CakeTestFixture {
/**
 * name property
 * 
 * @var string 'ItemsPortfolio'
 * @access public
 */
	var $name = 'ItemsPortfolio';
/**
 * fields property
 * 
 * @var array
 * @access public
 */
	var $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'item_id' => array('type' => 'integer', 'null' => false),
		'portfolio_id' => array('type' => 'integer', 'null' => false)
	);
/**
 * records property
 * 
 * @var array
 * @access public
 */
	var $records = array(
		array('item_id' => 1, 'portfolio_id' => 1),
		array('item_id' => 2, 'portfolio_id' => 2),
		array('item_id' => 3, 'portfolio_id' => 1),
		array('item_id' => 4, 'portfolio_id' => 1),
		array('item_id' => 5, 'portfolio_id' => 1),
		array('item_id' => 6, 'portfolio_id' => 2)
	);
}
?>
