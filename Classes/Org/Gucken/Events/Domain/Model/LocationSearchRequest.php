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
use Org\Gucken\Events\Domain\Model\Location;
use Org\Gucken\Events\Domain\Model\Type;

/**
 * An Event search request
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @Flow\Scope("prototype")
 *
 */
class LocationSearchRequest extends AbstractSearchRequest {

	/**
	 *
	 * @var string
	 */
	protected $name;

	/**
	 *
	 * @var string
	 */
	protected $city;

	/**
	 *
	 * @var integer
	 */
	protected $reviewed;

	/**
	 *
	 * @param string $title
	 * @param integer $reviewed
	 * @param string $orderColumn
	 * @param string $orderDirection
	 */
	public function __construct($name = null, $reviewed = null, $city = null,$orderColumn = 'name', $orderDirection = \TYPO3\Flow\Persistence\QueryInterface::ORDER_DESCENDING) {
		$this->setName($name);
		$this->setReviewed($reviewed);
		$this->setCity($city);
		$this->setOrder($orderColumn, $orderDirection);
	}


	/**
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 *
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 *
	 * @return integer
	 */
	public function getReviewed() {
		return $this->reviewed;
	}

	/**
	 *
	 * @param integer $reviewed
	 */
	public function setReviewed($reviewed) {
		$this->reviewed = $reviewed;
	}

	/**
	 *
	 * @return string
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 *
	 * @param string $city
	 */
	public function setCity($city) {
		$this->city = $city;
	}


	/**
	 *
	 * @param \TYPO3\Flow\Persistence\QueryInterface $query
	 * @return array
	 */
	public function buildFilters(\TYPO3\Flow\Persistence\QueryInterface $query) {
		$conditions = array();

		if ($this->getName()) {
			$conditions[] = $query->like('name', $this->getName());
		}

		if ($this->getReviewed()) {
			$conditions[] = $query->equals('reviewed', $this->getReviewed() < 0 ? false : true);
		}
		if ($this->getCity()) {
			$conditions[] = $query->equals('address.addressLocality', $this->getCity());
		}


		return $conditions;
	}

	/**
	 *
	 * @param LocationSearchRequest $searchRequest
	 * @return LocationSearchRequest
	 */
	public function updateSearchRequest(AbstractSearchRequest $searchRequest = null) {
		$this->setName($searchRequest->getName());
		$this->setReviewed($searchRequest->getReviewed());
		$this->setCity($searchRequest->getCity());

		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return
			($this->getName() ? ', passend auf "'.$this->getName().'"' : '').
			($this->getReviewed() ? ($this->getReviewed() > 0 ? ' mit' : ' ohne').' Review' : '')
		;
	}


}
?>