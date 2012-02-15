<?php

namespace ToDate\Condition;

class DayOfWeekCondition extends AbstractDayOfWeekCondition implements DateConditionInterface {

	/**
	 * Stores select days as a fast to check lookup table
	 * @var array
	 */
	protected $daysOfWeek;

	/**
	 *
	 * @param array|int|string $dayOfWeek
	 */
	public function __construct($daysOfWeek) {
		parent::__construct();
		$this->daysOfWeek = self::prepareWeekdays($daysOfWeek);		
	}
	

	/**
	 *
	 * @param \DateTime $date
	 * @return boolean
	 */
	public function contains(\DateTime $date) {
		return isset($this->daysOfWeek[$date->format('N')]);
	}

	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return 'DayOfWeek = ' . implode(',',$this->getSymbolicWeekDays($this->daysOfWeek));
	}

}

?>