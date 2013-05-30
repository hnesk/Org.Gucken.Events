<?php

namespace Org\Gucken\Events\Service;

use Org\Gucken\Events\Domain\Model;
use Org\Gucken\Events\Domain\Repository;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * 
 */
class DocumentRepositoryService implements \Type\Document\RepositoryInterface {

	/**
	 *
	 * @var \Org\Gucken\Events\Domain\Repository\DocumentRepository
	 * @FLOW3\Inject
	 */
	protected $documentRepository;
	
	/**
	 *
	 * @var \TYPO3\FLOW3\Persistence\PersistenceManagerInterface
	 * @FLOW3\Inject
	 */
	protected $persistenceManager;
	
   /**
     *
     * @param \Type\Document $document
     * @return mixed
     */
    public function store(\Type\Document $document) {
		$documentModel = new Model\Document();
		$documentModel->setFromDocument($document);
		$this->documentRepository->add($documentModel);
		return $this->persistenceManager->getIdentifierByObject($documentModel);
	}
	
    /**
     *
     * @param mixed $id
     * @return \Type\Document 
     */	
    public function retrieveById($id) {
		return $this->documentRepository->findByIdentifier($id);
	}
	
    /**
     *
     * @param Url $url
     * @param int $maxAge
     * @return \Type\Document
     */
    public function retrieveLatestByUrl($url, $maxAge=null,$options=array()) {
        if (!is_null($maxAge)) {
            $newerThanDate =  \Type\Date::ago($maxAge)->getNativeValue();
        }
		$document = $this->documentRepository->findLatestByUrl((string) $url, $newerThanDate);
		if ($document) {		
			return \Type\Document\Builder::buildFromArray($document->asArray(),$options);
		} else {
			return null;
		}
		
	}

    /**
     *
     * @param Url $url
     * @param int $maxAge
     * @param int $limit
	 * @param array $options for DocumentBuilder
     * @return array<\Type\Document>
     */
    public function retrieveByUrl($url,$maxAge = null,$limit=5, $options =array()) {
		return null;
	}
    
}

?>
