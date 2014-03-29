<?php

namespace Org\Gucken\Events\Domain\Model;

/* *
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
 * An identifier for a location on an external website or service
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @Flow\Scope("prototype")
 * @Flow\Entity
 */
class LastFmLocationIdentifier extends ExternalLocationIdentifier {

	public function getSchemeLabel() {
		return 'Last.fm-ID';
	}
	
	/**
	 *
	 * @param Location $location
	 * @return array<LastFmLocationIdentifier>
	 */
	public function getCandidates(Location $location) {
		$results = array();
		$searchString = $location->getName().' '.$location->getAddress()->getAddressLocality();            
        foreach (\Lastfm\Type\Venue\Collection\Factory::fromString($searchString) as $venue) {
			/* @var $venue \Lastfm\Type\Venue */			
			$results[] = new self($venue->getId(), $location, (string)$venue);
		}
		return $results;
	}
	
	public function getUrl() {
		return 'http://www.last.fm/venue/'.$this->getId();
	}

}

?>