<?php

namespace ToDate\Iterator;

use ToDate\Condition;

class ConditionIterator extends \FilterIterator {

	/**
	 *
	 * @var \Condition\DateConditionInterface
	 */
	protected $condition;
	
	/**
	 *
	 * @param \Iterator $iterator
	 * @param type $condition 
	 */
	public function __construct(\Iterator $iterator, $condition) {
		if (is_string($condition)) {
			$p = new \ToDate\Parser\FormalDateExpressionParser($condition);
			$condition  = $p->parse();
		}
		if (!$condition instanceof Condition\DateConditionInterface) {
			throw new \InvalidArgumentException("Condition needs to implement DateConditionInterface or must be a parsable string");
		}		

		$this->condition = $condition;
		parent::__construct($iterator);	
	}
	
	public function accept() {
		$current = $this->getInnerIterator()->current();
		return $this->condition->contains($current);
	}

}

?>
