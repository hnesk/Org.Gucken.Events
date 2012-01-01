<?php

namespace Org\Gucken\Events\Service;


use Org\Gucken\Events\Domain\Model;
use Org\Gucken\Events\Domain\Repository;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * @FLOW3\Scope("singleton") 
 */
class ConvertEventFactoidService {
	
    /**
     *
     * @var Org\Gucken\Events\Domain\Repository\EventFactoidIdentityRepository
     */
    protected $eventFactoidIdentityRepository;	

    /**
     *
     * @var Org\Gucken\Events\Domain\Repository\EventRepository
     */
    protected $eventRepository;

	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Repository\EventFactoidIdentityRepository $eventFactoidIdentityRepository 
	 */
	public function injectFactoidIdentityRepository(\Org\Gucken\Events\Domain\Repository\EventFactoidIdentityRepository $eventFactoidIdentityRepository) {
		$this->eventFactoidIdentityRepository = $eventFactoidIdentityRepository;
	} 
	
	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Repository\EventRepository $eventRepository 
	 */
	public function injectEventRepository(\Org\Gucken\Events\Domain\Repository\EventRepository $eventRepository) {
		$this->eventRepository = $eventRepository;
	}

	/**
	 *
	 * @param Model\EventFactoidIdentity $identity
	 * @return Model\Event
	 */
    public function convert(Model\EventFactoidIdentity $identity) {
		$event = new Model\Event();			
		
		$factoid = $identity->getFactoid();
		
		$event->addLink($identity->createLink());
		$event->setLocation($identity->getLocation());
		$event->setStartDateTime($identity->getStartDateTime());			
				
		$event->setEndDateTime($factoid->getEndDateTime());
		$event->setDescription($factoid->getDescription());
		$event->setShortDescription($factoid->getShortDescription());		
		$event->setTitle($factoid->getTitle());
		$event->setUrl($factoid->getUrl());
		
		$event->addTypeIfNotExists($factoid->getType());					
				
		$this->eventRepository->add($event);
		$this->eventFactoidIdentityRepository->update($identity);
		
		return $event;
    }
	
	/**
	 * @param Model\Event $event
	 * @param Model\EventFactoidIdentity $identity
	 * @return Model\Event
	 */
    public function merge(Model\Event $event, Model\EventFactoidIdentity $identity) {
		$factoid = $identity->getFactoid();		
		
		$event->addLink($identity->createLink());				
		
		if (!$event->getLocation()) {
			$event->setLocation($identity->getLocation());
		}
		if (!$event->getStartDateTime()) {
			$event->setStartDateTime($identity->getStartDateTime());
		}		
		if (!$event->getEndDateTime()) {
			$event->setEndDateTime($factoid->getEndDateTime());
		}
		if (!$event->getDescription()) {
			$event->setDescription($factoid->getDescription());
		}
		if (!$event->getShortDescription()) {
			$event->setShortDescription($factoid->getShortDescription());
		}
		if (!$event->getTitle()) {
			$event->setTitle($factoid->getTitle());
		}
		if (!$event->getUrl()) {
			$event->setUrl($factoid->getUrl());
		}
		
		
		$event->addTypeIfNotExists($factoid->getType());
		
		$this->eventRepository->update($event);
		$this->eventFactoidIdentityRepository->update($identity);
		return $event;
    }
	

	
}


?>
