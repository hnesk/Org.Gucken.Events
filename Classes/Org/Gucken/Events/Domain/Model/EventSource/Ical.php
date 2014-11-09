<?php

namespace Org\Gucken\Events\Domain\Model\EventSource;

use Org\Gucken\Events\Domain\Model\Location;
use Org\Gucken\Events\Domain\Model\Type;
use Type\Calendar\Event;
use Type\Record\Collection;
use Type\Record;
use Type\Url,
    Type\Xml;
use Org\Gucken\Events\Annotations as Events,
    TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("prototype")
 */
class Ical extends AbstractEventSource implements EventSourceInterface
{

    /**
     * @Events\Configurable
     * @var Location
     */
    protected $location;

    /**
     * @Events\Configurable
     * @var Type
     */
    protected $type;

    /**
     * @param Location $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Type $type
     */
    public function setType($type)
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
     * @return Collection
     */
    public function getEvents()
    {
        return $this->getUrl()->load()->getContent()
            ->getEvents()->filterByDate(\Type\Date::ago(DAY), \Type\Date::in(4 * WEEK))
            ->map(array($this, 'getEvent'));
    }

    /**
     *
     * @param  Event  $event
     * @return Record
     */
    public function getEvent(Event $event)
    {
        return new Record(
            array(
                'title' => $event->title(),
                'date' => $event->startDate(),
                'end' => $event->endDate(),
                'location' => $this->getLocation(),
                'type' => $this->getType(),
                'description' => $event->description(),
            )
        );
    }
}
