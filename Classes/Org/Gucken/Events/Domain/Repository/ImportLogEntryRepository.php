<?php
namespace Org\Gucken\Events\Domain\Repository;

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * A repository for import log entries
 *
 * @package Org.Gucken.Events
 * @subpackage Domain
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @FLOW3\Scope("singleton")
 */
class ImportLogEntryRepository extends \TYPO3\FLOW3\Persistence\Repository {

	protected $defaultOrderings = array(
		'startTime' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING
	);

	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventSource $source
	 * @return \TYPO3\FLOW3\Persistence\QueryResultInterface 
	 */
	public function findLastBySource(\Org\Gucken\Events\Domain\Model\EventSource $source) {		
		$query = $this->createQuery();
		return $query->matching($query->equals('source', $source))
				->setLimit(1)->execute()->getFirst();
	}
	
	
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
		);
		if ($start) {
			$conditions[] = $query->greaterThanOrEqual('startTime', $start);			
		}
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