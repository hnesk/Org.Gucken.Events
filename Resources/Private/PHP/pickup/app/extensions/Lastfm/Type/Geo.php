<?php

namespace Lastfm\Type;

use Lastfm\Type\Track;

/**
 * An Class representing a last.fm geolocation
 *
 * @author jk
 */
class Geo extends \Type\Base {

    /**
     *
     * @var \Lastfm\Api\Geo
     */
    protected $api;

    /**
     *
     * @param string $user
     */
    public function __construct($geo, \Lastfm\Api\Geo $api = null) {
        $this->value = (string)$geo;
        $this->api = $api ?: \Lastfm\Injector::injectLastfmGeoApi();
    }

    /**
     *
     * @param int $distance
     * @param int $limit
     * @param int $page
     * @return Event\Collection
     */
    public function getEvents($distance = 20, $limit = 50, $page = 1) {
        return Event\Collection\Factory::fromSimpleXmlElement($this->api->getEventsByLocation($this->value, $distance, $limit, $page));
    }

}
?>