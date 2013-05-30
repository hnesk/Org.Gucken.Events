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
use TYPO3\Flow\Annotations as Flow;

/**
 * An Event link
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @Flow\Scope("prototype")
 * @Flow\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * 
 */
abstract class EventLink {	
	/**
	 *
	 * @var \Org\Gucken\Events\Domain\Model\Event
	 * @ORM\ManyToOne(inversedBy="links")
	 */
	protected $event;
	
	/**
	 *
	 * @var \Org\Gucken\Events\Domain\Model\EventFactoidIdentity
	 * @ORM\OneToOne
	 * 
	 */
	protected $factoidIdentity;

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
	
	public function getClass() {
		$qualifiedType = explode('\\',$this->getType());
		return strtolower(end($qualifiedType));
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
	 * Get the Event
	 *
	 * @return \Org\Gucken\Events\Domain\Model\Event The Event
	 */
	public function getEvent() {
		return $this->event;
	}

	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoidIdentity $factoid 
	 * @return void
	 */
	public function setFactoidIdentity(\Org\Gucken\Events\Domain\Model\EventFactoidIdentity $factoidIdentity) {
		$this->factoidIdentity = $factoidIdentity;
	}	
	
	/**
	 * @return \Org\Gucken\Events\Domain\Model\EventFactoidIdentity
	 */
	public function getFactoidIdentity() {
		return $this->factoidIdentity;
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