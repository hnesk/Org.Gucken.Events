<?php

namespace ToDate\Condition;

class DayOfMonthCondition extends FeatureInSetCondition implements DateConditionInterface  {
	
	/**
	 *
	 * @param array|string $days 
	 */
	public function __construct($days) {
		parent::__construct('j', $days);
	}
	
	public function __toString() {
		return self::formatSet('DayOfMonth', $this->set);
	}

	
}

?>