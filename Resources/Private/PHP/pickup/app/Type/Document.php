<?php

namespace Type;

/**
 * Description of Document
 *
 * @author jk
 */
abstract class Document extends Base {

    /**
     *
     * @var Document\Metadata
     */
    protected $metaData;

    /**
     *
     * @var string
     */
    protected $rawContent;

    /**
     *
     * @param Document\Metadata $metaData
     * @param string $rawContent
     */
    public function __construct(Document\Metadata $metaData, $rawContent) {
        $this->metaData = $metaData;
        $this->rawContent = $rawContent;
    }

    /**
     * @return Document\Metadata
     */
    public function getMeta() {
        return $this->metaData;
    }


    /**
     * @return \Type\Base
     */
    abstract public function getContent($options = array());

    /**
     * @return string
     */
    public function getRawContent() {
        return $this->rawContent;
    }


    public function getNativeValue() {
        return $this->getRawContent();
    }

}
?>
