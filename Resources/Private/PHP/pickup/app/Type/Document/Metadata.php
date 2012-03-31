<?php

namespace Type\Document;

use Type\Url;
use Type\Date;

/**
 * Description of Metadata
 *
 * @author jk
 */
abstract class Metadata implements \ArrayAccess, \IteratorAggregate {
    /**
     *
     * @var array
     */
    protected $data;

    protected $options;

    /**
     *
     * @param Data\Url|string $requestedUrl
     * @param Data\Url|string $fetchedUrl
     * @param array $responseHeaders
     * @param int $localtime
     * @param string $raw
     */
    public function __construct($requestedUrl, $fetchedUrl = null, $time = null, $data = array(), $options= array()) {
        $this->setOptions($options);

        $this->setRequestedUrl($requestedUrl);
        $this->setFetchedUrl($fetchedUrl ? $fetchedUrl : $requestedUrl);
        $this->setLocaltime($time ? $time : \App::instance()->date());
        $this->setData($data);        
        $this->postConstruct();
    }

    /**
     * to override if needed
     */
    protected function postConstruct() {}

    /**
     *
     * @param array $data
     * @return Scrape_Document
     */
    protected function setData($data) {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
        return $this;
    }

    protected function setOptions($options = array()) {
        $this->options = $options;
    }

    /**
     *
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     *
     * @return string
     */
    public function get($key) {
        $key = mb_strtolower($key);
        if (isset($this->options['override'][$key])) {
            return $this->options['override'][$key];
        } else if (isset($this->data[$key])) {
            return $this->data[$key];
        } else if (isset($this->options['default'][$key])) {
            return $this->options['default'][$key];
        } else {
            return null;
        }

    }

    /**
     *
     * @return string
     */
    protected function set($key,$value) {
        if (is_array($value)) {
            $value = implode(';', $value);
        }
        $this->data[mb_strtolower($key)] = $value;
        return $this;
    }

    /**
     * @param string
     * @return boolean
     */
    public function has($key) {
        return isset($this->data[mb_strtolower($key)]);
    }


    /**
     * get the requested url
     * @return \Type\Url
     */
    public function getRequestedUrl() {
        return $this->get('requested-url');
    }
    /**
     *
     * @param Data\Url $requestedUrl
     * @return Metadata
     */
    protected function setRequestedUrl($requestedUrl) {
        return $this->set('requested-url',is_string($requestedUrl) ? new Url($requestedUrl) : $requestedUrl);
    }

    /**
     * get the fetched url
     * @return \Type\Url
     */
    public function getFetchedUrl() {
        return $this->data['fetched-url'];
    }
    /**
     *
     * @param \Type\Url $requestedUrl
     * @return Metadata
     */
    protected function setFetchedUrl($fetchedUrl) {
        return $this->set('fetched-url', is_string($fetchedUrl) ? new Url($fetchedUrl) : $fetchedUrl);
    }

    /**
     * Content-Type
     * @return string
     */
    public function getContentType() {
        return $this->get('content-type');
    }
    /**
     *
     * @param string $contentType
     * @return Metadata
     */
    public function setContentType($contentType) {
        return $this->set('content-type', $contentType);
    }

    /**
     * Charset
     * @return string
     */
    public function getCharset() {
        return $this->get('charset');
    }
    /**
     * @param string
     * @return Metadata
     */
    protected function setCharset($charset) {
        return $this->set('charset', $charset);
    }


    /**
     * timestamp of server date
     * @return \Type\Date
     */
    public function getDocumentTime() {
        return $this->get('document-time');
    }
    /**
     * @param \Type\Date
     * @return Metadata
     */
    public function setDocumentTime($time) {
        return $this->set('document-time', $time instanceof Date ? $time : new Date($time));
    }


    /**
     * timestamp of server last modified header
     * @return \Type\Date
     */
    public function getUpdatedTime() {
        return $this->get('updated-time');
    }
    /**
     *
     * @param DateTime $time
     * @return Metadata
     */
    public function setUpdatedTime($time) {
        return $this->set('updated-time', $time instanceof Date ? $time : new Date($time));
    }

    /**
     *
     * @return \Type\Date
     */
    public function getLocalTime() {
        return $this->get('local-time');
    }

    /**
     *
     * @param \Type\Date $localTime
     * @return Metadata
     */
    protected function setLocaltime($time) {
        return $this->set('local-time', $time instanceof Date ? $time : new Date($time));
    }

    /**
     * Size in Bytes
     * @return int
     */
    public function getSize() {
        return $this->get('size');
    }
    /**
     *
     * @param int $size
     * @return File
     */
    protected function setSize($size) {
        return $this->set('size', $size);
    }



    /**
     * Sets content-type and charset by mimetype
     * @param string $mimeType
     * @return Metadata
     */
    protected function setByMimeType($mimeType) {
        if (preg_match('#^([^;]+);?\s*(charset\s*=\s*)?(.+)?$#i', $mimeType, $matches)) {
            $this->setContentType(trim($matches[1]));
            if (isset($matches[3])) {
                $this->setCharset(trim($matches[3]));
            }
        }
        return $this;
    }

    /** implements @see ArrayAccess */

    public function offsetExists($offset) {
        return isset ($this->data[$offset]);
    }

    public function offsetGet($offset) {
        return $this->get($offset);
    }

    public function offsetSet($offset,$value) {
        return $this->set($offset,$value);
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    /**
     * implements @see Traversable 
     * @return \ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->data);
    }

}
?>
