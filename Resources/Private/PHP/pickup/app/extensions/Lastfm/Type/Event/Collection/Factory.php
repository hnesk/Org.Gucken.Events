<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Lastfm\Type\Event\Collection;
use \Lastfm\Type\Event;
use \Type\Xml;

class Factory {

    /**
     *
     * @param \Type\Xml $xml
     * @return Event\Collection
     */
    public static function fromTypeXml(Xml $xml) {
        $collection = new Event\Collection();
        foreach ($xml->css('event') as $singleXml) {
            $collection->addOne(Event\Factory::fromTypeXml($singleXml));
        }
        return $collection;
    }

    /**
     *
     * @param \SimpleXMLElement $xml
     * @return Event\Collection
     */
    public static function fromSimpleXmlElement(\SimpleXMLElement $xml) {
        return self::fromTypeXml(\Type\Xml\Factory::fromSimpleXml($xml));
    }

    /**
     *
     * @param string $file
     * @return Event\Collection
     */
    public static function fromXmlFile($file) {
        return self::fromTypeXml(\Type\Xml\Factory::fromXmlFile($file));
    }


}
?>
