<?php
/* SVN FILE: $Id: set.php 7495 2008-08-24 22:08:13Z gwoo $ */
/**
 * Library of array functions for Cake.
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
 * @since			CakePHP(tm) v 1.2.0
 * @version			$Revision: 7495 $
 * @modifiedby		$LastChangedBy: gwoo $
 * @lastmodified	$Date: 2008-08-25 00:08:13 +0200 (Mo, 25 Aug 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Class used for manipulation of arrays.
 *
 * Long description for class
 *
 * @package		cake
 * @subpackage	cake.cake.libs
 */
class Set extends Object {
/**
 * Deprecated
 *
 */
	var $value = array();
/**
 * This function can be thought of as a hybrid between PHP's array_merge and array_merge_recursive. The difference
 * to the two is that if an array key contains another array then the function behaves recursive (unlike array_merge)
 * but does not do if for keys containing strings (unlike array_merge_recursive). See the unit test for more information.
 *
 * Note: This function will work with an unlimited amount of arguments and typecasts non-array parameters into arrays.
 *
 * @param array $arr1 Array to be merged
 * @param array $arr2 Array to merge with
 * @return array Merged array
 * @access public
 */
	function merge($arr1, $arr2 = null) {
		$args = func_get_args();

		if (!isset($r)) {
			$r = (array)current($args);
		}

		while (($arg = next($args)) !== false) {
			foreach ((array)$arg as $key => $val)	 {
				if (is_array($val) && isset($r[$key]) && is_array($r[$key])) {
					$r[$key] = Set::merge($r[$key], $val);
				} elseif (is_int($key)) {
					$r[] = $val;
				} else {
					$r[$key] = $val;
				}
			}
		}
		return $r;
	}
/**
 * Filters empty elements out of a route array, excluding '0'.
 *
 * @param mixed $var Either an array to filter, or value when in callback
 * @param boolean $isArray Force to tell $var is an array when $var is empty
 * @return mixed Either filtered array, or true/false when in callback
 * @access public
 */
	function filter($var, $isArray = false) {
		if (is_array($var) && (!empty($var) || $isArray)) {
			return array_filter($var, array('Set', 'filter'));
		} else {
			if ($var === 0 || $var === '0' || !empty($var)) {
				return true;
			} else {
				return false;
			}
		}
	}
/**
 * Pushes the differences in $array2 onto the end of $array
 *
 * @param mixed $array Original array
 * @param mixed $array2 Differences to push
 * @return array Combined array
 * @access public
 */
	function pushDiff($array, $array2) {
		if (empty($array) && !empty($array2)) {
			return $array2;
		}
		if (!empty($array) && !empty($array2)) {
			foreach ($array2 as $key => $value) {
				if (!array_key_exists($key, $array)) {
					$array[$key] = $value;
				} else {
					if (is_array($value)) {
						$array[$key] = Set::pushDiff($array[$key], $array2[$key]);
					}
				}
			}
		}
		return $array;
	}
/**
 * Maps the contents of the Set object to an object hierarchy.
 * Maintains numeric keys as arrays of objects
 *
 * @param string $class A class name of the type of object to map to
 * @param string $tmp A temporary class name used as $class if $class is an array
 * @return object Hierarchical object
 * @access public
 */
	function map($class = 'stdClass', $tmp = 'stdClass') {
		if (is_array($class)) {
			$val = $class;
			$class = $tmp;
		}

		if (empty($val) || $val == null) {
			return null;
		}
		return Set::__map($val, $class);
	}

/**
 * Get the array value of $array. If $array is null, it will return
 * the current array Set holds. If it is an object of type Set, it
 * will return its value. If it is another object, its object variables.
 * If it is anything else but an array, it will return an array whose first
 * element is $array.
 *
 * @param mixed $array Data from where to get the array.
 * @return array Array from $array.
 * @access private
 */
	function __array($array) {
		if (empty($array)) {
			$array = array();
		} elseif (is_object($array)) {
			$array = get_object_vars($array);
		} elseif (!is_array($array)) {
			$array = array($array);
		}
		return $array;
	}

/**
 * Maps the given value as an object. If $value is an object,
 * it returns $value. Otherwise it maps $value as an object of
 * type $class, and if primary assign _name_ $key on first array.
 * If $value is not empty, it will be used to set properties of
 * returned object (recursively). If $key is numeric will maintain array
 * structure
 *
 * @param mixed $value Value to map
 * @param string $class Class name
 * @param boolean $primary whether to assign first array key as the _name_
 * @return mixed Mapped object
 * @access private
 */
	function __map(&$array, $class, $primary = false) {
		if ($class === true) {
			$out = new stdClass;
		} else {
			$out = new $class;
		}
		if (is_array($array)) {
			$keys = array_keys($array);
			foreach ($array as $key => $value) {
				if ($keys[0] === $key && $class !== true) {
					$primary = true;
				}
				if (is_numeric($key)) {
					if (is_object($out)) {
						$out = get_object_vars($out);
					}
					$out[$key] = Set::__map($value, $class, true);
				} elseif ($primary === true && is_array($value)) {
					$out->_name_ = $key;
					$primary = false;
					foreach($value as $key2 => $value2) {
						$out->{$key2} = Set::__map($value2, true);
					}
				} else {
					$out->{$key} = Set::__map($value, $class);
					if (is_object($out->{$key})) {
						$out->{$key}->_name_ = $key;
					}
				}
			}
		} else {
			$out = $array;
		}
		return $out;
	}
/**
 * Checks to see if all the values in the array are numeric
 *
 * @param array $array The array to check.  If null, the value of the current Set object
 * @return boolean true if values are numeric, false otherwise
 * @access public
 */
	function numeric($array = null) {
		if (empty($array)) {
			return null;
		}

		if ($array === range(0, count($array) - 1)) {
			return true;
		}

		$numeric = true;
		$keys = array_keys($array);
		$count = count($keys);

		for ($i = 0; $i < $count; $i++) {
			if (!is_numeric($array[$keys[$i]])) {
				$numeric = false;
				break;
			}
		}
		return $numeric;
	}
/**
 * Return a value from an array list if the key exists.
 *
 * If a comma separated $list is passed arrays are numeric with the key of the first being 0
 * $list = 'no, yes' would translate to  $list = array(0 => 'no', 1 => 'yes');
 *
 * If an array is used, keys can be strings example: array('no' => 0, 'yes' => 1);
 *
 * $list defaults to 0 = no 1 = yes if param is not passed
 *
 * @param mixed $select Key in $list to return
 * @param mixed $list can be an array or a comma-separated list.
 * @return string the value of the array key or null if no match
 * @access public
 */
	function enum($select, $list = null) {
		if (empty($list)) {
			$list = array('no', 'yes');
		}

		$return = null;
		$list = Set::normalize($list, false);

		if (array_key_exists($select, $list)) {
			$return = $list[$select];
		}
		return $return;
	}
/**
 * Returns a series of values extracted from an array, formatted in a format string.
 *
 * @param array		$data Source array from which to extract the data
 * @param string	$format Format string into which values will be inserted, see sprintf()
 * @param array		$keys An array containing one or more Set::extract()-style key paths
 * @return array	An array of strings extracted from $keys and formatted with $format
 * @access public
 */
	function format($data, $format, $keys) {

		$extracted = array();
		$count = count($keys);

		if (!$count) {
			return;
		}

		for ($i = 0; $i < $count; $i++) {
			$extracted[] = Set::extract($data, $keys[$i]);
		}
		$out = array();
		$data = $extracted;
		$count = count($data[0]);

		if (preg_match_all('/\{([0-9]+)\}/msi', $format, $keys2) && isset($keys2[1])) {
			$keys = $keys2[1];
			$format = preg_split('/\{([0-9]+)\}/msi', $format);
			$count2 = count($format);

			for ($j = 0; $j < $count; $j++) {
				$formatted = '';
				for ($i = 0; $i <= $count2; $i++) {
					if (isset($format[$i])) {
						$formatted .= $format[$i];
					}
					if (isset($keys[$i]) && isset($data[$keys[$i]][$j])) {
						$formatted .= $data[$keys[$i]][$j];
					}
				}
				$out[] = $formatted;
			}
		} else {
			$count2 = count($data);
			for ($j = 0; $j < $count; $j++) {
				$args = array();
				for ($i = 0; $i < $count2; $i++) {
					if (isset($data[$i][$j])) {
						$args[] = $data[$i][$j];
					}
				}
				$out[] = vsprintf($format, $args);
			}
		}
		return $out;
	}
/**
 * Implements partial support for XPath 2.0. If $path is an array or $data is empty it the call is delegated to Set::classicExtract.
 *
 * Currently implemented selectors:
 * - /User/id (similar to the classic {n}.User.id)
 * - /User[2]/name (selects the name of the second User)
 * - /User[id>2] (selects all Users with an id > 2)
 * - /User[id>2][<5] (selects all Users with an id > 2 but < 5)
 * - /Post/Comment[author_name=john]/../name (Selects the name of all Posts that have at least one Comment written by john)
 * - /Posts[title] (Selects all Posts that have a 'name' key)
 * - /Comment/.[1] (Selects the contents of the first comment)
 * - /Comment/.[:last] (Selects the last comment)
 * - /Comment/.[:first] (Selects the first comment)
 * - /Comment[text=/cakephp/i] (Selects the all comments that have a text matching the regex /cakephp/i)
 * - /Comment/@* (Selects the all key names of all comments)
 *
 * Other limitations:
 * - Only absolute paths starting with a single '/' are supported right now
 *
 * Warning: Even so it has plenty of unit tests the XPath support has not gone through a lot of real-world testing. Please report
 * Bugs as you find them. Suggestions for additional features to imlement are also very welcome!
 *
 * @param string $path An absolute XPath 2.0 path
 * @param string $data An array of data to extract from
 * @param string $options Currently only supports 'flatten' which can be disabled for higher XPath-ness
 * @return array An array of matched items
 * @access public
 */
	function extract($path, $data = null, $options = array()) {
		if (empty($data) && is_string($path) && $path{0} == '/') {
			return array();
		}
		if (is_string($data) && $data{0} == '/') {
			$tmp = $path;
			$path = $data;
			$data = $tmp;
		}
		if (is_array($path) || empty($data) || is_object($path) || empty($path)) {
			return Set::classicExtract($path, $data);
		}
		if ($path == '/') {
			return $data;
		}
		$contexts = $data;
		$options = am(array('flatten' => true), $options);
		if (!isset($contexts[0])) {
			$contexts = array($data);
		}

		$tokens = array_slice(preg_split('/(?<!=)\/(?![a-z]*\])/', $path), 1);
		do {
			$token = array_shift($tokens);
			$conditions = false;
			if (preg_match_all('/\[([^\]]+)\]/', $token, $m)) {
				$conditions = $m[1];
				$token = substr($token, 0, strpos($token, '['));
			}
			$matches = array();
			$i = 0;
			$contextsCount = count($contexts);
			foreach ($contexts as $key => $context) {
				$i++;
				if (!isset($context['trace'])) {
					$context = array('trace' => array(), 'item' => $context, 'key' => null);
				}
				if ($token == '..') {
					$context['item'] = Set::extract(join('/', $context['trace']), $data);
					$context['key'] = array_pop($context['trace']);
					$context['item'] = $context['item'][0][$context['key']];
					$matches[] = $context;
					continue;
				}

				$match = false;
				if ($token == '@*' && is_array($context['item'])) {
					$matches[] = array(
						'trace' => am($context['trace'], $key),
						'key' => $key,
						'item' => array_keys($context['item']),
					);
				} elseif (is_array($context['item']) && array_key_exists($token, $context['item'])) {
					$items = $context['item'][$token];
					if (!is_array($items) || !isset($items[0])) {
						$items = array($items);
					}
					foreach ($items as $item) {
						$matches[] = array(
							'trace' => am($context['trace'], $context['key']),
							'key' => $token,
							'item' => $item,
						);
					}
				} elseif (($key === $token || (ctype_digit($token) && $key == $token) || $token === '.')) {
					$matches[] = array(
						'trace' => am($context['trace'], $key),
						'key' => $key,
						'item' => $context['item'],
					);
				}
			}
			if ($conditions) {
				foreach ($conditions as $condition) {
					$filtered = array();
					$length = count($matches);
					foreach ($matches as $i => $match) {
						if (Set::matches(array($condition), $match['item'], $i+1, $length)) {
							$filtered[] = $match;
						}
					}
					$matches = $filtered;
				}
			}
			$contexts = $matches;
			if (empty($tokens)) {
				break;
			}
		} while(1);

		$r = array();
		foreach ($matches as $match) {
			if ((!$options['flatten'] || is_array($match['item'])) && !is_int($match['key'])) {
				$r[] = array($match['key'] => $match['item']);
			} else {
				$r[] = $match['item'];
			}
		}
		return $r;
	}
/**
 * This function can be used to see if a single item or a given xpath match certain conditions.
 *
 * @param mixed $conditions An array of condition strings or an XPath expression
 * @param array $data  An array of data to execute the match on
 * @param integer $i Optional: The 'nth'-number of the item being matched.
 * @return boolean
 * @access public
 */
	function matches($conditions, $data = array(), $i = null, $length = null) {
		if (empty($conditions)) {
			return true;
		}
		if (is_string($conditions)) {
			return !!Set::extract($conditions, $data);
		}
		foreach ($conditions as $condition) {
			if ($condition == ':last') {
				if ($i != $length) {
					return false;
				}
				continue;
			} elseif ($condition == ':first') {
				if ($i != 1) {
					return false;
				}
				continue;
			}
			if (!preg_match('/(.+?)([><!]?[=]|[><])(.*)/', $condition, $match)) {
				if (ctype_digit($condition)) {
					if ($i != $condition) {
						return false;
					}
				} elseif (preg_match_all('/(?:^[0-9]+|(?<=,)[0-9]+)/', $condition, $matches)) {
					return in_array($i, $matches[0]);
				} elseif (!array_key_exists($condition, $data)) {
					return false;
				}
				continue;
			}
			list(,$key,$op,$expected) = $match;
			if (!isset($data[$key])) {
				return false;
			}
			$val = $data[$key];
			if ($op == '=' && $expected && $expected{0} == '/') {
				return preg_match($expected, $val);
			} elseif ($op == '=' &&  $val != $expected) {
				return false;
			} elseif ($op == '!=' && $val == $expected) {
				return false;
			} elseif ($op == '>' && $val <= $expected) {
				return false;
			} elseif ($op == '<' && $val >= $expected) {
				return false;
			} elseif ($op == '<=' && $val > $expected) {
				return false;
			} elseif ($op == '>=' && $val < $expected) {
				return false;
			}
		}
		return true;
	}
/**
 * Gets a value from an array or object that is contained in a given path using an array path syntax, i.e.:
 * "{n}.Person.{[a-z]+}" - Where "{n}" represents a numeric key, "Person" represents a string literal,
 * and "{[a-z]+}" (i.e. any string literal enclosed in brackets besides {n} and {s}) is interpreted as
 * a regular expression.
 *
 * @param array $data Array from where to extract
 * @param mixed $path As an array, or as a dot-separated string.
 * @return array Extracted data
 * @access public
 */
	function classicExtract($data, $path = null) {
		if (empty($path)) {
			return $data;
		}
		if (is_object($data)) {
			$data = get_object_vars($data);
		}
		if (!is_array($data)) {
			return $data;
		}

		if (!is_array($path)) {
			$path = String::tokenize($path, '.', '{', '}');
		}
		$tmp = array();

		if (!is_array($path) || empty($path)) {
			return null;
		}

		foreach ($path as $i => $key) {
			if (is_numeric($key) && intval($key) > 0 || $key == '0') {
				if (isset($data[intval($key)])) {
					$data = $data[intval($key)];
				} else {
					return null;
				}
			} elseif ($key == '{n}') {
				foreach ($data as $j => $val) {
					if (is_int($j)) {
						$tmpPath = array_slice($path, $i + 1);
						if (empty($tmpPath)) {
							$tmp[] = $val;
						} else {
							$tmp[] = Set::classicExtract($val, $tmpPath);
						}
					}
				}
				return $tmp;
			} elseif ($key == '{s}') {
				foreach ($data as $j => $val) {
					if (is_string($j)) {
						$tmpPath = array_slice($path, $i + 1);
						if (empty($tmpPath)) {
							$tmp[] = $val;
						} else {
							$tmp[] = Set::classicExtract($val, $tmpPath);
						}
					}
				}
				return $tmp;
			} elseif (false !== strpos($key,'{') && false !== strpos($key,'}')) {
				$pattern = substr($key, 1, -1);

				foreach ($data as $j => $val) {
					if (preg_match('/^'.$pattern.'/s', $j) !== 0) {
						$tmpPath = array_slice($path, $i + 1);
						if (empty($tmpPath)) {
							$tmp[$j] = $val;
						} else {
							$tmp[$j] = Set::classicExtract($val, $tmpPath);
						}
					}
				}
				return $tmp;
			} else {
				if (isset($data[$key])) {
					$data = $data[$key];
				} else {
					return null;
				}
			}
		}
		return $data;
	}
/**
 * Inserts $data into an array as defined by $path.
 *
 * @param mixed $list Where to insert into
 * @param mixed $path A dot-separated string.
 * @param array $data Data to insert
 * @return array
 * @access public
 */
	function insert($list, $path, $data = null) {
		if (!is_array($path)) {
			$path = explode('.', $path);
		}
		$_list =& $list;

		foreach ($path as $i => $key) {
			if (is_numeric($key) && intval($key) > 0 || $key == '0') {
				$key = intval($key);
			}
			if ($i == count($path) - 1) {
				$_list[$key] = $data;
			} else {
				if (!isset($_list[$key])) {
					$_list[$key] = array();
				}
				$_list =& $_list[$key];
			}
		}
		return $list;
	}
/**
 * Removes an element from a Set or array as defined by $path.
 *
 * @param mixed $list From where to remove
 * @param mixed $path A dot-separated string.
 * @return array Array with $path removed from its value
 * @access public
 */
	function remove($list, $path = null) {
		if (empty($path)) {
			return $list;
		}
		if (!is_array($path)) {
			$path = explode('.', $path);
		}
		$_list =& $list;

		foreach ($path as $i => $key) {
			if (is_numeric($key) && intval($key) > 0 || $key == '0') {
				$key = intval($key);
			}
			if ($i == count($path) - 1) {
				unset($_list[$key]);
			} else {
				if (!isset($_list[$key])) {
					return $list;
				}
				$_list =& $_list[$key];
			}
		}
		return $list;
	}
/**
 * Checks if a particular path is set in an array
 *
 * @param mixed $data Data to check on
 * @param mixed $path A dot-separated string.
 * @return boolean true if path is found, false otherwise
 * @access public
 */
	function check($data, $path = null) {
		if (empty($path)) {
			return $data;
		}
		if (!is_array($path)) {
			$path = explode('.', $path);
		}

		foreach ($path as $i => $key) {
			if (is_numeric($key) && intval($key) > 0 || $key == '0') {
				$key = intval($key);
			}
			if ($i == count($path) - 1) {
				return (is_array($data) && array_key_exists($key, $data));
			} else {
				if (!is_array($data) || !array_key_exists($key, $data)) {
					return false;
				}
				$data =& $data[$key];
			}
		}
		return true;
	}
/**
 * Computes the difference between a Set and an array, two Sets, or two arrays
 *
 * @param mixed $val1 First value
 * @param mixed $val2 Second value
 * @return array Computed difference
 * @access public
 */
	function diff($val1, $val2 = null) {
		if (empty($val1)) {
			return (array)$val2;
		} elseif (empty($val2)) {
			return (array)$val1;
		}
		$out = array();

		foreach ($val1 as $key => $val) {
			$exists = array_key_exists($key, $val2);

			if ($exists && $val2[$key] != $val) {
				$out[$key] = $val;
			} elseif (!$exists) {
				$out[$key] = $val;
			}
			unset($val2[$key]);
		}

		foreach ($val2 as $key => $val) {
			if (!array_key_exists($key, $out)) {
				$out[$key] = $val;
			}
		}
		return $out;
	}
/**
 * Determines if two Sets or arrays are equal
 *
 * @param array $val1 First value
 * @param array $val2 Second value
 * @return boolean true if they are equal, false otherwise
 * @access public
 */
	function isEqual($val1, $val2 = null) {
		return ($val1 == $val2);
	}
/**
 * Determines if one Set or array contains the exact keys and values of another.
 *
 * @param array $val1 First value
 * @param array $val2 Second value
 * @return boolean true if $val1 contains $val2, false otherwise
 * @access public
 */
	function contains($val1, $val2 = null) {

		if (empty($val1) || empty($val2)) {
			return false;
		}

		foreach ($val2 as $key => $val) {
			if (is_numeric($key)) {
				Set::contains($val, $val1);
			} else {
				if (!isset($val1[$key]) || $val1[$key] != $val) {
					return false;
				}
			}
		}
		return true;
	}
/**
 * Counts the dimensions of an array. If $all is set to false (which is the default) it will
 * only consider the dimension of the first element in the array.
 *
 * @param array $array Array to count dimensions on
 * @param boolean $all Set to true to count the dimension considering all elements in array
 * @param integer $count Start the dimension count at this number
 * @return integer The number of dimensions in $array
 * @access public
 */
	function countDim($array = null, $all = false, $count = 0) {
		if ($all) {
			$depth = array($count);
			if (is_array($array) && reset($array) !== false) {
				foreach ($array as $value) {
					$depth[] = Set::countDim($value, true, $count + 1);
				}
			}
			$return = max($depth);
		} else {
			if (is_array(reset($array))) {
				$return = Set::countDim(reset($array)) + 1;
			} else {
				$return = 1;
			}
		}
		return $return;
	}
/**
 * Normalizes a string or array list.
 *
 * @param mixed $list List to normalize
 * @param boolean $assoc If true, $list will be converted to an associative array
 * @param string $sep If $list is a string, it will be split into an array with $sep
 * @param boolean $trim If true, separated strings will be trimmed
 * @return array
 * @access public
 */
	function normalize($list, $assoc = true, $sep = ',', $trim = true) {
		if (is_string($list)) {
			$list = explode($sep, $list);
			if ($trim) {
				$list = array_map('trim', $list);
			}
			if ($assoc) {
				return Set::normalize($list);
			}
		} elseif (is_array($list)) {
			$keys = array_keys($list);
			$count = count($keys);
			$numeric = true;

			if (!$assoc) {
				for ($i = 0; $i < $count; $i++) {
					if (!is_int($keys[$i])) {
						$numeric = false;
						break;
					}
				}
			}
			if (!$numeric || $assoc) {
				$newList = array();
				for ($i = 0; $i < $count; $i++) {
					if (is_int($keys[$i])) {
						$newList[$list[$keys[$i]]] = null;
					} else {
						$newList[$keys[$i]] = $list[$keys[$i]];
					}
				}
				$list = $newList;
			}
		}
		return $list;
	}
/**
 * Creates an associative array using a $path1 as the path to build its keys, and optionally
 * $path2 as path to get the values. If $path2 is not specified, all values will be initialized
 * to null (useful for Set::merge). You can optionally group the values by what is obtained when
 * following the path specified in $groupPath.
 *
 * @param array $data Array from where to extract keys and values
 * @param mixed $path1 As an array, or as a dot-separated string.
 * @param mixed $path2 As an array, or as a dot-separated string.
 * @param string $groupPath As an array, or as a dot-separated string.
 * @return array Combined array
 * @access public
 */
	function combine($data, $path1 = null, $path2 = null, $groupPath = null) {
		if (empty($data)) {
			return array();
		}

		if (is_object($data)) {
			$data = get_object_vars($data);
		}

		if (is_array($path1)) {
			$format = array_shift($path1);
			$keys = Set::format($data, $format, $path1);
		} else {
			$keys = Set::extract($data, $path1);
		}

		if (!empty($path2) && is_array($path2)) {
			$format = array_shift($path2);
			$vals = Set::format($data, $format, $path2);

		} elseif (!empty($path2)) {
			$vals = Set::extract($data, $path2);

		} else {
			$count = count($keys);
			for ($i = 0; $i < $count; $i++) {
				$vals[$i] = null;
			}
		}

		if ($groupPath != null) {
			$group = Set::extract($data, $groupPath);
			if (!empty($group)) {
				$c = count($keys);
				for ($i = 0; $i < $c; $i++) {
					if (!isset($group[$i])) {
						$group[$i] = 0;
					}
					if (!isset($out[$group[$i]])) {
						$out[$group[$i]] = array();
					}
					$out[$group[$i]][$keys[$i]] = $vals[$i];
				}
				return $out;
			}
		}

		return array_combine($keys, $vals);
	}
/**
 * Converts an object into an array. If $object is no object, reverse
 * will return the same value.
 *
 * @param object $object Object to reverse
 * @return array
 */
	function reverse($object) {
		$out = array();
		if (is_a($object, 'XmlNode')) {
			$out = $object->toArray();
			return $out;
		} else if (is_object($object)) {
			$keys = get_object_vars($object);
			if (isset($keys['_name_'])) {
				$identity = $keys['_name_'];
				unset($keys['_name_']);
			}
			$new = array();
			foreach ($keys as $key => $value) {
				if (is_array($value)) {
					$new[$key] = (array)Set::reverse($value);
				} else {
					if (isset($value->_name_)) {
						$new = array_merge($new, Set::reverse($value));
					} else {
						$new[$key] = Set::reverse($value);
					}
				}
			}
			if (isset($identity)) {
				$out[$identity] = $new;
			} else {
				$out = $new;
			}
		} elseif (is_array($object)) {
			foreach ($object as $key => $value) {
				$out[$key] = Set::reverse($value);
			}
		} else {
			$out = $object;
		}
		return $out;
	}
/**
 * Flattens an array for sorting
 *
 * @param array $results
 * @param string $key
 * @return array
 * @access private
 */
	function __flatten($results, $key = null) {
		$stack = array();
		foreach ($results as $k => $r) {
			$id = $k;
			if (!is_null($key)) {
				$id = $key;
			}
			if (is_array($r)) {
				$stack = array_merge($stack, Set::__flatten($r, $id));
			} else {
				$stack[] = array('id' => $id, 'value' => $r);
			}
		}
		return $stack;
	}
/**
 * Sorts an array by any value, determined by a Set-compatible path
 *
 * @param array $data
 * @param string $path A Set-compatible path to the array value
 * @param string $dir asc/desc
 * @return array
 */
	function sort($data, $path, $dir) {
		$result = Set::__flatten(Set::extract($data, $path));
		list($keys, $values) = array(Set::extract($result, '{n}.id'), Set::extract($result, '{n}.value'));

		if ($dir == 'asc') {
			$dir = SORT_ASC;
		} elseif ($dir == 'desc') {
			$dir = SORT_DESC;
		}
		array_multisort($values, $dir, $keys, $dir);
		$sorted = array();

		$keys = array_unique($keys);

		foreach ($keys as $k) {
			$sorted[] = $data[$k];
		}
		return $sorted;
	}
/**
 * Deprecated, Set class should be called statically
 *
 */
	function &get() {
		trigger_error('get() is deprecated. Set class should be called statically', E_USER_WARNING);
	}
}
?>