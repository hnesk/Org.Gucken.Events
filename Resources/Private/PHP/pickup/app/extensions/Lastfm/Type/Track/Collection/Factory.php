<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Lastfm\Type\Track\Collection;
use \Lastfm\Type\Track;
use \Type\Xml;

class Factory {
    /**
     *
     * @param \Type\Xml $xml
     * @return Track\Collection
     */
    public static function fromTypeXml(Xml $xml) {
        $tracks = new Track\Collection();
        foreach ($xml->css('track') as $trackXml) {
            $tracks->addOne(Track\Factory::fromTypeXml($trackXml));
        }
        return $tracks;
    }


    /**
     *
     * @param \SimpleXMLElement $xml
     * @return Track\Collection
     */
    public static function fromSimpleXmlElement(\SimpleXMLElement $xml) {
        return self::fromTypeXml(\Type\Xml\Factory::fromSimpleXml($xml));
    }

}
?>
