<?php

namespace Org\Gucken\Events\Service;

use Org\Gucken\Events\Domain\Model;
use Org\Gucken\Events\Domain\Repository;
use Org\Gucken\Events\Domain\Repository\EventFactoidIdentityRepository;
use Org\Gucken\Events\Domain\Repository\EventRepository;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class ConvertEventFactoidService
{

    /**
     *
     * @var EventFactoidIdentityRepository
     */
    protected $eventFactoidIdentityRepository;

    /**
     *
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     *
     * @param EventFactoidIdentityRepository $eventFactoidIdentityRepository
     */
    public function injectFactoidIdentityRepository(EventFactoidIdentityRepository $eventFactoidIdentityRepository)
    {
        $this->eventFactoidIdentityRepository = $eventFactoidIdentityRepository;
    }

    /**
     *
     * @param EventRepository $eventRepository
     */
    public function injectEventRepository(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     *
     * @param  Model\EventFactoidIdentity $identity
     * @return Model\Event
     */
    public function convert(Model\EventFactoidIdentity $identity)
    {
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
        $this->eventRepository->persistAll();
        $this->eventFactoidIdentityRepository->update($identity);
        $this->eventFactoidIdentityRepository->persistAll();

        return $event;
    }

    /**
     * @param  Model\Event                $event
     * @param  Model\EventFactoidIdentity $identity
     * @return Model\Event
     */
    public function merge(Model\Event $event, Model\EventFactoidIdentity $identity)
    {
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
        $this->eventRepository->persistAll();
        $this->eventFactoidIdentityRepository->update($identity);
        $this->eventFactoidIdentityRepository->persistAll();

        return $event;
    }

    /**
     *
     * @param  Model\EventLink            $link
     * @return Model\EventFactoidIdentity
     */
    public function unlink(Model\EventLink $link)
    {
        $event = $link->getEvent();
        $identity = $link->getFactoidIdentity();

        $event->removeLink($link);
        #$identity->setLink(null);

        $this->eventRepository->update($event);
        $this->eventRepository->persistAll();
        #$this->eventFactoidIdentityRepository->update($identity);
        #$this->eventFactoidIdentityRepository->persistAll();

        return $identity;
    }

}
