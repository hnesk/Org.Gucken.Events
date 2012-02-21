<?php
namespace Org\Gucken\Events\Domain\Repository;

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * A repository for Events
 *
 * @package Org.Gucken.Events
 * @subpackage Domain
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * 
 * @FLOW3\Scope("singleton")
 */
class EventRepository extends \TYPO3\FLOW3\Persistence\Doctrine\Repository {
	
    protected $defaultOrderings = array(
        'startDateTime' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_ASCENDING,
    );
	
	
	/**
	 * @param \DateTime $day
	 * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
	 */
	public function findOn(\DateTime $day = null) {
		$day = $day ? clone $day : new \DateTime();
		$day->setTime(0,0,0);
		$nextDay = clone $day;
		$nextDay->modify('+1 day');
		return $this->findBetween($day, $nextDay);
	}	
	
	/**
	 *
	 * @param \DateTime $startDateTime 
	 * @param \DateTime $endDateTime 
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

	public function persistAll() {
		$this->persistenceManager->persistAll();
	}
	
}
?>