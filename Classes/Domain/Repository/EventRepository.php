<?php
namespace Org\Gucken\Events\Domain\Repository;

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

/**
 * A repository for Events
 *
 * @package Events
 * @subpackage Domain
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventRepository extends \TYPO3\FLOW3\Persistence\Repository {
	
    protected $defaultOrderings = array(
        'startDateTime' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_ASCENDING,
    );
	
	
	/**
	 *
	 * @param \DateTime $startDateTime 
	 * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
	 */
	public function findBetween(\DateTime $startDateTime = null, $endDateTime = null) {
		$startDateTime = $startDateTime ?: new \DateTime();
		if (empty($endDateTime)) {
			$endDateTime = clone $startDateTime;
			$endDateTime->modify('+1 month');
		}
		
		$query = $this->createQuery();
		return $query->matching($query->logicalAnd(
			$query->greaterThanOrEqual('startDateTime', $startDateTime),
			$query->lessThanOrEqual('startDateTime', $endDateTime)				
		))->execute();
	}
		

	/**
	 *
	 * @param \DateTime $date
	 * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
	 */
	public function findAfter(\DateTime $startDateTime = null) {
		$startDateTime = $startDateTime ?: new \DateTime();
		$query = $this->createQuery();		
		return $query->matching($query->greaterThanOrEqual('startDateTime', $startDateTime))->execute();
	}


}
?>