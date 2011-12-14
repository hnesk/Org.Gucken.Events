<?php

namespace Org\Gucken\Events\Service;


use Org\Gucken\Events\Domain\Model;
use Org\Gucken\Events\Domain\Repository;
use TYPO3\FLOW3\Annotations as FLOW3;


class ImportEventFactoidsService {
	
    /**
     *
     * @var Org\Gucken\Events\Domain\Repository\EventFactoidIdentityRepository
     * @FLOW3\Inject
     */
    protected $eventFactoidIdentityRepository;	

    /**
     *
     * @var Org\Gucken\Events\Domain\Repository\EventSourceRepository
     * @FLOW3\Inject
     */
    protected $eventSourceRepository;

	/**
	 * @var array
	 */
	protected $errors;
	
    /**
     *
     * @return int
     */
    public function import() {
		$this->errors = array();
        $cnt = 0;
        foreach ($this->eventSourceRepository->findAllActive() as $source) {
			try {
				/* @var $source Model\EventSource */
				$cnt += $this->importSource($source);
			} catch (\Exception $e) {
				$this->errors[] = $e;
			}
        }
        return $cnt;
    }

    /**
     *
     * @param Model\EventSource $source
     * @return int
     */
    public function importSource(Model\EventSource $source) {		
        $importCount = 0;
        $duplicateCount = 0;
		
		$eventFactoids = $source->getEventFactoids();
        foreach ($eventFactoids as $eventFactoid) {
            /* @var $eventFactoid Model\EventFactoid */   			
			$eventFactoidIdentity = $this->eventFactoidIdentityRepository->findOrCreateByEventFactoid($eventFactoid);
			$persistedFactoid = $eventFactoidIdentity->addFactoidIfNotExists($eventFactoid);
			
			if ($persistedFactoid !== $eventFactoid) {
				$duplicateCount++;
			} else {
				$importCount++;
			}
			
			$this->eventFactoidIdentityRepository->persist($eventFactoidIdentity);
						
        }
        return $importCount;
    }

	/**
	 * @return array
	 */
	public function getErrors() {
		return $this->errors;
	}
	
}


?>
