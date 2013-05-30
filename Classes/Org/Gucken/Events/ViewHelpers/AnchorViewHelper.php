<?php
namespace Org\Gucken\Events\ViewHelpers;

use TYPO3\Flow\Annotations as Flow;

/**
 */
class AnchorViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

	protected $tagName = 'a';

	
	/**
	 * Initialize the arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerUniversalTagAttributes();
	}	

	/**
	 * Renders an a anchor tag
	 *
	 * @param $target string
	 * @return string
	 */
	protected function render($target) {
		$this->tag->addAttribute('id', 'd'.$target);
		$this->tag->addAttribute('name', 'd'.$target);
		$this->tag->addAttribute('href', '#d'.$target);
		
		$content = $this->renderChildren();
		$this->tag->setContent($content ? $content : '#');

		return $this->tag->render();

	}

}
?>
