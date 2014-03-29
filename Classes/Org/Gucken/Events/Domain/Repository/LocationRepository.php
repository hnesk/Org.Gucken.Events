<?php

namespace Org\Gucken\Events\Domain\Repository;

use Org\Gucken\Events\Domain\Model\Location;
use Org\Gucken\Events\Domain\Model\LocationSearchRequest;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\QueryInterface;
use TYPO3\Flow\Persistence\QueryResultInterface;
use TYPO3\Flow\Persistence\Repository;

/**
 * A repository for Locations
 *
 * @package Org.Gucken.Events
 * @subpackage Domain
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @Flow\Scope("singleton")
 */
class LocationRepository extends Repository {

    protected $defaultOrderings = [
        'name' => QueryInterface::ORDER_ASCENDING
    ];


    /**
     *
     * @param string $scheme
     * @param string $id
     * @return Location
     */
    public function findOneByExternalId($scheme, $id) {
        $query = $this->createQuery();
        $query->matching($query->logicalAnd(
        #    $query->equals('externalIdentifiers.scheme', $scheme),
            $query->equals('externalIdentifiers.id', (string)$id)
        ));

        return $query->execute()->getFirst();
    }

    /**
     *
     * @param string $address
     * @return Location
     */
    public function findOneByExactAdress($address) {
		foreach ($this->findAll() as $location) {
			if ($address === (string) $location) {
				return $location;
			}
		}
		return NULL;
    }


	/**
	 *
	 * @param LocationSearchRequest $searchRequest
	 * @return QueryResultInterface
	 */
	public function findBySearchRequest(LocationSearchRequest $searchRequest) {
		$query = $this->createQuery();
		$query = $searchRequest->apply($query);
		return $query->execute();
	}


    /**
     *
     * @param string $string
     * @return Location
     */
    public function findOneByKeywordString($string) {
		$sortedResults = $this->getResultHeapByKeywordString($string);
		return $sortedResults->isEmpty() ? null : $sortedResults->top();
    }


    /**
     *
     * @param string $string
     * @param int $minScore
     * @return Location
     */
    public function findByKeywordString($string, $minScore = 1) {
		$sortedResults = $this->getResultHeapByKeywordString($string);
		return $sortedResults->getBest($minScore);
    }

    /**
     *
     * @param string $string
     * @return ScoreHeap
     */
    protected function getResultHeapByKeywordString($string) {
		$keywords = string($string)->asKeywords()->getNativeValue();

        $query = $this->createQuery();
        $results = $query->matching($query->in('keywords.keyword', $keywords))->execute();

		$sortedResults = new ScoreHeap($keywords);
		foreach ($results as $result) {
			$sortedResults->insert($result);
		}

		return $sortedResults;
    }


}

?>