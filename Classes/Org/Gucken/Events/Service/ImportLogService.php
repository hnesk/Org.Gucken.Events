<?php

namespace Org\Gucken\Events\Service;


use Org\Gucken\Events\Domain\Model;
use Org\Gucken\Events\Domain\Repository;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class ImportLogService {

	/**
	 * @var \TYPO3\Flow\Log\SystemLoggerInterface
	 */
	protected $systemLogger;
	
		
	/**
	 * @var Repository\ImportLogEntryRepository
	 */
	protected $importLogRepository;
	
	protected $logBySource = array();

	/**
	 * @param \TYPO3\Flow\Log\SystemLoggerInterface
	 */
	public function injectSystemLogger(\TYPO3\Flow\Log\SystemLoggerInterface $systemLogger) {
		$this->systemLogger = $systemLogger;
	}
	
	/**
	 * @param Repository\ImportLogEntryRepository $importLogRepository 
	 */
	public function injectImportLogRepository(Repository\ImportLogEntryRepository $importLogRepository) {
		$this->importLogRepository = $importLogRepository;
	}
	
	/**
	 *
	 * @param Model\EventSource $source 
	 */
	public function importStarted(Model\EventSource $source) {
		$this->logBySource[spl_object_hash($source)] = $source->createLogEntry();
	}	

	/**
	 *
	 * @param Model\EventSource $source
	 * @param Model\EventFactoidIdentity $identity
	 * @param Model\EventFactoid $factoid
	 * @param boolean $isNewFactoid 
	 */
	public function factoidImported(Model\EventSource $source, Model\EventFactoidIdentity $identity, Model\EventFactoid $factoid, $isNewFactoid) {
		$importLog = $this->logBySource[spl_object_hash($source)];
		if ($isNewFactoid) {
			$importLog->incrementImportCount();
		} else {
			$importLog->incrementDuplicateCount();
		}
		$importLog->addMessage((string)$identity . ' with ' . ($isNewFactoid ? 'new ' : '') . 'factoid "' . $factoid . '" imported');
	}		
	
	/**
	 *
	 * @param Model\EventSource $source
	 * @param \Exception $e 
	 */
	public function exceptionThrown(Model\EventSource $source, $e) {
		$this->systemLogger->log($e->getCode().' '.$e->getMessage(). ' in '.$e->getTraceAsString());
		$importLog = $this->logBySource[spl_object_hash($source)];
		$importLog->addError($e->getCode().' '.$e->getMessage(). ' in '.$e->getTraceAsString());
	}	
	
	/**
	 *
	 * @param Model\EventSource $source 
	 */
	public function importFinished(Model\EventSource $source) {
		$importLog = $this->logBySource[spl_object_hash($source)];
		$importLog->setEndTime(new \DateTime());
		$this->importLogRepository->add($importLog);		
		
		unset($this->logBySource[spl_object_hash($source)]);
	}			
	
}
?>