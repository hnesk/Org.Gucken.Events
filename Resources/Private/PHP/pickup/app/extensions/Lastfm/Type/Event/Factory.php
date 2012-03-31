<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Lastfm\Type\Event;
use Lastfm\Type\Event;
use Lastfm\Type\Venue;
use Lastfm\Type\Artist;
use Type\Xml;

class Factory {

    /**
     *
     * @param Xml $xml
     * @return \Lastfm\Type\Event
     */
    public static function fromTypeXml(Xml $xml) {
        return new \Lastfm\Type\Event(
            $xml->css('id')->asString()->first(),
            $xml->css('title')->asString()->first(),
            $xml->css('startDate')->asString()->first()->asDate('%a, %d %b %Y %H:%M:%S'),
            Artist\Collection\Factory::fromTypeXml($xml),
            Venue\Factory::fromTypeXml($xml->css('venue')->asXml()->first()),
            $xml->css('url')->asUrl()->last(),
            $xml->css('website')->asUrl()->last(),
            $xml->css('description')->asXml()->first()->decapsulateCdataAsHtml(),
            $xml,
            \Lastfm\Injector::injectLastfmEventApi()
        );
    }

}

?>
