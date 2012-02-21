<?php

namespace Type\Feed;
use \Type\String;


/**
 *
 * @author Johannes Künsebeck <kuensebeck@googlemail.com>
 */
class Item extends \Type\Base {

    /**
     *
     * @var \Zend_Feed_Reader_EntryAbstract
     */
    protected $element;

    /**
     *
     * @param \Zend_Feed_Reader_EntryAbstract $element
     */
    public function __construct(\Zend_Feed_Reader_EntryInterface $element) {
        $this->element = $element;
    }

    /**
     * @return \Type\String
     */
    public function title() {
        return new \Type\String($this->element->getTitle());
    }


    /**
     * @return \Type\Url
     */
    public function url() {
        return $this->link();
    }

    /**
     * @return \Type\Url
     */
    public function link() {
        return new \Type\Url($this->element->getLink());
    }

    /**
     * @return \Type\Url\Collection
     */
    public function links() {
        return new \Type\Url\Collection($this->element->getLinks(),true);
    }

    /**
     * @return \Type\String
     */
    public function description() {
        return new \Type\String($this->element->getDescription());
    }

    /**
     * @return \Type\String
     */
    public function content() {
        return new \Type\String($this->element->getContent());
    }

    /**
     * @return \Type\String
     */
    public function createdString() {
        return new \Type\String($this->element->getDateCreated());
    }

    /**
     * @return \Type\Date
     */
    public function createdDate() {
        return new \Type\Date($this->element->getDateCreated());
    }

    /**
     * @return \Type\String
     */
    public function modifiedString() {
        return new \Type\String($this->element->getDateModified());
    }

    /**
     * @return \Type\Date
     */
    public function modifiedDate() {
        return new \Type\Date($this->element->getDateModified());
    }


    /**
     * @return \Type\String
     */
    public function author() {
        return new \Type\String($this->element->getAuthor());
    }

    /**
     * @return \Type\String\Collection
     */
    public function authors() {
        return new \Type\String\Collection($this->element->getAuthors());
    }

    /**
     * @return \Type\Url
     */
    public function permalink() {
        return new \Type\Url($this->element->getPermalink());
    }

    /**
     * @return \Type\String
     */
    public function id() {
        return new \Type\String($this->element->getId());
    }

    /**
     * @return \Type\Xml
     */
    public function contentAsXml() {
        #new \Zend_Feed_Reader_Entry_Rss();
        return \Type\Xml\Factory::fromXmlString('<content>'.$this->element->getContent().'</content>');
    }


    /**
     * @return \Type\Xml
     */
    public function asXml() {
        return \Type\Xml\Factory::fromDom($this->element->getElement());
    }


    /**
     *
     * @param string $xpath
     * @return Xml\XPath
     */
    public function xpath($xpath) {
        return $this->asXml()->xpath($xpath);
    }

    /**
     *
     * @param string $selector
     * @return Xml\XPath
     */
    public function css($selector) {
        return $this->asXml()->css($selector);
    }

    /**
     *
     * @return mixed
     */
    public function getNativeValue() {
        return $this->element;
    }

    public function __toString() {
        return (string)$this->element->__toString();
    }

}
?>