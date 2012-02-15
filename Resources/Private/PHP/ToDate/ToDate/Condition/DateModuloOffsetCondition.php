<?php

namespace ToDate\Condition;

class DateModuloOffsetCondition extends AbstractDateCondition implements DateConditionInterface  {

	/**
	 * @var $date
	 */
	protected $date;
	
	/**
	 *
	 * @var int
	 */
	protected $offsetInDays;

	/**
	 *
	 * @param \DateTime $date
	 * @param int $offsetInDays 
	 */
	public function __construct(\DateTime $date, $offsetInDays) {
		$this->date = self::normalizeDate($date);
		$this->offsetInDays = $offsetInDays;
	}
	
	/**
	 *
	 * @param \DateTime $date
	 * @return boolean
	 */
	public function contains(\DateTime $date) {
		$days = $this->date->diff(self::normalizeDate($date))->days;
		return $days % $this->offsetInDays === 0;
	}
	
	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return 'DateModulo = '.$this->date->format('Y-m-d').'%'.$this->offsetInDays.'d';
	}	
}

?>