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


use TYPO3\FLOW3\Annotations as FLOW3;
use Org\Gucken\Events\Domain\Model\Location;
use Org\Gucken\Events\Domain\Model\Type;
use Org\Gucken\Events\Domain\Model\AbstractSearchRequest;
/**
 * An Event search request
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @FLOW3\Scope("prototype")
 *
 */
class EventSearchRequest extends AbstractSearchRequest {

	/**
	 *
	 * @var \DateTime
	 */
	protected $startDate;


	/**
	 *
	 * @var \DateTime
	 */
	protected $endDate;


	/**
	 *
	 * @var string
	 */
	protected $title;

	/**
	 *
	 * @var \Org\Gucken\Events\Domain\Model\Type
	 */
	protected $type;

	/**
	 *
	 * @var \Org\Gucken\Events\Domain\Model\Location
	 */
	protected $location;

	/**
	 *
	 * @var string
	 * @FLOW3\Validate(type="\Org\Gucken\Events\Domain\Validator\PropertyNameValidator", options = {"class" = "\Org\Gucken\Events\Domain\Model\Event"})
	 */
	protected $orderColumn;

	/**
	 *
	 * @param \DateTime $startDate
	 * @param \DateTime $endDate
	 */
	public function __construct($startDate = null, $endDate = null, $title = null, $type = null, $location = null, $orderColumn = 'startDateTime', $orderDirection = \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING) {
		$this->setStartDate($startDate);
		$this->setEndDate($endDate);
		$this->setTitle($title);
		$this->setType($type);
		$this->setLocation($location);
		$this->setOrder($orderColumn, $orderDirection);
	}


	/**
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 *
	 * @param string $title
	 * @FLOW3\Session(autoStart=true)
	 */
	public function setTitle($title) {
		$this->title= $title;
	}


		/**
	 *
	 * @return \DateTime
	 */
	public function getStartDate() {
		return $this->startDate;
	}


	/**
	 *
	 * @param \DateTime $startDate
	 */
	public function setStartDate(\DateTime $startDate = null) {
		$this->startDate = $startDate ?: new \DateTime();
	}

	/**
	 *
	 * @return \DateTime
	 */
	public function getEndDate() {
		return $this->endDate;
	}

	/**
	 *
	 * @param \DateTime $endDate
	 * @FLOW3\Session(autoStart=true)
	 */
	public function setEndDate(\DateTime $endDate = null) {
		if (!$endDate) {
			if ($this->startDate) {
				$endDate = clone $this->startDate;
			} else {
				$endDate = new \DateTime();
			}
			$endDate->modify('+14 days');
		}
		$this->endDate = $endDate;

	}

	/**
	 *
	 * @return Type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\Type $type
	 */
	public function setType(\Org\Gucken\Events\Domain\Model\Type $type = null) {
		$this->type = $type;
	}

	/**
	 *
	 * @return Location
	 */
	public function getLocation() {
		return $this->location;
	}

	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\Location $location
	 */
	public function setLocation(\Org\Gucken\Events\Domain\Model\Location $location = null) {
		$this->location = $location;
	}


	/**
	 *
	 * @param \TYPO3\FLOW3\Persistence\QueryInterface $query
	 * @return \TYPO3\FLOW3\Persistence\QueryInterface
	 */
	public function buildFilters(\TYPO3\FLOW3\Persistence\QueryInterface $query) {
		$conditions = array();

		if ($this->getStartDate()) {
			$conditions[] = $query->greaterThanOrEqual('startDateTime', $this->getStartDate());
		}
		if ($this->getEndDate()) {
			$conditions[] = $query->lessThanOrEqual('startDateTime', $this->getEndDate());
		}

		if ($this->getTitle()) {
			$conditions[] = $query->like('title', $this->getTitle());
		}

		if ($this->getLocation()) {
			$conditions[] = $query->equals('location', $this->getLocation());
		}

		if ($this->getType()) {
			$conditions[] = $query->contains('types', $this->getType());
		}

		return $conditions;
	}

	/**
	 *
	 * @param EventSearchRequest $searchRequest
	 * @return EventSearchRequest
	 */
	public function updateSearchRequest(AbstractSearchRequest $searchRequest = null) {
		if ($searchRequest->getStartDate()) {
			$this->setStartDate($searchRequest->getStartDate());
		}
		if ($searchRequest->getEndDate()) {
			$this->setEndDate($searchRequest->getEndDate());
		}

		$this->setLocation($searchRequest->getLocation());
		$this->setTitle($searchRequest->getTitle());
		$this->setType($searchRequest->getType());

		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return 'vom '.($this->startDate ? $this->startDate->format('d.m') : '') . ' - ' .  ($this->endDate ? $this->endDate->format('d.m.Y') : '').
			($this->getTitle() ? ', passend auf "'.$this->getTitle().'"' : '').
			($this->getLocation() ? ', im "'.$this->getLocation()->getName().'"' : '').
			($this->getType() ? ' vom Typ "'.$this->getType()->getTitle().'"' : '');
	}

}
?>