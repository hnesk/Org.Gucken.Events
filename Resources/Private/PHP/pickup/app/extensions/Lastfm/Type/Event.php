<?php

namespace Lastfm\Type;
use \Type\String;
use \Type\Url;

/**
 * A Class representing a last.fm Event
 *
 * @author jk
 */
class Event extends \Type\Base {
    /**
     *
     * @var String
     */
    protected $id;

    /**
     *
     * @var String
     */
    protected $title;

    /**
     *
     * @var Date
     */
    protected $startTime;


    /**
     *
     * @var Artist\Collection
     */
    protected $artists;

    /**
     *
     * @var String
     */
    protected $venue;

    /**
     *
     * @var Url
     */
    protected $url;
    
    /**
     *
     * @var Url
     */
    protected $website;

    /**
     *
     * @var String
     */
    protected $decription;

    /**
     *
     * @var \Type\Xml
     */
    protected $proof;
    
    

    /**
     *
     * @var \Lastfm\Api\Event
     */
    protected $api;

    /**
     * @param Number $id
     * @param String $title
     * @param Date $startTime
     * @param Artist\Collection $artists
     * @param Venue $venue
     * @param Url $url
     * @param \Type\Xml $description
     * @param \Type\Xml $proof
     * @param \Lastfm\Api\Track $api optional
     */
    public function __construct($id, $title, $startTime, $artists, $venue, $url, $website = null, $description = null, $proof = null, \Lastfm\Api\Event $api = null) {
        $this->id = $id;
        $this->title = $title;
        $this->startTime  = $startTime;
        $this->artists = $artists;
        $this->venue = $venue;
        $this->url = $url;
        $this->website = $website;
        $this->description = $description;
        $this->proof = $proof;
        $this->api = $api ?: \Lastfm\Injector::injectLastfmEventApi();
    }
    

    /**
     *
     * @return Number
     */
    public function getId() {
        return $this->id;
    }

    /**
     *
     * @return String
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     *
     * @return \Type\Xml
     */
    public function getDescription() {
        return $this->description;
    }
    
    
    /**
     *
     * @return Date
     */
    public function getStartTime() {
        return $this->startTime;
    }

    /**
     *
     * @return Artist\Collection
     */
    public function getArtists() {
        return $this->artists;
    }

    /**
     *
     * @return Url
     */
    public function getUrl() {
        return $this->url;
    }
    
    /**
     *
     * @return Url
     */
    public function getWebsite() {
        return $this->website;
    }
    

    /**
     *
     * @return Venue
     */
    public function getVenue() {
        return $this->venue;
    }
    
    /**
     *
     * @return \Type\Xml
     */
    public function getProof() {
        return $this->proof;
    }
    


    public function getNativeValue() {
        return (object) array( 
            'id' => $this->id->getNativeValue(),
            'title' => $this->title->getNativeValue(),
            'startTime' => $this->startTime->getNativeValue(),
            'artists' => $this->artists->getNativeValue(),
            'venue' => $this->venue->getNativeValue(),
            'url' => $this->url->getNativeValue(),
            'description' => $this->description->getNativeValue(),
        );
    }
}
?>
