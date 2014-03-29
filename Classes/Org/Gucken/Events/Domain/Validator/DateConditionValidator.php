<?php
namespace Org\Gucken\Events\Domain\Validator;
use ToDate\Condition\ErrorCondition;
use TYPO3\Flow\Validation\Validator\AbstractValidator;

/**
 * A Validator for Date expressions
 *
 */
class DateConditionValidator extends AbstractValidator {

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
		if ($value instanceof ErrorCondition) {
			$this->addError((string)$value, 1328970008);
		}
	}

}
?>