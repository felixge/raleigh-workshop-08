<?php
/* SVN FILE: $Id: dbo_adodb.test.php 7295 2008-06-27 08:17:02Z gwoo $ */
/**
 * AdoDB layer for DBO.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.cake.libs.model.datasources.dbo
 * @since			CakePHP(tm) v 0.2.9
 * @version			$Rev: 7295 $
 * @modifiedby		$LastChangedBy: gwoo $
 * @lastmodified	$Date: 2008-06-27 10:17:02 +0200 (Fr, 27 Jun 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */

require_once LIBS.'model'.DS.'model.php';
require_once LIBS.'model'.DS.'datasources'.DS.'datasource.php';
require_once LIBS.'model'.DS.'datasources'.DS.'dbo_source.php';
require_once LIBS.'model'.DS.'datasources'.DS.'dbo'.DS.'dbo_adodb.php';

/**
 * Short description for class.
 *
 * @package		cake.tests
 * @subpackage	cake.tests.cases.libs.model.datasources
 */
class DboAdoTestDb extends DboAdodb {
/**
 * simulated property
 * 
 * @var array
 * @access public
 */
	var $simulated = array();
/**
 * testing property
 * 
 * @var bool true
 * @access public
 */
	var $testing = true;
/**
 * execute method
 * 
 * @param mixed $sql 
 * @access protected
 * @return void
 */
	function _execute($sql) {
		if ($this->testing) {
			$this->simulated[] = $sql;
			return null;
		}
		return parent::_execute($sql);
	}
/**
 * getLastQuery method
 * 
 * @access public
 * @return void
 */
	function getLastQuery() {
		return $this->simulated[count($this->simulated) - 1];
	}
}

/**
 * Short description for class.
 *
 * @package		cake.tests
 * @subpackage	cake.tests.cases.libs.model.datasources
 */
class AdodbTestModel extends CakeTestModel {
/**
 * name property
 * 
 * @var string 'AdodbTestModel'
 * @access public
 */
	var $name = 'AdodbTestModel';
/**
 * useTable property
 * 
 * @var bool false
 * @access public
 */
	var $useTable = false;
/**
 * find method
 * 
 * @param mixed $conditions 
 * @param mixed $fields 
 * @param mixed $order 
 * @param mixed $recursive 
 * @access public
 * @return void
 */
	function find($conditions = null, $fields = null, $order = null, $recursive = null) {
		return $conditions;
	}
/**
 * findAll method
 * 
 * @param mixed $conditions 
 * @param mixed $fields 
 * @param mixed $order 
 * @param mixed $recursive 
 * @access public
 * @return void
 */
	function findAll($conditions = null, $fields = null, $order = null, $recursive = null) {
		return $conditions;
	}
/**
 * schema method
 * 
 * @access public
 * @return void
 */
	function schema() {
		return array(
			'id'		=> array('type' => 'integer', 'null' => '', 'default' => '', 'length' => '8'),
			'client_id'	=> array('type' => 'integer', 'null' => '', 'default' => '0', 'length' => '11'),
			'name'		=> array('type' => 'string', 'null' => '', 'default' => '', 'length' => '255'),
			'login'		=> array('type' => 'string', 'null' => '', 'default' => '', 'length' => '255'),
			'passwd'	=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '255'),
			'addr_1'	=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '255'),
			'addr_2'	=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '25'),
			'zip_code'	=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'),
			'city'		=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'),
			'country'	=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'),
			'phone'		=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'),
			'fax'		=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'),
			'url'		=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '255'),
			'email'		=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'),
			'comments'	=> array('type' => 'text', 'null' => '1', 'default' => '', 'length' => ''),
			'last_login'=> array('type' => 'datetime', 'null' => '1', 'default' => '', 'length' => ''),
			'created'	=> array('type' => 'date', 'null' => '1', 'default' => '', 'length' => ''),
			'updated'	=> array('type' => 'datetime', 'null' => '1', 'default' => '', 'length' => null)
		);
	}
}

if (!class_exists('Article')) {
/**
 * Article class
 * 
 * @package              cake
 * @subpackage           cake.tests.cases.libs.model.datasources.dbo
 */
	class Article extends CakeTestModel {
/**
 * name property
 * 
 * @var string 'Article'
 * @access public
 */
		var $name = 'Article';
	
	}
}

/**
 * The test class for the DboAdobd
 *
 * @package		cake.tests
 * @subpackage	cake.tests.cases.libs.model.datasources.dbo
 */
class DboAdodbTest extends CakeTestCase {
/**
 * The Dbo instance to be tested
 *
 * @var object
 * @access public
 */
	var $db = null;
	
/**
 * undocumented class variable
 *
 * @var string
 **/
	var $fixtures = array('core.article');
	
/**
 * Skip if cannot connect to AdoDb
 *
 * @access public
 */
	function skip() {
		$this->_initDb();
		$db =& ConnectionManager::getDataSource('test_suite');
		$this->skipif($db->config['driver'] != 'adodb', 'Adodb connection not available');
	}
/**
 * Sets up a Dbo class instance for testing
 *
 * @access public
 */
	function setUp() {
		$db = ConnectionManager::getDataSource('test_suite');
		$this->db = new DboAdoTestDb($db->config);
		$this->model = new AdodbTestModel();
	}
/**
 * Sets up a Dbo class instance for testing
 *
 * @access public
 */
	function tearDown() {
		unset($this->db);
	}
/**
 * Test Dbo value method
 *
 * @access public
 */
	function testQuoting() {
		$result = $this->db->fields($this->model);
		$expected = array(
			'`AdodbTestModel`.`id` AS `AdodbTestModel__id`',
			'`AdodbTestModel`.`client_id` AS `AdodbTestModel__client_id`',
			'`AdodbTestModel`.`name` AS `AdodbTestModel__name`',
			'`AdodbTestModel`.`login` AS `AdodbTestModel__login`',
			'`AdodbTestModel`.`passwd` AS `AdodbTestModel__passwd`',
			'`AdodbTestModel`.`addr_1` AS `AdodbTestModel__addr_1`',
			'`AdodbTestModel`.`addr_2` AS `AdodbTestModel__addr_2`',
			'`AdodbTestModel`.`zip_code` AS `AdodbTestModel__zip_code`',
			'`AdodbTestModel`.`city` AS `AdodbTestModel__city`',
			'`AdodbTestModel`.`country` AS `AdodbTestModel__country`',
			'`AdodbTestModel`.`phone` AS `AdodbTestModel__phone`',
			'`AdodbTestModel`.`fax` AS `AdodbTestModel__fax`',
			'`AdodbTestModel`.`url` AS `AdodbTestModel__url`',
			'`AdodbTestModel`.`email` AS `AdodbTestModel__email`',
			'`AdodbTestModel`.`comments` AS `AdodbTestModel__comments`',
			'`AdodbTestModel`.`last_login` AS `AdodbTestModel__last_login`',
			'`AdodbTestModel`.`created` AS `AdodbTestModel__created`',
			'`AdodbTestModel`.`updated` AS `AdodbTestModel__updated`'
		);
		$this->assertEqual($result, $expected);

		$expected = "'1.2'";
		$result = $this->db->value(1.2, 'float');
		$this->assertEqual($expected, $result);

		$expected = "'1,2'";
		$result = $this->db->value('1,2', 'float');
		$this->assertEqual($expected, $result);

		$expected = "'4713e29446'";
		$result = $this->db->value('4713e29446');
		$this->assertEqual($expected, $result);

		$expected = "'10010001'";
		$result = $this->db->value('10010001');
		$this->assertEqual($expected, $result);

		$expected = "'00010010001'";
		$result = $this->db->value('00010010001');
		$this->assertEqual($expected, $result);
	}
/**
 * testColumns method
 * 
 * @access public
 * @return void
 */
	function testColumns() {

	}

}
?>