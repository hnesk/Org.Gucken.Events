<?php

namespace Org\Gucken\Events\Domain\Model;

/* *
 * This script belongs to the Flow package "Org.Gucken.Events".           *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use Type\Record;
use Type\Xml;
use Type\Xml\Factory as XmlFactory;
use TYPO3\Flow\Annotations as Flow;

/**
 * A Event factoid
 *
 * @Flow\Scope("prototype")
 * @Flow\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="model", type="string")
 */
class EventFactoid
{

    /**
     * The title
     * @var string
     */
    protected $title;

    /**
     * The start date time
     * @var \DateTime
     */
    protected $startDateTime;

    /**
     * The end date time
     * @var \DateTime
     * @ORM\Column(nullable=true)
     */
    protected $endDateTime;

    /**
     * The location
     * @var \Org\Gucken\Events\Domain\Model\Location
     * @ORM\ManyToOne
     */
    protected $location;

    /**
     * The type
     * @var \Org\Gucken\Events\Domain\Model\Type
     * @ORM\ManyToOne
     */
    protected $type;

    /**
     * The url
     * @var string
     * @ORM\Column(nullable=true)
     */
    protected $url;

    /**
     * The short description
     * @var string
     * @ORM\Column(nullable=true)
     */
    protected $shortDescription;

    /**
     * The description
     * @var string
     * @ORM\Column(type="text",nullable=true)
     */
    protected $description;

    /**
     *
     * @var string
     * @ORM\Column(type="text", nullable = true)
     */
    protected $proof;

    /**
     * The import date time
     * @var \DateTime
     */
    protected $importDateTime;

    /**
     *
     * @var \Org\Gucken\Events\Domain\Model\EventFactoidIdentity
     * @ORM\ManyToOne(inversedBy="factoids")
     */
    protected $identity;

    /**
     * The location details varying by event (Room, etc)
     * @var string
     */
    protected $locationDetail;

    /**
     *
     * @var \Org\Gucken\Events\Domain\Model\EventSource
     * @Flow\Transient
     */
    protected $source;

    /**
     *
     * @param Record $record
     */
    public function __construct(Record $record = null)
    {
        if ($record) {
            $this->setTitle($record->getNative('title'));
            $this->setStartDateTime($record->getNative('date'));
            $this->setEndDateTime($record->getNative('end'));
            $this->setUrl((string) $record->getNative('url'));
            $this->setShortDescription((string) $record->getNative('short'));
            $description = $record->get('description');
            if (trim((string) $description)) {
                if ($description instanceof Xml) {
                    $description = $description->markdown()->normalizeSpaceKeepBreaks();
                }
            }
            $this->setLocationDetail((string) $record->getNative('locationDetail'));
            $this->setDescription((string) $description);
            $this->setType($record->getNative('type'));
            $this->setLocation($record->getNative('location'));
            if ($record->get('proof')) {
                $this->setProof($record->get('proof')->asXmlString());
            } else {
                $this->setProof('');
            }
        }
    }

    /**
     * Get the Event factoid's title
     *
     * @return string The Event factoid's title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets this Event factoid's title
     *
     * @param  string $title The Event factoid's title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
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
     * Get the Event factoid's end date time
     *
     * @return \DateTime The Event factoid's end date time
     */
    public function getEndDateTime()
    {
        return $this->endDateTime;
    }

    /**
     * Sets this Event factoid's end date time
     *
     * @param  \DateTime $endDateTime The Event factoid's end date time
     * @return void
     */
    public function setEndDateTime(\DateTime $endDateTime = null)
    {
        $this->endDateTime = $endDateTime;
    }

    /**
     * Get the Event factoid's location
     *
     * @return \Org\Gucken\Events\Domain\Model\Location The Event factoid's location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Sets this Event factoid's location
     *
     * @param  \Org\Gucken\Events\Domain\Model\Location $location The Event factoid's location
     * @return void
     */
    public function setLocation(Location $location = null)
    {
        $this->location = $location;
    }

    /**
     * Get the Event factoid's type
     *
     * @return \Org\Gucken\Events\Domain\Model\Type The Event factoid's type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets this Event factoid's type
     *
     * @param  \Org\Gucken\Events\Domain\Model\Type $type The Event factoid's type
     * @return void
     */
    public function setType(Type $type = null)
    {
        $this->type = $type;
    }

    /**
     * Get the Event factoid's url
     *
     * @return string The Event factoid's url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets this Event factoid's url
     *
     * @param  string $url The Event factoid's url
     * @return void
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get the Event factoid's short description
     *
     * @return string The Event factoid's short description
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Sets this Event factoid's short description
     *
     * @param  string $shortDescription The Event factoid's short description
     * @return void
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = (string) $shortDescription;
    }

    /**
     * Get the Event factoid's description
     *
     * @return string The Event factoid's description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets this Event factoid's description
     *
     * @param  string $description The Event factoid's description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get the Event factoid's source
     *
     * @return \Org\Gucken\Events\Domain\Model\EventSource The Event factoid's source
     */
    public function getSource()
    {
        return $this->identity ? $this->identity->getSource() : $this->source;
    }

    /**
     * Sets this Event factoid's source
     *
     * @param  \Org\Gucken\Events\Domain\Model\EventSource $source The Event factoid's source
     * @return void
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * Get the Event factoid's import date time
     *
     * @return \DateTime The Event factoid's import date time
     */
    public function getImportDateTime()
    {
        return $this->importDateTime;
    }

    /**
     * Sets this Event factoid's import date time
     *
     * @param  \DateTime $importDateTime The Event factoid's import date time
     * @return void
     */
    public function setImportDateTime($importDateTime)
    {
        $this->importDateTime = $importDateTime;
    }

    /**
     * @param string
     */
    public function setProof($proof)
    {
        $this->proof = $proof;
    }

    /**
     * @return string
     */
    public function getProof()
    {
        return $this->proof;
    }

    /**
     * @return Xml
     */
    public function getProofAsXml()
    {
        $proofXml = null;
        $proof = $this->getProof();
        if (!empty($proof)) {
            $proofXml = XmlFactory::fromXmlString($proof);
        }

        return $proofXml;
    }

    /**
     *
     * @param EventFactoidIdentity $identity
     */
    public function setIdentity(EventFactoidIdentity $identity)
    {
        $this->identity = $identity;
    }

    /**
     *
     * @return EventFactoidIdentity
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     *
     * @return string
     */
    public function getLocationDetail()
    {
        return $this->locationDetail;
    }

    /**
     *
     * @param string $locationDetail
     */
    public function setLocationDetail($locationDetail)
    {
        $this->locationDetail = $locationDetail;
    }

    /**
     *
     * @param  EventFactoid $factoid
     * @return bool
     */
    public function equals(EventFactoid $factoid)
    {
        $identityProperties = array(
            'startDateTime',
            'endDateTime',
            'title',
            'shortDescription',
            'description',
            'source',
            'location',
            'type',
            'url'
        );
        foreach ($identityProperties as $identityProperty) {
            if ($this->$identityProperty != $factoid->$identityProperty) {
                return false;
            }
        }

        return true;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}
