<?php

namespace Org\Gucken\Events\Aspect;

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * @FLOW3\Scope("singleton")
 * @FLOW3\Aspect
 */
class DeprecationLoggingAspect {

	/**
	 * 
	 * @FLOW3\Inject
	 * @var \TYPO3\FLOW3\Log\SystemLoggerInterface
	 */
	protected $deprecationLogger;


	/**
	 * Log a message if a deprecated method is called
	 *
	 * @param \TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint
	 * @FLOW3\Before("filter(Org\Gucken\Events\Aspect\Pointcut\PointcutMethodDeprecatedFilter)")
	 * @return void
	 */
	public function logDeprecatedMethodCall(\TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint) {
		$className = trim(get_class($joinPoint->getProxy()), '\\');
		$methodName = $joinPoint->getMethodName();
		
		$class = new \TYPO3\FLOW3\Reflection\ClassReflection($className);
		if ($class->implementsInterface('TYPO3\FLOW3\Object\Proxy\ProxyInterface')) {
			$className = $class->getParentClass()->getName();
			$class = new \TYPO3\FLOW3\Reflection\ClassReflection($className);
		}
		
		$method = new \TYPO3\FLOW3\Reflection\MethodReflection($className, $methodName);
		
		if ($class->isTaggedWith('deprecated')) {
			$deprecationComment = implode(' | ',  $class->getTagValues('deprecated'));		
			$message = sprintf('method %s::%s in deprecated class called, Comment: "%s"', $methodName, $className, $deprecationComment);			
		} else if ($method->isTaggedWith('deprecated')) {		
			$deprecationComment = implode(' | ',  $method->getTagValues('deprecated'));		
			$message = sprintf('method %s::%s called, Comment: "%s"', $className, $methodName, $deprecationComment);
		}
		$this->deprecationLogger->log('Deprecated: '.$message, LOG_WARNING);
	}

}

?>
