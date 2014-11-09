<?php
namespace Org\Gucken\Events\Domain\Validator;

use TYPO3\Flow\Validation\Validator\AbstractValidator;

/**
 * A Validator for Date expressions
 *
 */
class FutureValidator extends AbstractValidator
{

    /**
     * Checks if the given value is a date expression.
     *
     * If at least one error occurred, the result is FALSE and any errors can
     * be retrieved through the getErrors() method.
     *
     * @param  mixed $value The value that should be validated
     * @return void
     */
    public function isValid($value)
    {
        /* @var $value \DateTime */
        $comparand = new \DateTime();
        $comparand->setTime(0, 0, 0);

        if ($value->getTimestamp() - $comparand->getTimestamp() < 0) {
            $this->addError('Datum sollte in der Zukunft liegen', 1333804165);
        }
    }

}
