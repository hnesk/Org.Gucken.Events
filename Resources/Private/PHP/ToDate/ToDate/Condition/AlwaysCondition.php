<?php

namespace ToDate\Condition;

class AlwaysCondition extends AbstractDateCondition implements DateConditionInterface  {

	
	/**
	 *
	 * @param \DateTime $date
	 * @return boolean
	 */
	public function contains(\DateTime $date) {
		return true;
	}
	
	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return 'Always';
	}	
}

?>