<?php

namespace Org\Gucken\Events\Controller;

/* *
 * This script belongs to the FLOW3 package "Events".                     *
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

use Org\Gucken\Events\Domain\Model\Location;
use Org\Gucken\Events\Domain\Model\EventSource;
use Org\Gucken\Events\Domain\Model\EventFactoid;
use Org\Gucken\Events\Domain\Model\ExternalLocationIdentifier;
use TYPO3\Flow\Annotations as Flow;
use Lastfm\Type\Venue as Venue;

/**
 * Standard controller for the Events package
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class LocationController extends AbstractAdminController {

    /**
     *
     * @var \Org\Gucken\Events\Domain\Repository\LocationRepository
     * @Flow\Inject
     */
    protected $locationRepository;

    /**
     *
     * @var \Org\Gucken\Events\Domain\Model\ExternalLocationIdentifierFactory
     * @Flow\Inject
     */
	protected $identifierFactory;

	/**
	 *
	 * @var \Org\Gucken\Events\Domain\Model\BackendSession
	 * @Flow\Inject
	 */
	protected $backendSession;


	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\LocationSearchRequest $searchRequest
	 * @param string $orderColumn
	 * @param string $orderDirection
	 * @param boolean $reset
	 */
    public function indexAction(\Org\Gucken\Events\Domain\Model\LocationSearchRequest $searchRequest = null, $orderColumn = null, $orderDirection = null, $reset = false) {
		if ($reset) {
			$searchRequest = $this->backendSession->setLocationSearchRequest();
		} else {
			$searchRequest = $this->backendSession->updateLocationSearchRequest($searchRequest, $orderColumn, $orderDirection);
		}

		$locations = $this->locationRepository->findBySearchRequest($searchRequest);

        $this->view->assign('locations', $locations);
		$this->view->assign('searchRequest', $searchRequest);
    }

    /**
     *
     * @param Org\Gucken\Events\Domain\Model\Location $location
     * @param string $type
	 * @param int $position
     */
    public function lookupAction(Location $location,  $type, $position) {
		$this->view->assign('externalIdentifierCandidates', $this->identifierFactory->getCandidatesForLocation($type, $location));
		$this->view->assign('position',$position);
    }

    /**
     *
     * @param Org\Gucken\Events\Domain\Model\Location $location
     * @Flow\IgnoreValidation("location")
     * @return void
     */
    public function addAction(Location $location = null) {
        $this->view->assign('location', $location);
    }

	/**
	 * @param Org\Gucken\Events\Domain\Model\EventSource $source
	 * @param Org\Gucken\Events\Domain\Model\EventFactoid $factoid
	 *
	 */
	public function addFromSourceAction(EventSource $source, EventFactoid $factoid) {
		$location = $source->convertLocation($factoid);
		$this->locationRepository->add($location);
		$this->forward('edit', NULL, NULL, array('location'=>$location));
	}

    public function initializeSaveAction() {
		$this->allowForProperty('location', 'address',self::CREATION);
    }

    /**
     *
     * @param Org\Gucken\Events\Domain\Model\Location $location
     */
    public function saveAction(Location $location) {
		$location->setReviewed(true);
        $this->locationRepository->add($location);
        $this->redirect('index');
    }

    /**
     *
     * @param Org\Gucken\Events\Domain\Model\Location $location
	 * @Flow\IgnoreValidation("location")
     * @return void
     */
    public function editAction(\Org\Gucken\Events\Domain\Model\Location $location) {
        $this->view->assign('location', $location);
		$externalIdentifiers = $this->identifierFactory->getIdentifierOptions();
        $this->view->assign('externalIdentifiers', $externalIdentifiers);
		$this->view->assign('nextExternalIdentifiersCount', count($location->getExternalIdentifiers()) - 1);
    }


    public function initializeUpdateAction() {
		$this->preprocessProperty('location', 'externalIdentifiers.*', '__type');
		$this->preprocessProperty('location', 'keywords.*', 'keyword');
		$this->allowForProperty('location', 'address', self::MODIFICATION);
		$this->allowForProperty('location', 'externalIdentifiers.*', self::EVERYTHING);
		$this->allowForProperty('location', 'keywords.*', self::CREATION);
    }

    /**
     *
     * @param Org\Gucken\Events\Domain\Model\Location $location
     */
    public function updateAction(Location $location) {
		$location->removeEmptyRelations();
		$location->setReviewed(true);
        $this->locationRepository->update($location);
        $this->redirect('index');
    }

    /**
     *
     * @param Org\Gucken\Events\Domain\Model\Location $location
     */
    public function deleteAction(Location $location) {
        $this->locationRepository->remove($location);
		$this->addNotice($location . ' wurde gelöscht');
        $this->redirect('index');
    }




}

?>