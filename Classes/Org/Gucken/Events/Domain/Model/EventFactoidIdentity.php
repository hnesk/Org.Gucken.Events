<?php

namespace Org\Gucken\Events\Domain\Model;

/* *
 * This script belongs to the Flow package "Org.Gucken.Events".           *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * A Event factoids persistent identity
 *
 * @Flow\Scope("prototype")
 * @Flow\Entity
 */
class EventFactoidIdentity
{

    /**
     * The start date time
     * @var \DateTime
     */
    protected $startDateTime;

    /**
     * The location
     * @var Location
     * @ORM\ManyToOne
     */
    protected $location;

    /**
     * The source
     * @var EventSource
     * @ORM\ManyToOne
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $source;

    /**
     * The link
     * @var EventLink
     * @ORM\OneToOne
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $link;

    /**
     *
     * @var boolean
     * @ORM\Column(nullable=true)
     */
    protected $shouldSkip;

    /**
     * @var ArrayCollection<\Org\Gucken\Events\Domain\Model\EventFactoid>
     * @ORM\OneToMany(mappedBy="identity", cascade={"all"}, orphanRemoval=true)
     */
    protected $factoids;

    public function __construct()
    {
        $this->factoids = new ArrayCollection();
    }

    /**
     * Get the Event factoid's start date time
     *
     * @return \DateTime The Event factoid's start date time
     */
    public function getStartDateTime()
    {
        return $this->startDateTime;
    }

    /**
     * Sets this Event factoid's start date time
     *
     * @param  \DateTime                 $startDateTime The Event factoid's start date time
     * @throws \InvalidArgumentException
     * @return void
     */
    public function setStartDateTime($startDateTime = null)
    {
        if (!$startDateTime instanceof \DateTime) {
            throw new \InvalidArgumentException(
                sprintf(
                    '"%s" is not an instance of DateTime but an %s',
                    $startDateTime,
                    is_object($startDateTime) ? get_class($startDateTime) : gettype($startDateTime)
                )
            );
        }
        $this->startDateTime = $startDateTime;
    }

    /**
     * Get the Event factoid's location
     *
     * @return Location The Event factoid's location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Sets this Event factoid's location
     *
     * @param  Location $location The Event factoid's location
     * @return void
     */
    public function setLocation(Location $location = null)
    {
        $this->location = $location;
    }

    /**
     * Get the Event factoid's source
     *
     * @return EventSource The Event factoid's source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     *
     * @return EventLink
     */
    public function createLink()
    {
        $this->setLink($this->source->convertLink($this));

        return $this->getLink();
    }

    /**
     * @return EventLink
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     *
     * @param EventLink $link
     */
    public function setLink(EventLink $link = null)
    {
        $this->link = $link;
    }

    /**
     * Sets this Event factoid's source
     *
     * @param  EventSource $source The Event factoid's source
     * @return void
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @param Collection|EventFactoid[] $factoids
     * @internal  $factoids
     */
    public function setFactoids(Collection $factoids)
    {
        $this->factoids = $factoids;
    }

    /**
     * @return Collection|EventFactoid[]
     */
    public function getFactoids()
    {
        return $this->factoids;
    }

    /**
     * @return Collection|EventFactoid[]
     */
    public function getSortedFactoids()
    {
        $factoids = $this->factoids->toArray();
        usort(
            $factoids,
            function (EventFactoid $a, EventFactoid $b) {
                return $b->getImportDateTime()->getTimestamp() - $a->getImportDateTime()->getTimestamp();
            }
        );

        return new ArrayCollection($factoids);
    }

    /**
     *
     * @param EventFactoid $factoid
     */
    public function addFactoid(EventFactoid $factoid)
    {
        $factoid->setIdentity($this);
        if (!$factoid->getImportDateTime()) {
            $factoid->setImportDateTime(new \DateTime());
        }
        $this->factoids->add($factoid);
    }

    /**
     *
     * @param  EventFactoid $factoid
     * @return EventFactoid
     */
    public function addFactoidIfNotExists(EventFactoid $factoid)
    {
        foreach ($this->factoids as $aFactoid) {
            /* @var $aFactoid EventFactoid */
            if ($aFactoid->equals($factoid)) {
                return $aFactoid;
            }
        }

        $this->addFactoid($factoid);

        return $factoid;
    }

    /**
     *
     * @return EventFactoid
     */
    public function getFactoid()
    {
        $newestFactoid = null;
        $newestTimestamp = 0;
        foreach ($this->factoids as $factoid) {
            /* @var $factoid EventFactoid */
            $dateTime = $factoid->getImportDateTime();
            if ($dateTime->getTimestamp() > $newestTimestamp) {
                $newestTimestamp = $dateTime->getTimestamp();
                $newestFactoid = $factoid;
            }
        }

        return $newestFactoid;
    }

    /**
     *
     * @param EventFactoid $factoid
     */
    public function removeFactoid(EventFactoid $factoid)
    {
        $this->factoids->removeElement($factoid);
    }

    /**
     * @return int
     */
    public function countFactoids()
    {
        return $this->factoids->count();
    }

    /**
     * @return boolean
     */
    public function hasFactoids()
    {
        return $this->countFactoids() > 0;
    }

    /**
     *
     * @param boolean $shouldSkip
     */
    public function setShouldSkip($shouldSkip)
    {
        $this->shouldSkip = $shouldSkip;
    }

    /**
     *
     * @return boolean
     */
    public function getShouldSkip()
    {
        return $this->shouldSkip;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return
            $this->getStartDateTime()->format('d.m.Y H:i') .
            ' @' . ($this->getLocation() ? $this->getLocation()->getName() : '[unknown]') .
            ' #' . $this->factoids->count();
    }

}
