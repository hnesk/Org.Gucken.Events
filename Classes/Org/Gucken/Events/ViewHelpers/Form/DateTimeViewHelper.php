<?php
namespace Org\Gucken\Events\ViewHelpers\Form;

use TYPO3\Flow\Annotations as Flow;


class DateTimeViewHelper extends \TYPO3\Fluid\ViewHelpers\Form\AbstractFormFieldViewHelper {

	/**
	 * @var string
	 */
	protected $tagName = 'input';

	/**
	 *
	 * @var \TYPO3\Flow\Property\PropertyMapper
	 * @Flow\Inject
	 */
	protected $propertyMapper;


	/**
	 *
	 * @var \TYPO3\Fluid\Core\ViewHelper\TagBuilder
	 * @Flow\Inject
	 */
	protected $tagDateHidden;


	/**
	 *
	 * @var \TYPO3\Fluid\Core\ViewHelper\TagBuilder
	 * @Flow\Inject
	 */
	protected $tagTime;

	/**
	 *
	 * @var \TYPO3\Fluid\Core\ViewHelper\TagBuilder
	 * @Flow\Inject
	 */
	protected $tagTimeHidden;

	/**
	 * Initialize the arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerTagAttribute('disabled', 'string', 'Specifies that the input element should be disabled when the page loads');
		$this->registerTagAttribute('maxlength', 'int', 'The maxlength attribute of the input field (will not be validated)');
		$this->registerTagAttribute('readonly', 'string', 'The readonly attribute of the input field');
		$this->registerTagAttribute('size', 'int', 'The size of the input field');
		$this->registerArgument('errorClass', 'string', 'CSS class to set if there are errors for this view helper', FALSE, 'f3-form-error');
		$this->registerUniversalTagAttributes();
	}

	/**
	 * Renders the textfield.
	 *
	 * @param boolean $required If the field is required or not
	 * @param string $type The field type, e.g. "text", "email", "url" etc.
	 * @param string $placeholder A string used as a placeholder for the value to enter
	 * @param string $dateFormat
	 * @param string $timeFormat
	 * @return string
	 * @api
	 */
	public function render($required = NULL, $type = 'date', $placeholder = NULL, $dateFormat = 'd.m.y', $timeFormat = 'H:i') {
		$name = $this->getName();
		$this->registerFieldNameForFormTokenGeneration($name);

		$value = $this->getValue();

		if (!($value instanceof \DateTime)) {
			$value = $this->propertyMapper->convert($value, 'DateTime');

		}

		if ($placeholder !== NULL) {
			$this->tag->addAttribute('placeholder', $placeholder);
		}

		if ($value !== NULL) {
			$this->tag->addAttribute('value', $value->format($dateFormat));
		}

		if ($required !== NULL) {
			$this->tag->addAttribute('required', 'required');
		}

		$class = (string)$this->arguments['class'];

		$this->setErrorClassAttribute();

		$this->tag->addAttribute('type', $type);
		$this->tag->addAttribute('name', $name.'[date]');
		$this->tag->addAttribute('class', 'span1 datepicker');

		$this->tagDateHidden->setTagName('input');
		$this->tagDateHidden->addAttribute('type', 'hidden');
		$this->tagDateHidden->addAttribute('name', $name.'[dateFormat]');
		$this->tagDateHidden->addAttribute('value', $dateFormat);

		$this->tagTime->setTagName('select');
		$this->tagTime->addAttribute('name', $name.'[time]');
		$this->tagTime->addAttribute('class', 'span1');

		$this->tagTime->setContent($this->renderOptions($this->generateTimeOptions($timeFormat), $value->format($timeFormat)));

		$this->tagTimeHidden->setTagName('input');
		$this->tagTimeHidden->addAttribute('type', 'hidden');
		$this->tagTimeHidden->addAttribute('name', $name.'[timeFormat]');
		$this->tagTimeHidden->addAttribute('value', $timeFormat);


		return $this->tag->render().
			$this->tagDateHidden->render().
			' / '.
			$this->tagTime->render().
			$this->tagTimeHidden->render();
	}


	protected function renderOptions($options, $selected) {
		$optionString = '';
		foreach ($options as $value => $label) {
			$optionString .= '<option value="'.htmlspecialchars($value, ENT_QUOTES, 'UTF-8').'"'.($value == $selected ? ' selected="selected"' : '').'>'.htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'</option>'.PHP_EOL;
		}
		return $optionString;
	}

	/**
	 *
	 * @param string $format
	 * @param string $step
	 * @return array
	 */
	protected function generateTimeOptions($format, $step = '+30 minutes') {
		$values = array();
		$date = new \DateTime('1970-01-01T00:00');
		while ($date->format('d') == 1) {
			$values[$date->format($format)] = $date->format($format);
			$date->modify('+30 minutes');
		}
		return $values;
	}

}


?>
