<?php

namespace Lastfm\Type;


/**
 * An Class representing a last.fm Track
 *
 * @author jk
 */
class Track extends \Type\Base {

    /**
     *
     * @var \Lastfm\Api\Track
     */
    protected $api;

    /**
     * 
     * @param string $artist
     * @param string $track
     * @param \Lastfm\Api\Track $api optional
     */
    public function __construct($artist, $track, \Lastfm\Api\Track $api = null) {
        $this->artist = $artist;
        $this->value = $track;
        $this->api = $api ?: \Lastfm\Injector::injectLastfmTrackApi();
    }


    public function getArtist() {
        return new Data\String($this->artist);
    }

    public function getTitle() {
        return new Data\String($this->value);
    }

    public function getImage($type='small') {
        
    }

    public function getTopFans() {
        return User\Collection\Factory::fromSimpleXmlElement($this->api->getTopFans($this->value, $this->artist));

    }

    public function __toString() {
        return $this->artist.': '.$this->value;
    }

}
?>