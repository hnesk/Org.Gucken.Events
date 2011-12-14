<?php
namespace Org\Gucken\Events\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "Events".                     *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License as published by the Free   *
 * Software Foundation, either version 3 of the License, or (at your      *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        *
 * You should have received a copy of the GNU General Public License      *
 * along with the script.                                                 *
 * If not, see http://www.gnu.org/licenses/gpl.html                       *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Org\Gucken\Events\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * An Event
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @FLOW3\Scope("prototype")
 * @FLOW3\Entity
 */
class Event {

	/**
	 * The title
	 * @var string
	 */
	protected $title;

	/**
	 * The start date and time
	 * @var \DateTime
	 */
	protected $startDateTime;
	
	/**
	 * The end date and time
	 * @var \DateTime
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
	 * @var \Doctrine\Common\Collections\Collection<\Org\Gucken\Events\Domain\Model\Type>
	 * @ORM\ManyToMany
	 */
	protected $types;
	
	/**
	 * The identity of the imported factoids this event is based on
	 * @var \Doctrine\Common\Collections\Collection<\Org\Gucken\Events\Domain\Model\EventFactoidIdentity>
	 * @ORM\OneToMany(mappedBy="event", cascade={"all"}, orphanRemoval=true)
	 */	
	protected $factoidIdentitys;
	
	
	/**
	 * Markdown version of the description
	 * 
	 * @ORM\Column(type="text") 
	 * @var string
	 */
	protected $description;
	
	/**
	 * Markdown version of the description
	 * 
	 * @ORM\Column(type="text") 
	 * @var string
	 */
	protected $shortDescription;

	/**
	 * The url
	 * 
	 * @var string
	 */
	protected $url;
	
	
	/**
	 *
	 * @var \Org\Gucken\Events\Domain\Model\Day\Factory
	 * @FLOW3\Inject
	 */
	protected $dayFactory;
	
	public function __construct() {
		$this->types = new \Doctrine\Common\Collections\ArrayCollection();
		$this->factoidIdentitys = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Get the Event's title
	 *
	 * @return string The Event's title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets this Event's title
	 *
	 * @param string $title The Event's title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Get the Event's date
	 *
	 * @return \DateTime The Event's start date
	 */
	public function getStartDateTime() {
		return $this->startDateTime;
	}

	/**
	 * Sets this Event's start date
	 *
	 * @param \DateTime $startDateTime The Event's date
	 * @return void
	 */
	public function setStartDateTime(\DateTime $startDateTime) {
		$this->startDateTime = $startDateTime;
	}
	
	/**
	 * Get the Event's end date
	 *
	 * @return \DateTime The Event's end date
	 */
	public function getEndDateTime() {
		return $this->endDateTime;
	}

	/**
	 * Sets this Event's end date
	 *
	 * @param \DateTime $endDateTime The Event's end date
	 * @return void
	 */
	public function setEndDateTime(\DateTime $endDateTime = null) {
		$this->endDateTime = $endDateTime;
	}
	

	/**
	 * @return Org\Gucken\Events\Domain\Model\Day
	 */
	public function getDay() {
		return $this->dayFactory->build($this->startDateTime);
	}


	/**
	 * Get the Event's location
	 *
	 * @return \Org\Gucken\Events\Domain\Model\Location The Event's location
	 */
	public function getLocation() {
		return $this->location;
	}

	/**
	 * Sets this Event's location
	 *
	 * @param \Org\Gucken\Events\Domain\Model\Location $location The Event's location
	 * @return void
	 */
	public function setLocation(\Org\Gucken\Events\Domain\Model\Location $location) {
		$this->location = $location;
	}
	
	
	/**
	 *
	 * @param string $description 
	 */
	public function setDescription($description) {
		$this->description = $description;
	}
	
	/**
	 *
	 * @return string 
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 *
	 * @param string $shortDescription 
	 */
	public function setShortDescription($shortDescription) {
		$this->shortDescription = $shortDescription;
	}
	
	/**
	 *
	 * @return string 
	 */
	public function getShortDescription() {
		return $this->shortDescription;
	}
	
	
    /**
     * Setter for types
     *
     * @param \Doctrine\Common\Collections\Collection<\Org\Gucken\Events\Domain\Model\Types> $types
     * @return void
     */
    public function setTypes(\Doctrine\Common\Collections\Collection $types) {
        $this->types = $types;
    }

    /**
     * Adds a type
     *
     * @param \Org\Gucken\Events\Domain\Model\Type $type
     * @return void
     */
    public function addType(\Org\Gucken\Events\Domain\Model\Type $type = null) {
		if ($type) {
			$this->types->add($type);
		}
    }

    /**
     * removes a type
     *
     * @param \Org\Gucken\Events\Domain\Model\Type $type
     * @return void
     */
    public function removeType(\Org\Gucken\Events\Domain\Model\Type $type) {
        $this->types->removeElement($type);
    }

    /**
     * Getter for types
     *
     * @return \Doctrine\Common\Collections\Collection<\Org\Gucken\Events\Domain\Model\Type> 
     */
    public function getTypes() {
        return clone $this->types;
    }

	
    /**
     * Setter for factoid identities
     *
     * @param \Doctrine\Common\Collections\Collection<\Org\Gucken\Events\Domain\Model\EventFactoidIdentities> $factoidIdentities
     * @return void
     */
    public function setFactoidIdentitys(\Doctrine\Common\Collections\Collection $factoidIdentitys) {
        $this->factoidIdentitys = $factoidIdentitys;
    }

    /**
     * Adds a type
     *
     * @param \Org\Gucken\Events\Domain\Model\Type $type
     * @return void
     */
    public function addFactoidIdentity(\Org\Gucken\Events\Domain\Model\EventFactoidIdentity $factoidIdentity) {
        $this->factoidIdentitys->add($factoidIdentity);
    }

    /**
     * removes a type
     *
     * @param \Org\Gucken\Events\Domain\Model\Type $type
     * @return void
     */
    public function removeFactoidIdentity(\Org\Gucken\Events\Domain\Model\EventFactoidIdentity $factoidIdentity) {
        $this->factoidIdentitys->removeElement($factoidIdentity);
    }

    /**
     * Getter for types
     *
     * @return \Doctrine\Common\Collections\Collection<\Org\Gucken\Events\Domain\Model\EventFactoidIdentitys> 
     */
    public function getFactoidIdentitys() {
        return clone $this->factoidIdentitys;
    }
	
	/**
	 *
	 * @param string $url 
	 */
	public function setUrl($url) {
		$this->url = $url;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->getTitle().' '.$this->getStartDateTime()->format('d.m.Y H:i'). ($this->location ? ' @ '.$this->location->getName() : '');
	}
}
?>