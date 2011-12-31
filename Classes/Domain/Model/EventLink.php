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
 * An Event link
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @FLOW3\Scope("prototype")
 * @FLOW3\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * 
 */
abstract class EventLink {
	
	#@ORM\DiscriminatorMap({"person" = "Person", "employee" = "Employee"})
	/**
	 *
	 * @var Org\Gucken\Events\Domain\Model\Event
	 * @ORM\ManyToOne(inversedBy="links")
	 */
	protected $event;
	
	/**
	 *
	 * @var Org\Gucken\Events\Domain\Model\EventFactoid
	 * @ORM\OneToOne
	 */
	protected $factoid;

	/**
	 * @var string
	 */
	protected $url;
		

	/**
	 * Get the event link type
	 *
	 * @return string The event link type
	 */
	public function getType() {
		return get_class($this);
	}


	/**
	 * Get the Event
	 *
	 * @return \Org\Gucken\Events\Domain\Model\Event The Event
	 */
	public function getEvent() {
		return $this->event;
	}

	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoid $factoid 
	 * @return void
	 */
	public function setFactoid(\Org\Gucken\Events\Domain\Model\EventFactoid $factoid) {
		$this->factoid = $factoid;
	}	
	
	/**
	 * @return \Org\Gucken\Events\Domain\Model\EventFactoid 
	 */
	public function getFactoid() {
		return $this->factoid;
	}

	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\Event $event The Event
	 * @return void
	 */
	public function setEvent(\Org\Gucken\Events\Domain\Model\Event $event) {
		$this->event= $event;
	}		
	
	
	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}
	
	/**
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}
	
	/**
	 * @return string
	 */
	public function getIcon() {
		return 'web.png';
	}

	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->getUrl();
	}
}
?>