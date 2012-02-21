<?php
namespace Type\Document;
use \Type;

/**
 * Document\Collection: A type-safe array for documents
 *
 * @author jk
 */
class Collection extends \Type\BaseCollection {
    protected $itemDataType = '\Type\Document';


    /**
     *
     * @return \Type\BaseCollection
     */
    public function getMeta() {
        return $this->map(function (\Type\Document $document)  {
            return $document->getMeta();
        },'\Type\BaseCollection');
    }

    /**
     *
     * @return \Type\Xml\Collection
     */
    public function getContent($options=array()) {
        return $this->map(function (\Type\Document $document) use ($options) {
            return $document->getContent($options);
        },'\Type\Xml\Collection');
    }

    /**
     *
     * @return \Type\String\Collection
     */
    public function getRawContent() {
        return $this->map(function (\Type\Document $document) {
            return $document->getRawContent();
        },'\Type\String\Collection');
    }

}
?>
