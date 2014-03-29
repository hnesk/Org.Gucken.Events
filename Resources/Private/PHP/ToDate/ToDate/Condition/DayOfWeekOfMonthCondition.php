<?php

namespace ToDate\Condition;

class DayOfWeekOfMonthCondition extends AbstractDayOfWeekCondition implements DateConditionInterface {

	const FIRST = 1;
	const SECOND = 2;
	const THIRD = 3;
	const FOURTH = 4;
	const FIFTH = 5;
	
	const LAST = -1;
	const ULTIMATE = -1;
	const SECOND_LAST = -2;
	const PENULTIMATE = -2;
	const THIRD_LAST = -3;
	const ANTEPENULTIMATE = -3;
	const FOURTH_LAST = -4;
	const FIFTH_LAST = -5;	
	
	/**
	 * Stores select days as a fast to check lookup table
	 * @var int
	 */
	protected $dayOfWeek;
	
	/**
	 *
	 * @var int
	 */
	protected $weeksOfMonth;

	/**
	 *
	 * @param int week of month
	 * @param int|string $dayOfWeek
	 */
	public function __construct($weekOfMonth, $dayOfWeek) {
		parent::__construct();
		$this->weeksOfMonth = self::toArray($weekOfMonth);
		$this->dayOfWeek = self::lookupWeekday($dayOfWeek);
	}
	

	/**
	 *
	 * @param \DateTime $date
	 * @return boolean
	 */
	public function contains(\DateTime $date) {
		$date = self::normalizeDate($date);
		if ($this->dayOfWeek != $date->format('N')) {
			return false;
		}
		
		foreach ($this->weeksOfMonth as $weekOfMonth) {
			if ($weekOfMonth > 0) {
				// starting from the beginning of the month
				$anchorDateString =  $date->format('Y-m-01');
			} else {
				// starting from the end of the next month
				$anchorDateString = $date->format('Y').'-'.(1+intval($date->format('m'))).'-01';
			}
			$testDate = new \DateTime($anchorDateString);
			$testDate->modify($weekOfMonth . ' '. self::$PUKOOL[$this->dayOfWeek]);
			if ($date == $testDate) {
				return true;
			}
		}
		
		return false;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return 'DayOfWeekOfMonth = ' . implode(',', $this->weeksOfMonth).self::$PUKOOL[$this->dayOfWeek];
	}

}

?>