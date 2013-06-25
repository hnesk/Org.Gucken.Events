<?php
namespace Org\Gucken\Events\Domain\Repository;

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for event Types
 *
 * @package Org.Gucken.Events
 * @subpackage Domain
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * 
 * @Flow\Scope("singleton")
 */
class TypeRepository extends \TYPO3\Flow\Persistence\Repository {
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