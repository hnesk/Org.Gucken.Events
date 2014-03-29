<?php

namespace Type\Date;

/**
 * Description of Parser
 *
 * @author jk
 */
class Parser {

	/**
	 * the regexp to parse the date
	 * @var string
	 */
	protected $pattern = '';

	/**
	 * default values for time
	 * @var array
	 */
	protected $defaults = array(
		'hour' => 20,
		'minute' => 0,
		'second' => 0,
	);

	/**
	 * which hours to interpret as today (e.g. for parties starting at 2:00 AM)
	 * @var float
	 */
	protected $midnightHour = 0;

	/**
	 * an offset in seconds to add to the parsed date
	 * @var int
	 */
	protected $offset = 0;

	/**
	 * range start to crop the date to
	 * @var int
	 */
	protected $rangeStart;

	/**
	 * range end to crop the date to
	 * @var int
	 */
	protected $rangeEnd;

	/**
	 * the regexp to parse the date
	 * @var string
	 */
	protected $compiledPattern = '';

	/**
	 * matches of the regexp
	 * @var array
	 */
	protected $matches = array();

	/**
	 * some lookup arrays
	 * @var array
	 */
	protected $lookup = array(
		'month' => array(
			'jan' => 1,
			'feb' => 2,
			'mar' => 3,
			'apr' => 4,
			'may' => 5,
			'jun' => 6,
			'jul' => 7,
			'aug' => 8,
			'sep' => 9,
			'oct' => 10,
			'nov' => 11,
			'dec' => 12,
			'mär' => 3,
			'mÄr' => 3,
			'mrz' => 3,
			'mae' => 3,
			'mai' => 5,
			'okt' => 10,
			'dez' => 12,
			'january' => 1,
			'february' => 2,
			'march' => 3,
			'april' => 4,
			'june' => 6,
			'july' => 7,
			'august' => 8,
			'september' => 9,
			'october' => 10,
			'november' => 11,
			'december' => 12,
			'januar' => 1,
			'febuar' => 2,
			'februar' => 2,
			'märz' => 3,
			'mÄrz' => 3,
			'juni' => 6,
			'juli' => 7,
			'sebtember' => 9,
			'oktober' => 10,
			'dezember' => 12,
		),
		'weekday' => array(
			'mo' => 1,
			'di' => 2,
			'mi' => 3,
			'do' => 4,
			'fr' => 5,
			'sa' => 6,
			'so' => 7,
			'mon' => 1,
			'die' => 2,
			'mit' => 3,
			'don' => 4,
			'fre' => 5,
			'sam' => 6,
			'son' => 7,
			'montag' => 1,
			'dienstag' => 2,
			'mittwoch' => 3,
			'donnerstag' => 4,
			'freitag' => 5,
			'samstag' => 6,
			'sonntag' => 7,
			'tu' => 2,
			'we' => 3,
			'th' => 4,
			'su' => 7,
			'tue' => 2,
			'wed' => 3,
			'thu' => 4,
			'fri' => 5,
			'sat' => 6,
			'sun' => 7,
			'monday' => 1,
			'tuesday' => 2,
			'wednesday' => 3,
			'thursday' => 4,
			'friday' => 5,
			'saturday' => 6,
			'sunday' => 7,
		)
	);

	/**
	 * translations of strftime patterns to matching regexps
	 * @var unknown_type
	 */
	protected $patterns = array(
		'%a' => '(?<a_weekday>[A-Za-zäöüÄÖÜ]{2,3})', // abbreviated weekday name according to the current locale
		'%A' => '(?<f_weekday>[A-Za-zäöüÄÖÜ]+)', // full weekday name according to the current locale
		'%b' => '(?<a_month>[A-Za-zäöüÄÖÜ]{2,5})', // abbreviated month name according to the current locale
		'%B' => '(?<f_month>[A-Za-zäöüÄÖÜ]+)', // full month name according to the current locale
		'%C' => '(?<century>\d{2})', // century number (the year divided by 100 and truncated to an integer, range 00 to 99)
		'%d' => '(?<day>(?:3[01]|[12][0-9]|0?[1-9]))', //day of the month as a decimal number (range 01 to 31)
		'%e' => '(?<day>(?:3[01]|[12][0-9]|0?[1-9]))', //day of the month as a decimal number, a single digit is preceded by a space (range ' 1' to '31')
		'%g' => '(?<iso_year>\d{2})', // like %G, but without the century.
		'%G' => '(?<iso_year>\d{4})', // The 4-digit year corresponding to the ISO week number (see %V). This has the same format and value as %Y, except that if the ISO week number belongs to the previous or next year, that year is used instead.
		'%H' => '(?<hour24>(?:2[0-3]|1[0-9]|0?[0-9]))', // hour as a decimal number using a 24-hour clock (range 00 to 23)
		'%I' => '(?<hour12>(?:1[0-2]|0?[0-9]))', // hour as a decimal number using a 12-hour clock (range 01 to 12)
		'%j' => '(?<dayofyear>[0-3][0-9][0-9])', // day of the year as a decimal number (range 001 to 366)
		'%m' => '(?<month>(?:1[0-2]|0?[0-9]))', // month as a decimal number (range 01 to 12)
		'%M' => '(?<minute>[0-5][0-9])', // minute as a decimal number
		'%n' => "\n", // newline character
		'%p' => '(?<ampm>[APap]\.?[Mm]\.?)', // UPPER-CASE `AM' or `PM' according to the given time value, or the corresponding strings for the current locale
		'%P' => '(?<ampm>[APap]\.?[Mm]\.?)', // lower-case `am' or `pm' according to the given time value, or the corresponding strings for the current locale
		'%S' => '(?<second>[0-5][0-9])', // second as a decimal number
		'%t' => "\t", // tab character
		'%u' => '(?<m_weekday>[1-7])', // weekday as a decimal number [1,7], with 1 representing Monday
		# %U - week number of the current year as a decimal number, starting with the first Sunday as the first day of the first week
		# %V - The ISO 8601:1988 week number of the current year as a decimal number, range 01 to 53, where week 1 is the first week that has at least 4 days in the current year, and with Monday as the first day of the week. (Use %G or %g for the year component that corresponds to the week number for the specified timestamp.)
		# %W - week number of the current year as a decimal number, starting with the first Monday as the first day of the first week
		'%w' => '(?<s_weekday>[0-6])', // day of the week as a decimal, Sunday being 0
		'%y' => '(?<year>\d{2})', // year as a decimal number without a century (range 00 to 99)
		'%Y' => '(?<year>\d{4})', // year as a decimal number including the century
		'%Z' => '(?<timezone>(GMT|UTC|)([+-]\d{3,4})?)', // ??? time zone offset or name or abbreviation (Operating System dependent)
		'%%' => '%',
		'%s' => '(?<timestamp>\d{6,10})', // unix timestamp
	);

	# '%c' => ''  						// preferred date and time representation for the current locale
	# %D - same as %m/%d/%y
	# %h - same as %b
	# %r - time in a.m. and p.m. notation
	# %R - time in 24 hour notation
	# %T - current time, equal to %H:%M:%S
	# %x - preferred date representation for the current locale without the time
	# %X - preferred time representation for the current locale without the date

	/**
	 * constructs a dateparser with the given date pattern
	 *
	 * @param string $pattern
	 * @param array $defaults
	 */
	public function __construct($pattern, $defaults = null, $offset = 0, $midnightHour = 0, $rangeStart = PHP_INT_MAX, $rangeEnd = PHP_INT_MAX) {
		$this->pattern = trim($pattern);
		$this->offset = $offset;
		$this->midnightHour = $midnightHour;
		$this->rangeStart = $rangeStart == PHP_INT_MAX ? -PHP_INT_MAX : $rangeStart;
		$this->rangeEnd = $rangeEnd;

		if (is_array($defaults)) {
			$this->defaults = $defaults;
		} else if (is_numeric($defaults)) {
			$dateTimes = explode('.', gmstrftime('%d.%m.%Y.%H.%M.%S', $defaults));
			$this->defaults = array(
				'day' => $dateTimes[0],
				'month' => $dateTimes[1],
				'year' => $dateTimes[2],
				'hour' => $dateTimes[3],
				'minute' => $dateTimes[4],
				'second' => $dateTimes[5],
			);
		} else if (preg_match('/(\d{4})-?(\d{2})-?(\d{2})\s*(\d{2})[.:-]?(\d{2})[.:-]?(\d{2})?/', $defaults, $matches)) {
			$this->defaults = array(
				'day' => $matches[3],
				'month' => $matches[2],
				'year' => $matches[1],
				'hour' => $matches[4],
				'minute' => $matches[5],
				'second' => isset($matches[6]) ? $matches[6] : 0,
			);
		} else if (preg_match('/(\d{2})[.:-]?(\d{2})[.:-]?(\d{2})?/', $defaults, $matches)) {
			$this->defaults = array(
				'hour' => $matches[1],
				'minute' => $matches[2],
				'second' => isset($matches[3]) ? $matches[3] : 0,
			);
		}
		/*
		  if (!isset($this->defaults['year'])) {
		  $this->defaults['year'] = strftime('%Y');
		  }
		  if (!isset($this->defaults['month'])) {
		  $this->defaults['month'] = strftime('%m');
		  }

		 */
		if (!isset($this->defaults['second'])) {
			$this->defaults['second'] = 0;
		}

		$this->compiledPattern = $this->compilePattern($this->pattern);
	}

	/**
	 * gets the uncompiled pattern
	 * @return string the date pattern
	 */
	public function getPattern() {
		return $this->pattern;
	}

	/**
	 * gets the default values
	 * @return array of default values
	 */
	public function getDefaults() {
		return $this->defaults;
	}

	/**
	 * gets the compiled patters
	 * @return string pattern compiled as a pure regexp
	 */
	public function getCompiledPattern() {
		return $this->compiledPattern;
	}

	/**
	 * gets the raw regexp matches
	 * @return array
	 */
	public function getMatches() {
		return $this->matches;
	}

	/**
	 * compiles the pattern
	 * @param string $pattern pattern in strftime / regexp syntax
	 * @return string pattern compiled as a pure regexp
	 */
	protected function compilePattern($pattern) {
		return '#' . strtr($pattern, $this->patterns) . '#';
	}

	/**
	 * matches the string for the date pattern
	 *
	 * @param string $string
	 */
	public function match($string) {
		$this->date = false;
		
		if ($string instanceof \DateTime) {
			$this->date = $string;
			return true;
		}
		if (!preg_match($this->compiledPattern, $string, $matches)) {
			return false;
		}
		$this->matches = $matches;

		if (!$this->getDate()) {
			return false;
		}

		return true;
	}

	/**
	 * returns the parsed date as a timestamp
	 *
	 * @return \DateTime
	 */
	public function getDate() {
		if ($this->date) {
			return $this->date;
		}
		$day = false;
		$month = false;
		$year = false;
		$hour = false;
		$timeFound = false;

		if (!empty($this->matches['a_month'])) {
			$month = $this->lookup($this->matches['a_month'], 'month');
			if ($month === false) {
				return false;
			}
		} else if (!empty($this->matches['f_month'])) {
			$month = $this->lookup($this->matches['f_month'], 'month');
			if ($month === false) {
				return false;
			}
		} else if (!empty($this->matches['month'])) {
			$month = (int) $this->matches['month'];
		}


		if (!empty($this->matches['hour24'])) {
			$hour = (int) $this->matches['hour24'];
			$timeFound = true;
		} else if (!empty($this->matches['hour12']) && !empty($this->matches['ampm'])) {
			$hour = (int) $this->matches['hour12'] + (strtolower($this->matches['ampm']) == 'pm' ? 12 : 0);
			$timeFound = true;
		}

		if (!empty($this->matches['year'])) {
			$year = intval($this->matches['year']);
			$year = $year < 100 ? ($year < 70 ? $year + 2000 : $year + 1900) : $year;
		}
		if (!empty($this->matches['day'])) {
			$day = (int) $this->matches['day'];
		}

		// no regluar date given, but weekday
		$weekDay = $this->lookupWeekday($this->matches);

		if ($weekDay && !($day && $month) && $this->rangeStart != -PHP_INT_MAX) {
			$weekDayStart = strftime('%u', $this->rangeStart);
			$skipDays = (7 + $weekDay - $weekDayStart) % 7;
			list($day, $month, $year) = explode('|', strftime('%d|%m|%Y', $this->rangeStart + $skipDays * 24 * 60 * 60));
		}

		$hour = $hour ? $hour : (int) $this->defaults['hour'];
		$minute = isset($this->matches['minute']) ? (int) $this->matches['minute'] : (int) $this->defaults['minute'];
		$second = isset($this->matches['second']) ? (int) $this->matches['second'] : (int) $this->defaults['second'];

		$day = $day ? (int) $day : (int) @$this->defaults['day'];
		// world <-> computer mapping of perceived midnight:
		// people think new day starts after going to bed,
		// computer thinks new day starts at midnight
		if ($hour < $this->midnightHour) {
			$day++;
		}

		$month = $month ? (int) $month : (int) @$this->defaults['month'];
		$year = $year ? (int) $year : (int) @$this->defaults['year'];

		if (array_key_exists('timestamp', $this->matches)) {
			$this->date = new \DateTime('@' . $this->matches['timestamp']);
		} else if (!$year) {
			$now = \App::instance()->date();
			$thisYear = clone \App::instance()->date();
			$thisYear->setDate(intval(strftime('%Y')), intval($month), intval($day));
			$nextYear = clone \App::instance()->date();
			$nextYear->setDate(1 + intval(strftime('%Y')), intval($month), intval($day));

			if ($this->rangeStart != -PHP_INT_MAX && $thisYear->getTimestamp() < $this->rangeStart) {
				$this->date = $nextYear;
			} else if ($this->rangeEnd != PHP_INT_MAX && $thisYear->getTimestamp() < $this->rangeEnd) {
				$this->date = $thisYear;
			} else {
				$thisYearCloserThanNextYear = abs($thisYear->getTimestamp() - $now->getTimestamp()) < abs($nextYear->getTimestamp() - $now->getTimestamp());
				$this->date = $thisYearCloserThanNextYear ? $thisYear : $nextYear;
			}
		} else {
			$this->date = clone \App::instance()->date();
			$this->date->setDate(intval($year), intval($month), intval($day));
		}

		if ($timeFound) {
			$this->date->setTime(intval($hour), intval($minute), intval($second));
		} else if (isset($this->defaults['hour']) || isset($this->defaults['minute'])) {
			$this->date->setTime(
				isset($this->defaults['hour']) ? $this->defaults['hour'] : 0, isset($this->defaults['minute']) ? $this->defaults['minute'] : 0, isset($this->defaults['second']) ? $this->defaults['second'] : 0
			);
		}
		return $this->date;
	}

	/**
	 * looks up the key in specified table
	 * @throws Exception when value not found
	 * @param $key
	 * @param $table
	 * @return int|false looked up value or false when value not found
	 */
	protected function lookup($key, $table = 'month') {
		if (!array_key_exists($table, $this->lookup)) {
			throw new Exception('no lookuptable "' . $table . '"');
		}

		$lookup = trim(strtolower($key));
		if (!array_key_exists($lookup, $this->lookup[$table])) {
			return false;
		}

		return (int) $this->lookup[$table][$lookup];
	}

	protected function lookupWeekday($matches) {
		$weekDay = null;
		if (array_key_exists('a_weekday', $matches)) {
			$weekDay = $this->lookup($matches['a_weekday'], 'weekday');
		} else if (array_key_exists('f_weekday', $matches)) {
			$weekDay = $this->lookup($matches['f_weekday'], 'weekday');
		} else if (array_key_exists('m_weekday', $matches)) {
			$weekDay = intval($matches['m_weekday']);
		} else if (array_key_exists('s_weekday', $matches)) {
			$weekDay = intval($matches['s_weekday']);
			$weekDay = $weekDay === 0 ? 7 : 0;
		}
		return $weekDay;
	}

}

?>
