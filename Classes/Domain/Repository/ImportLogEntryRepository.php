<?php
namespace Org\Gucken\Events\Domain\Repository;

/*                                                                        *
 * This script belongs to the FLOW3 package "Org.Gucken.Events".          *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * A repository for import log entries
 *
 * @FLOW3\Scope("singleton")
 */
class ImportLogEntryRepository extends \TYPO3\FLOW3\Persistence\Repository {


	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventSource $source
	 * @param \DateTime $start
	 * @return \TYPO3\FLOW3\Persistence\QueryResultInterface 
	 */
	public function findBySourceAndDate(\Org\Gucken\Events\Domain\Model\EventSource $source, \DateTime $start) {
		$query = $this->createQuery();
		$conditions = array(
			$query->equals('source', $source),
			$query->greaterThanOrEqual('startTime', $start),
		);
		return $query->matching($query->logicalAnd($conditions))->execute();
	}
	
	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventSource $source
	 * @param \DateTime $start
	 * @return \TYPO3\FLOW3\Persistence\QueryResultInterface 
	 */
	public function findWithErrorsBySourceAndDate(\Org\Gucken\Events\Domain\Model\EventSource $source, \DateTime $start) {
		$query = $this->createQuery();
		$conditions = array(
			$query->equals('source', $source),
			$query->greaterThanOrEqual('startTime', $start),
			$query->logicalNot($query->equals('errors', ''))
		);
		return $query->matching($query->logicalAnd($conditions))->execute();
	}
	
	
	

}
?>