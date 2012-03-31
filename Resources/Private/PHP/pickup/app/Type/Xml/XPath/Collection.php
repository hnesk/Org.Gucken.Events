<?php
namespace Type\Xml\XPath;

use \Type;
use \Type\String;
use \Type\Url;
use \Type\Xml;
/**
 * Xml\XPath\Collection: A type-safe array for XPath objects
 * @author jk
 */
class Collection extends \Type\BaseCollection {
    protected $itemDataType = '\Type\Xml\XPath';

    public function css($s) {
        return $this->map(function (Xml\XPath $xpath) use ($s) {return $xpath->css($s);},'\Type\Xml\XPath\Collection');
    }

    public function xpath($x) {
        return $this->map(function (Xml\XPath $xpath) use ($x) {return $xpath->xpath($x);},'\Type\Xml\XPath\Collection');
    }

    public function contains($s) {
        return $this->map(function (Xml\XPath $xpath) use ($s) {return $xpath->contains($s);},'\Type\Xml\XPath\Collection');
    }


    /**
     *
     * @return \Type\Xml\Collection
     */
    public function asXml() {
        return $this->map(function (Xml\XPath $xpath) {return $xpath->asXml();}, '\Type\Xml\Collection');
    }

    /**
     *
     * @return \Type\String\Collection
     */
    public function asString() {
        return $this->map(function (Xml\XPath $xpath) {return $xpath->asString();}, '\Type\String\Collection');
    }

    /**
     *
     * @return \Type\Url\Collection
     */
    public function asUrl() {
        return $this->map(function (Xml\XPath $xpath) {return $xpath->asUrl();}, '\Type\Url\Collection');
    }

}
?>