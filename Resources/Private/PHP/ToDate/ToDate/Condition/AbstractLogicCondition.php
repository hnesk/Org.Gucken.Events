<?php

namespace ToDate\Condition;

abstract class AbstractLogicCondition extends AbstractDateCondition implements DateConditionInterface {
	/**
	 *
	 * @var DateConditionInterface
	 */
	protected $condition1;

	/**
	 *
	 * @var DateConditionInterface
	 */	
	protected $condition2;
	
	protected static $glue = 'XXX';
	
	/**
	 *
	 * @param DateConditionInterface $condition1
	 * @param DateConditionInterface $condition2 
	 */
	public function __construct($condition1, $condition2) {
		$this->condition1 = $condition1;
		$this->condition2 = $condition2;
	}

	abstract protected function evaluate($a, $b);
	
	/**
	 *
	 * @param \DateTime $date
	 * @return boolean
	 */
	public function contains(\DateTime $date) {
		return $this->evaluate($this->condition1->contains($date),$this->condition2->contains($date));
	}

	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return '('.$this->condition1->__toString() . ') '.  static::$glue.' (' . $this->condition2->__toString().')';
	}
	
}

?>
