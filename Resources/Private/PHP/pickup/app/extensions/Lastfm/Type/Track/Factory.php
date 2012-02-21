<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Lastfm\Type\Track;
use Lastfm\Type\Track;
use Type\Xml;

class Factory {

    /**
     *
     * @param Xml $xml
     * @return \Lastfm\Type\Track
     */
    public static function fromTypeXml(Xml $xml) {
        $track = new \Lastfm\Type\Track(
            $xml->css('artist name, artist')->asString()->first(),
            $xml->css('name')->asString()->first(),
            \Lastfm\Injector::injectLastfmTrackApi()
        );
        return $track;
    }

}

?>
