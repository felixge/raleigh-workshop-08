<?php
/* SVN FILE: $Id: code_coverage_manager.php 7378 2008-07-30 13:39:17Z DarkAngelBGE $ */
/**
 * A class to manage all aspects for Code Coverage Analysis
 *
 * This class
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
 * @subpackage		cake.cake.tests.lib
 * @since			CakePHP(tm) v 1.2.0.4433
 * @version			$Revision: 7378 $
 * @modifiedby		$LastChangedBy: DarkAngelBGE $
 * @lastmodified	$Date: 2008-07-30 15:39:17 +0200 (Mi, 30 Jul 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Core', 'Folder');
/**
 * Short description for class.
 *
 * @package    cake
 * @subpackage cake.cake.tests.lib
 */
class CodeCoverageManager {
/**
 * Is this an app test case?
 *
 * @var string
 */
	var $appTest = false;
/**
 * Is this an app test case?
 *
 * @var string
 */
	var $pluginTest = false;
/**
 * Is this a grouptest?
 *
 * @var string
 * @access public
 */
	var $groupTest = false;
/**
 * The test case file to analyze
 *
 * @var string
 */
	var $testCaseFile = '';
/**
 * The currently used CakeTestReporter
 *
 * @var string
 */
	var $reporter = '';
/**
 * undocumented variable
 *
 * @var string
 */
	var $numDiffContextLines = 7;
/**
 * Returns a singleton instance
 *
 * @return object
 * @access public
 */
	function &getInstance() {
		static $instance = array();
		if (!isset($instance[0]) || !$instance[0]) {
			$instance[0] =& new CodeCoverageManager();
		}
		return $instance[0];
	}
/**
 * Starts a new Coverage Analyzation for a given test case file
 * @TODO: Works with $_GET now within the function body, which will make it hard when we do code coverage reports for CLI
 *
 * @param string $testCaseFile
 * @param string $reporter
 * @return void
 */
	function start($testCaseFile, &$reporter) {
		$manager =& CodeCoverageManager::getInstance();
		$manager->reporter = $reporter;
		$testCaseFile = str_replace(DS . DS, DS, $testCaseFile);
		$thisFile = str_replace('.php', '.test.php', basename(__FILE__));

		if (strpos($testCaseFile, $thisFile) !== false) {
			trigger_error('Xdebug supports no parallel coverage analysis - so this is not possible.', E_USER_ERROR);
		}

		if (isset($_GET['app'])) {
			$manager->appTest = true;
		}

		if (isset($_GET['group'])) {
			$manager->groupTest = true;
		}

		if (isset($_GET['plugin'])) {
			$manager->pluginTest = Inflector::underscore($_GET['plugin']);
		}
		$manager->testCaseFile = $testCaseFile;
		xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);
	}
/**
 * Stops the current code coverage analyzation and dumps a nice report depending on the reporter that was passed to start()
 *
 * @return void
 */
	function report($output = true) {
		$manager =& CodeCoverageManager::getInstance();

		if (!$manager->groupTest) {
			$testObjectFile = $manager->__testObjectFileFromCaseFile($manager->testCaseFile, $manager->appTest);

			if (!file_exists($testObjectFile)) {
				trigger_error('This test object file is invalid: ' . $testObjectFile);
				return ;
			}
			$dump = xdebug_get_code_coverage();
			xdebug_stop_code_coverage();
			$coverageData = array();

			foreach ($dump as $file => $data) {
				if ($file == $testObjectFile) {
					$coverageData = $data;
					break;
				}
			}

			if (empty($coverageData) && $output) {
				echo 'The test object file is never loaded.';
			}
			$execCodeLines = $manager->__getExecutableLines(file_get_contents($testObjectFile));
			$result = '';

			switch (get_class($manager->reporter)) {
				case 'CakeHtmlReporter':
					$result = $manager->reportCaseHtmlDiff(@file($testObjectFile), $coverageData, $execCodeLines, $manager->numDiffContextLines);
					break;
				case 'CLIReporter':
					$result = $manager->reportCaseCli(@file($testObjectFile), $coverageData, $execCodeLines, $manager->numDiffContextLines);
					break;
				default:
					trigger_error('Currently only HTML and CLI reporting is supported for code coverage analysis.');
					break;
			}
		} else {
			$testObjectFiles = $manager->__testObjectFilesFromGroupFile($manager->testCaseFile, $manager->appTest);

			foreach ($testObjectFiles as $file) {
				if (!file_exists($file)) {
					trigger_error('This test object file is invalid: ' . $file);
					return ;
				}
			}
			$dump = xdebug_get_code_coverage();
			xdebug_stop_code_coverage();
			$coverageData = array();
			foreach ($dump as $file => $data) {
				if (in_array($file, $testObjectFiles)) {
					$coverageData[$file] = $data;
				}
			}

			if (empty($coverageData) && $output) {
				echo 'The test object files are never loaded.';
			}
			$execCodeLines = $manager->__getExecutableLines($testObjectFiles);
			$result = '';

			switch (get_class($manager->reporter)) {
				case 'CakeHtmlReporter':
					$result = $manager->reportGroupHtml($testObjectFiles, $coverageData, $execCodeLines, $manager->numDiffContextLines);
					break;
				case 'CLIReporter':
					$result = $manager->reportGroupCli($testObjectFiles, $coverageData, $execCodeLines, $manager->numDiffContextLines);
					break;
				default:
					trigger_error('Currently only HTML and CLI reporting is supported for code coverage analysis.');
					break;
			}
		}

		if ($output) {
			echo $result;
		}
	}
/**
 * Html reporting
 *
 * @param string $testObjectFile
 * @param string $coverageData
 * @param string $execCodeLines
 * @param string $output
 * @return void
 */
	function reportCaseHtml($testObjectFile, $coverageData, $execCodeLines) {
		$manager = CodeCoverageManager::getInstance();
		$lineCount = $coveredCount = 0;
		$report = '';

		foreach ($testObjectFile as $num => $line) {
			$num++;
			$foundByManualFinder = array_key_exists($num, $execCodeLines) && trim($execCodeLines[$num]) != '';
			$foundByXdebug = array_key_exists($num, $coverageData) && $coverageData[$num] !== -2;

			// xdebug does not find all executable lines (zend engine fault)
			if ($foundByManualFinder && $foundByXdebug) {
				$class = 'uncovered';
				$lineCount++;

				if ($coverageData[$num] > 0) {
					$class = 'covered';
					$coveredCount++;
				}
			} else {
				$class = 'ignored';
			}
			$report .= $manager->__paintCodeline($class, $num, $line);;
		}
		return $manager->__paintHeader($lineCount, $coveredCount, $report);
	}
/**
 * Diff reporting
 *
 * @param string $testObjectFile
 * @param string $coverageData
 * @param string $execCodeLines
 * @param string $output
 * @return void
 */
	function reportCaseHtmlDiff($testObjectFile, $coverageData, $execCodeLines, $numContextLines) {
		$manager = CodeCoverageManager::getInstance();
		$total = count($testObjectFile);
		$lines = array();

		for ($i = 1; $i < $total + 1; $i++) {
			$foundByManualFinder = array_key_exists($i, $execCodeLines) && trim($execCodeLines[$i]) != '';
			$foundByXdebug = array_key_exists($i, $coverageData);

			if (!$foundByManualFinder || !$foundByXdebug || $coverageData[$i] === -2) {
				if (array_key_exists($i, $lines)) {
					$lines[$i] = 'ignored ' . $lines[$i];
				} else {
					$lines[$i] = 'ignored';
				}
				continue;
			}

			if ($coverageData[$i] !== -1) {
				if (array_key_exists($i, $lines)) {
					$lines[$i] = 'covered ' . $lines[$i];
				} else {
					$lines[$i] = 'covered';
				}
				continue;
			}
			$lines[$i] = 'uncovered show';
			$foundEndBlockInContextSearch = false;

			for ($j = 1; $j <= $numContextLines; $j++) {
				$key = $i - $j;

				if ($key > 0 && array_key_exists($key, $lines)) {
					if (strpos($lines[$key], 'end') !== false) {
						$foundEndBlockInContextSearch = true;
						if ($j < $numContextLines) {
							$lines[$key] = str_replace('end', '', $lines[$key-1]);
						}
					}

					if (strpos($lines[$key], 'uncovered') === false) {
						if (strpos($lines[$key], 'covered') !== false) {
							$lines[$key] .= ' show';
						} else {
							$lines[$key] = 'ignored show';
						}
					}

					if ($j == $numContextLines) {
						$lineBeforeIsEndBlock = strpos($lines[$key-1], 'end') !== false;
						$lineBeforeIsShown = strpos($lines[$key-1], 'show') !== false;
						$lineBeforeIsUncovered = strpos($lines[$key-1], 'uncovered') !== false;

						if (!$foundEndBlockInContextSearch && !$lineBeforeIsUncovered && ($lineBeforeIsEndBlock)) {
							$lines[$key-1] = str_replace('end', '', $lines[$key-1]);
						}

						if (!$lineBeforeIsShown && !$lineBeforeIsUncovered) {
							$lines[$key] .= ' start';
						}
					}
				}
				$key = $i + $j;

				if ($key < $total) {
					$lines[$key] = 'show';

					if ($j == $numContextLines) {
						$lines[$key] .= ' end';
					}
				}
			}
		}
		// find the last "uncovered" or "show"n line and "end" its block
		$lastShownLine = $manager->__array_strpos($lines, 'show', true);

		if (isset($lines[$lastShownLine])) {
			$lines[$lastShownLine] .= ' end';
		}
		// give the first start line another class so we can control the top padding of the entire results
		$firstShownLine = $manager->__array_strpos($lines, 'show');
		if (isset($lines[$firstShownLine])) {
			$lines[$firstShownLine] .= ' realstart';
		}
		// get the output
		$lineCount = $coveredCount = 0;
		$report = '';
		foreach ($testObjectFile as $num => $line) {
			// start line count at 1
			$num++;
			$class = $lines[$num];

			if (strpos($class, 'ignored') === false) {
				$lineCount++;

				if (strpos($class, 'covered') !== false && strpos($class, 'uncovered') === false) {
					$coveredCount++;
				}
			}

			if (strpos($class, 'show') !== false) {
				$report .= $manager->__paintCodeline($class, $num, $line);
			}
		}
		return $manager->__paintHeader($lineCount, $coveredCount, $report);
	}
/**
 * CLI reporting
 *
 * @param string $testObjectFile
 * @param string $coverageData
 * @param string $execCodeLines
 * @param string $output
 * @return void
 */
	function reportCaseCli($testObjectFile, $coverageData, $execCodeLines) {
		$manager = CodeCoverageManager::getInstance();
		$lineCount = $coveredCount = 0;
		$report = '';

		foreach ($testObjectFile as $num => $line) {
			$num++;
			$foundByManualFinder = array_key_exists($num, $execCodeLines) && trim($execCodeLines[$num]) != '';
			$foundByXdebug = array_key_exists($num, $coverageData) && $coverageData[$num] !== -2;

			if ($foundByManualFinder && $foundByXdebug) {
				$lineCount++;

				if ($coverageData[$num] > 0) {
					$coveredCount++;
				}
			}
		}
		return $manager->__paintHeaderCli($lineCount, $coveredCount, $report);
	}
/**
 * Diff reporting
 *
 * @param string $testObjectFile
 * @param string $coverageData
 * @param string $execCodeLines
 * @param string $output
 * @return void
 */
	function reportGroupHtml($testObjectFiles, $coverageData, $execCodeLines, $numContextLines) {
		$manager = CodeCoverageManager::getInstance();
		$report = '';

		foreach ($testObjectFiles as $testObjectFile) {
			$lineCount = $coveredCount = 0;
			$objFilename = $testObjectFile;
			$testObjectFile = file($testObjectFile);

			foreach ($testObjectFile as $num => $line) {
				$num++;
				$foundByManualFinder = array_key_exists($num, $execCodeLines[$objFilename]) && trim($execCodeLines[$objFilename][$num]) != '';
				$foundByXdebug = array_key_exists($num, $coverageData[$objFilename]) && $coverageData[$objFilename][$num] !== -2;

				if ($foundByManualFinder && $foundByXdebug) {
					$class = 'uncovered';
					$lineCount++;

					if ($coverageData[$objFilename][$num] > 0) {
						$class = 'covered';
						$coveredCount++;
					}
				} else {
					$class = 'ignored';
				}
			}
			$report .= $manager->__paintGroupResultLine($objFilename, $lineCount, $coveredCount);
		}
		return $manager->__paintGroupResultHeader($report);
	}
/**
 * CLI reporting
 *
 * @param string $testObjectFile
 * @param string $coverageData
 * @param string $execCodeLines
 * @param string $output
 * @return void
 */
	function reportGroupCli($testObjectFiles, $coverageData, $execCodeLines) {
		$manager = CodeCoverageManager::getInstance();
		$report = '';

		foreach ($testObjectFiles as $testObjectFile) {
			$lineCount = $coveredCount = 0;
			$objFilename = $testObjectFile;
			$testObjectFile = file($testObjectFile);

			foreach ($testObjectFile as $num => $line) {
				$num++;
				$foundByManualFinder = array_key_exists($num, $execCodeLines[$objFilename]) && trim($execCodeLines[$objFilename][$num]) != '';
				$foundByXdebug = array_key_exists($num, $coverageData[$objFilename]) && $coverageData[$objFilename][$num] !== -2;

				if ($foundByManualFinder && $foundByXdebug) {
					$lineCount++;

					if ($coverageData[$objFilename][$num] > 0) {
						$coveredCount++;
					}
				}
			}
			$report .= $manager->__paintGroupResultLineCli($objFilename, $lineCount, $coveredCount);
		}
		return $report;
	}
/**
 * Returns the name of the test object file based on a given test case file name
 *
 * @param string $file
 * @param string $isApp
 * @return string name of the test object file
 * @access private
 */
	function __testObjectFileFromCaseFile($file, $isApp = true) {
		$manager = CodeCoverageManager::getInstance();
		$path = $manager->__getTestFilesPath($isApp);
		$folderPrefixMap = array(
			'behaviors' => 'models',
			'components' => 'controllers',
			'helpers' => 'views'
		);

		foreach ($folderPrefixMap as $dir => $prefix) {
			if (strpos($file, $dir) === 0) {
				$path .= $prefix . DS;
				break;
			}
		}
		$testManager =& new TestManager();
		$testFile = str_replace(array('/', $testManager->_testExtension), array(DS, '.php'), $file);

		$folder =& new Folder();
		$folder->cd(ROOT . DS . CAKE_TESTS_LIB);
		$contents = $folder->ls();

		if (in_array(basename($testFile), $contents[1])) {
			$testFile = basename($testFile);
			$path = ROOT . DS . CAKE_TESTS_LIB;
		}
		$path .= $testFile;
		$realpath = realpath($path);

		if ($realpath) {
			return $realpath;
		}
		return $path;
	}
/**
 * Returns an array of names of the test object files based on a given test group file name
 *
 * @param array $files
 * @param string $isApp
 * @return array names of the test object files
 * @access private
 */
	function __testObjectFilesFromGroupFile($groupFile, $isApp = true) {
		$manager = CodeCoverageManager::getInstance();
		$testManager =& new TestManager();

		$path = TESTS . 'groups';

		if (!$isApp) {
			$path = ROOT . DS . 'cake' . DS . 'tests' . DS . 'groups';
		}

		if (!!$manager->pluginTest) {
			$path = APP . 'plugins' . DS . $manager->pluginTest . DS . 'tests' . DS . 'groups';

			$pluginPaths = Configure::read('pluginPaths');
			foreach ($pluginPaths as $pluginPath) {
				$tmpPath = $pluginPath . $manager->pluginTest . DS . 'tests' . DS. 'groups';
				if (file_exists($tmpPath)) {
					$path = $tmpPath;
					break;
				}
			}
		}
		$path .= DS . $groupFile . $testManager->_groupExtension;

		if (!file_exists($path)) {
			trigger_error('This group file does not exist!');
			return array();
		}
		$result = array();
		$groupContent = file_get_contents($path);
		$ds = '\s*\.\s*DS\s*\.\s*';
		$pluginTest = 'APP\.\'plugins\'' . $ds . '\'' . $manager->pluginTest . '\'' . $ds . '\'tests\'' . $ds . '\'cases\'';
		$pattern = '/\s*TestManager::addTestFile\(\s*\$this,\s*(' . $pluginTest . '|APP_TEST_CASES|CORE_TEST_CASES)' . $ds . '(.*?)\)/i';
		preg_match_all($pattern, $groupContent, $matches);

		foreach ($matches[2] as $file) {
			$patterns = array(
				'/\s*\.\s*DS\s*\.\s*/',
				'/\s*APP_TEST_CASES\s*/',
				'/\s*CORE_TEST_CASES\s*/',
			);
			$replacements = array(DS, '', '');
			$file = preg_replace($patterns, $replacements, $file);
			$file = str_replace("'", '', $file);
			$result[] = $manager->__testObjectFileFromCaseFile($file, $isApp) . '.php';
		}
		return $result;
	}
/**
 * Parses a given code string into an array of lines and replaces some non-executable code lines with the needed
 * amount of new lines in order for the code line numbers to stay in sync
 *
 * @param string $content
 * @return array array of lines
 * @access private
 */
	function __getExecutableLines($content) {
		if (is_array($content)) {
			$manager = CodeCoverageManager::getInstance();
			$result = array();
			foreach ($content as $file) {
				$result[$file] = $manager->__getExecutableLines(file_get_contents($file));
			}
			return $result;
		}
		$content = h($content);
		// arrays are 0-indexed, but we want 1-indexed stuff now as we are talking code lines mind you (**)
		$content = "\n" . $content;
		// // strip unwanted lines
		$content = preg_replace_callback("/(@codeCoverageIgnoreStart.*?@codeCoverageIgnoreEnd)/is", array('CodeCoverageManager', '__replaceWithNewlines'), $content);
		// strip php | ?\> tag only lines
		$content = preg_replace('/[ |\t]*[&lt;\?php|\?&gt;]+[ |\t]*/', '', $content);

		// strip lines that contain only braces and parenthesis
		$content = preg_replace('/[ |\t]*[{|}|\(|\)]+[ |\t]*/', '', $content);
		$result = explode("\n", $content);
		// unset the zero line again to get the original line numbers, but starting at 1, see (**)
		unset($result[0]);
		return $result;
	}
/**
 * Replaces a given arg with the number of newlines in it
 *
 * @return string the number of newlines in a given arg
 * @access private
 */
	function __replaceWithNewlines() {
		$args = func_get_args();
		$numLineBreaks = count(explode("\n", $args[0][0]));
		return str_pad('', $numLineBreaks - 1, "\n");
	}
/**
 * Paints the headline for code coverage analysis
 *
 * @param string $codeCoverage
 * @param string $report
 * @return void
 * @access private
 */
	function __paintHeader($lineCount, $coveredCount, $report) {
		$manager =& CodeCoverageManager::getInstance();
		$codeCoverage = $manager->__calcCoverage($lineCount, $coveredCount);
		return $report = '<h2>Code Coverage: ' . $codeCoverage . '%</h2>
						<div class="code-coverage-results"><pre>' . $report . '</pre></div>';
	}
/**
 * Displays a notification concerning group test results
 *
 * @return void
 * @access public
 */
	function __paintGroupResultHeader($report) {
		return '<div class="code-coverage-results"><p class="note">Please keep in mind that the coverage can vary a little bit depending on how much the different tests in the group interfere. If for example, TEST A calls a line from TEST OBJECT B, the coverage for TEST OBJECT B will be a little greater than if you were running the corresponding test case for TEST OBJECT B alone.</p><pre>' . $report . '</pre></div>';
	}
/**
 * Paints the headline for code coverage analysis
 *
 * @param string $codeCoverage
 * @param string $report
 * @return void
 * @access private
 */
	function __paintGroupResultLine($file, $lineCount, $coveredCount) {
		$manager =& CodeCoverageManager::getInstance();
		$codeCoverage = $manager->__calcCoverage($lineCount, $coveredCount);
		$class = 'result-bad';

		if ($codeCoverage > 50) {
			$class = 'result-ok';
		}
		if ($codeCoverage > 80) {
			$class = 'result-good';
		}
		return '<p>Code Coverage for ' . $file . ': <span class="' . $class . '">' . $codeCoverage . '%</span></p>';
	}
/**
 * Paints the headline for code coverage analysis
 *
 * @param string $codeCoverage
 * @param string $report
 * @return void
 * @access private
 */
	function __paintGroupResultLineCli($file, $lineCount, $coveredCount) {
		$manager =& CodeCoverageManager::getInstance();
		$codeCoverage = $manager->__calcCoverage($lineCount, $coveredCount);
		$class = 'bad';

		if ($codeCoverage > 50) {
			$class = 'ok';
		}
		if ($codeCoverage > 80) {
			$class = 'good';
		}
		return "\n" . 'Code Coverage for ' . $file . ': ' . $codeCoverage . '% (' . $class . ')' . "\n";
	}
/**
 * Paints the headline for code coverage analysis in the CLI
 *
 * @param string $codeCoverage
 * @param string $report
 * @return void
 * @access private
 */
	function __paintHeaderCli($lineCount, $coveredCount, $report) {
		$manager =& CodeCoverageManager::getInstance();
		$codeCoverage = $manager->__calcCoverage($lineCount, $coveredCount);
		return $report = 'Code Coverage: ' . $codeCoverage . '%';
	}
/**
 * Paints a code line for html output
 *
 * @package default
 * @access private
 */
	function __paintCodeline($class, $num, $line) {
		$line = h($line);

		if (trim($line) == '') {
			$line = '&nbsp;'; // Win IE fix
		}
		return '<div class="code-line ' . trim($class) . '"><span class="line-num">' . $num . '</span><span class="content">' . $line . '</span></div>';
	}
/**
 * Calculates the coverage percentage based on a line count and a covered line count
 *
 * @param string $lineCount
 * @param string $coveredCount
 * @return void
 * @access private
 */
	function __calcCoverage($lineCount, $coveredCount) {
		if ($coveredCount > $lineCount) {
			trigger_error('Sorry, you cannot have more covered lines than total lines!');
		}
		return ($lineCount != 0)
				? round(100 * $coveredCount / $lineCount, 2)
				: '0.00';
	}
/**
 * Gets us the base path to look for the test files
 *
 * @param string $isApp
 * @return void
 * @access public
 */
	function __getTestFilesPath($isApp = true) {
		$manager = CodeCoverageManager::getInstance();
		$path = ROOT . DS;

		if ($isApp) {
			$path .= APP_DIR . DS;
		} elseif (!!$manager->pluginTest) {
			$pluginPath = APP . 'plugins' . DS . $manager->pluginTest . DS;

			$pluginPaths = Configure::read('pluginPaths');
			foreach ($pluginPaths as $tmpPath) {
				$tmpPath = $tmpPath . $manager->pluginTest . DS;
				if (file_exists($tmpPath)) {
					$pluginPath = $tmpPath;
					break;
				}
			}

			$path = $pluginPath;
		} else {
			$path = TEST_CAKE_CORE_INCLUDE_PATH;
		}

		return $path;
	}
/**
 * Finds the last element of an array that contains $needle in a strpos computation
 *
 * @param array $arr
 * @param string $needle
 * @return void
 * @access private
 */
	function __array_strpos($arr, $needle, $reverse = false) {
		if (!is_array($arr) || empty($arr)) {
			return false;
		}

		if ($reverse) {
			$arr = array_reverse($arr, true);
		}

		foreach ($arr as $key => $val) {
			if (strpos($val, $needle) !== false) {
				return $key;
			}
		}
		return false;
	}
}
?>