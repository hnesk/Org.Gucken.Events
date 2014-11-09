<?php

namespace Org\Gucken\Events\Domain\Model\EventSource;

use Org\Gucken\Events\Annotations as Events;
use Org\Gucken\Events\Domain\Model\FacebookEventLink;
use TYPO3\Flow\Annotations as Flow;

use Org\Gucken\Events\Domain\Model\EventFactoid;
use Org\Gucken\Events\Domain\Model\EventFactoidIdentity;
use Org\Gucken\Events\Domain\Model\EventLink;
use Org\Gucken\Events\Domain\Model\Location;
use Org\Gucken\Events\Domain\Model\Type;
use Facebook\Type\Page as FacebookPage;
use Facebook\Type\Event as FacebookEvent;
use Type\Url;

/**
 * @Flow\Scope("prototype")
 */
class Facebook implements EventSourceInterface
{
    /**
     * @Flow\Inject
     * @var \Org\Gucken\Events\Domain\Repository\LocationRepository
     */
    protected $locationRepository;

    /**
     * @Events\Configurable
     * @var string
     */
    protected $page;

    /**
     * @Events\Configurable
     * @var Type
     */
    protected $type;

    /**
     * @param string $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     *
     * @return FacebookPage
     */
    public function getPage()
    {
        return new FacebookPage($this->page);
    }

    /**
     *
     * @param Type $type
     */
    public function setType(Type $type)
    {
        $this->type = $type;
    }

    /**
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @param  EventFactoid  $factoid
     * @return Location|null
     */
    public function convertLocation(EventFactoid $factoid)
    {
        $location = null;

        return $location;
    }

    /**
     *
     * @param  EventFactoidIdentity $factoidIdentity
     * @param  EventLink            $link            if set link will be updated else created
     * @return EventLink
     */
    public function convertLink(EventFactoidIdentity $factoidIdentity, $link = null)
    {
        $link = $link ?: new FacebookEventLink();
        $link->setUrl($factoidIdentity->getFactoid()->getUrl());

        return $link;
    }

    /**
     * @return \Type\Record\Collection
     */
    public function getEvents()
    {
        return $this->getPage()->getEvents('-1 days', '+28 days')->map(array($this, 'getEvent'));
    }

    /**
     *
     * @param  FacebookEvent $event
     * @return \Type\Record
     */
    public function getEvent(FacebookEvent $event)
    {
        return new \Type\Record(
            array(
                'title' => $event->getTitle(),
                #'image' => $xml->css('img')->asUrl()->first(),
                #'short' =>
                'description' => $event->getDescription(),
                'date' => $event->getStartTime(),
                'url' => $event->getUrl(),
                'type' => $this->getType(),
                'location' => $this->locationRepository->findOneByExternalId(
                    'org_gucken_events_facebooklocationidentifier',
                    $event->getVenueId()
                )
            )
        );
    }
}
