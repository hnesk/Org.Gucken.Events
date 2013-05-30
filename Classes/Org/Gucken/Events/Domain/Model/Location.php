<?php

namespace Org\Gucken\Events\Domain\Model;

/* *
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

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A Location
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @Flow\Scope("prototype")
 * @Flow\Entity
 */
class Location implements \Org\Gucken\Events\Domain\Model\ScorableInterface {

    /**
     * The name
     * @var string
     */
    protected $name;

    /**
     * The description
     * @var string
     */
    protected $description;

    /**
     * The url
     * @var string
     */
    protected $url;


    /**
     * Is this location reviewed
     * @var boolean
     */
    protected $reviewed;


    /**
     * The address
     * @var \Org\Gucken\Events\Domain\Model\PostalAddress
     * @ORM\OneToOne(cascade={"all"}, orphanRemoval=true)
     */
    protected $address;

    /**
     * The geo
     * @var \Org\Gucken\Events\Domain\Model\GeoCoordinates
     * @ORM\OneToOne(cascade={"all"}, orphanRemoval=true)
     */
    protected $geo;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection<\Org\Gucken\Events\Domain\Model\ExternalLocationIdentifier>
     * @ORM\OneToMany(mappedBy="location", cascade={"all"}, orphanRemoval=true)
     */
    protected $externalIdentifiers;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection<\Org\Gucken\Events\Domain\Model\LocationKeyword>
     * @ORM\OneToMany(mappedBy="location", cascade={"all"}, orphanRemoval=true)
     */
    protected $keywords;


    /**
     * The fax number
     * @var string
     */
    protected $faxNumber;

    /**
     * The telephone
     * @var string
     */
    protected $telephone;

    /**
     * The email
     * @var string
     */
    protected $email;

	/**
	 *
	 * @var string
	 */
	protected $code;

    public function __construct() {
        $this->externalIdentifiers = new \Doctrine\Common\Collections\ArrayCollection();
		$this->keywords = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get the Location's name
     *
     * @return string The Location's name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets this Location's name
     *
     * @param string $name The Location's name
     * @return void
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Get the Location's description
     *
     * @return string The Location's description
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Sets this Location's description
     *
     * @param string $description The Location's description
     * @return void
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Get the Location's url
     *
     * @return string The Location's url
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Sets this Location's url
     *
     * @param string $url The Location's url
     * @return void
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * Get the Location's address
     *
     * @return \Org\Gucken\Events\Domain\Model\PostalAddress The Location's address
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Sets this Location's address
     *
     * @param \Org\Gucken\Events\Domain\Model\PostalAddress $address The Location's address
     * @return void
     */
    public function setAddress($address) {
        $this->address = $address;
    }

    /**
     * Get the Location's geo
     *
     * @return \Org\Gucken\Events\Domain\Model\GeoCoordinates The Location's geo
     */
    public function getGeo() {
        return $this->geo;
    }

    /**
     * Sets this Location's geo
     *
     * @param \Org\Gucken\Events\Domain\Model\GeoCoordinates $geo The Location's geo
     * @return void
     */
    public function setGeo($geo) {
        $this->geo = $geo;
    }

    /**
     * Get the Location's fax number
     *
     * @return string The Location's fax number
     */
    public function getFaxNumber() {
        return $this->faxNumber;
    }

    /**
     * Sets this Location's fax number
     *
     * @param string $faxNumber The Location's fax number
     * @return void
     */
    public function setFaxNumber($faxNumber) {
        $this->faxNumber = $faxNumber;
    }

    /**
     * Get the Location's telephone
     *
     * @return string The Location's telephone
     */
    public function getTelephone() {
        return $this->telephone;
    }

    /**
     * Sets this Location's telephone
     *
     * @param string $telephone The Location's telephone
     * @return void
     */
    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    /**
     * Get the Location's email
     *
     * @return string The Location's email
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Sets this Location's email
     *
     * @param string $email The Location's email
     * @return void
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * Setter for extenal identifiers
     *
     * @param \Doctrine\Common\Collections\Collection<\Org\Gucken\Events\Domain\Model\ExternalLocationIdentifier> $externalIdentifiers
     * @return void
     */
    public function setExternalIdentifiers(\Doctrine\Common\Collections\Collection $externalIdentifiers) {
        $this->externalIdentifiers = $externalIdentifiers;
    }

    /**
     * Adds a location identifier
     *
     * @param \Org\Gucken\Events\Domain\Model\ExternalLocationIdentifier $externalIdentifier
     * @return void
     */
    public function addExternalIdentifier(\Org\Gucken\Events\Domain\Model\ExternalLocationIdentifier $externalIdentifier) {
        $externalIdentifier->setLocation($this);
        $this->externalIdentifiers->add($externalIdentifier);
    }

    /**
     * Adds a location identifier
     *
     * @param \Org\Gucken\Events\Domain\Model\ExternalLocationIdentifier $externalIdentifier
     * @return void
     */
    public function removeExternalIdentifier(\Org\Gucken\Events\Domain\Model\ExternalLocationIdentifier $externalIdentifier) {
        $this->externalIdentifiers->removeElement($externalIdentifier);
        ;
    }

    /**
     * Getter for location indentifiers
     *
     * @return \Doctrine\Common\Collections\Collection<\Org\Gucken\Events\Domain\Model\ExternalLocationIdentifier>
     */
    public function getExternalIdentifiers() {
        return clone $this->externalIdentifiers;
    }

	/**
	 *
	 * @return int
	 */
	public function getExternalIdentifierCount() {
		return count($this->externalIdentifiers);
	}


    /**
     * Setter for keywords
     *
     * @param \Doctrine\Common\Collections\Collection<\Org\Gucken\Events\Domain\Model\LocationKeyword> $keywords
     * @return void
     */
    public function setKeywords(\Doctrine\Common\Collections\Collection $keywords) {
        $this->keywords = $keywords;
    }

    /**
     * Adds a keyword
     *
     * @param \Org\Gucken\Events\Domain\Model\LocationKeyword $keyword
     * @return void
     */
    public function addKeyword(\Org\Gucken\Events\Domain\Model\LocationKeyword $keyword) {
        #$keyword->setLocation($this);
        $this->keywords->add($keyword);
    }

    /**
     * Adds a keyword
     *
     * @param string $keyword
     * @return void
     */
    public function addKeywordAsString($keyword) {
        $this->addKeyword(new LocationKeyword($keyword, $this, $this->getName()));
    }


    /**
     * removes a keyword
     *
     * @param \Org\Gucken\Events\Domain\Model\LocationKeyword $keyword
     * @return void
     */
    public function removeKeyword(\Org\Gucken\Events\Domain\Model\LocationKeyword $keyword) {
        $this->keywords->removeElement($keyword);
    }

    /**
     * Getter for location keywords
     *
     * @return \Doctrine\Common\Collections\Collection<\Org\Gucken\Events\Domain\Model\LocationKeyword>
     */
    public function getKeywords() {
        return clone $this->keywords;
    }


	/**
	 *
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 *
	 * @param string $code
	 */
	public function setCode($code) {
		$this->code = $code;
	}


    /**
     * Getter for location keywords as an plain array
     *
     * @return array
     */
    public function getKeywordArray() {
		$keywords = array();
		foreach ($this->keywords as $keyword) {
			$keywords[] = mb_strtolower($keyword->getKeyword(), 'utf-8');
		}
		return $keywords;
    }

	/**
	 *
	 * @param array $keywords
	 * @return float
	 */
	public function score(array $keywordLookup) {
		$score = 0;
		foreach ($this->getKeywordArray() as $keyword) {
			if (isset($keywordLookup[$keyword])) {
				$score++;
			}
		}
		return $score;
	}


	/**
	 * Helper function to remove empty keywords
	 */
	public function removeEmptyKeywords() {
        foreach ($this->keywords as $key =>$locationKeyword) {
            if (trim($locationKeyword->getKeyword()) === '') {
                $this->keywords->remove($key);
            }
        }
	}

	/**
	 * Helper function to remove empty keywords
	 */
	public function removeEmptyExternalIdentifier() {
        foreach ($this->externalIdentifiers as $key =>$externalIdentifier) {
            if (trim($externalIdentifier->getSchemeLabel()) === '') {
                $this->externalIdentifiers->remove($key);
            }
        }
	}

	/**
	 *
	 */
	public function removeEmptyRelations() {
		$this->removeEmptyExternalIdentifier();
		$this->removeEmptyKeywords();
	}


	/**
	 *
	 * @return boolean
	 */
	public function isReviewed() {
		return $this->reviewed;
	}


	/**
	 *
	 * @return boolean
	 */
	public function getReviewed() {
		return $this->reviewed;
	}

	/**
	 *
	 * @param boolean $reviewed
	 */
	public function setReviewed($reviewed) {
		$this->reviewed = $reviewed;
	}


    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->getName() . ' - ' . $this->getAddress();
    }

}

?>