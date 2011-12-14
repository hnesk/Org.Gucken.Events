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
use TYPO3\FLOW3\Annotations as FLOW3;
use Lastfm\Type\Venue as Venue;

/**
 * Standard controller for the Events package 
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class LocationController extends BaseController {
    	
    /**
     *
     * @var \Org\Gucken\Events\Domain\Repository\LocationRepository
     * @FLOW3\Inject
     */
    protected $locationRepository;
        

    /**
     * Index action
     *
     * @return void
     */
    public function indexAction() {
        $locations = $this->locationRepository->findAll();
        $this->view->assign('locations', $locations);
    }
    
    /**
     *
     * @param Org\Gucken\Events\Domain\Model\Location $location
     * @param int $position
     * @param string $type 
     */
    public function lookupAction(Location $location, $position, $type) {
        if ($type === ExternalLocationIdentifier::LASTFM) {
            $lookup = $location->getName().' '.$location->getAddress()->getAddressLocality();            
            $this->view->assign('venues', Venue\Collection\Factory::fromString($lookup)->toArray('strval', function($v) {return ''.$v->getId();}));
            $this->view->assign('position',$position);
        }        
    }

    /**
     *
     * @param Org\Gucken\Events\Domain\Model\Location $location
     * @FLOW3\IgnoreValidation("location")
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
        $this->locationRepository->add($location);
        $this->redirect('index');
    }

    /**
     *
     * @param Org\Gucken\Events\Domain\Model\Location $location
     * @return void
     */
    public function editAction($location) {
        $this->view->assign('location', $location);
        $this->view->assign('externalIdentifiers', $this->getExternalIdentifiers());
    }
    
    
    public function initializeUpdateAction() {
		$this->allowForProperty('location', 'address', self::MODIFICATION);
		$this->allowForProperty('location', 'externalIdentifiers.*', self::EVERYTHING);
		$this->allowForProperty('location', 'keywords.*', self::CREATION);
		/*
		 * Thats missing in allowForProperty
        foreach ($locationData['externalIdentifiers'] as $key => $externalIdentifierData) {
            if (empty($externalIdentifierData['__identity'])) {
                $locationPropertyMapping->allowCreationForSubProperty('externalIdentifiers.'.$key);
            } else {
                $locationPropertyMapping->allowModificationForSubProperty('externalIdentifiers.'.$key);
            }
        } 		 
		 */               
        
    }
    
    /**
     *
     * @param Org\Gucken\Events\Domain\Model\Location $location 
     */
    public function updateAction(Location $location) {
		$location->removeEmptyRelations();
        $this->locationRepository->update($location);
        $this->redirect('index');
    }
    
    protected function getExternalIdentifiers() {
        return array(
            '' => '---',
            'facebook' => 'Facebook',
            'lastfm' => 'Last.fm'
        );
    }
    

}

?>