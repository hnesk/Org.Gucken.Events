<?php

namespace Org\Gucken\Events\Domain\Repository;

use Org\Gucken\Events\Domain\Model\EventFactoid;
use Org\Gucken\Events\Domain\Model\EventFactoidIdentity;
use Org\Gucken\Events\Domain\Model\EventSearchRequest;
use TYPO3\Flow\Persistence\Repository;
use TYPO3\Flow\Persistence\QueryInterface;
use TYPO3\Flow\Persistence\QueryResultInterface;

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for EventFactoids
 * @package Org.Gucken.Events
 * @subpackage Domain
 *
 * @Flow\Scope("singleton")
 */
class EventFactoidIdentityRepository extends Repository
{

    protected $defaultOrderings = [
        'startDateTime' => QueryInterface::ORDER_ASCENDING,
        'source' => QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * @param  \DateTime            $startDateTime
     * @param  \DateTime            $endDateTime
     * @return QueryResultInterface
     */
    public function findUnassignedBetween(\DateTime $startDateTime = null, \DateTime $endDateTime = null)
    {
        return $this->_findBetween($startDateTime, $endDateTime, true);
    }

    /**
     * @param  \DateTime            $day
     * @return QueryResultInterface
     */
    public function findUnassignedOn(\DateTime $day = null)
    {
        $day = $day ? clone $day : new \DateTime();
        $day->setTime(0, 0, 0);
        $nextDay = clone $day;
        $nextDay->modify('+1 day');

        return $this->_findBetween($day, $nextDay, true);
    }

    /**
     * @param  \DateTime            $startDateTime
     * @param  \DateTime            $endDateTime
     * @return QueryResultInterface
     */
    public function findBetween(\DateTime $startDateTime = null, \DateTime $endDateTime = null)
    {
        return $this->_findBetween($startDateTime, $endDateTime);
    }

    /**
     *
     * @param  \DateTime            $startDateTime
     * @return QueryResultInterface
     */
    public function findAfter(\DateTime $startDateTime = null)
    {
        return $this->_findBetween($startDateTime);
    }

    /**
     * @param  \DateTime            $startDateTime
     * @param  \DateTime            $endDateTime
     * @param  boolean              $unassignedOnly
     * @return QueryResultInterface
     */
    protected function _findBetween(
        \DateTime $startDateTime = null,
        \DateTime $endDateTime = null,
        $unassignedOnly = false
    ) {
        $startDateTime = $startDateTime ?: new \DateTime();

        $query = $this->createQuery();
        $conditions = array();
        $conditions[] = $query->greaterThanOrEqual('startDateTime', $startDateTime);
        if (!empty($endDateTime)) {
            $conditions[] = $query->lessThanOrEqual('startDateTime', $endDateTime);
        }

        if ($unassignedOnly) {
            $conditions[] = $query->equals('link', null);
            $conditions[] = $query->equals('shouldSkip', null);
        }

        return $query->matching($query->logicalAnd($conditions))->execute();
    }

    /**
     *
     * @param  EventSearchRequest   $searchRequest
     * @return QueryResultInterface
     */
    public function findBySearchRequest(EventSearchRequest $searchRequest)
    {
        $query = $this->createQuery();
        $query = $searchRequest->apply($query);

        return $query->execute();
    }

    /**
     *
     * @param  EventFactoid         $factoid
     * @return EventFactoidIdentity
     */
    public function findOrCreateByEventFactoid(EventFactoid $factoid)
    {
        $identity = $this->findOneByEventFactoidProperties($factoid);
        if (!$identity) {
            $identity = $this->createByEventFactoid($factoid);
        }

        return $identity;
    }

    /**
     *
     * @param  EventFactoid         $factoid
     * @return EventFactoidIdentity
     */
    public function findOneByEventFactoidProperties(EventFactoid $factoid)
    {
        $query = $this->createQuery();

        return $query->matching(
            $query->logicalAnd(
                array(
                    $query->equals('startDateTime', $factoid->getStartDateTime()),
                    $query->equals('source', $factoid->getSource()),
                    $query->equals('location', $factoid->getLocation()),
                )
            )
        )->execute()->getFirst();
    }

    /**
     * @param  EventFactoid         $factoid
     * @return EventFactoidIdentity
     */
    public function createByEventFactoid(EventFactoid $factoid)
    {
        $identity = new EventFactoidIdentity();
        $identity->setStartDateTime($factoid->getStartDateTime());
        $identity->setLocation($factoid->getLocation());
        $identity->setSource($factoid->getSource());

        $factoid->setIdentity($identity);
        $identity->addFactoid($factoid);

        return $identity;
    }

    /**
     * @param EventFactoidIdentity $identity
     */
    public function persist(EventFactoidIdentity $identity)
    {
        if ($this->persistenceManager->isNewObject($identity)) {
            $this->add($identity);
        } else {
            $this->update($identity);
        }
    }

    public function persistAll()
    {
        $this->persistenceManager->persistAll();
    }

}
