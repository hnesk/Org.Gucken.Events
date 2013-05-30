<?php
namespace Org\Gucken\Events\Domain\Validator;
                                             
/**
 * A Validator for Date expressions
 *
 */
class DateConditionValidator extends \TYPO3\Flow\Validation\Validator\AbstractValidator {	

	/**
	 * Checks if the given value is a date expression.
	 *
	 * If at least one error occurred, the result is FALSE and any errors can
	 * be retrieved through the getErrors() method.
	 *
	 * @param mixed $value The value that should be validated
	 * @return void
	 */
	public function isValid($value) {
		if ($value instanceof \ToDate\Condition\ErrorCondition) {
			$this->addError((string)$value, 1328970008);
		}
	}

}
?>