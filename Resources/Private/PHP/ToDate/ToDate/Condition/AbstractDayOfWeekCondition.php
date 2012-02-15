<?php

namespace ToDate\Condition;

abstract class AbstractDayOfWeekCondition extends AbstractDateCondition implements DateConditionInterface {

	const MON = 1;
	const TUE = 2;
	const WED = 3;
	const THU = 4;
	const FRI = 5;
	const SAT = 6;
	const SUN = 7;

	/**
	 *
	 * @var array
	 */
	static $LOOKUP = array(
		'MON' => self::MON,
		'TUE' => self::TUE,
		'WED' => self::WED,
		'THU' => self::THU,
		'FRI' => self::FRI,
		'SAT' => self::SAT,
		'SUN' => self::SUN
	);
	
	/**
	 * reverse lookup
	 * @var array
	 */
	static $PUKOOL = array();
	
	public function __construct() {
		if (!self::$PUKOOL) {
			self::$PUKOOL = array_flip(self::$LOOKUP);
		}		
	}

	/**
	 * build an array of wekday number => flag 
	 * 
	 * Input can be anything, an array or single value or comma seperated string of
	 * integers or symbolic weekday names, eg:
	 * 
	 * array(1,3,5) => Monday, Wednesday, Friday => array(1=>true, 3=>true, 5=> true)
	 * AbstractDayOfWeekCondition::TUE => Tuesday => array(2=>true)
	 * "MON,TUE" => Monday, Tuesday => array(1 => true, 2 => true)
	 * array('MON','THU') => array(1 => true, 4 => true)
	 * 
	 * @param $daysOfWeek
	 * @return array
	 * @throws \InvalidArgumentException 
	 */
	protected static function prepareWeekdays($daysOfWeek) {
		$daysOfWeek = self::toArray($daysOfWeek);
		$result = array();
		foreach ($daysOfWeek as $dayOfWeek) {
			$result[self::lookupWeekday($dayOfWeek)] = true;
		}
		return $result;
	}

	/**
	 *
	 * @param string|int $dayOfWeek
	 * @return int
	 * @throws \InvalidArgumentException 
	 */
	public static function lookupWeekday($dayOfWeek) {
		$dayOfWeek = trim($dayOfWeek);
		if (!is_numeric($dayOfWeek)) {
			if (!isset(self::$LOOKUP[$dayOfWeek])) {
				throw new \InvalidArgumentException('Day of needs to be one of MON,TUE,WED,THU,FRI,SAT,SUN or one of the class constants, but was: '.$dayOfWeek);
			} else {
				$dayOfWeek = self::$LOOKUP[$dayOfWeek];
			}
		} else {
			if ($dayOfWeek < self::MON || $dayOfWeek > self::SUN) {
				throw new \InvalidArgumentException('Day of week needs to be one of DayOfWeekCondition::MON, ..., but was: '.$dayOfWeek);
			}
		}
		return $dayOfWeek;
	}

	/**
	 * Translates numeric to Symbolic Weekday names
	 * @param array $daysOfWeek
	 * @return array
	 */
	protected static function getSymbolicWeekDays($daysOfWeek) {
		return array_intersect_key(self::$PUKOOL, $daysOfWeek);
	}
	
}

?>
