<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Lastfm\Type\Venue;
use Type\Xml;
use Type\String;

class Factory {
    
    /**
     *
     * @param string $string
     * @return \Lastfm\Type\Venue
     */    
    public static function fromXmlString($string) {
        return self::fromTypeXml(Xml\Factory::fromXmlString($string));
    }
    
    /**
     *
     * @param Xml $xml
     * @return \Lastfm\Type\Venue
     */
    public static function fromTypeXml(Xml $xml) {
        return new \Lastfm\Type\Venue(
            $xml->css('id')->asString()->first(),
            $xml->css('name')->asString()->first(),
            $xml->css('location city')->asString()->first(),
            $xml->css('location country')->asString()->first(),
            $xml->css('location street')->asString()->first(),
            $xml->css('location postalcode')->asString()->first(),
            $xml->css('location geo|point geo|lat', true)->asString()->first(),                
            $xml->css('location geo|point geo|long', true)->asString()->first(),
            $xml->css('url')->asUrl()->first(),
            $xml->css('website')->asUrl()->first()          
        );        
    }

}

?>