<?php

namespace Org\Gucken\Events\Service;

use Org\Gucken\Events\Domain\Model;
use Org\Gucken\Events\Domain\Repository;
use Type\Date;
use Type\Document\RepositoryInterface;
use Type\Document;
use Type\Url;
use TYPO3\Flow\Annotations as Flow;

/**
 *
 */
class DocumentRepositoryService implements RepositoryInterface
{

    /**
     *
     * @var \Org\Gucken\Events\Domain\Repository\DocumentRepository
     * @Flow\Inject
     */
    protected $documentRepository;

    /**
     *
     * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
     * @Flow\Inject
     */
    protected $persistenceManager;

    /**
     *
     * @param  Document $document
     * @return mixed
     */
    public function store(Document $document)
    {
        $documentModel = new Model\Document();
        $documentModel->setFromDocument($document);
        $this->documentRepository->add($documentModel);

        return $this->persistenceManager->getIdentifierByObject($documentModel);
    }

    /**
     *
     * @param  mixed    $id
     * @return Document
     */
    public function retrieveById($id)
    {
        return $this->documentRepository->findByIdentifier($id);
    }

    /**
     *
     * @param  Url|string $url
     * @param  int        $maxAge
     * @param  array      $options
     * @return Document
     */
    public function retrieveLatestByUrl($url, $maxAge = null, $options = array())
    {
        $newerThanDate = is_null($maxAge) ? null : Date::ago($maxAge)->getNativeValue();
        $document = $this->documentRepository->findLatestByUrl((string) $url, $newerThanDate);
        if ($document) {
            return Document\Builder::buildFromArray($document->asArray(), $options);
        } else {
            return null;
        }

    }

    /**
     *
     * @param  Url                   $url
     * @param  int                   $maxAge
     * @param  int                   $limit
     * @param  array                 $options for DocumentBuilder
     * @return array<\Type\Document>
     */
    public function retrieveByUrl($url, $maxAge = null, $limit = 5, $options = array())
    {
        return null;
    }

}
