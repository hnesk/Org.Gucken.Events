<?php

namespace Org\Gucken\Events\Domain\Repository;

/* *
 * This script belongs to the FLOW3 package "Org.Gucken.Events".          *
 *                                                                        *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * A repository for EventFactoids
 *
 * @FLOW3\Scope("singleton")
 */
class EventFactoidIdentityRepository extends \TYPO3\FLOW3\Persistence\Repository {
    
    protected $defaultOrderings = array(
        'startDateTime' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_ASCENDING,
		'source' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_ASCENDING,
    );
	
	
	/**
	 *
	 * @param \DateTime $startDateTime 
	 * 
	 */
	public function findAfter(\DateTime $startDateTime = null) {
		$startDateTime = $startDateTime ?: new \DateTime();
		$query = $this->createQuery();
		return $query->matching($query->greaterThanOrEqual('startDateTime', $startDateTime))->execute();
	}
	
	/**
	 *
	 * @param \DateTime $startDateTime 
	 * 
	 */
	public function findBetween(\DateTime $startDateTime = null, $endDateTime = null) {
		$startDateTime = $startDateTime ?: new \DateTime();
		if (empty($endDateTime)) {
			$endDateTime = clone $startDateTime;
			$endDateTime->modify('+1 month');
		}
				
		$query = $this->createQuery();
		return $query->matching($query->logicalAnd(
			$query->greaterThanOrEqual('startDateTime', $startDateTime),
			$query->lessThanOrEqual('startDateTime', $endDateTime),
			$query->equals('event',  NULL),
			$query->equals('shouldSkip',  false)
		))->execute();
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
	
	
    

}

?>