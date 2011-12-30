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
     *
     * @var Org\Gucken\Events\Domain\Repository\ImportLogEntryRepository
     * @FLOW3\Inject
     */
    protected $importLogRepository;
	

	
    /**
     *
     * @return int
     */
    public function import() {
        $cnt = 0;
        foreach ($this->eventSourceRepository->findAllActive() as $source) {
			/* @var $source Model\EventSource */
			$cnt += $this->importSource($source);
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
		$this->emitImportStarted($source);
		try {
			foreach ($source->getEventFactoids() as $eventFactoid) {
				/* @var $eventFactoid Model\EventFactoid */
				$eventFactoidIdentity = $this->eventFactoidIdentityRepository->findOrCreateByEventFactoid($eventFactoid);
				$persistedFactoid = $eventFactoidIdentity->addFactoidIfNotExists($eventFactoid);
				$this->eventFactoidIdentityRepository->persist($eventFactoidIdentity);

				$isNewFactoid = $persistedFactoid === $eventFactoid;
				$importCount += $isNewFactoid;
				$this->emitFactoidImported($source, $eventFactoidIdentity, $persistedFactoid, $isNewFactoid);
			}
		} catch(\Exception $exception) {
			$this->emitExceptionThrown($source, $exception);
		}

		$this->emitImportFinished($source);

        return $importCount;
    }
	
	/**
	 * @param Model\EventSource $source
	 * @FLOW3\Signal
	 */
	protected function emitImportStarted(Model\EventSource $source) {}	
	

	/**
	 * @param Model\EventSource $source
	 * @param Model\EventFactoidIdentity $identity
	 * @param Model\EventFactoid $factoid
	 * @param boolean $isNewFactoid
	 * @FLOW3\Signal
	 */
	protected function emitFactoidImported(Model\EventSource $source, Model\EventFactoidIdentity $identity, Model\EventFactoid $factoid, $isNewFactoid) {}		

	/**
	 * @param Model\EventSource $source
	 * @param \Exception $e
	 * @FLOW3\Signal
	 */
	protected function emitExceptionThrown(Model\EventSource $source, $e) {}	
	
	
	/**
	 * @param Model\EventSource $source
	 * @FLOW3\Signal
	 */
	protected function emitImportFinished(Model\EventSource $source) {}	
	
		
}


?>
