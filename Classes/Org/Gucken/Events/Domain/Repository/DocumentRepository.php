<?php

namespace Org\Gucken\Events\Domain\Repository;

use Org\Gucken\Events\Domain\Model\Document;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\QueryInterface;
use TYPO3\Flow\Persistence\Repository;

/**
 * A repository for HTTP Documents or a: Cache
 *
 * @package Org.Gucken.Events
 * @subpackage Domain
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @Flow\Scope("singleton")
 */
class DocumentRepository extends Repository
{

    protected $defaultOrderings = [
        'localTime' => QueryInterface::ORDER_DESCENDING
    ];

    /**
     *
     * @param  string    $url
     * @param  \DateTime $newerThanDate
     * @return Document
     */
    public function findLatestByUrl($url, \DateTime $newerThanDate = null)
    {
        $query = $this->createQuery();

        $conditions = array($query->equals('requestedUrl', $url));
        if ($newerThanDate) {
            $conditions[] = $query->greaterThanOrEqual('localTime', $newerThanDate);
        }

        return $query->matching($query->logicalAnd($conditions))->setLimit(1)->execute()->getFirst();
    }

}
