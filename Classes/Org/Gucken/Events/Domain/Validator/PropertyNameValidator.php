<?php
namespace Org\Gucken\Events\Domain\Validator;


use TYPO3\Flow\Annotations as Flow;

/**
 * A Validator for property names of a class
 * @Flow\Scope("prototype")
 */
class PropertyNameValidator extends \TYPO3\Flow\Validation\Validator\AbstractValidator {

    protected $supportedOptions = array(
        'class' => array(NULL, 'Validate the property to be a member of this class', 'string', TRUE)
    );

	/**
	 *
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 */
	protected $reflectionService;

	/**
	 *
	 * @param \TYPO3\Flow\Reflection\ReflectionService $reflectionService
	 */
	public function injectReflectionService(\TYPO3\Flow\Reflection\ReflectionService $reflectionService) {
		$this->reflectionService = $reflectionService;
	}

	/**
	 * Checks if the given value matches the specified regular expression.
	 * Note: a value of NULL or empty string ('') is considered valid
	 *
	 * @param mixed $value The value that should be validated
	 * @return void
	 * @api
	 */
	protected function isValid($value) {
		if (!isset($this->options['class'])) {
			throw new \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException('"class" in PropertyNameValidator was empty.', 1333376577);
		}

		$className = $this->options['class'];

		$propertyNames = $this->reflectionService->getClassPropertyNames($className);

		$result = in_array($value, $propertyNames);
		if (!$result) {
			$this->addError('"%1$s" is not a property of "%2$s"', 1333376882, array($value, $className));
		}

	}

}
?>