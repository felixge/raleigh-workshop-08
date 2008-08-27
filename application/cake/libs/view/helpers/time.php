<?php
/* SVN FILE: $Id: time.php 7496 2008-08-25 03:15:10Z mark_story $ */

/**
 * Time Helper class file.
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
 * @subpackage		cake.cake.libs.view.helpers
 * @since			CakePHP(tm) v 0.10.0.1076
 * @version			$Revision: 7496 $
 * @modifiedby		$LastChangedBy: mark_story $
 * @lastmodified	$Date: 2008-08-25 05:15:10 +0200 (Mo, 25 Aug 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Time Helper class for easy use of time data.
 *
 * Manipulation of time data.
 *
 * @package		cake
 * @subpackage	cake.cake.libs.view.helpers
 */
class TimeHelper extends AppHelper {
/**
 * Converts given time (in server's time zone) to user's local time, given his/her offset from GMT.
 *
 * @param string $server_time UNIX timestamp
 * @param int $user_offset User's offset from GMT (in hours)
 * @return string UNIX timestamp
 */
	function convert($serverTime, $userOffset) {
		$serverOffset = $this->serverOffset();
		$gmtTime = $serverTime - $serverOffset;
		$userTime = $gmtTime + $userOffset * (60*60);
		return $userTime;
	}
/**
 * Returns server's offset from GMT in seconds.
 *
 * @return int Offset
 */
	function serverOffset() {
		return date('Z', time());	
	}
/**
 * Returns a UNIX timestamp, given either a UNIX timestamp or a valid strtotime() date string.
 *
 * @param string $dateString Datetime string
 * @param int $userOffset User's offset from GMT (in hours)
 * @return string Formatted date string
 */
	function fromString($dateString, $userOffset = null) {
		if (is_integer($dateString) || is_numeric($dateString)) {
			$date = intval($dateString);
		} else {
			$date = strtotime($dateString);
		}
		if ($userOffset !== null) {
			return $this->convert($date, $userOffset);
		}
		return $date;
	}
/**
 * Returns a nicely formatted date string for given Datetime string.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param int $userOffset User's offset from GMT (in hours)
 * @return string Formatted date string
 */
	function nice($dateString = null, $userOffset = null) {
		if ($dateString != null) {
			$date = $this->fromString($dateString, $userOffset);
		} else {
			$date = time();
		}

		$ret = date("D, M jS Y, H:i", $date);
		return $this->output($ret);
	}
/**
 * Returns a formatted descriptive date string for given datetime string.
 *
 * If the given date is today, the returned string could be "Today, 16:54".
 * If the given date was yesterday, the returned string could be "Yesterday, 16:54".
 * If $dateString's year is the current year, the returned string does not
 * include mention of the year.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param int $userOffset User's offset from GMT (in hours)
 * @return string Described, relative date string
 */
	function niceShort($dateString = null, $userOffset = null) {
		$date = $dateString ? $this->fromString($dateString, $userOffset) : time();

		$y = $this->isThisYear($date) ? '' : ' Y';

		if ($this->isToday($date)) {
			$ret = sprintf(__('Today, %s',true), date("H:i", $date));
		} elseif ($this->wasYesterday($date)) {
			$ret = sprintf(__('Yesterday, %s',true), date("H:i", $date));
		} else {
			$ret = date("M jS{$y}, H:i", $date);
		}

		return $this->output($ret);
	}
/**
 * Returns a partial SQL string to search for all records between two dates.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param string $end Datetime string or Unix timestamp
 * @param string $fieldName Name of database field to compare with
 * @param int $userOffset User's offset from GMT (in hours)
 * @return string Partial SQL string.
 */
	function daysAsSql($begin, $end, $fieldName, $userOffset = null) {
		$begin = $this->fromString($begin, $userOffset);
		$end = $this->fromString($end, $userOffset);
		$begin = date('Y-m-d', $begin) . ' 00:00:00';
		$end = date('Y-m-d', $end) . ' 23:59:59';

		$ret  ="($fieldName >= '$begin') AND ($fieldName <= '$end')";
		return $this->output($ret);
	}
/**
 * Returns a partial SQL string to search for all records between two times
 * occurring on the same day.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param string $fieldName Name of database field to compare with
 * @param int $userOffset User's offset from GMT (in hours)
 * @return string Partial SQL string.
 */
	function dayAsSql($dateString, $fieldName, $userOffset = null) {
		$date = $this->fromString($dateString, $userOffset);
		$ret = $this->daysAsSql($dateString, $dateString, $fieldName);
		return $this->output($ret);
	}
/**
 * Returns true if given datetime string is today.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param int $userOffset User's offset from GMT (in hours)
 * @return boolean True if datetime string is today
 */
	function isToday($dateString, $userOffset = null) {
		$date = $this->fromString($dateString, $userOffset);
		return date('Y-m-d', $date) == date('Y-m-d', time());
	}
/**
 * Returns true if given datetime string is within this week
 * @param string $dateString
 * @param int $userOffset User's offset from GMT (in hours)
 * @return boolean True if datetime string is within current week
 */
	function isThisWeek($dateString, $userOffset = null) {
		$date = $this->fromString($dateString, $userOffset);
		return date('W Y', $date) == date('W Y', time());
	}
/**
 * Returns true if given datetime string is within this month
 * @param string $dateString
 * @param int $userOffset User's offset from GMT (in hours)
 * @return boolean True if datetime string is within current month
 */
	function isThisMonth($dateString, $userOffset = null) {
		$date = $this->fromString($dateString);
		return date('m Y',$date) == date('m Y', time());
	}
/**
 * Returns true if given datetime string is within current year.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @return boolean True if datetime string is within current year
 */
	function isThisYear($dateString, $userOffset = null) {
		$date = $this->fromString($dateString, $userOffset);
		return  date('Y', $date) == date('Y', time());
	}
/**
 * Returns true if given datetime string was yesterday.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param int $userOffset User's offset from GMT (in hours)
 * @return boolean True if datetime string was yesterday
 */
	function wasYesterday($dateString, $userOffset = null) {
		$date = $this->fromString($dateString, $userOffset);
		return date('Y-m-d', $date) == date('Y-m-d', strtotime('yesterday'));
	}
/**
 * Returns true if given datetime string is tomorrow.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param int $userOffset User's offset from GMT (in hours)
 * @return boolean True if datetime string was yesterday
 */
	function isTomorrow($dateString, $userOffset = null) {
		$date = $this->fromString($dateString, $userOffset);
		return date('Y-m-d', $date) == date('Y-m-d', strtotime('tomorrow'));
	}
/**
 * Returns the quart
 * @param string $dateString
 * @param boolean $range if true returns a range in Y-m-d format
 * @return boolean True if datetime string is within current week
 */
	function toQuarter($dateString, $range = false) {
		$time = $this->fromString($dateString);
		$date = ceil(date('m', $time) / 3);

		if ($range === true) {
			$range = 'Y-m-d';
		}

		if ($range !== false) {
			$year = date('Y', $time);

			switch ($date) {
				case 1:
					$date = array($year.'-01-01', $year.'-03-31');
					break;
				case 2:
					$date = array($year.'-04-01', $year.'-06-30');
					break;
				case 3:
					$date = array($year.'-07-01', $year.'-09-30');
					break;
				case 4:
					$date = array($year.'-10-01', $year.'-12-31');
					break;
			}
		}
		return $this->output($date);
	}
/**
 * Returns a UNIX timestamp from a textual datetime description. Wrapper for PHP function strtotime().
 *
 * @param string $dateString Datetime string to be represented as a Unix timestamp
 * @param int $userOffset User's offset from GMT (in hours)
 * @return integer Unix timestamp
 */
	function toUnix($dateString, $userOffset = null) {
		$ret = $this->fromString($dateString, $userOffset);
		return $this->output($ret);
	}
/**
 * Returns a date formatted for Atom RSS feeds.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param int $userOffset User's offset from GMT (in hours)
 * @return string Formatted date string
 */
	function toAtom($dateString, $userOffset = null) {
		$date = $this->fromString($dateString, $userOffset);
		$ret = date('Y-m-d\TH:i:s\Z', $date);
		return $this->output($ret);
	}
/**
 * Formats date for RSS feeds
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param int $userOffset User's offset from GMT (in hours)
 * @return string Formatted date string
 */
	function toRSS($dateString, $userOffset = null) {
		$date = $this->fromString($dateString, $userOffset);
		$ret = date("r", $date);
		return $this->output($ret);
	}
/**
 * Returns either a relative date or a formatted date depending
 * on the difference between the current time and given datetime.
 * $datetime should be in a <i>strtotime</i>-parsable format, like MySQL's datetime datatype.
 *
 * Options:
 *  'format' => a fall back format if the relative time is longer than the duration specified by end
 *  'end' =>  The end of relative time telling
 *  'userOffset' => Users offset from GMT (in hours)
 *
 * Relative dates look something like this:
 *	3 weeks, 4 days ago
 *	15 seconds ago
 * Formatted dates look like this:
 *	on 02/18/2004
 *
 * The returned string includes 'ago' or 'on' and assumes you'll properly add a word
 * like 'Posted ' before the function output.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param array $options Default format if timestamp is used in $dateString
 * @return string Relative time string.
 */
	function timeAgoInWords($dateTime, $options = array()) {
		$userOffset = null;
		if (is_array($options) && isset($options['userOffset'])) {
			$userOffset = $options['userOffset'];
		}
		$now = time();
		if (!is_null($userOffset)) {
			$now = 	$this->convert(time(), $userOffset);
		}
		$in_seconds = $this->fromString($dateTime, $userOffset);
		$backwards = ($in_seconds > $now);

		$format = 'j/n/y';
		$end = '+1 month';

		if (is_array($options)) {
			if (isset($options['format'])) {
				$format = $options['format'];
				unset($options['format']);
			}
			if (isset($options['end'])) {
				$end = $options['end'];
				unset($options['end']);
			}
		} else {
			$format = $options;
		}

		if ($backwards) {
			$future_time = $in_seconds;
			$past_time = $now;
		} else {
			$future_time = $now;
			$past_time = $in_seconds;
		}
		$diff = $future_time - $past_time;

		// If more than a week, then take into account the length of months
		if ($diff >= 604800) {
			$current = array();
			$date = array();

			list($future['H'], $future['i'], $future['s'], $future['d'], $future['m'], $future['Y']) = explode('/', date('H/i/s/d/m/Y', $future_time));

			list($past['H'], $past['i'], $past['s'], $past['d'], $past['m'], $past['Y']) = explode('/', date('H/i/s/d/m/Y', $past_time));
			$years = $months = $weeks = $days = $hours = $minutes = $seconds = 0;

			if ($future['Y'] == $past['Y'] && $future['m'] == $past['m']) {
				$months = 0;
				$years = 0;
			} else {
				if ($future['Y'] == $past['Y']) {
					$months = $future['m'] - $past['m'];
				} else {
					$years = $future['Y'] - $past['Y'];
					$months = $future['m'] + ((12 * $years) - $past['m']);

					if ($months >= 12) {
						$years = floor($months / 12);
						$months = $months - ($years * 12);
					}

					if ($future['m'] < $past['m'] && $future['Y'] - $past['Y'] == 1) {
						$years --;
					}
				}
			}

			if ($future['d'] >= $past['d']) {
				$days = $future['d'] - $past['d'];
			} else {
				$days_in_past_month = date('t', $past_time);
				$days_in_future_month = date('t', mktime(0, 0, 0, $future['m'] - 1, 1, $future['Y']));

				if (!$backwards) {
					$days = ($days_in_past_month - $past['d']) + $future['d'];
				} else {
					$days = ($days_in_future_month - $past['d']) + $future['d'];
				}

				if ($future['m'] != $past['m']) {
					$months --;
				}
			}

			if ($months == 0 && $years >= 1 && $diff < ($years * 31536000)){
				$months = 11;
				$years --;
			}

			if ($months >= 12) {
				$years = $years + 1;
				$months = $months - 12;
			}

			if ($days >= 7) {
				$weeks = floor($days / 7);
				$days = $days - ($weeks * 7);
			}
		} else {
			$years = $months = $weeks = 0;
			$days = floor($diff / 86400);

			$diff = $diff - ($days * 86400);

			$hours = floor($diff / 3600);
			$diff = $diff - ($hours * 3600);

			$minutes = floor($diff / 60);
			$diff = $diff - ($minutes * 60);
			$seconds = $diff;
		}
		$relative_date = '';
		$diff = $future_time - $past_time;

		if ($diff > abs($now - $this->fromString($end))) {
			$relative_date = sprintf(__('on %s',true), date($format, $in_seconds));
		} else {
			if ($years > 0) {
				// years and months and days
				$relative_date .= ($relative_date ? ', ' : '') . $years . ' ' . __n('year', 'years', $years, true);
				$relative_date .= $months > 0 ? ($relative_date ? ', ' : '') . $months . ' ' . __n('month', 'months', $months, true) : '';
				$relative_date .= $weeks > 0 ? ($relative_date ? ', ' : '') . $weeks . ' ' . __n('week', 'weeks', $weeks, true) : '';
				$relative_date .= $days > 0 ? ($relative_date ? ', ' : '') . $days . ' ' . __n('day', 'days', $days, true) : '';
			} elseif (abs($months) > 0) {
				// months, weeks and days
				$relative_date .= ($relative_date ? ', ' : '') . $months . ' ' . __n('month', 'months', $months, true);
				$relative_date .= $weeks > 0 ? ($relative_date ? ', ' : '') . $weeks . ' ' . __n('week', 'weeks', $weeks, true) : '';
				$relative_date .= $days > 0 ? ($relative_date ? ', ' : '') . $days . ' ' . __n('day', 'days', $days, true) : '';
			} elseif (abs($weeks) > 0) {
				// weeks and days
				$relative_date .= ($relative_date ? ', ' : '') . $weeks . ' ' . __n('week', 'weeks', $weeks, true);
				$relative_date .= $days > 0 ? ($relative_date ? ', ' : '') . $days . ' ' . __n('day', 'days', $days, true) : '';
			} elseif (abs($days) > 0) {
				// days and hours
				$relative_date .= ($relative_date ? ', ' : '') . $days . ' ' . __n('day', 'days', $days, true);
				$relative_date .= $hours > 0 ? ($relative_date ? ', ' : '') . $hours . ' ' . __n('hour', 'hours', $hours, true) : '';
			} elseif (abs($hours) > 0) {
				// hours and minutes
				$relative_date .= ($relative_date ? ', ' : '') . $hours . ' ' . __n('hour', 'hours', $hours, true);
				$relative_date .= $minutes > 0 ? ($relative_date ? ', ' : '') . $minutes . ' ' . __n('minute', 'minutes', $minutes, true) : '';
			} elseif (abs($minutes) > 0) {
				// minutes only
				$relative_date .= ($relative_date ? ', ' : '') . $minutes . ' ' . __n('minute', 'minutes', $minutes, true);
			} else {
				// seconds only
				$relative_date .= ($relative_date ? ', ' : '') . $seconds . ' ' . __n('second', 'seconds', $seconds, true);
			}

			if (!$backwards) {
				$relative_date = sprintf(__('%s ago', true), $relative_date);
			}
		}
		return $this->output($relative_date);
	}
/**
 * Alias for timeAgoInWords
 *
 * @param mixed $dateTime Datetime string (strtotime-compatible) or Unix timestamp
 * @param mixed $options Default format string, if timestamp is used in $dateTime, or an array of options to be passed
 *						 on to timeAgoInWords().
 * @return string Relative time string.
 * @see		TimeHelper::timeAgoInWords
 */
	function relativeTime($dateTime, $options = array()) {
		return $this->timeAgoInWords($dateTime, $options);
	}
/**
 * Returns true if specified datetime was within the interval specified, else false.
 *
 * @param mixed $timeInterval the numeric value with space then time type. Example of valid types: 6 hours, 2 days, 1 minute.
 * @param mixed $dateString the datestring or unix timestamp to compare
 * @param int $userOffset User's offset from GMT (in hours)
 * @return bool
 */
	function wasWithinLast($timeInterval, $dateString, $userOffset = null) {
		$tmp = r(' ', '', $timeInterval);
		if (is_numeric($tmp)) {
			$timeInterval = $tmp . ' ' . __('days', true);
		}

		$date = $this->fromString($dateString, $userOffset);
		$interval = $this->fromString('-'.$timeInterval);

		if ($date >= $interval && $date <= time()) {
			return true;
		}

		return false;
	}
/**
 * Returns gmt, given either a UNIX timestamp or a valid strtotime() date string.
 *
 * @param string $dateString Datetime string
 * @return string Formatted date string
 */
	function gmt($string = null) {
		if ($string != null) {
			$string = $this->fromString($string);
		} else {
			$string = time();
		}
		$string = $this->fromString($string);
		$hour = intval(date("G", $string));
		$minute = intval(date("i", $string));
		$second = intval(date("s", $string));
		$month = intval(date("n", $string));
		$day = intval(date("j", $string));
		$year = intval(date("Y", $string));

		$return = gmmktime($hour, $minute, $second, $month, $day, $year);
		return $return;
	}
/**
 * Returns a UNIX timestamp, given either a UNIX timestamp or a valid strtotime() date string.
 *
 * @param string $format date format string. defaults to 'd-m-Y'
 * @param string $dateString Datetime string
 * @param boolean $invalid flag to ignore results of fromString == false
 * @param int $userOffset User's offset from GMT (in hours)
 * @return string Formatted date string
 */
	function format($format = 'd-m-Y', $date, $invalid = false, $userOffset = null) {
		$date = $this->fromString($date, $userOffset);
		if ($date === false && $invalid !== false) {
			return $invalid;
		}
		return date($format, $date);
	}
}

?>