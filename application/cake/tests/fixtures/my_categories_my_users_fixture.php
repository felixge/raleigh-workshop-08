<?php
/* SVN FILE: $Id: my_categories_my_users_fixture.php 7237 2008-06-22 15:18:30Z DarkAngelBGE $ */
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
 * @version			$Revision: 7237 $
 * @modifiedby		$LastChangedBy: DarkAngelBGE $
 * @lastmodified	$Date: 2008-06-22 17:18:30 +0200 (So, 22 Jun 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * Short description for class.
 *
 * @package		cake.tests
 * @subpackage	cake.tests.fixtures
 */
class MyCategoriesMyUsersFixture extends CakeTestFixture {
/**
 * name property
 * 
 * @var string 'MyCategoriesMyUsers'
 * @access public
 */
	var $name = 'MyCategoriesMyUsers';
/**
 * fields property
 * 
 * @var array
 * @access public
 */
	var $fields = array(
		'my_category_id' => array('type' => 'integer'),
		'my_user_id' => array('type' => 'integer'),
	);
/**
 * records property
 * 
 * @var array
 * @access public
 */
	var $records = array(
		array('my_category_id' => 1, 'my_user_id' => 1),
		array('my_category_id' => 3, 'my_user_id' => 1),
		array('my_category_id' => 1, 'my_user_id' => 2),
		array('my_category_id' => 2, 'my_user_id' => 2),
	);
}

?>