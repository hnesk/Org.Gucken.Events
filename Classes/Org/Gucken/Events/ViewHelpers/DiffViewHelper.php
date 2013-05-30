<?php
namespace Org\Gucken\Events\ViewHelpers;

use TYPO3\Flow\Annotations as Flow;
use Org\Gucken\Events\Utility;

/**
 */
class DiffViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {
	
	/**
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 */
	protected $localReflectionService;
	
	/**
	 *
	 * @param \TYPO3\Flow\Reflection\ReflectionService $reflectionService 
	 */
	public function injectLocalReflectionService(\TYPO3\Flow\Reflection\ReflectionService $reflectionService) {
		$this->localReflectionService = $reflectionService;
	}
	
	/**
	 * Render differences between old & new
	 *
	 * @param object $old
	 * @param object $new
	 * @param string $ignoredProperties
	 * @return string
	 */
	protected function render($old, $new, $ignoredProperties = '') {
		$ignoredProperties = array_map(function ($s) {return trim($s);},explode(',',$ignoredProperties));
		if ($old === $new) {
			return '';
		} else if (get_class($old) !== get_class($new)) {
			return sprintf('Completely different classes: %s and %s',get_class($old),get_class($new));			
		} else {
			$properties = $this->localReflectionService->getClassPropertyNames(get_class($old));
			$lines = array();
			foreach ($properties as $property) {
				if (in_array($property, $ignoredProperties)) {
					continue;
				}
				$oldValue = $this->propertyAsString($old, $property);
				$newValue = $this->propertyAsString($new, $property);
				
				if ($oldValue !== $newValue) {
					$lines[] = '<dt>'.$property.'</dt><dd>'.Utility\Algorithms::htmlDiff($oldValue, $newValue).'</dd>';					
				}
			}
			return '<dl>'.implode("\n", $lines).'</dl>';
		}
	}

	protected function propertyAsString($object,$property) {
		$value = \TYPO3\Flow\Reflection\ObjectAccess::getPropertyInternal($object, $property, false, $exists);
		if (is_object($value)) {
			if ($value instanceof \DateTime) {
				return $value->format('d.M.Y H:i');
			} else {
				return (string)$value;
			} 
		} else {
			return (string)$value;
		}
	}
}
?>
