<?php
namespace Org\Gucken\Events\Property\TypeConverter;

/*                                                                        *
 * This script belongs to the Flow package "Org.Gucken.Events".           *
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
use TYPO3\Flow\Property\Exception\TypeConverterException;

/**
 * Converter which transforms from different input formats into DateTime objects.
 *
 * Overriden to allow combined H:i:s field
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @Flow\Scope("singleton")
 */
class DateTimeConverter extends \TYPO3\Flow\Property\TypeConverter\DateTimeConverter {


	/**
	 * @var integer
	 */
	protected $priority = 3;

	const CONFIGURATION_TIME_FORMAT = 'timeFormat';

    /**
     * Overrides hour, minute & second of the given date with the values in the $source array
     *
     * Overriden implementation to also allow parameter time and timeFormat
     *
     * @param \DateTime $date
     * @param array $source
     * @throws TypeConverterException
     * @return void
     */
	protected function overrideTimeIfSpecified(\DateTime $date, array $source) {
		if (!isset($source['time']) || !is_string($source['time']) || strlen(trim($source['time']))===0) {
			parent::overrideTimeIfSpecified($date, $source);
            return;
		}

		$timeFormat = self::CONFIGURATION_TIME_FORMAT;
		$timeAsString = $source['time'];
		if (isset($source[self::CONFIGURATION_TIME_FORMAT]) && strlen($source[self::CONFIGURATION_TIME_FORMAT]) > 0) {
			$timeFormat = $source[self::CONFIGURATION_TIME_FORMAT];
		}

		$time = \DateTime::createFromFormat($timeFormat, $timeAsString);
		if (!$time) {
			throw new TypeConverterException('Could not convert "'.$timeAsString.'" to a time specification in format "'.$timeFormat.'"', 1309383873);
		}
		$date->setTime($time->format('H'), $time->format('i'), $time->format('s'));
	}
}
?>