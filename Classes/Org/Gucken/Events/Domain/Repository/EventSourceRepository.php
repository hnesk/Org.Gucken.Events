<?php
namespace Org\Gucken\Events\Domain\Repository;

use Org\Gucken\Events\Domain\Model\EventSource;
use TYPO3\Flow\Persistence\QueryInterface;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\QueryResultInterface;
use TYPO3\Flow\Persistence\Repository;

/**
 * A repository for Events
 *
 * @package Org.Gucken.Events
 * @subpackage Domain
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @Flow\Scope("singleton")
 */
class EventSourceRepository extends Repository
{
    protected $defaultOrderings = [
        'name' => QueryInterface::ORDER_ASCENDING
    ];

    /**
     * @param $code
     * @return EventSource
     */
    public function findOneByCode($code)
    {
        $query = $this->createQuery();

        return $query->matching($query->equals('code', $code))->execute()->getFirst();

    }

    /**
     *
     * @return QueryResultInterface
     */
    public function findAllActive()
    {
        $query = $this->createQuery();

        return $query->matching($query->equals('active', 1))->execute();
    }

    /**
     * @param $name
     * @return EventSource
     */
    public function findOneByName($name)
    {
        $query = $this->createQuery();

        return $query->matching($query->equals('name', $name))->execute()->getFirst();

    }

}
