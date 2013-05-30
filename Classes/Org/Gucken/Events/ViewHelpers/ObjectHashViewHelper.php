<?php
namespace Org\Gucken\Events\ViewHelpers;

use TYPO3\Flow\Annotations as Flow;

/**
 */
class ObjectHashViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {
	/**
	 * Returns an uniqe hash for an object
	 *
	 * @param object $object
	 * @return string
	 */
	protected function render($object) {
		return sha1(var_export($object,true));
	}

}
?>
