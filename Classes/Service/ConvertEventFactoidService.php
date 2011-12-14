<?php

namespace Org\Gucken\Events\Service;


use Org\Gucken\Events\Domain\Model;
use Org\Gucken\Events\Domain\Repository;
use TYPO3\FLOW3\Annotations as FLOW3;


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
		$event->addFactoidIdentity($identity);		
		$event->setLocation($identity->getLocation());
		$event->setStartDateTime($identity->getStartDateTime());
		
		$factoid = $identity->getFactoid();
		
		$event->setEndDateTime($factoid->getEndDateTime());
		$event->setDescription($factoid->getDescription());
		$event->setShortDescription($factoid->getShortDescription());
		$event->setTitle($factoid->getTitle());
		$event->addType($factoid->getType());		
		
		$this->eventRepository->add($event);
		
		$identity->setEvent($event);
		$this->eventFactoidIdentityRepository->update($identity);
		return $event;
    }
	
	/**
	 *
	 * @param Model\EventFactoidIdentity $identity
	 * @return Model\Event
	 */
    public function merge(Model\Event $event, Model\EventFactoidIdentity $identity) {
		$event->addFactoidIdentity($identity);		
		$event->setLocation($identity->getLocation());
		$event->setStartDateTime($identity->getStartDateTime());
		
		$factoid = $identity->getFactoid();		
		$event->setEndDateTime($factoid->getEndDateTime());
		$event->setDescription($factoid->getDescription());
		$event->setShortDescription($factoid->getShortDescription());
		$event->setTitle($factoid->getTitle());
		$event->addType($factoid->getType());		
		
		$this->eventRepository->update($event);
		return $event;
    }
	

	
}


?>
