<?php

namespace Org\Gucken\Events\Domain\Repository;

/* *
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