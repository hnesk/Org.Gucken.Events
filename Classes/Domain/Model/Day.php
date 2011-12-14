<?php
namespace Org\Gucken\Events\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "Events".                     *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License as published by the Free   *
 * Software Foundation, either version 3 of the License, or (at your      *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        *
 * You should have received a copy of the GNU General Public License      *
 * along with the script.                                                 *
 * If not, see http://www.gnu.org/licenses/gpl.html                       *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * A Wrapper for \DateTime which only saves the date
 * Used as grouping key in <f:groupedFor> view helper
 * I would prefer the valueobject annotation would take care of that automagically
 * or do I missunderstand the purpose of valueobject?
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @FLOW3\Scope("prototype")
 */
class Day {
	/**
	 *
	 * @var \DateTime
	 */
	protected $date;

	/**
	 *
	 * @param \DateTime $date
	 */
	public function __construct(\DateTime $date = null) {
		$date = $date ?: new \DateTime('0000-00-00');
		$this->date = new \DateTime($date->format('Y-m-d'), $date->getTimezone());
	}

	/**
	 *
	 * @return \DateTime
	 */
	public function getDate() {
		return $this->date;
	}

}
?>