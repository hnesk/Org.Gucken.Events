<?php
namespace Org\Gucken\Events\Domain\Repository;

use Org\Gucken\Events\Domain\Model\EventSource;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\QueryInterface;
use TYPO3\Flow\Persistence\QueryResultInterface;
use TYPO3\Flow\Persistence\Repository;

/**
 * A repository for import log entries
 *
 * @package Org.Gucken.Events
 * @subpackage Domain
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @Flow\Scope("singleton")
 */
class ImportLogEntryRepository extends Repository {

	protected $defaultOrderings = [
		'startTime' => QueryInterface::ORDER_DESCENDING
	];

	/**
	 *
	 * @param EventSource $source
	 * @return QueryResultInterface
	 */
	public function findLastBySource(EventSource $source) {
		$query = $this->createQuery();
		return $query->matching($query->equals('source', $source))
				->setLimit(1)->execute()->getFirst();
	}
	
	
	/**
	 *
	 * @param EventSource $source
	 * @param \DateTime $start
	 * @return QueryResultInterface
	 */
	public function findBySourceAndDate(EventSource $source, \DateTime $start) {
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
	 * @param EventSource $source
	 * @param \DateTime $start
	 * @return QueryResultInterface
	 */
	public function findWithErrorsBySourceAndDate(EventSource $source, \DateTime $start) {
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