<?php

namespace ToDate\Condition;

class YearCondition extends FeatureInSetCondition implements DateConditionInterface  {
		
	/**
	 *
	 * @param array|string $days 
	 */
	public function __construct($years) {
		parent::__construct('Y',$years);
	}
	
	public function __toString() {
		return self::formatSet('Year', $this->set);
	}
}

?>