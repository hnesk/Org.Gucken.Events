<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Lastfm\Type\Venue\Collection;
use Lastfm\Type\Venue;
use Type\Xml;

class Factory {

    /**
     *
     * @param \Type\String $name
     * @return Venue\Collection
     */
    public static function fromTypeString(\Type\String $name, Lastfm\Api\Venue $api = null) {        
        return self::fromString((string)$name, $api);
    }
        

    /**
     *
     * @param string $name
     * @return Venue\Collection
     */
    public static function fromString($name, Lastfm\Api\Venue $api = null) {        
        $api = $api ?: \Lastfm\Injector::injectLastfmVenueApi();
        return self::fromSimpleXmlElement($api->search($name));        
    }
        
    
    
    /**
     *
     * @param \Type\Xml $xml
     * @return Venue\Collection
     */
    public static function fromTypeXml(Xml $xml) {
        $collection = new Venue\Collection();
        foreach ($xml->css('venue') as $singleXml) {
            $collection->addOne(Venue\Factory::fromTypeXml($singleXml));
        }
        return $collection;
    }


    /**
     *
     * @param \SimpleXMLElement $xml
     * @return Venue\Collection
     */
    public static function fromSimpleXmlElement(\SimpleXMLElement $xml) {
        return self::fromTypeXml(\Type\Xml\Factory::fromSimpleXml($xml));
    }



}
?>
