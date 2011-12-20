<?php

namespace Org\Gucken\Events\Domain\Model;

/* *
 * This script belongs to the FLOW3 package "Org.Gucken.Events".          *
 *                                                                        *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * A Event factoids persitstent identity
 *
 * @FLOW3\Scope("prototype")
 * @FLOW3\Entity
 */
class EventFactoidIdentity {

    /**
     * The start date time
     * @var \DateTime
     */
    protected $startDateTime;

    /**
     * The location
     * @var \Org\Gucken\Events\Domain\Model\Location
     * @ORM\ManyToOne
     */
    protected $location;


    /**
     * The source
     * @var \Org\Gucken\Events\Domain\Model\EventSource
     * @ORM\ManyToOne
     */
    protected $source;
	
    /**
     * The source
     * @var \Org\Gucken\Events\Domain\Model\Event
	 * @ORM\ManyToOne(inversedBy="factoidIdentitys")
     */
    protected $event;
	
	/**
	 *
	 * @var boolean
	 */
	protected $shouldSkip;
	
	
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection<\Org\Gucken\Events\Domain\Model\EventFactoid>
     * @ORM\OneToMany(mappedBy="identity", cascade={"all"}, orphanRemoval=true)
     */
	protected $factoids;
	
    public function __construct() {
		$this->factoids = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
	
    /**
     * Get the Event factoid's start date time
     *
     * @return \DateTime The Event factoid's start date time
     */
    public function getStartDateTime() {
        return $this->startDateTime;
    }

    /**
     * Sets this Event factoid's start date time
     *
     * @param \DateTime $startDateTime The Event factoid's start date time
     * @return void
     */
    public function setStartDateTime($startDateTime = null) {
        if (!$startDateTime instanceof \DateTime) {
            throw new \InvalidArgumentException(sprintf('"%s" is not an instance of DateTime but an %s', $startDateTime, is_object($startDateTime) ? get_class($startDateTime) : gettype($startDateTime)));
        }
        $this->startDateTime = $startDateTime;
    }

    /**
     * Get the Event factoid's location
     *
     * @return \Org\Gucken\Events\Domain\Model\Location The Event factoid's location
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * Sets this Event factoid's location
     *
     * @param \Org\Gucken\Events\Domain\Model\Location $location The Event factoid's location
     * @return void
     */
    public function setLocation(Location $location = null) {
        $this->location = $location;
    }

    /**
     * Get the Event factoid's source
     *
     * @return \Org\Gucken\Events\Domain\Model\EventSource The Event factoid's source
     */
    public function getSource() {
        return $this->source;
    }

    /**
     * Sets this Event factoid's source
     *
     * @param \Org\Gucken\Events\Domain\Model\EventSource $source The Event factoid's source
     * @return void
     */
    public function setSource($source) {
        $this->source = $source;
    }
	
	/**
	 * @param Event
	 */
	public function setEvent(Event $event) {
		$this->event = $event;
	}
	
	/**
	 *
	 * @return Event
	 */
	public function getEvent() {
		return $this->event;
	}
	
	
	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection<\Org\Gucken\Events\Domain\Model\EventFactoid> $factoids
	 */
	public function setFactoids(\Doctrine\Common\Collections\ArrayCollection $factoids) {
		$this->factoids = $factoids;
	}
	
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection<\Org\Gucken\Events\Domain\Model\EventFactoid> 
	 */
	public function getFactoids() {
		return $this->factoids;
	}					
	
	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoid $factoid 
	 */
	public function addFactoid(\Org\Gucken\Events\Domain\Model\EventFactoid $factoid) {
		$factoid->setIdentity($this);
		if (!$factoid->getImportDateTime()) {
			$factoid->setImportDateTime(new \DateTime());
		}
		$this->factoids->add($factoid);
	}
	
	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoid $factoid 
	 * @return \Org\Gucken\Events\Domain\Model\EventFactoid 
	 */
	public function addFactoidIfNotExists(\Org\Gucken\Events\Domain\Model\EventFactoid $factoid) {
		foreach ($this->factoids as $aFactoid) {
			if ($aFactoid->equals($factoid)) {
				return $aFactoid;
			}
		}
			
		$this->addFactoid($factoid);
		return $factoid;
	}
	
	/**
	 *
	 * @return \Org\Gucken\Events\Domain\Model\EventFactoid 
	 */
	public function getFactoid() {		
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
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoid $factoid 
	 */
	public function removeFactoid(\Org\Gucken\Events\Domain\Model\EventFactoid $factoid) {
		$this->factoids->removeElement($factoid);
	}
	
	/**
	 * @return int
	 */
	public function countFactoids() {
		return $this->factoids->count();
	}
	
	/**
	 * @return boolean
	 */
	public function hasFactoids() {
		return $this->countFactoids() > 0;
	}
	
	
	/**
	 *
	 * @param boolean $shouldSkip 
	 */
	public function setShouldSkip($shouldSkip) {
		$this->shouldSkip = $shouldSkip;
	} 
	
	/**
	 *
	 * @return boolean 
	 */
	public function getShouldSkip() {
		return $this->shouldSkip;
	} 
		
	
	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return 
			$this->getStartDateTime()->format('d.M.Y H:i').
			' from '.($this->getSource() ? $this->getSource()->getName() : '[unknown]').
			' at '.($this->getLocation() ? $this->getLocation()->getName() : '[unknown]') .
			' with '.$this->factoids->count().' Factoids';
	}

}

?>