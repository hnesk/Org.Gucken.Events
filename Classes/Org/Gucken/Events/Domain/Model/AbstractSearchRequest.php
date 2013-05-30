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
 * An abstract search request
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @Flow\Scope("prototype")
 *
 */
abstract class AbstractSearchRequest {

	/**
	 *
	 * @var string
	 */
	protected $orderColumn;

	/**
	 *
	 * @var string
	 * @Flow\Validate(type="RegularExpression", options={ "regularExpression"="#ASC|DESC#"})
	 */
	protected $orderDirection;

	/**
	 *
	 * @param \TYPO3\Flow\Persistence\QueryInterface $query
	 * @return \TYPO3\Flow\Persistence\QueryInterface
	 */
	public function apply(\TYPO3\Flow\Persistence\QueryInterface $query) {


		if ($this->getOrderColumn()) {
			$query->setOrderings(array($this->getOrderColumn() => $this->getOrderDirection()));
		}

		$conditions = $this->buildFilters($query);

		if (count($conditions) > 0) {
			return $query->matching($query->logicalAnd($conditions));
		} else {
			return $query;
		}
	}

	/**
	 *  @param \TYPO3\Flow\Persistence\QueryInterface $query
	 *  @return array
	 */
	abstract protected function buildFilters(\TYPO3\Flow\Persistence\QueryInterface $query);

	/**
	 *
	 * @param string $column
	 * @param string $direction
	 *
	 */
	public function setOrder($column, $direction) {
		$this->setOrderColumn($column);
		$this->setOrderDirection($direction);
	}

	/**
	 *
	 * @return string
	 */
	public function getOrderColumn() {
		return $this->orderColumn;
	}

	/**
	 *
	 * @param string $orderColumn
	 */
	public function setOrderColumn($orderColumn) {
		$this->orderColumn = $orderColumn;
	}

	/**
	 *
	 * @return string
	 */
	public function getOrderDirection() {
		return $this->orderDirection ?: \TYPO3\Flow\Persistence\QueryInterface::ORDER_ASCENDING;
	}

	/**
	 *
	 * @param string $orderDirection
	 */
	public function setOrderDirection($orderDirection) {
		$this->orderDirection = $orderDirection;
	}

	/**
	 *
	 * @param EventSearchRequest $searchRequest
	 * @return EventSearchRequest
	 */
	public function update(AbstractSearchRequest $searchRequest = null, $orderColumn = null, $orderDirection = null) {
		if ($searchRequest) {
			$this->updateSearchRequest($searchRequest);
		}

		if ($orderColumn) {
			$this->setOrder($orderColumn, $orderDirection);
		}
		return $this;
	}

	abstract protected function updateSearchRequest(AbstractSearchRequest $searchRequest);

}
?>