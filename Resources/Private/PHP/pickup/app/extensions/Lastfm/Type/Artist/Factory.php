<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Lastfm\Type\Artist;
use Lastfm\Type\Artis;
use Type\Xml;
use Type\String;

class Factory {

    /**
     *
     * @param string $artistName
     * @return \Lastfm\Type\Artist
     */
    public static function fromString($artistName) {
        return new \Lastfm\Type\Artist(
            (string)$artistName,
            \Lastfm\Injector::injectLastfmArtistApi()
        );
    }

    /**
     *
     * @param Xml $xml
     * @return \Lastfm\Type\Artist
     */
    public static function fromTypeXml(Xml $xml) {
        return self::fromString($xml->text());
    }

    /**
     *
     * @param String $string
     * @return \Lastfm\Type\Artist
     */
    public static function fromTypeString(String $string) {
        return self::fromString((string)$string);
    }

}

?>
