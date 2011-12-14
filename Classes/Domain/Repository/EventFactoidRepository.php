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
class EventFactoidRepository extends \TYPO3\FLOW3\Persistence\Repository {

    /**
     * @var \TYPO3\FLOW3\Reflection\ReflectionService
     * @FLOW3\Inject
     */
    protected $reflectionService;
    
    /**
     * Cache for reflected properties that define identity  (= all/importDateTime)
     * @var array
     */
    protected $identityProperties = array();
    
    protected $defaultOrderings = array(
        'startDateTime' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_ASCENDING,
		'title' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_ASCENDING,
		'importDateTime' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING
    );
    
    
    public function findExactDuplicates(\Org\Gucken\Events\Domain\Model\EventFactoid $factoid) {
        return $this->findDuplicatesByEventFactoid($factoid, $this->getIdentityProperties());
    }
    
    public function findPossibleDuplicates(\Org\Gucken\Events\Domain\Model\EventFactoid $factoid) {
        return $this->findDuplicatesByEventFactoid($factoid, array('location','startDateTime'));
    }
       
    /**
     *
     * @param \Org\Gucken\Events\Domain\Model\EventFactoid $factoid
     * @param array<string> Properties to check for identity
     * @return \Doctrine\Common\Collections\ArrayCollection<\Org\Gucken\Events\Domain\Model\EventFactoid>
     */
    protected function findDuplicatesByEventFactoid(\Org\Gucken\Events\Domain\Model\EventFactoid $factoid, array $identityProperties) {        
        $query = $this->createQuery();
        $conditions = array();        
        foreach ($identityProperties as $identityProperty) {
            $conditions[] = $query->equals($identityProperty, $factoid->{'get'.ucfirst($identityProperty)}()); 
        }

        $results = $query->matching($query->logicalAnd($conditions))->execute()->toArray();
        // remove the factoid itself
        #unset($results[array_search($factoid, $results)]);
        return $results;
    }
    
    protected function getIdentityProperties() {
        if (empty($this->identityProperties)) {
            $this->identityProperties = array_diff(
                $this->reflectionService->getClassPropertyNames('\Org\Gucken\Events\Domain\Model\EventFactoid'), 
				// TODO: Blacklist ist mist
                array('importDateTime','identityDirty','persistenceManager')
            );
        }
        return $this->identityProperties;
        
    }

}

?>