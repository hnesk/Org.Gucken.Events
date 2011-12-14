<?php
namespace Org\Gucken\Events\Aspect\Pointcut;

/*                                                                        *
 * This script belongs to the FLOW3 framework.                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * A method filter which fires on methods annotated with deprecated
 * @FLOW3\Scope("singleton")
 * @FLOW3\Proxy(false)
 */
class PointcutMethodDeprecatedFilter implements \TYPO3\FLOW3\AOP\Pointcut\PointcutFilterInterface {

	/**
	 * @var \TYPO3\FLOW3\Reflection\ReflectionService
	 */
	protected $reflectionService;


	/**
	 * Injects the reflection service
	 *
	 * @param \TYPO3\FLOW3\Reflection\ReflectionService $reflectionService The reflection service
	 * @return void
	 */
	public function injectReflectionService(\TYPO3\FLOW3\Reflection\ReflectionService $reflectionService) {
		$this->reflectionService = $reflectionService;
	}

	/**
	 * Checks if the specified method matches with the method annotation filter pattern
	 *
	 * @param string $className Name of the class to check against - not used here
	 * @param string $methodName Name of the method
	 * @param string $methodDeclaringClassName Name of the class the method was originally declared in
	 * @param mixed $pointcutQueryIdentifier Some identifier for this query - must at least differ from a previous identifier. Used for circular reference detection - not used here
	 * @return boolean TRUE if the class matches, otherwise FALSE
	 */
	public function matches($className, $methodName, $methodDeclaringClassName, $pointcutQueryIdentifier) {
		if ($methodDeclaringClassName === NULL || !$this->reflectionService->hasMethod($methodDeclaringClassName, $methodName)) {
			return FALSE;
		} else if ($this->isClassTaggedWith($methodDeclaringClassName, 'deprecated')) {
			return TRUE;
		} else if ($this->isMethodTaggedWith($methodDeclaringClassName, $methodName, 'deprecated')) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Checks if method is tagged with the given tag
	 *
	 * @param string $className Name of the class containing the method
	 * @param string $methodName Name of the method to analyze
	 * @param string $tag Tag to check for
	 * @return boolean 
	 */
	protected function isMethodTaggedWith($className, $methodName, $tag) {
		$method = new \TYPO3\FLOW3\Reflection\MethodReflection(trim($className, '\\'), $methodName);
		$tagsValues = $method->getTagsValues();
		return isset($tagsValues[$tag]);
	}
	
	/**
	 * Checks if class is tagged with the given tag
	 *
	 * @param string $className Name of the class containing the method
	 * @param string $methodName Name of the method to analyze
	 * @param string $tag Tag to check for
	 * @return boolean 
	 */
	protected function isClassTaggedWith($className, $tag) {
		$class = new \TYPO3\FLOW3\Reflection\ClassReflection(trim($className, '\\'));
		$tagsValues = $class->getTagsValues();
		return isset($tagsValues[$tag]);
	}
	
	

	/**
	 * Returns TRUE if this filter holds runtime evaluations for a previously matched pointcut
	 *
	 * @return boolean TRUE if this filter has runtime evaluations
	 */
	public function hasRuntimeEvaluationsDefinition() {
		return FALSE;
	}

	/**
	 * Returns runtime evaluations for the pointcut.
	 *
	 * @return array Runtime evaluations
	 */
	public function getRuntimeEvaluationsDefinition() {
		return array();
	}

}
?>