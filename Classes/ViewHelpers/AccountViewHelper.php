<?php
namespace Org\Gucken\Events\ViewHelpers;

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 */
class AccountViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {
	/**
	 * @var \TYPO3\FLOW3\Security\Context
	 * @FLOW3\Inject
	 */
	protected $securityContext;

	/**
	 * Set the template variable given as $as to the current account
	 *
	 * @param $as string
	 * @return string
	 */
	protected function render($as = 'account') {
		$this->templateVariableContainer->add($as, $this->securityContext->getAccount());
		$output = $this->renderChildren();
		$this->templateVariableContainer->remove($as);
		return $output;
	}

}
?>
