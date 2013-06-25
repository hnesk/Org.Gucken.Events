<?php

namespace Org\Gucken\Events\Domain\Model;

/* *
 * This script belongs to the Flow package "Org.Gucken.Events".           *
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
 * An identifier for a location on an external website or service
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @Flow\Scope("prototype")
 * @Flow\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="scheme", type="string")
 */
abstract class ExternalLocationIdentifier {

    /**
     * The external Id
     * 
     * @var string
     * @Flow\Validate(type="StringLength", options={ "minimum"=0, "maximum"=255 })     * 
     */
    protected $id;
    
    /**
     *
     * @var Org\Gucken\Events\Domain\Model\Location
     * @ORM\ManyToOne(inversedBy="externalIdentifiers")
     */
    protected $location;
	
	/**
	 *
	 * @var string
     * @ORM\Column(nullable=true)
	 */
	protected $label;

    /**
     *
     * @param string $scheme
     * @param string $id 
     * @param Org\Gucken\Events\Domain\Model\Location $location 
     */
    public function __construct($id = 0, \Org\Gucken\Events\Domain\Model\Location $location = null, $label = '') {
        $this->id = $id;
        $this->location = $location;
		$this->label = $label;
    }
    
	abstract public function getSchemeLabel();
	
	abstract public function getCandidates(Location $location);
	
	abstract public function getUrl(); 

    /**
     * Get the Location's id in the scheme
     *
     * @return string The Location's Id 
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     *
     * @param string $id 
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    
    /**
     *
     * @return Org\Gucken\Events\Domain\Model\Location
     */
    protected function getLocation() {
        return $this->location;
    }
    
    /**
     *
     * @param \Org\Gucken\Events\Domain\Model\Location $location 
     */
    public function setLocation(\Org\Gucken\Events\Domain\Model\Location $location) {
        $this->location = $location;
    }
	
	/**
	 *
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 *
	 * @param string $label 
	 */
	public function setLabel($label) {
		$this->label = $label;
	}

    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->getSchemeLabel() . ':' . $this->getId();
    }

}

?>