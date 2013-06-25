<?php

namespace Org\Gucken\Events\Domain\Repository;

use TYPO3\Flow\Annotations as Flow;
use Org\Gucken\Events\Domain\Model\EventSearchRequest;


/**
 * A repository for EventFactoids
 * @package Org.Gucken.Events
 * @subpackage Domain
 *
 * @Flow\Scope("singleton")
 */
class EventFactoidIdentityRepository extends \TYPO3\Flow\Persistence\Repository {

    protected $defaultOrderings = array(
        'startDateTime' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_ASCENDING,
		'source' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_ASCENDING,
    );

	/**
	 * @param \DateTime $startDateTime
	 * @param \DateTime $endDateTime
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findUnassignedBetween(\DateTime $startDateTime = null, \DateTime $endDateTime = null) {
		return $this->_findBetween($startDateTime, $endDateTime, true);
	}

	/**
	 * @param \DateTime $day
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findUnassignedOn(\DateTime $day = null) {
		$day = $day ? clone $day : new \DateTime();
		$day->setTime(0,0,0);
		$nextDay = clone $day;
		$nextDay->modify('+1 day');
		return $this->_findBetween($day, $nextDay, true);
	}



	/**
	 * @param \DateTime $startDateTime
	 * @param \DateTime $endDateTime
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findBetween(\DateTime $startDateTime = null, \DateTime $endDateTime = null) {
		return $this->_findBetween($startDateTime, $endDateTime);
	}

	/**
	 *
	 * @param \DateTime $startDateTime
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findAfter(\DateTime $startDateTime = null) {
		return $this->_findBetween($startDateTime);
	}

	/**
	 * @param \DateTime $startDateTime
	 * @param \DateTime $endDateTime
	 * @param boolean $unassignedOnly
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	protected function _findBetween(\DateTime $startDateTime = null, \DateTime $endDateTime = null, $unassignedOnly = false) {
		$startDateTime = $startDateTime ?: new \DateTime();

		$query = $this->createQuery();
		$conditions = array();
		$conditions[] = $query->greaterThanOrEqual('startDateTime', $startDateTime);
		if (!empty($endDateTime)) {
			$conditions[] = $query->lessThanOrEqual('startDateTime', $endDateTime);
		}

		if ($unassignedOnly) {
			$conditions[] = $query->equals('link',  NULL);
			$conditions[] = $query->equals('shouldSkip',  NULL);
		}

		return $query->matching($query->logicalAnd($conditions))->execute();
	}


	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventSearchRequest $searchRequest
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findBySearchRequest(EventSearchRequest $searchRequest) {
		$query = $this->createQuery();
		$query = $searchRequest->apply($query);
		return $query->execute();
	}




	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoid $factoid
	 * @return \Org\Gucken\Events\Domain\Model\EventFactoidIdentity
	 */
	public function findOrCreateByEventFactoid(\Org\Gucken\Events\Domain\Model\EventFactoid $factoid) {
		$identity = $this->findOneByEventFactoidProperties($factoid);
		if (!$identity) {
			$identity = $this->createByEventFactoid($factoid);
		}
		return $identity;
	}

	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoid $factoid
	 * @return \Org\Gucken\Events\Domain\Model\EventFactoidIdentity
	 */
    public function findOneByEventFactoidProperties(\Org\Gucken\Events\Domain\Model\EventFactoid $factoid) {
		$query = $this->createQuery();
		return $query->matching($query->logicalAnd(array(
			$query->equals('startDateTime', $factoid->getStartDateTime()),
			$query->equals('source', $factoid->getSource()),
			$query->equals('location', $factoid->getLocation()),
		)))->execute()->getFirst();
    }

	/**
	 * @return \Org\Gucken\Events\Domain\Model\EventFactoidIdentity
	 */
	public function createByEventFactoid(\Org\Gucken\Events\Domain\Model\EventFactoid $factoid) {
		$identity = new \Org\Gucken\Events\Domain\Model\EventFactoidIdentity();
		$identity->setStartDateTime($factoid->getStartDateTime());
		$identity->setLocation($factoid->getLocation());
		$identity->setSource($factoid->getSource());

		$factoid->setIdentity($identity);
		$identity->addFactoid($factoid);

		return $identity;
	}


	public function persist(\Org\Gucken\Events\Domain\Model\EventFactoidIdentity $identity) {
		if ($this->persistenceManager->isNewObject($identity)) {
			$this->add($identity);
		} else {
			$this->update($identity);
		}
	}

	public function persistAll() {
		$this->persistenceManager->persistAll();
	}




}

?>