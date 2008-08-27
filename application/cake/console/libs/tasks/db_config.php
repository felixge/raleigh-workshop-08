<?php
/* SVN FILE: $Id: db_config.php 7116 2008-06-04 19:04:58Z gwoo $ */
/**
 * The DbConfig Task handles creating and updating the database.php
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
 * @subpackage		cake.cake.console.libs.tasks
 * @since			CakePHP(tm) v 1.2
 * @version			$Revision: 7116 $
 * @modifiedby		$LastChangedBy: gwoo $
 * @lastmodified	$Date: 2008-06-04 21:04:58 +0200 (Mi, 04 Jun 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
if (!class_exists('File')) {
	uses('file');
}
/**
 * Task class for creating and updating the database configuration file.
 *
 * @package		cake
 * @subpackage	cake.cake.console.libs.tasks
 */
class DbConfigTask extends Shell {
/**
 * path to CONFIG directory
 *
 * @var string
 * @access public
 */
	var $path = null;
/**
 * Default configuration settings to use
 *
 * @var array
 * @access private
 */
	var $__defaultConfig = array(
		'name' => 'default', 'driver'=> 'mysql', 'persistent'=> 'false', 'host'=> 'localhost',
		'login'=> 'root', 'password'=> 'password', 'database'=> 'project_name',
		'schema'=> null, 'prefix'=> null, 'encoding' => null, 'port' => null
	);
/**
 * initialization callback
 *
 * @var string
 * @access public
 */
	function initialize() {
		$this->path = $this->params['working'] . DS . 'config' . DS;
	}
/**
 * Execution method always used for tasks
 *
 * @access public
 */
	function execute() {
		if (empty($this->args)) {
			$this->__interactive();
			$this->_stop();
		}
	}
/**
 * Interactive interface
 *
 * @access private
 */
	function __interactive() {
		$this->hr();
		$this->out('Database Configuration:');
		$this->hr();
		$done = false;
		$dbConfigs = array();

		while ($done == false) {
			$name = '';

			while ($name == '') {
				$name = $this->in("Name:", null, 'default');
				if (preg_match('/[^a-z0-9_]/i', $name)) {
					$name = '';
					$this->out('The name may only contain unaccented latin characters, numbers or underscores');
				}
				else if (preg_match('/^[^a-z_]/i', $name)) {
					$name = '';
					$this->out('The name must start with an unaccented latin character or an underscore');
				}
			}
			$driver = '';

			while ($driver == '') {
				$driver = $this->in('Driver:', array('db2', 'firebird', 'mssql', 'mysql', 'mysqli', 'odbc', 'oracle', 'postgres', 'sqlite', 'sybase'), 'mysql');
			}
			$persistent = '';

			while ($persistent == '') {
				$persistent = $this->in('Persistent Connection?', array('y', 'n'), 'n');
			}

			if (low($persistent) == 'n') {
				$persistent = 'false';
			} else {
				$persistent = 'true';
			}
			$host = '';

			while ($host == '') {
				$host = $this->in('Database Host:', null, 'localhost');
			}
			$port = '';

			while ($port == '') {
				$port = $this->in('Port?', null, 'n');
			}

			if (low($port) == 'n') {
				$port = null;
			}
			$login = '';

			while ($login == '') {
				$login = $this->in('User:', null, 'root');
			}
			$password = '';
			$blankPassword = false;

			while ($password == '' && $blankPassword == false) {
				$password = $this->in('Password:');

				if ($password == '') {
					$blank = $this->in('The password you supplied was empty. Use an empty password?', array('y', 'n'), 'n');
					if ($blank == 'y')
					{
						$blankPassword = true;
					}
				}
			}
			$database = '';

			while ($database == '') {
				$database = $this->in('Database Name:', null, 'cake');
			}
			$prefix = '';

			while ($prefix == '') {
				$prefix = $this->in('Table Prefix?', null, 'n');
			}

			if (low($prefix) == 'n') {
				$prefix = null;
			}
			$encoding = '';

			while ($encoding == '') {
				$encoding = $this->in('Table encoding?', null, 'n');
			}

			if (low($encoding) == 'n') {
				$encoding = null;
			}
			$schema = '';

			if ($driver == 'postgres') {
				while ($schema == '') {
					$schema = $this->in('Table schema?', null, 'n');
				}
			}

			if (low($schema) == 'n') {
				$schema = null;
			}

			$config = compact('name', 'driver', 'persistent', 'host', 'login', 'password', 'database', 'prefix', 'encoding', 'port', 'schema');

			while ($this->__verify($config) == false) {
				$this->__interactive();
			}
			$dbConfigs[] = $config;
			$doneYet = $this->in('Do you wish to add another database configuration?', null, 'n');

			if (low($doneYet == 'n')) {
				$done = true;
			}
		}

		$this->bake($dbConfigs);
		config('database');
		return true;
	}
/**
 * Output verification message and bake if it looks good
 *
 * @return boolean True if user says it looks good, false otherwise
 * @access private
 */
	function __verify($config) {
		$config = array_merge($this->__defaultConfig, $config);
		extract($config);
		$this->out('');
		$this->hr();
		$this->out('The following database configuration will be created:');
		$this->hr();
		$this->out("Name:         $name");
		$this->out("Driver:       $driver");
		$this->out("Persistent:   $persistent");
		$this->out("Host:         $host");

		if ($port) {
			$this->out("Port:         $port");
		}

		$this->out("User:         $login");
		$this->out("Pass:         " . str_repeat('*', strlen($password)));
		$this->out("Database:     $database");

		if ($prefix) {
			$this->out("Table prefix: $prefix");
		}

		if ($schema) {
			$this->out("Schema:       $schema");
		}

		if ($encoding) {
			$this->out("Encoding:     $encoding");
		}

		$this->hr();
		$looksGood = $this->in('Look okay?', array('y', 'n'), 'y');

		if (low($looksGood) == 'y' || low($looksGood) == 'yes') {
			return $config;
		}
		return false;
	}
/**
 * Assembles and writes database.php
 *
 * @param array $configs Configuration settings to use
 * @return boolean Success
 * @access public
 */
	function bake($configs) {
		if (!is_dir($this->path)) {
			$this->err($this->path . ' not found');
			return false;
		}

		$filename = $this->path . 'database.php';
		$oldConfigs = array();

		if (file_exists($filename)) {
			$db = new DATABASE_CONFIG;
			$temp = get_class_vars(get_class($db));

			foreach ($temp as $configName => $info) {
				$info = array_merge($this->__defaultConfig, $info);

				if (!isset($info['schema'])) {
					$info['schema'] = null;
				}
				if (!isset($info['encoding'])) {
					$info['encoding'] = null;
				}
				if (!isset($info['port'])) {
					$info['port'] = null;
				}

				if($info['persistent'] === false) {
					$info['persistent'] = 'false';
				} else {
					$info['persistent'] = 'false';
				}

				$oldConfigs[] = array(
					'name' => $configName,
					'driver' => $info['driver'],
					'persistent' => $info['persistent'],
					'host' => $info['host'],
					'port' => $info['port'],
					'login' => $info['login'],
					'password' => $info['password'],
					'database' => $info['database'],
					'prefix' => $info['prefix'],
					'schema' => $info['schema'],
					'encoding' => $info['encoding']
				);
			}
		}

		foreach ($oldConfigs as $key => $oldConfig) {
			foreach ($configs as $key1 => $config) {
				if ($oldConfig['name'] == $config['name']) {
					unset($oldConfigs[$key]);
				}
			}
		}

		$configs = array_merge($oldConfigs, $configs);
		$out = "<?php\n";
		$out .= "class DATABASE_CONFIG {\n\n";

		foreach ($configs as $config) {
			$config = array_merge($this->__defaultConfig, $config);
			extract($config);

			$out .= "\tvar \${$name} = array(\n";
			$out .= "\t\t'driver' => '{$driver}',\n";
			$out .= "\t\t'persistent' => {$persistent},\n";
			$out .= "\t\t'host' => '{$host}',\n";

			if ($port) {
				$out .= "\t\t'port' => {$port},\n";
			}

			$out .= "\t\t'login' => '{$login}',\n";
			$out .= "\t\t'password' => '{$password}',\n";
			$out .= "\t\t'database' => '{$database}',\n";

			if ($schema) {
				$out .= "\t\t'schema' => '{$schema}',\n";
			}

			if ($prefix) {
				$out .= "\t\t'prefix' => '{$prefix}',\n";
			}

			if ($encoding) {
				$out .= "\t\t'encoding' => '{$encoding}'\n";
			}

			$out .= "\t);\n";
		}

		$out .= "}\n";
		$out .= "?>";
		$filename = $this->path.'database.php';
		return $this->createFile($filename, $out);
	}
}
?>