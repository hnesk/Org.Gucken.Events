<?php
namespace Org\Gucken\Events\Domain\Model;

/*                                                                        *
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
	 * @var EventSearchRequest
	 */
	protected $eventSearchRequest;

	/**
	 *
	 * @var EventSearchRequest
	 */
	protected $factoidSearchRequest;

	/**
	 *
	 * @var LocationSearchRequest
	 */
	protected $locationSearchRequest;

	/**
	 *
	 * @return EventSearchRequest
	 */
	public function getEventSearchRequest() {
		return $this->eventSearchRequest  ?: new EventSearchRequest();
	}

	/**
	 *
	 * @param EventSearchRequest $eventSearchRequest
     * @return EventSearchRequest
     */
	public function setEventSearchRequest(EventSearchRequest $eventSearchRequest = null) {
		$this->eventSearchRequest = $eventSearchRequest ?: new EventSearchRequest();
		return $this->eventSearchRequest;
	}

	/**
	 *
	 * @param EventSearchRequest $eventSearchRequest
	 * @param string $orderColumn
	 * @param string $orderDirection
	 * @return EventSearchRequest
	 */
	public function updateEventSearchRequest(EventSearchRequest $eventSearchRequest = null, $orderColumn = '', $orderDirection = '') {
		return $this->setEventSearchRequest($this->getEventSearchRequest()->update($eventSearchRequest, $orderColumn, $orderDirection));
	}


	/**
	 *
	 * @return LocationSearchRequest
	 */
	public function getLocationSearchRequest() {
		return $this->locationSearchRequest  ?: new LocationSearchRequest();
	}

	/**
	 *
	 * @param LocationSearchRequest $locationSearchRequest
	 * @return LocationSearchRequest
	 */
	public function setLocationSearchRequest(LocationSearchRequest $locationSearchRequest = null) {
		$this->locationSearchRequest = $locationSearchRequest ?: new LocationSearchRequest();
		return $this->locationSearchRequest;
	}

	/**
	 *
	 * @param LocationSearchRequest $locationSearchRequest
	 * @param string $orderColumn
	 * @param string $orderDirection
	 * @return LocationSearchRequest
	 */
	public function updateLocationSearchRequest(LocationSearchRequest $locationSearchRequest = null, $orderColumn = 'title', $orderDirection = '') {
		return $this->setLocationSearchRequest($this->getLocationSearchRequest()->update($locationSearchRequest, $orderColumn, $orderDirection));
	}

	/**
	 *
	 * @return EventSearchRequest
	 */
	public function getFactoidSearchRequest() {
		return $this->factoidSearchRequest ?: new EventSearchRequest();
	}

    /**
     *
     * @param EventSearchRequest $factoidSearchRequest
     * @return EventSearchRequest
     */
	public function setFactoidSearchRequest(EventSearchRequest $factoidSearchRequest = null) {
		$this->factoidSearchRequest = $factoidSearchRequest ?: new EventSearchRequest();
		return $this->factoidSearchRequest;
	}

	/**
	 *
	 * @param EventSearchRequest $factoidSearchRequest
	 * @param string $orderColumn
	 * @param string $orderDirection
	 * @return EventSearchRequest
	 */
	public function updateFactoidSearchRequest(EventSearchRequest $factoidSearchRequest = null, $orderColumn = '', $orderDirection = '') {
		return $this->setFactoidSearchRequest($this->getFactoidSearchRequest()->update($factoidSearchRequest, $orderColumn, $orderDirection));
	}


}
?>