<?php
/* SVN FILE: $Id: cake_test_fixture.php 7421 2008-08-02 19:41:53Z AD7six $ */
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
 * @package			cake
 * @subpackage		cake.cake.tests.libs
 * @since			CakePHP(tm) v 1.2.0.4667
 * @version			$Revision: 7421 $
 * @modifiedby		$LastChangedBy: AD7six $
 * @lastmodified	$Date: 2008-08-02 21:41:53 +0200 (Sa, 02 Aug 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * Short description for class.
 *
 * @package    cake
 * @subpackage cake.cake.tests.lib
 */
class CakeTestFixture extends Object {
/**
 * Cake's DBO driver (e.g: DboMysql).
 *
 * @access public
 */
	var $db = null;
/**
 * Full Table Name
 *
 * @access public
 */
	var $table = null;

/**
 * Instantiate the fixture.
 *
 * @param object	Cake's DBO driver (e.g: DboMysql).
 *
 * @access public
 */
	function __construct(&$db) {
		App::import('Model', 'Schema');
		$this->Schema = new CakeSchema(array('name' => 'TestSuite', 'connection' => 'test_suite'));

		$this->init();
	}
/**
 * Initialize the fixture.
 *
 * @param object	Cake's DBO driver (e.g: DboMysql).
 * @access public
 *
 */
	function init() {
		if (isset($this->import) && (is_string($this->import) || is_array($this->import))) {
			$import = array();

			if (is_string($this->import) || is_array($this->import) && isset($this->import['model'])) {
				$import = array_merge(array('records' => false), ife(is_array($this->import), $this->import, array()));
				$import['model'] = ife(is_array($this->import), $this->import['model'], $this->import);
			} elseif (isset($this->import['table'])) {
				$import = array_merge(array('connection' => 'default', 'records' => false), $this->import);
			}

			if (isset($import['model']) && (class_exists($import['model']) || App::import('Model', $import['model']))) {
				$connection = isset($import['connection'])
						? $import['connection']
						: 'test_suite';
				ClassRegistry::config(array('ds' => $connection));
				$model =& ClassRegistry::init($import['model']);

				$db =& ConnectionManager::getDataSource($model->useDbConfig);
				$db->cacheSources = false;
				$this->fields = $model->schema(true);
				$this->fields[$model->primaryKey]['key'] = 'primary';
			} elseif (isset($import['table'])) {
				$model =& new Model(null, $import['table'], $import['connection']);
				$db =& ConnectionManager::getDataSource($import['connection']);
				$db->cacheSources = false;
				$model->name = Inflector::camelize(Inflector::singularize($import['table']));
				$model->table = $import['table'];
				$model->tablePrefix = $db->config['prefix'];
				$this->fields = $model->schema(true);
			}

			if ($import['records'] !== false && isset($model) && isset($db)) {
				$this->records = array();

				$query = array(
					'fields' => array_keys($this->fields),
					'table' => $db->name($model->table),
					'alias' => $model->alias,
					'conditions' => array(),
					'order' => null,
					'limit' => null,
					'group' => null
				);

				foreach ($query['fields'] as $index => $field) {
					$query['fields'][$index] = $db->name($query['alias']) . '.' . $db->name($field);
				}
				$records = $db->fetchAll($db->buildStatement($query, $model), false, $model->alias);

				if ($records !== false && !empty($records)) {
					$this->records = Set::extract($records, '{n}.' . $model->alias);
				}
			}
		}

		if (!isset($this->table)) {
			$this->table = Inflector::underscore(Inflector::pluralize($this->name));
		}

		if (!isset($this->primaryKey) && isset($this->fields['id'])) {
			$this->primaryKey = 'id';
		}
	}
/**
 * Run before all tests execute, should return SQL statement to create table for this fixture could be executed successfully.
 *
 * @param object	$db	An instance of the database object used to create the fixture table
 * @return boolean True on success, false on failure
 * @access public
 */
	function create(&$db) {
		if (!isset($this->fields) || empty($this->fields)) {
			return false;
		}

		$this->Schema->_build(array($this->table => $this->fields));
		$fullDebug = $db->fullDebug;
		$db->fullDebug = false;
		$return = ($db->execute($db->createSchema($this->Schema)) !== false);
		$db->fullDebug = $fullDebug;
		return $return;
	}
/**
 * Run after all tests executed, should return SQL statement to drop table for this fixture.
 *
 * @param object	$db	An instance of the database object used to create the fixture table
 * @return boolean True on success, false on failure
 * @access public
 */
	function drop(&$db) {
		if (empty($this->fields)) {
			return;
		}
		$this->Schema->_build(array($this->table => $this->fields));
		$fullDebug = $db->fullDebug;
		$db->fullDebug = false;
		$return = ($db->execute($db->dropSchema($this->Schema)) !== false);
		$db->fullDebug = $fullDebug;
		return $return;
	}
/**
 * Run before each tests is executed, should return a set of SQL statements to insert records for the table
 * of this fixture could be executed successfully.
 *
 * @param object $db An instance of the database into which the records will be inserted
 * @return boolean on success or if there are no records to insert, or false on failure
 * @access public
 */
	function insert(&$db) {
		if (!isset($this->_insert)) {
			$values = array();

			if (isset($this->records) && !empty($this->records)) {
				foreach ($this->records as $record) {
					$fields = array_keys($record);
					$values[] = '(' . implode(', ', array_map(array(&$db, 'value'), array_values($record))) . ')';
				}
				return $db->insertMulti($this->table, $fields, $values);
			}
			return true;
		}
	}
/**
 * Truncates the current fixture. Can be overwritten by classes extending CakeFixture to trigger other events before / after
 * truncate.
 *
 * @param object $db A reference to a db instance
 * @return void
 * @access public
 */
	function truncate(&$db) {
		$fullDebug = $db->fullDebug;
		$db->fullDebug = false;
		$return = $db->truncate($this->table);
		$db->fullDebug = $fullDebug;
		return $return;
	}
}
?>
