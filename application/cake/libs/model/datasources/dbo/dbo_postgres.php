<?php
/* SVN FILE: $Id: dbo_postgres.php 7517 2008-08-27 04:21:01Z nate $ */

/**
 * PostgreSQL layer for DBO.
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
 * @since			CakePHP(tm) v 0.9.1.114
 * @version			$Revision: 7517 $
 * @modifiedby		$LastChangedBy: nate $
 * @lastmodified	$Date: 2008-08-27 06:21:01 +0200 (Mi, 27 Aug 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * PostgreSQL layer for DBO.
 *
 * Long description for class
 *
 * @package		cake
 * @subpackage	cake.cake.libs.model.datasources.dbo
 */
class DboPostgres extends DboSource {

/**
 * Driver description
 *
 * @var string
 * @access public
 */
	var $description = "PostgreSQL DBO Driver";
/**
 * Index of basic SQL commands
 *
 * @var array
 * @access protected
 */
	var $_commands = array(
		'begin'    => 'BEGIN',
		'commit'   => 'COMMIT',
		'rollback' => 'ROLLBACK'
	);
/**
 * Base driver configuration settings.  Merged with user settings.
 *
 * @var array
 * @access protected
 */
	var $_baseConfig = array(
		'connect'	=> 'pg_pconnect',
		'persistent' => true,
		'host' => 'localhost',
		'login' => 'root',
		'password' => '',
		'database' => 'cake',
		'schema' => 'public',
		'port' => 5432,
		'encoding' => ''
	);

	var $columns = array(
		'primary_key' => array('name' => 'serial NOT NULL'),
		'string' => array('name'  => 'varchar', 'limit' => '255'),
		'text' => array('name' => 'text'),
		'integer' => array('name' => 'integer', 'formatter' => 'intval'),
		'float' => array('name' => 'float', 'formatter' => 'floatval'),
		'datetime' => array('name' => 'timestamp', 'format' => 'Y-m-d H:i:s', 'formatter' => 'date'),
		'timestamp' => array('name' => 'timestamp', 'format' => 'Y-m-d H:i:s', 'formatter' => 'date'),
		'time' => array('name' => 'time', 'format' => 'H:i:s', 'formatter' => 'date'),
		'date' => array('name' => 'date', 'format' => 'Y-m-d', 'formatter' => 'date'),
		'binary' => array('name' => 'bytea'),
		'boolean' => array('name' => 'boolean'),
		'number' => array('name' => 'numeric'),
		'inet' => array('name'  => 'inet')
	);

	var $startQuote = '"';

	var $endQuote = '"';
/**
 * Contains mappings of custom auto-increment sequences, if a table uses a sequence name
 * other than what is dictated by convention.
 *
 * @var array
 */
	var $_sequenceMap = array();
/**
 * Connects to the database using options in the given configuration array.
 *
 * @return True if successfully connected.
 */
	function connect() {
		$config = $this->config;
		$conn  = "host='{$config['host']}' port='{$config['port']}' dbname='{$config['database']}' ";
		$conn .= "user='{$config['login']}' password='{$config['password']}'";

		if (!$config['persistent']) {
			$this->connection = pg_connect($conn, PGSQL_CONNECT_FORCE_NEW);
		} else {
			$this->connection = pg_pconnect($conn);
		}
		$this->connected = false;

		if ($this->connection) {
			$this->connected = true;
			$this->_execute("SET search_path TO " . $config['schema']);
		}
		if (!empty($config['encoding'])) {
			$this->setEncoding($config['encoding']);
		}
		return $this->connected;
	}
/**
 * Disconnects from database.
 *
 * @return boolean True if the database could be disconnected, else false
 */
	function disconnect() {
		if ($this->hasResult()) {
			pg_free_result($this->_result);
		}
		if (is_resource($this->connection)) {
			$this->connected = !pg_close($this->connection);
		} else {
			$this->connected = false;
		}
		return !$this->connected;
	}
/**
 * Executes given SQL statement.
 *
 * @param string $sql SQL statement
 * @return resource Result resource identifier
 */
	function _execute($sql) {
		return pg_query($this->connection, $sql);
	}
/**
 * Returns an array of tables in the database. If there are no tables, an error is raised and the application exits.
 *
 * @return array Array of tablenames in the database
 */
	function listSources() {
		$cache = parent::listSources();

		if ($cache != null) {
			return $cache;
		}

		$schema = $this->config['schema'];
		$sql = "SELECT table_name as name FROM INFORMATION_SCHEMA.tables WHERE table_schema = '{$schema}';";
		$result = $this->fetchAll($sql, false);

		if (!$result) {
			return array();
		} else {
			$tables = array();

			foreach ($result as $item) {
				$tables[] = $item[0]['name'];
			}

			parent::listSources($tables);
			return $tables;
		}
	}

/**
 * Returns an array of the fields in given table name.
 *
 * @param string $tableName Name of database table to inspect
 * @return array Fields in table. Keys are name and type
 */
	function &describe(&$model) {
		$fields = parent::describe($model);
		$table = $this->fullTableName($model, false);
		$this->_sequenceMap[$table] = array();

		if ($fields === null) {
			$cols = $this->fetchAll(
				"SELECT DISTINCT column_name AS name, data_type AS type, is_nullable AS null,
					column_default AS default, ordinal_position AS position, character_maximum_length AS char_length,
					character_octet_length AS oct_length FROM information_schema.columns
				WHERE table_name = " . $this->value($table) . " AND table_schema = " .
				$this->value($this->config['schema'])."  ORDER BY position",
				false
			);

			foreach ($cols as $column) {
				$colKey = array_keys($column);

				if (isset($column[$colKey[0]]) && !isset($column[0])) {
					$column[0] = $column[$colKey[0]];
				}

				if (isset($column[0])) {
					$c = $column[0];

					if (!empty($c['char_length'])) {
						$length = intval($c['char_length']);
					} elseif (!empty($c['oct_length'])) {
						$length = intval($c['oct_length']);
					} else {
						$length = $this->length($c['type']);
					}
					$fields[$c['name']] = array(
						'type'    => $this->column($c['type']),
						'null'    => ($c['null'] == 'NO' ? false : true),
						'default' => preg_replace("/^'(.*)'$/", "$1", preg_replace('/::.*/', '', $c['default'])),
						'length'  => $length
					);
					if ($c['name'] == $model->primaryKey) {
						$fields[$c['name']]['key'] = 'primary';
						if ($fields[$c['name']]['type'] !== 'string') {
							$fields[$c['name']]['length'] = 11;
						}
					}
					if ($fields[$c['name']]['default'] == 'NULL' || preg_match('/nextval\([\'"]?(\w+)/', $c['default'], $seq)) {
						$fields[$c['name']]['default'] = null;
						if (!empty($seq) && isset($seq[1])) {
							$this->_sequenceMap[$table][$c['name']] = $seq[1];
						}
					}
				}
			}
			$this->__cacheDescription($table, $fields);
		}
		if (isset($model->sequence)) {
			$this->_sequenceMap[$table][$model->primaryKey] = $model->sequence;
		}
		return $fields;
	}
/**
 * Returns a quoted and escaped string of $data for use in an SQL statement.
 *
 * @param string $data String to be prepared for use in an SQL statement
 * @param string $column The column into which this data will be inserted
 * @param boolean $read Value to be used in READ or WRITE context
 * @return string Quoted and escaped
 * @todo Add logic that formats/escapes data based on column type
 */
	function value($data, $column = null, $read = true) {

		$parent = parent::value($data, $column);
		if ($parent != null) {
			return $parent;
		}

		if ($data === null) {
			return 'NULL';
		}
		if (empty($column)) {
			$column = $this->introspectType($data);
		}

		switch($column) {
			case 'inet':
			case 'float':
			case 'integer':
				if ($data === '') {
					return $read ? 'NULL' : 'DEFAULT';
				}
			case 'binary':
				$data = pg_escape_bytea($data);
			break;
			case 'boolean':
				if ($data === true || $data === 't') {
					return 'TRUE';
				} elseif ($data === false || $data === 'f') {
					return 'FALSE';
				}
				return (!empty($data) ? 'TRUE' : 'FALSE');
			break;
			default:
				$data = pg_escape_string($data);
			break;
		}
		return "'" . $data . "'";
	}
/**
 * Returns a formatted error message from previous database operation.
 *
 * @return string Error message
 */
	function lastError() {
		$error = pg_last_error($this->connection);
		if ($error) {
			return $error;
		}
		return null;
	}

/**
 * Returns number of affected rows in previous database operation. If no previous operation exists, this returns false.
 *
 * @return integer Number of affected rows
 */
	function lastAffected() {
		if ($this->_result) {
			$return = pg_affected_rows($this->_result);
			return $return;
		}
		return false;
	}
/**
 * Returns number of rows in previous resultset. If no previous resultset exists,
 * this returns false.
 *
 * @return integer Number of rows in resultset
 */
	function lastNumRows() {
		if ($this->_result) {
			$return = pg_num_rows($this->_result);
			return $return;
		}
		return false;
	}
/**
 * Returns the ID generated from the previous INSERT operation.
 *
 * @param string $source Name of the database table
 * @param string $field Name of the ID database field. Defaults to "id"
 * @return integer
 */
	function lastInsertId($source, $field = 'id') {
		$seq = $this->getSequence($source, $field);
		$data = $this->fetchRow("SELECT currval('{$seq}') as max");
		return $data[0]['max'];
	}
/**
 * Gets the associated sequence for the given table/field
 *
 * @param mixed $table Either a full table name (with prefix) as a string, or a model object
 * @param string $field Name of the ID database field. Defaults to "id"
 * @return string The associated sequence name from the sequence map, defaults to "{$table}_{$field}_seq"
 */
	function getSequence($table, $field = 'id') {
		if (is_object($table)) {
			$table = $this->fullTableName($table, false);
		}
		if (isset($this->_sequenceMap[$table]) && isset($this->_sequenceMap[$table][$field])) {
			return $this->_sequenceMap[$table][$field];
		} else {
			return "{$table}_{$field}_seq";
		}
	}
/**
 * Deletes all the records in a table and drops all associated auto-increment sequences
 *
 * @param mixed $table A string or model class representing the table to be truncated
 * @param integer $reset If -1, sequences are dropped, if 0 (default), sequences are reset,
 *						and if 1, sequences are not modified
 * @return boolean	SQL TRUNCATE TABLE statement, false if not applicable.
 * @access public
 */
	function truncate($table, $reset = 0) {
		if (parent::truncate($table)) {
			$table = $this->fullTableName($table, false);
			if (isset($this->_sequenceMap[$table]) && $reset !== 1) {
				foreach ($this->_sequenceMap[$table] as $field => $sequence) {
					if ($reset === 0) {
						$this->execute("ALTER SEQUENCE \"{$sequence}\" RESTART WITH 1");
					} elseif ($reset === -1) {
						$this->execute("DROP SEQUENCE IF EXISTS \"{$sequence}\"");
					}
				}
			}
			return true;
		}
		return false;
	}
/**
 * Prepares field names to be quoted by parent
 *
 * @param string $data
 * @return string SQL field
 */
	function name($data) {
		return parent::name(str_replace('"__"', '__', $data));
	}
/**
 * Generates the fields list of an SQL query.
 *
 * @param Model $model
 * @param string $alias Alias tablename
 * @param mixed $fields
 * @return array
 */
	function fields(&$model, $alias = null, $fields = array(), $quote = true) {
		if (empty($alias)) {
			$alias = $model->alias;
		}
		$fields = parent::fields($model, $alias, $fields, false);

		if (!$quote) {
			return $fields;
		}
		$count = count($fields);

		if ($count >= 1 && $fields[0] != '*' && strpos($fields[0], 'COUNT(*)') === false) {
			for ($i = 0; $i < $count; $i++) {
				if (!preg_match('/^.+\\(.*\\)/', $fields[$i]) && !preg_match('/\s+AS\s+/', $fields[$i])) {
					$prepend = '';
					if (strpos($fields[$i], 'DISTINCT') !== false) {
						$prepend = 'DISTINCT ';
						$fields[$i] = trim(str_replace('DISTINCT', '', $fields[$i]));
					}

					if (strrpos($fields[$i], '.') === false) {
						$fields[$i] = $prepend . $this->name($alias) . '.' . $this->name($fields[$i]) . ' AS ' . $this->name($alias . '__' . $fields[$i]);
					} else {
						$build = explode('.', $fields[$i]);
						$fields[$i] = $prepend . $this->name($build[0]) . '.' . $this->name($build[1]) . ' AS ' . $this->name($build[0] . '__' . $build[1]);
					}
				}
			}
		}
		return $fields;
	}
/**
 * Returns a limit statement in the correct format for the particular database.
 *
 * @param integer $limit Limit of results returned
 * @param integer $offset Offset from which to start results
 * @return string SQL limit/offset statement
 */
	function limit($limit, $offset = null) {
		if ($limit) {
			$rt = '';
			if (!strpos(strtolower($limit), 'limit') || strpos(strtolower($limit), 'limit') === 0) {
				$rt = ' LIMIT';
			}

			$rt .= ' ' . $limit;
			if ($offset) {
				$rt .= ' OFFSET ' . $offset;
			}

			return $rt;
		}
		return null;
	}
/**
 * Converts database-layer column types to basic types
 *
 * @param string $real Real database-layer column type (i.e. "varchar(255)")
 * @return string Abstract column type (i.e. "string")
 */
	function column($real) {
		if (is_array($real)) {
			$col = $real['name'];
			if (isset($real['limit'])) {
				$col .= '(' . $real['limit'] . ')';
			}
			return $col;
		}

		$col = str_replace(')', '', $real);
		$limit = null;
		if (strpos($col, '(') !== false) {
			list($col, $limit) = explode('(', $col);
		}

		if (in_array($col, array('date', 'time'))) {
			return $col;
		}
		if (strpos($col, 'timestamp') !== false) {
			return 'datetime';
		}
		if (strpos($col, 'time') === 0) {
			return 'time';
		}
		if ($col == 'inet') {
			return('inet');
		}
		if ($col == 'boolean') {
			return 'boolean';
		}
		if (strpos($col, 'int') !== false && $col != 'interval') {
			return 'integer';
		}
		if (strpos($col, 'char') !== false || $col == 'uuid') {
			return 'string';
		}
		if (strpos($col, 'text') !== false) {
			return 'text';
		}
		if (strpos($col, 'bytea') !== false) {
			return 'binary';
		}
		if (in_array($col, array('float', 'float4', 'float8', 'double', 'double precision', 'decimal', 'real', 'numeric'))) {
			return 'float';
		}
		return 'text';
	}
/**
 * Gets the length of a database-native column description, or null if no length
 *
 * @param string $real Real database-layer column type (i.e. "varchar(255)")
 * @return int An integer representing the length of the column
 */
	function length($real) {
		$col = str_replace(array(')', 'unsigned'), '', $real);
		$limit = null;

		if (strpos($col, '(') !== false) {
			list($col, $limit) = explode('(', $col);
		}
		if ($col == 'uuid') {
			return 36;
		}
		if ($limit != null) {
			return intval($limit);
		}
		return null;
	}
/**
 * Enter description here...
 *
 * @param unknown_type $results
 */
	function resultSet(&$results) {
		$this->results =& $results;
		$this->map = array();
		$num_fields = pg_num_fields($results);
		$index = 0;
		$j = 0;

		while ($j < $num_fields) {
			$columnName = pg_field_name($results, $j);

			if (strpos($columnName, '__')) {
				$parts = explode('__', $columnName);
				$this->map[$index++] = array($parts[0], $parts[1]);
			} else {
				$this->map[$index++] = array(0, $columnName);
			}
			$j++;
		}
	}
/**
 * Fetches the next row from the current result set
 *
 * @return unknown
 */
	function fetchResult() {
		if ($row = pg_fetch_row($this->results)) {
			$resultRow = array();

			foreach ($row as $index => $field) {
				list($table, $column) = $this->map[$index];
				$type = pg_field_type($this->results, $index);

				switch ($type) {
					case 'bool':
						$resultRow[$table][$column] = $this->boolean($row[$index], false);	
					break;
					case 'binary':
					case 'bytea':
						$resultRow[$table][$column] = pg_unescape_bytea($row[$index]);
					break;
					default:
						$resultRow[$table][$column] = $row[$index];
					break;
				}
			}
			return $resultRow;
		} else {
			return false;
		}
	}
/**
 * Translates between PHP boolean values and PostgreSQL boolean values
 *
 * @param mixed $data Value to be translated
 * @param boolean $quote	True to quote value, false otherwise
 * @return mixed Converted boolean value
 */
	function boolean($data, $quote = true) {
		switch (true) {
			case ($data === true || $data === false):
				return $data;
			case ($data === 't' || $data === 'f'):
				return ($data === 't');
			default:
				return (bool)$data;
			break;
		}
	}
/**
 * Sets the database encoding
 *
 * @param mixed $enc Database encoding
 * @return boolean True on success, false on failure
 */
	function setEncoding($enc) {
		return pg_set_client_encoding($this->connection, $enc) == 0;
	}
/**
 * Gets the database encoding
 *
 * @return string The database encoding
 */
	function getEncoding() {
		return pg_client_encoding($this->connection);
	}
/**
 * Generate a Postgres-native column schema string
 *
 * @param array $column An array structured like the following: array('name'=>'value', 'type'=>'value'[, options]),
 *                      where options can be 'default', 'length', or 'key'.
 * @return string
 */
	function buildColumn($column) {
		$col = $this->columns[$column['type']];
		if (!isset($col['length']) && !isset($col['limit'])) {
			unset($column['length']);
		}
		$out = preg_replace('/integer\([0-9]+\)/', 'integer', parent::buildColumn($column));
		$out = str_replace('integer serial', 'serial', $out);

		if (strpos($out, 'DEFAULT DEFAULT')) {
			if (isset($column['null']) && $column['null']) {
				$out = str_replace('DEFAULT DEFAULT', 'DEFAULT NULL', $out);
			} elseif (in_array($column['type'], array('integer', 'float'))) {
				$out = str_replace('DEFAULT DEFAULT', 'DEFAULT 0', $out);
			} elseif ($column['type'] == 'boolean') {
				$out = str_replace('DEFAULT DEFAULT', 'DEFAULT FALSE', $out);
			}
		}
		return $out;
	}
/**
 * Format indexes for create table
 *
 * @param array $indexes
 * @param string $table
 * @return string
 */
	function buildIndex($indexes, $table = null) {
		$join = array();

		foreach ($indexes as $name => $value) {
			if ($name == 'PRIMARY') {
				$out = 'PRIMARY KEY  (' . $this->name($value['column']) . ')';
			} else {
				$out = 'CREATE ';
				if (!empty($value['unique'])) {
					$out .= 'UNIQUE ';
				}
				if (is_array($value['column'])) {
					$value['column'] = join(', ', array_map(array(&$this, 'name'), $value['column']));
				} else {
					$value['column'] = $this->name($value['column']);
				}
				$out .= "INDEX {$name} ON {$table}({$value['column']});";
			}
			$join[] = $out;
		}
		return $join;
	}
/**
 * Overrides DboSource::renderStatement to handle schema generation with Postgres-style indexes
 *
 * @param string $type
 * @param array $data
 * @return string
 */
	function renderStatement($type, $data) {
		switch (strtolower($type)) {
			case 'schema':
				extract($data);

				foreach ($indexes as $i => $index) {
					if (preg_match('/PRIMARY KEY/', $index)) {
						unset($indexes[$i]);
						$columns[] = $index;
						break;
					}
				}

				foreach (array('columns', 'indexes') as $var) {
					if (is_array(${$var})) {
						${$var} = "\t" . join(",\n\t", array_filter(${$var}));
					}
				}
				return "CREATE TABLE {$table} (\n{$columns});\n{$indexes}";
			break;
			default:
				return parent::renderStatement($type, $data);
			break;
		}
	}
}

?>