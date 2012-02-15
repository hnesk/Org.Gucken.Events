<?php

namespace ToDate\Condition;

class DateCondition extends AbstractDateCondition implements DateConditionInterface  {

	/**
	 * @var $date
	 */
	protected $date;
	
	public function __construct(\DateTime $date) {
		$this->date = self::normalizeDate($date);
	}
	
	/**
	 *
	 * @param \DateTime $date
	 * @return boolean
	 */
	public function contains(\DateTime $date) {
		return $this->date == self::normalizeDate($date);
	}
	
	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return 'Date = '.$this->date->format('Y-m-d');
	}	
}

?>