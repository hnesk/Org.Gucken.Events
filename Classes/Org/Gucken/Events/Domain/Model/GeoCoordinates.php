<?php
namespace Org\Gucken\Events\Domain\Model;

/*                                                                        *
 * This script belongs to the Flow package "Org.Gucken.Events".           *
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

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A Geo coodinates
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @Flow\Scope("prototype")
 * @Flow\Entity
 */
class GeoCoordinates {

	/**
	 * The elevation
	 * @var float
	 */
	protected $elevation;

	/**
	 * The latitude
	 * @var float
	 */
	protected $latitude;

	/**
	 * The longitude
	 * @var float
	 */
	protected $longitude;

	public function  __construct($latitude, $longitude, $elevation = 0) {
		$this->latitude = (float)$latitude;
		$this->longitude = (float)$longitude;
		$this->elevation = (float)$elevation;
	}


	/**
	 * Get the Geo coodinates's elevation
	 *
	 * @return float The Geo coodinates's elevation
	 */
	public function getElevation() {
		return $this->elevation;
	}

	/**
	 * Sets this Geo coodinates's elevation
	 *
	 * @param float $elevation The Geo coodinates's elevation
	 * @return void
	 */
	public function setElevation($elevation) {
		$this->elevation = $elevation;
	}

	/**
	 * Get the Geo coodinates's latitude
	 *
	 * @return float The Geo coodinates's latitude
	 */
	public function getLatitude() {
		return $this->latitude;
	}

	/**
	 * Sets this Geo coodinates's latitude
	 *
	 * @param float $latitude The Geo coodinates's latitude
	 * @return void
	 */
	public function setLatitude($latitude) {
		$this->latitude = $latitude;
	}

	/**
	 * Get the Geo coodinates's longitude
	 *
	 * @return float The Geo coodinates's longitude
	 */
	public function getLongitude() {
		return $this->longitude;
	}

	/**
	 * Sets this Geo coodinates's longitude
	 *
	 * @param float $longitude The Geo coodinates's longitude
	 * @return void
	 */
	public function setLongitude($longitude) {
		$this->longitude = $longitude;
	}

}
?>