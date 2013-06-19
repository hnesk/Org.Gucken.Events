<?php

namespace Org\Gucken\Events\Domain\Model;

/* *
 * This script belongs to the Flow package "Org.Gucken.Events".           *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A Event factoids persitstent identity
 *
 * @Flow\Scope("prototype")
 * @Flow\Entity
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
	 * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $source;
	
    /**
     * The link 
     * @var \Org\Gucken\Events\Domain\Model\EventLink
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
	 *
	 * @return \Org\Gucken\Events\Domain\Model\EventLink
	 */
	public function createLink() {
		$this->setLink($this->source->convertLink($this));
		return $this->getLink();
	}
	/**
	 * @return \Org\Gucken\Events\Domain\Model\EventLink
	 */
	public function getLink() {
		return $this->link;
	}
	
	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventLink $link 
	 */
	public function setLink(\Org\Gucken\Events\Domain\Model\EventLink $link = null) {
		$this->link = $link;
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
	
	public function getSortedFactoids() {		
		$factoids = $this->factoids->toArray();
		usort($factoids, function (EventFactoid $a, EventFactoid $b) { return $b->getImportDateTime()->getTimestamp() -  $a->getImportDateTime()->getTimestamp(); });
		return new \Doctrine\Common\Collections\ArrayCollection($factoids);
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
			$this->getStartDateTime()->format('d.m.Y H:i').
			' @'.($this->getLocation() ? $this->getLocation()->getName() : '[unknown]') .
			' #'.$this->factoids->count();
	}

}

?>