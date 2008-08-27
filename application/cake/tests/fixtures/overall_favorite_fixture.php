<?php
/* SVN FILE: $Id: overall_favorite_fixture.php 7198 2008-06-16 03:11:32Z phpnut $ */
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
 * @since			CakePHP(tm) v 1.2.0.7198
 * @version			$Revision: 7198 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2008-06-16 05:11:32 +0200 (Mo, 16 Jun 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * Short description for class.
 *
 * @package		cake.tests
 * @subpackage	cake.tests.fixtures
 */
class OverallFavoriteFixture extends CakeTestFixture {
/**
 * name property
 *
 * @var string 'OverallFavorite'
 * @access public
 */
	var $name = 'OverallFavorite';
/**
 * fields property
 *
 * @var array
 * @access public
 */
	var $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'model_type' => array('type' => 'string', 'length' => 255),
		'model_id' => array('type' => 'integer'),
		'priority' => array('type' => 'integer')
	);
/**
 * records property
 *
 * @var array
 * @access public
 */
	var $records = array(
		array('id' => 1, 'model_type' => 'Cd', 'model_id' => '1', 'priority' => '1'),
		array('id' => 2, 'model_type' => 'Book', 'model_id' => '1', 'priority' => '2')
	);
}
?>