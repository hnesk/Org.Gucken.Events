<?php

namespace Org\Gucken\Events\Domain\Repository;

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * A repository for Locations
 *
 * @package Org.Gucken.Events
 * @subpackage Domain
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * 
 * @FLOW3\Scope("singleton")
 */
class LocationRepository extends \TYPO3\FLOW3\Persistence\Repository {

    protected $defaultOrderings = array(
        'name' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_ASCENDING
    );
    

    /**
     *
     * @param sring $scheme
     * @param string $id
     * @return \Org\Gucken\Events\Domain\Model\Location
     */
    public function findOneByExternalId($scheme, $id) {
        $query = $this->createQuery();
        $query->matching($query->logicalAnd(
            $query->equals('externalIdentifiers.scheme', $scheme),
            $query->equals('externalIdentifiers.id', $id)
        ));
        #echo $query->getDoctrineQuery()->getSQL();die();
                
        return $query->execute()->getFirst();
    }
    

    /**
     *
     * @param string $keyword 
     * @return \Org\Gucken\Events\Domain\Model\Location
     */
    public function findOneByKeywordString($string) {
		$keywords = string($string)->asKeywords()->getNativeValue();
		
        $query = $this->createQuery();		
        $results = $query->matching($query->in('keywords.keyword', $keywords))->execute();
		
		$sortedResults = new ScoreHeap($keywords);
		foreach ($results as $result) {
			$sortedResults->insert($result);
		}
		return $sortedResults->isEmpty() ? null : $sortedResults->top();
    }

}

?>