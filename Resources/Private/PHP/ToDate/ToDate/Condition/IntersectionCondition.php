<?php

namespace ToDate\Condition;

class IntersectionCondition extends AbstractLogicCondition implements DateConditionInterface {

	protected static $glue = 'AND';
	
	protected function evaluate($a, $b) {
		return $a && $b;
	}

}

?>
