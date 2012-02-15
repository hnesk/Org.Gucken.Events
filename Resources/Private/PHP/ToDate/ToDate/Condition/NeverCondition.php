<?php

namespace ToDate\Condition;

class NeverCondition extends AbstractDateCondition implements DateConditionInterface  {

	
	/**
	 *
	 * @param \DateTime $date
	 * @return boolean
	 */
	public function contains(\DateTime $date) {
		return false;
	}
	
	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return 'Never';
	}	
}

?>