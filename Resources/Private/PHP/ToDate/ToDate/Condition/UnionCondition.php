<?php

namespace ToDate\Condition;

class UnionCondition extends AbstractLogicCondition implements DateConditionInterface {

	protected static $glue = 'OR';
	
	protected function evaluate($a, $b) {
		return $a || $b;
	}

}

?>
