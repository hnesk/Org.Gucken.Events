<?php
namespace Org\Gucken\Events\Annotations;

/*                                                                        *
 * This script belongs to the FLOW3 framework.                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Doctrine\Common\Annotations\Annotation as DoctrineAnnotation;

/**
 * @Annotation
 * @DoctrineAnnotation\Target("METHOD")
 */
final class Deprecated {
	/**
	 * @var string
	 */
	public $comment;

	/**
	 * @param array $values
	 */
	public function __construct(array $values) {
		$this->comment = isset($values['value']) ? $values['value'] : (isset($values['comment']) ? $values['comment'] : '[No comment given]');
	}
	
	/**
	 *
	 * @return string
	 */
	public function getComment() {
		return $this->comment;
	}
	
}

?>