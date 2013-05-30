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


use TYPO3\Flow\Annotations as Flow;

/**
 * The backend session
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @Flow\Scope("session")
 *
 */
class BackendSession {
	/**
	 *
	 * @var \Org\Gucken\Events\Domain\Model\EventSearchRequest
	 */
	protected $eventSearchRequest;

	/**
	 *
	 * @var \Org\Gucken\Events\Domain\Model\EventSearchRequest
	 */
	protected $factoidSearchRequest;

	/**
	 *
	 * @var \Org\Gucken\Events\Domain\Model\LocationSearchRequest
	 */
	protected $locationSearchRequest;

	/**
	 *
	 * @return \Org\Gucken\Events\Domain\Model\EventSearchRequest
	 */
	public function getEventSearchRequest() {
		return $this->eventSearchRequest  ?: new \Org\Gucken\Events\Domain\Model\EventSearchRequest();
	}

	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventSearchRequest $eventSearchRequest
	 */
	public function setEventSearchRequest(\Org\Gucken\Events\Domain\Model\EventSearchRequest $eventSearchRequest = null) {
		$this->eventSearchRequest = $eventSearchRequest ?: new \Org\Gucken\Events\Domain\Model\EventSearchRequest();
		return $this->eventSearchRequest;
	}

	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventSearchRequest $eventSearchRequest
	 * @param string $orderColumn
	 * @param string $orderDirection
	 * @return \Org\Gucken\Events\Domain\Model\EventSearchRequest
	 */
	public function updateEventSearchRequest(\Org\Gucken\Events\Domain\Model\EventSearchRequest $eventSearchRequest = null, $orderColumn = '', $orderDirection = '') {
		return $this->setEventSearchRequest($this->getEventSearchRequest()->update($eventSearchRequest, $orderColumn, $orderDirection));
	}


	/**
	 *
	 * @return \Org\Gucken\Events\Domain\Model\LocationSearchRequest
	 */
	public function getLocationSearchRequest() {
		return $this->locationSearchRequest  ?: new \Org\Gucken\Events\Domain\Model\LocationSearchRequest();
	}

	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\LocationSearchRequest $locationSearchRequest
	 * @return \Org\Gucken\Events\Domain\Model\LocationSearchRequest
	 */
	public function setLocationSearchRequest(\Org\Gucken\Events\Domain\Model\LocationSearchRequest $locationSearchRequest = null) {
		$this->locationSearchRequest = $locationSearchRequest ?: new \Org\Gucken\Events\Domain\Model\LocationSearchRequest();
		return $this->locationSearchRequest;
	}

	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\LocationSearchRequest $locationSearchRequest
	 * @param string $orderColumn
	 * @param string $orderDirection
	 * @return \Org\Gucken\Events\Domain\Model\LocationSearchRequest
	 */
	public function updateLocationSearchRequest(\Org\Gucken\Events\Domain\Model\LocationSearchRequest $locationSearchRequest = null, $orderColumn = 'title', $orderDirection = '') {
		return $this->setLocationSearchRequest($this->getLocationSearchRequest()->update($locationSearchRequest, $orderColumn, $orderDirection));
	}

	/**
	 *
	 * @return \Org\Gucken\Events\Domain\Model\EventSearchRequest
	 */
	public function getFactoidSearchRequest() {
		return $this->factoidSearchRequest ?: new \Org\Gucken\Events\Domain\Model\EventSearchRequest();
	}

	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventSearchRequest $factoidSearchRequest
	 */
	public function setFactoidSearchRequest(\Org\Gucken\Events\Domain\Model\EventSearchRequest $factoidSearchRequest = null) {
		$this->factoidSearchRequest = $factoidSearchRequest ?: new \Org\Gucken\Events\Domain\Model\EventSearchRequest();
		return $this->factoidSearchRequest;
	}

	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventSearchRequest $factoidSearchRequest
	 * @param string $orderColumn
	 * @param string $orderDirection
	 * @return \Org\Gucken\Events\Domain\Model\EventSearchRequest
	 */
	public function updateFactoidSearchRequest(\Org\Gucken\Events\Domain\Model\EventSearchRequest $factoidSearchRequest = null, $orderColumn = '', $orderDirection = '') {
		return $this->setFactoidSearchRequest($this->getFactoidSearchRequest()->update($factoidSearchRequest, $orderColumn, $orderDirection));
	}


}
?>