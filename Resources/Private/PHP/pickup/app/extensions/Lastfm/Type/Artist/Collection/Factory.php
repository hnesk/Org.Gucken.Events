<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Lastfm\Type\Artist\Collection;
use \Lastfm\Type\Artist;
use \Type\Xml;

class Factory {
    /**
     *
     * @param \Type\Xml $xml
     * @return Artist\Collection
     */
    public static function fromTypeXml(Xml $xml) {
        $collection = new Artist\Collection();
        foreach ($xml->css('artist') as $singleXml) {
            $collection->addOne(Artist\Factory::fromTypeXml($singleXml));
        }
        return $collection;
    }


    /**
     *
     * @param \SimpleXMLElement $xml
     * @return Artist\Collection
     */
    public static function fromSimpleXmlElement(\SimpleXMLElement $xml) {
        return self::fromTypeXml(\Type\Xml\Factory::fromSimpleXml($xml));
    }

}
?>
