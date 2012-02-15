<?php

namespace ToDate\Condition;

interface DateConditionInterface {
	/**
	 * @param \DateTime 
	 * @return boolean
	 */
	public function contains(\DateTime $date);

	/**
	 * @return string
	 */
	public function __toString();
	
}

?>
