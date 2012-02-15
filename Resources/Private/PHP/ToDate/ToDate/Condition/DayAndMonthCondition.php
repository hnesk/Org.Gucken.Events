<?php

namespace ToDate\Condition;

class DayAndMonthCondition extends FeatureInSetCondition implements DateConditionInterface  {
	
	/**
	 *
	 * @param array|string $days 
	 */
	public function __construct($day,$month) {
		parent::__construct('j/n', (int)$day.'/'.(int)$month);
	}
	
	public function __toString() {
		return self::formatSet('DayAndMonth', $this->set);
	}

	
}

?>