<?php

namespace Lastfm\Type;

use Lastfm\Type\Track;

/**
 * An Class representing a last.fm user
 *
 * @author jk
 */
class User extends \Type\Base {

    /**
     *
     * @var \Lastfm\Api\User
     */
    protected $api;

    /**
     *
     * @param string $user
     */
    public function __construct($user, \Lastfm\Api\User $api = null) {
        $this->value = $user;
        $this->api = $api ?: \Lastfm\Injector::injectLastfmUserApi();
    }

    /**
     * 
     * @return Track\Collection
     */
    public function getLovedTracks() {
        return Track\Collection\Factory::fromSimpleXmlElement($this->api->getLovedTracks($this->value));
    }

    /**
     *
     * @return Track\Collection
     */
    public function getBannedTracks() {
        return Track\Collection\Factory::fromSimpleXmlElement($this->api->getBannedTracks($this->value));
    }

    /**
     *
     * @return Track\Collection
     */
    public function getArtistTracks($artist) {
        return Track\Collection\Factory::fromSimpleXmlElement($this->api->getArtistTracks($this->value, (string)$artist));
    }

}
?>