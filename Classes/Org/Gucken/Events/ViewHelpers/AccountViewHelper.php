<?php
namespace Org\Gucken\Events\ViewHelpers;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 */
class AccountViewHelper extends AbstractViewHelper
{
    /**
     * @var \TYPO3\Flow\Security\Context
     * @Flow\Inject
     */
    protected $securityContext;

    /**
     * Set the template variable given as $as to the current account
     *
     * @param $as string
     * @return string
     */
    protected function render($as = 'account')
    {
        $this->templateVariableContainer->add($as, $this->securityContext->getAccount());
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove($as);

        return $output;
    }

}
