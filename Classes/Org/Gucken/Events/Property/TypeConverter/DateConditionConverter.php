<?php
namespace Org\Gucken\Events\Property\TypeConverter;

/*                                                                        *
 * This script belongs to the FLOW3 framework.                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Converter which transform string to DateConditions.
 *
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @Flow\Scope("singleton")
 */
class DateConditionConverter extends \TYPO3\Flow\Property\TypeConverter\AbstractTypeConverter {

	/**
	 * @var array<string>
	 */
	protected $sourceTypes = array('string');

	/**
	 * @var string
	 */
	protected $targetType = '\ToDate\Condition\AbstractDateCondition';

	/**
	 * Converts $source to a \Type\Url
	 *
	 * @param string $source the string to be converted to a \ToDate\Condition\DateConditionInterface object
	 * @param string $targetType must be "\ToDate\Condition\AbstractDateCondition"
	 * @return \ToDate\Condition\DateConditionInterface
	 */
	public function convertFrom($source, $targetType, array $convertedChildProperties = array(), \TYPO3\Flow\Property\PropertyMappingConfigurationInterface $configuration = NULL) {
		$parser = new \ToDate\Parser\FormalDateExpressionParser($source);
		$condition = $parser->parse();
		return $condition ? $condition : new \ToDate\Condition\ErrorCondition("", $source);
	}
}
?>