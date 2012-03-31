<?php

namespace Lastfm\Type;

/**
 * An Class representing a last.fm Artist
 *
 * @author jk
 */
class Artist extends \Type\Base {

    /**
     *
     * @var \Lastfm\Api\Artist
     */
    protected $api;

    /**
     * 
     * @param string $artist
     * @param string $track
     * @param \Lastfm\Api\Artist $api optional
     */
    public function __construct($artist, \Lastfm\Api\Artist $api = null) {
        $this->value = $artist;
        $this->api = $api ?: \Lastfm\Injector::injectLastfmArtistApi();
    }

    public function getArtist() {
        return $this->value;
    }


    /**
     *
     * @return \Lastfm\Type\User\Collection
     */
    public function getTopFans() {
        return \Lastfm\Type\User\Collection\Factory::fromSimpleXmlElement($this->api->getTopFans($this->value));
    }

    public function __toString() {
        return $this->value;
    }

}
?>