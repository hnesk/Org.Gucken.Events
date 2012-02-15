<?php

namespace ToDate\Condition;

class ErrorCondition extends AbstractDateCondition implements DateConditionInterface  {

	protected $errorMessage;
	
	protected $originalExpression;
	
	public function __construct($errorMessage, $orginalExpression) {
		$this->errorMessage = $errorMessage;
		$this->originalExpression = $orginalExpression;			
	}
	
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
		return 'Error In:'.$this->originalExpression.', : '.$this->errorMessage;
	}	
}

?>