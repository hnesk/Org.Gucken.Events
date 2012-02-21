<?php

namespace Org\Gucken\Events\Domain\Repository;

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * A repository for HTTP Documents or a: Cache
 *
 * @package Org.Gucken.Events
 * @subpackage Domain
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * 
 * @FLOW3\Scope("singleton")
 */
class DocumentRepository extends \TYPO3\FLOW3\Persistence\Repository {

	protected $defaultOrderings = array(
		'localTime' =>  \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING
	);
	
	/**
	 *
	 * @param string $url
	 * @param \DateTime $maxAge 
	 * @return \Org\Gucken\Events\Domain\Model\Document
	 */
	public function findLatestByUrl($url, \DateTime $newerThanDate=null) {
		$query = $this->createQuery();
		
		$conditions = array($query->equals('requestedUrl', $url));
		if ($newerThanDate) {
			$conditions[] = $query->greaterThanOrEqual('localTime', $newerThanDate);
		}
		
		return $query->matching($query->logicalAnd($conditions))->setLimit(1)->execute()->getFirst();		
	}


}

?>