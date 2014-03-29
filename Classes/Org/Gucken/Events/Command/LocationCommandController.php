<?php

namespace Org\Gucken\Events\Command;

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

use Org\Gucken\Events\Domain\Model\Location as Location;
use Org\Gucken\Events\Domain\Repository\LocationRepository;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController as CommandController;

/**
 * Command controller for the Importer
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class LocationCommandController extends CommandController {


    /**
     * @Flow\Inject
     * @var LocationRepository
     */
    protected $locationRepository;

    /**
     * Search locations by keywords
	 *
     * @param string $searchString
     */
    public function searchCommand($searchString) {
        $location = $this->locationRepository->findOneByKeywordString($searchString);
		$this->outputLocation($location);
    }

    /**
     * List locations
     *
     */
    public function listCommand() {
        $locations = $this->locationRepository->findAll();
		foreach ($locations as $location) {
			$this->outputLocation($location);
		}
    }


	protected function outputLocation(Location $location) {
		if ($location) {
			$this->outputLine(sprintf(
					'%-30s: %-50s',
					$location->getName().' '.$location->getAddress()->getAddressLocality(),
					implode(', ', $location->getKeywords()->toArray())
			));
		}
	}

}

?>