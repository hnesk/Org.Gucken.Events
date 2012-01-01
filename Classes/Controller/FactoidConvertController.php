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
use Org\Gucken\Events\Domain\Model\Event;
use Org\Gucken\Events\Domain\Model\EventFactoid;
use Org\Gucken\Events\Domain\Model\EventFactoidIdentity;

use TYPO3\FLOW3\Annotations as FLOW3;
use Lastfm\Type\Venue as Venue;

/**
 * Standard controller for the Events package 
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class FactoidConvertController extends BaseController {
        
    /**
     *
     * @var \Org\Gucken\Events\Domain\Repository\EventFactoidIdentityRepository
     * @FLOW3\Inject
     */
    protected $identityRepository;
	
    /**
     *
     * @var \Org\Gucken\Events\Domain\Repository\EventRepository
     * @FLOW3\Inject
     */
    protected $eventRepository;
	
	
	/**
	 *
	 * @var \Org\Gucken\Events\Service\ConvertEventFactoidService
	 * @FLOW3\Inject
	 */
	protected $convertService;
	        
    /**
     * Index action
     *
     * @return void
     */
    public function indexAction() {   		
		$startDateTime = new \DateTime('-1 day');
		$endDateTime = clone $startDateTime;
		$endDateTime->modify('+1 month');
        $this->view->assign('identities', $this->identityRepository->findUnassignedBetween($startDateTime, $endDateTime));
		$this->view->assign('events', $this->eventRepository->findBetween($startDateTime, $endDateTime));
    }
	
	
	/**
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoidIdentity $identity
	 */
	public function deleteAction(EventFactoidIdentity $identity) {		
		$this->identityRepository->remove($identity);
		$this->addNotice('Removed Identity "'.$identity.'"');
		$this->redirect('index');
	}

	/**
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoidIdentity $identity
	 */
	public function skipAction(EventFactoidIdentity $identity) {		
		$identity->setShouldSkip(true);
		$this->identityRepository->update($identity);
		$this->addNotice('Skipped Identity "'.$identity.'"');
		$this->redirect('index');
	}
	
	/**
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoidIdentity $identity
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoid $factoid
	 */
	public function detailFactoidAction(EventFactoidIdentity $identity, EventFactoid $factoid) {
		$this->view->assign('factoid', $factoid);
		$this->view->assign('identity', $identity);
	}
	
	
	/**
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoidIdentity $identity
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoid $factoid
	 */
	public function deleteFactoidAction(EventFactoidIdentity $identity, EventFactoid $factoid) {
		$identity->removeFactoid($factoid);
		$this->addNotice('Removed '.$factoid->getTitle());
		if ($identity->hasFactoids()) {			
			$this->identityRepository->update($identity);			
		} else {
			$this->identityRepository->remove($identity);
			$this->addNotice('Removed the identity, too');
		}
		$this->redirect('index');
	}

	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoidIdentity $identity
	 */
    public function convertAction(EventFactoidIdentity $identity) {        
		$event = $this->convertService->convert($identity);
		$this->addNotice('Created event "'.$event.'"');
		$this->redirect('index');
    }
	
	/**
	 * @param \Org\Gucken\Events\Domain\Model\Event $event
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoidIdentity $identity
	 */
    public function mergeAction(Event $event, EventFactoidIdentity $identity) {        
		$event = $this->convertService->merge($event, $identity);
		$this->addNotice('Merged "'.$identity.'" to "'.$event.'"');
		$this->view->assign('event',$event);
		$this->view->assign('identity',$identity);
    }
	
	
}

?>