<?php

namespace ToDate\Condition;

class MonthCondition extends FeatureInSetCondition implements DateConditionInterface  {
		
	/**
	 *
	 * @param array|string $days 
	 */
	public function __construct($months) {
		parent::__construct('n',$months);
	}
	
	public function __toString() {
		return self::formatSet('Month', $this->set);
	}

}

?>