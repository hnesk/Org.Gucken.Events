<?php

namespace ToDate\Condition;

class NotCondition extends AbstractDateCondition implements DateConditionInterface {
	/**
	 *
	 * @var DateConditionInterface
	 */
	protected $condition;
	
	/**
	 *
	 * @param DateConditionInterface $condition
	 */
	public function __construct($condition) {
		$this->condition = $condition;
	}

	public function contains(\DateTime $date) {
		return !$this->condition->contains($date);
	}

	public function __toString() {
		return 'NOT('.$this->condition->__toString() . ')';
	}
}


?>
