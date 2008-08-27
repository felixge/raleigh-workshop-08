<?php
/* SVN FILE: $Id: test.php 7116 2008-06-04 19:04:58Z gwoo $ */
/**
 * The TestTask handles creating and updating test files.
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
/**
 * Task class for creating and updating test files.
 *
 * @package		cake
 * @subpackage	cake.cake.console.libs.tasks
 */
class TestTask extends Shell {
/**
 * Name of plugin
 *
 * @var string
 * @access public
 */		
	var $plugin = null;
/**
 * path to TESTS directory
 *
 * @var string
 * @access public
 */
	var $path = TESTS;
/**
 * Execution method always used for tasks
 *
 * @access public
 */
	function execute() {
		if (empty($this->args)) {
			$this->__interactive();
		}
		
		if (count($this->args) == 1) {
			$this->__interactive($this->args[0]);
		}

		if (count($this->args) > 1) {
			$class = Inflector::underscore($this->args[0]);
			if ($this->bake($class, $this->args[1])) {
				$this->out('done');
			}
		}
	}
/**
 * Handles interactive baking
 *
 * @access private
 */
	function __interactive($class = null) {
		$this->hr();
		$this->out(sprintf("Bake Tests\nPath: %s", $this->path));
		$this->hr();
		
		$key = null;	
		$options = array('Behavior', 'Helper', 'Component', 'Model', 'Controller');
		
		if ($class !== null) {
			$class = Inflector::camelize($class);
			if (in_array($class, $options)) {
				$key = array_search($class);
			}
		}
		
		while ($class == null) {
			
				$this->hr();
				$this->out("Select a class:");
				$this->hr();
				
				$keys = array();
				foreach ($options as $key => $option) {
					$this->out(++$key . '. ' . $option);
					$keys[] = $key;
				}
				$keys[] = 'q';
				
				$key = $this->in(__("Enter the class to test or (q)uit", true), $keys, 'q');
				
			if ($key != 'q') {
				if (isset($options[--$key])) {
					$class = $options[$key];
				}
				
				if ($class) {
					$this->path .= 'cases' . DS . Inflector::tableize($class) . DS;
			
					$name = $this->in(__("Enter the name for the test or (q)uit", true), null, 'q');
					if ($name !== 'q') {
						$case = null;
						while ($case !== 'q') {
							$case = $this->in(__("Enter a test case or (q)uit", true), null, 'q');
							if ($case !== 'q') {
								$cases[] = $case;
							}
						}
						if ($this->bake($class, $name, $cases)) {
							$this->out(__("Test baked\n", true));
							$type = null;
						}
						$class = null;
					}
				}
			} else {
				$this->_stop();
			}
		}
	}
/**
 * Writes File
 *
 * @access public
 */	
	function bake($class, $name = null, $cases = array()) {
		if (!$name) {
			return false;
		}
		
		if (!is_array($cases)) {
			$cases = array($cases);
		}
				
		$name = Inflector::camelize($name);
		$import = $name;
		if (isset($this->plugin)) {
			$import = $this->plugin . '.' . $name;
		}
		$extras = $this->__extras($class);
		$out = "App::import('$class', '$import');\n";
		if ($class == 'Model') {
			$class = null;
		}
		$out .= "class Test{$name} extends {$name}{$class} {\n";
		$out .= "{$extras}";
		$out .= "}\n\n";
		$out .= "class {$name}{$class}Test extends CakeTestCase {\n";
		$out .= "\n\tfunction start() {\n\t\tparent::start();\n\t\t\$this->{$name} = new Test{$name}();\n\t}\n";
		$out .= "\n\tfunction test{$name}Instance() {\n";
		$out .= "\t\t\$this->assertTrue(is_a(\$this->{$name}, '{$name}{$class}'));\n\t}\n";
		foreach ($cases as $case) {
			$case = Inflector::classify($case);
			$out .= "\n\tfunction test{$case}() {\n\n\t}\n";
		}
		$out .= "}\n";
		
		$this->out("Baking unit test for $name...");
		$this->out($out);
		$ok = $this->in(__('Is this correct?'), array('y', 'n'), 'y');
		if ($ok == 'n') {
			return false;
		}

		$header = '$Id';
		$content = "<?php \n/* SVN FILE: $header$ */\n/* ". $name ." Test cases generated on: " . date('Y-m-d H:m:s') . " : ". time() . "*/\n{$out}?>";
		return $this->createFile($this->path . Inflector::underscore($name) . '.test.php', $content);
	}
/**
 * Handles the extra stuff needed 
 *
 * @access private
 */	
	function __extras($class) {
		$extras = null;
		switch ($class) {
			case 'Model':
				$extras = "\n\tvar \$cacheSources = false;\n";
			break;
		}
		return $extras;
	}
}
?>