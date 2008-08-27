<?php
/* SVN FILE: $Id: debugger.php 7387 2008-07-30 20:34:01Z TommyO $ */
/**
 * Framework debugging and PHP error-handling class
 *
 * Provides enhanced logging, stack traces, and rendering debug views
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
 * @subpackage		cake.cake.libs
 * @since			CakePHP(tm) v 1.2.4560
 * @version			$Revision: 7387 $
 * @modifiedby		$LastChangedBy: TommyO $
 * @lastmodified	$Date: 2008-07-30 22:34:01 +0200 (Mi, 30 Jul 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Included libraries.
 *
 */
	if (!class_exists('Object')) {
		uses('object');
	}
	if (!class_exists('CakeLog')) {
		uses('cake_log');
	}
/**
 * Provide custom logging and error handling.
 *
 * Debugger overrides PHP's default error handling to provide stack traces and enhanced logging
 *
 * @package		cake
 * @subpackage	cake.cake.libs
 */
class Debugger extends Object {

/**
 * Holds a reference to errors generated by the application
 *
 * @var array
 * @access public
 */
	var $errors = array();
/**
 * Contains the base URL for error code documentation
 *
 * @var string
 * @access public
 */
	var $helpPath = null;
/**
 * holds current output format
 *
 * @var string
 * @access private
 */
	var $__outputFormat = 'js';
/**
 * holds current output data when outputFormat is false
 *
 * @var string
 * @access private
 */
	var $__data = array();
/**
 * Constructor
 *
 */
	function __construct() {
		$docRef = ini_get('docref_root');
		if (empty($docRef)) {
			ini_set('docref_root', 'http://php.net/');
		}
		if (!defined('E_RECOVERABLE_ERROR')) {
			define('E_RECOVERABLE_ERROR', 4096);
		}
	}
/**
 * Gets a reference to the Debugger object instance
 *
 * @return object
 * @access public
 */
	function &getInstance() {
		static $instance = array();

		if (!isset($instance[0]) || !$instance[0]) {
			$instance[0] =& new Debugger();
			if (Configure::read() > 0) {
				Configure::version(); // Make sure the core config is loaded
				$instance[0]->helpPath = Configure::read('Cake.Debugger.HelpPath');
			}
		}
		return $instance[0];
	}
/**
 * formats and outputs the passed var
*/
	function dump($var) {
		$_this = Debugger::getInstance();
		pr($_this->exportVar($var));
	}
/**
 *  neatly logs a given var
*/
	function log($var, $level = LOG_DEBUG) {
		$_this = Debugger::getInstance();
		$trace = $_this->trace(array('start' => 1, 'depth' => 2, 'format' => 'array'));
		$source = null;

		if (is_object($trace[0]['object']) && isset($trace[0]['object']->_reporter->_test_stack)) {
			$stack = $trace[0]['object']->_reporter->_test_stack;
			$source = "[". $stack[0].", ". $stack[2] ."::" . $stack[3] ."()]\n";
		}

		CakeLog::write($level, $source . $_this->exportVar($var));
	}

/**
 * Overrides PHP's default error handling
 *
 * @param integer $code Code of error
 * @param string $description Error description
 * @param string $file File on which error occurred
 * @param integer $line Line that triggered the error
 * @param array $context Context
 * @return boolean true if error was handled
 * @access public
 */
	function handleError($code, $description, $file = null, $line = null, $context = null) {
		if (error_reporting() == 0 || $code === 2048) {
			return;
		}

		$_this = Debugger::getInstance();

		if (empty($file)) {
			$file = '[internal]';
		}
		if (empty($line)) {
			$line = '??';
		}
		$file = $_this->trimPath($file);

		$info = compact('code', 'description', 'file', 'line');
		if (!in_array($info, $_this->errors)) {
			$_this->errors[] = $info;
		} else {
			return;
		}

		$level = LOG_DEBUG;
		switch ($code) {
			case E_PARSE:
			case E_ERROR:
			case E_CORE_ERROR:
			case E_COMPILE_ERROR:
			case E_USER_ERROR:
				$error = 'Fatal Error';
				$level = LOG_ERROR;
			break;
			case E_WARNING:
			case E_USER_WARNING:
			case E_COMPILE_WARNING:
			case E_RECOVERABLE_ERROR:
				$error = 'Warning';
				$level = LOG_WARNING;
			break;
			case E_NOTICE:
			case E_USER_NOTICE:
				$error = 'Notice';
				$level = LOG_NOTICE;
			break;
			default:
				return false;
			break;
		}

		$helpCode = null;
		if (!empty($_this->helpPath) && preg_match('/.*\[([0-9]+)\]$/', $description, $codes)) {
			if (isset($codes[1])) {
				$helpCode = $codes[1];
				$description = trim(preg_replace('/\[[0-9]+\]$/', '', $description));
			}
		}

		echo $_this->__output($level, $error, $code, $helpCode, $description, $file, $line, $context);

		if (Configure::read('log')) {
			CakeLog::write($level, "{$error} ({$code}): {$description} in [{$file}, line {$line}]");
		}

		if ($error == 'Fatal Error') {
			die();
		}
		return true;
	}
/**
 * Outputs a stack trace with the given options
 *
 * @param array $options Format for outputting stack trace
 * @return string Formatted stack trace
 * @access public
 */
	function trace($options = array()) {
		$options = array_merge(array(
				'depth'		=> 999,
				'format'	=> '',
				'args'		=> false,
				'start'		=> 0,
				'scope'		=> null,
				'exclude'	=> null
			),
			$options
		);

		$backtrace = debug_backtrace();
		$back = array();

		for ($i = $options['start']; $i < count($backtrace) && $i < $options['depth']; $i++) {
			$trace = array_merge(
				array(
					'file' => '[internal]',
					'line' => '??'
				),
				$backtrace[$i]
			);

			if (isset($backtrace[$i + 1])) {
				$next = array_merge(
					array(
						'line'		=> '??',
						'file'		=> '[internal]',
						'class'		=> null,
						'function'	=> '[main]'
					),
					$backtrace[$i + 1]
				);
				$function = $next['function'];

				if (!empty($next['class'])) {
					$function = $next['class'] . '::' . $function . '(';
					if ($options['args'] && isset($next['args'])) {
						$args = array();
						foreach ($next['args'] as $arg) {
							$args[] = Debugger::exportVar($arg);
						}
						$function .= join(', ', $args);
					}
					$function .= ')';
				}
			} else {
				$function = '[main]';
			}
			if (in_array($function, array('call_user_func_array', 'trigger_error'))) {
				continue;
			}
			if ($options['format'] == 'points' && $trace['file'] != '[internal]') {
				$back[] = array('file' => $trace['file'], 'line' => $trace['line']);
			} elseif (empty($options['format'])) {
				$back[] = $function . ' - ' . Debugger::trimPath($trace['file']) . ', line ' . $trace['line'];
			} else {
				$back[] = $trace;
			}
		}

		if ($options['format'] == 'array' || $options['format'] == 'points') {
			return $back;
		}
		return join("\n", $back);
	}
/**
 * Shortens file paths by replacing the application base path with 'APP', and the CakePHP core
 * path with 'CORE'
 *
 * @param string $path Path to shorten
 * @return string Normalized path
 * @access public
 */
	function trimPath($path) {
		if (!defined('CAKE_CORE_INCLUDE_PATH') || !defined('APP')) {
			return $path;
		}

		if (strpos($path, APP) === 0) {
			return str_replace(APP, 'APP' . DS, $path);
		} elseif (strpos($path, CAKE_CORE_INCLUDE_PATH) === 0) {
			return str_replace(CAKE_CORE_INCLUDE_PATH, 'CORE', $path);
		} elseif (strpos($path, ROOT) === 0) {
			return str_replace(ROOT, 'ROOT', $path);
		}
		$corePaths = Configure::corePaths('cake');
		foreach ($corePaths as $corePath) {
			if (strpos($path, $corePath) === 0) {
				return str_replace($corePath, 'CORE' .DS . 'cake' .DS, $path);
			}
		}
		return $path;
	}
/**
 * Grabs an excerpt from a file and highlights a given line of code
 *
 * @param string $file Absolute path to a PHP file
 * @param integer $line Line number to highlight
 * @param integer $context Number of lines of context to extract above and below $line
 * @return array Set of lines highlighted
 * @access public
 */
	function excerpt($file, $line, $context = 2) {
		$data = $lines = array();
		$data = @explode("\n", file_get_contents($file));

		if (empty($data) || !isset($data[$line])) {
			return;
		}
		for ($i = $line - ($context + 1); $i < $line + $context; $i++) {
			if (!isset($data[$i])) {
				continue;
			}
			$string = str_replace(array("\r\n", "\n"), "", highlight_string($data[$i], true));
			if ($i == $line) {
				$lines[] = '<span class="code-highlight">' . $string . '</span>';
			} else {
				$lines[] = $string;
			}
		}
		return $lines;
	}
/**
 * Converts a variable to a string for debug output
 *
 * @param string $var Variable to convert
 * @return string Variable as a formatted string
 * @access public
 */
	function exportVar($var, $recursion = 0) {
		$_this =  Debugger::getInstance();
		switch(strtolower(gettype($var))) {
			case 'boolean':
				return ($var) ? 'true' : 'false';
			break;
			case 'integer':
			case 'double':
				return $var;
			break;
			case 'string':
				if (trim($var) == "") {
					return '""';
				}
				return '"' . h($var) . '"';
			break;
			case 'object':
				return get_class($var) . "\n" . $_this->__object($var);
			case 'array':
				$out = "array(";
				$vars = array();
				foreach ($var as $key => $val) {
					if ($recursion >= 0) {
						if (is_numeric($key)) {
							$vars[] = "\n\t" . $_this->exportVar($val, $recursion - 1);
						} else {
							$vars[] = "\n\t" .$_this->exportVar($key, $recursion - 1)
										. ' => ' . $_this->exportVar($val, $recursion - 1);
						}
					}
				}
				$n = null;
				if (count($vars) > 0) {
					$n = "\n";
				}
				return $out . join(",", $vars) . "{$n})";
			break;
			case 'resource':
				return strtolower(gettype($var));
			break;
			case 'null':
				return 'null';
			break;
		}
	}
/**
 * Handles object conversion to debug string
 *
 * @param string $var Object to convert
 * @access private
 */
	function __object($var) {
		$out = array();

		if(is_object($var)) {
			$className = get_class($var);
			$objectVars = get_object_vars($var);

			foreach($objectVars as $key => $value) {
				if(is_object($value)) {
					$value = get_class($value) . ' object';
				} elseif (is_array($value)) {
					$value = 'array';
				} elseif ($value === null) {
					$value = 'NULL';
				} elseif (in_array(gettype($value), array('boolean', 'integer', 'double', 'string', 'array', 'resource'))) {
					$value = Debugger::exportVar($value);
				}
				$out[] = "$className::$$key = " . $value;
			}
		}
		return join("\n", $out);
	}
/**
 * Handles object conversion to debug string
 *
 * @param string $var Object to convert
 * @access protected
 */
	function output($format = 'js') {
		$_this = Debugger::getInstance();
		$data = null;

		if ($format === true && !empty($_this->__data)) {
			$data = $_this->__data;
			$_this->__data = array();
			$format = false;
		}
		$_this->__outputFormat = $format;

		return $data;
	}
/**
 * Handles object conversion to debug string
 *
 * @param string $var Object to convert
 * @access private
 */
	function __output($level, $error, $code, $helpCode, $description, $file, $line, $kontext) {
		$_this = Debugger::getInstance();

		$files = $_this->trace(array('start' => 2, 'format' => 'points'));
		$listing = $_this->excerpt($files[0]['file'], $files[0]['line'] - 1, 1);
		$trace = $_this->trace(array('start' => 2, 'depth' => '20'));
		$context = array();

		foreach ((array)$kontext as $var => $value) {
			$context[] = "\${$var}\t=\t" . $_this->exportVar($value, 1);
		}

		switch ($_this->__outputFormat) {
			default:
			case 'js':
				$link = "document.getElementById(\"CakeStackTrace" . count($_this->errors) . "\").style.display = (document.getElementById(\"CakeStackTrace" . count($_this->errors) . "\").style.display == \"none\" ? \"\" : \"none\")";
				$out = "<a href='javascript:void(0);' onclick='{$link}'><b>{$error}</b> ({$code})</a>: {$description} [<b>{$file}</b>, line <b>{$line}</b>]";
				if (Configure::read() > 0) {
					debug($out, false, false);
					e('<div id="CakeStackTrace' . count($_this->errors) . '" class="cake-stack-trace" style="display: none;">');
						$link = "document.getElementById(\"CakeErrorCode" . count($_this->errors) . "\").style.display = (document.getElementById(\"CakeErrorCode" . count($_this->errors) . "\").style.display == \"none\" ? \"\" : \"none\")";
						e("<a href='javascript:void(0);' onclick='{$link}'>Code</a>");

						if (!empty($context)) {
							$link = "document.getElementById(\"CakeErrorContext" . count($_this->errors) . "\").style.display = (document.getElementById(\"CakeErrorContext" . count($_this->errors) . "\").style.display == \"none\" ? \"\" : \"none\")";
							e(" | <a href='javascript:void(0);' onclick='{$link}'>Context</a>");

							if (!empty($helpCode)) {
								e(" | <a href='{$_this->helpPath}{$helpCode}' target='_blank'>Help</a>");
							}

							e("<pre id=\"CakeErrorContext" . count($_this->errors) . "\" class=\"cake-context\" style=\"display: none;\">");
							e(implode("\n", $context));
							e("</pre>");
						}

						if (!empty($listing)) {
							e("<div id=\"CakeErrorCode" . count($_this->errors) . "\" class=\"cake-code-dump\" style=\"display: none;\">");
								pr(implode("\n", $listing) . "\n", false);
							e('</div>');
						}
						pr($trace, false);
					e('</div>');
				}
			break;
			case 'html':
				echo "<pre class=\"cake-debug\"><b>{$error}</b> ({$code}) : {$description} [<b>{$file}</b>, line <b>{$line}]</b></pre>";
				if (!empty($context)) {
					echo "Context:\n" .implode("\n", $context) . "\n";
				}
				echo "<pre class=\"cake-debug context\"><b>Context</b> <p>" . implode("\n", $context) . "</p></pre>";
				echo "<pre class=\"cake-debug trace\"><b>Trace</b> <p>" . $trace. "</p></pre>";
			break;
			case 'text':
			case 'txt':
				echo "{$error}: {$code} :: {$description} on line {$line} of {$file}\n";
				if (!empty($context)) {
					echo "Context:\n" .implode("\n", $context) . "\n";
				}
				echo "Trace:\n" . $trace;
			break;
			case 'log':
				$_this->log(compact('error', 'code', 'description', 'line', 'file', 'context', 'trace'));
			break;
			case false:
				$this->__data[] = compact('error', 'code', 'description', 'line', 'file', 'context', 'trace');
			break;
		}
	}
/**
 * Verify that the application's salt has been changed from the default value
 *
 * @access public
 */
	function checkSessionKey() {
		if (Configure::read('Security.salt') == 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi') {
			trigger_error(__('Please change the value of \'Security.salt\' in app/config/core.php to a salt value specific to your application', true), E_USER_NOTICE);
		}
	}
/**
 * Invokes the given debugger object as the current error handler, taking over control from the previous handler
 * in a stack-like hierarchy.
 *
 * @param object $debugger A reference to the Debugger object
 * @access public
 */
	function invoke(&$debugger) {
		set_error_handler(array(&$debugger, 'handleError'));
	}
}

if (!defined('DISABLE_DEFAULT_ERROR_HANDLING')) {
	Debugger::invoke(Debugger::getInstance());
}

?>