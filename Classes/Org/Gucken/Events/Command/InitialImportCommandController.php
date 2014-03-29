<?php
namespace Org\Gucken\Events\Command;


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

use Org\Gucken\Events\Domain\Model\Type as Type;
use Org\Gucken\Events\Domain\Model\Location as Location;
use Org\Gucken\Events\Domain\Model\PostalAddress as PostalAddress;
use Org\Gucken\Events\Domain\Model\GeoCoordinates as GeoCoordinates;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController as CommandController;

/**
 * Command controller for the Importer
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class InitialImportCommandController extends CommandController {

	/**
	 * @var \Org\Gucken\Events\Domain\Repository\LocationRepository
	 * @Flow\Inject
	 */
	protected $locationRepository;

	/**
	 * @var \Org\Gucken\Events\Domain\Repository\TypeRepository
	 * @Flow\Inject
	 */
	protected $typeRepository;





	/**
	 * Import types
	 * @return string
	 */
	public function typeCommand() {
            #print_r($this->getTypes());
            foreach ($this->getTypes() as $typedata) {
                $type = new Type($typedata['title'], $typedata['titlepl'], $typedata['description']);
                $this->typeRepository->add($type);
            }
	}


	/**
	 * Import locations from TYPO3 DB
	 * @param int $limit
	 * @return string
	 * @author Johannes Künsebeck <kuensebeck@googlemail.com>
	 */
	public function locationCommand($limit = null) {
		foreach ($this->getLocations($limit) as $locationData) {
			$location = new Location();
			$location->setName(trim($locationData['title']));
			$location->setDescription(trim($locationData['description']));
			$location->setUrl(trim($locationData['url']));
			$location->setTelephone(trim($locationData['phone']));
			$location->setFaxNumber(trim($locationData['fax']));
			$location->setEmail(trim($locationData['email']));
			$location->setAddress(
				new PostalAddress(
					trim($locationData['address']),
					trim($locationData['zip']),
					trim($locationData['city'])
				)
			);
			if ($locationData['latitude'] && $locationData['longitude']) {
				$location->setGeo(
					new GeoCoordinates(
						(float)($locationData['latitude']),
						(float)($locationData['longitude'])
					)
				);
			}

			$this->locationRepository->add($location);
		}

	}

    /**
     *
     * @param int|null $limit
     * @return array
     */
	protected function getLocations($limit = null) {
		$locations = array();
		$tx_eventcollab_location = array();
        /** @noinspection PhpIncludeInspection */
        include 'resource://Org.Gucken.Events/Private/PHP/Data/gehweg_typo3.php';
		foreach ($tx_eventcollab_location as $location) {
			if ($location['address'] && !$location['hidden'] && !$location['deleted'] && $location['pid'] != 0) {
				$locations[] = $location;
			}

		}
		return $limit ? \array_slice($locations, 0, $limit) : $locations;
	}


	/**
	 *
	 * @return array
	 */
	protected function getTypes() {
		$types = array();
		$tx_eventcollab_type = array();
        /** @noinspection PhpIncludeInspection */
		include 'resource://Org.Gucken.Events/Private/PHP/Data/gehweg_typo3.php';
		foreach ($tx_eventcollab_type as $type) {
			if (!$type['hidden'] && !$type['deleted'] && $type['pid'] != 0) {
				$types[] = $type;
			}

		}
		return $types;
	}

}
?>