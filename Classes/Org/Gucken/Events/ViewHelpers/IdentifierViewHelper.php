<?php
namespace Org\Gucken\Events\ViewHelpers;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\PersistenceManagerInterface;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 */
class IdentifierViewHelper extends AbstractViewHelper {
	
	/**
	 *
	 * @var PersistenceManagerInterface
	 */
	protected $persistenceManager;
	
	/**
	 * Injects the Flow Persistence Manager
	 *
	 * @param PersistenceManagerInterface $persistenceManager
	 * @return void
	 */
	public function injectPersistenceManager(PersistenceManagerInterface $persistenceManager) {
		$this->persistenceManager = $persistenceManager;
	}
	/**
	 * Set the template variable given as $as to the current account
	 *
	 * @param object $object
	 * @return string
	 */
	protected function render($object) {
		if (is_null($object)) {
			return '';
		} else {
			return $this->persistenceManager->getIdentifierByObject($object);
		}
	}

}
?>
