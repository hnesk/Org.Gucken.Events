<?php
namespace Org\Gucken\Events\ViewHelpers;

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 */
class IdentifierViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {
	
	/**
	 *
	 * @var \TYPO3\FLOW3\Persistence\PersistenceManagerInterface 
	 */
	protected $persistenceManager;
	
	/**
	 * Injects the FLOW3 Persistence Manager
	 *
	 * @param \TYPO3\FLOW3\Persistence\PersistenceManagerInterface $persistenceManager
	 * @return void
	 */
	public function injectPersistenceManager(\TYPO3\FLOW3\Persistence\PersistenceManagerInterface $persistenceManager) {
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
