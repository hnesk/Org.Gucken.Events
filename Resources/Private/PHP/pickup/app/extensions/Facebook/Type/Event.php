<?php

namespace Facebook\Type;

use \Type\Number;
use \Type\String;
use \Type\Date;

/**
 * An Class representing a Facebook Event
 *
 * @author jk
 */
class Event extends \Type\Base {

    /**
     *
     * @var \Facebook\Api
     */
    protected $api;

    /**
     *
     * @var Number
     */
    protected $id;

    /**
     *
     * @var String
     */
    protected $title;

    /**
     *
     * @var String
     */
    protected $description;
    
    
    /**
     *
     * @var Date
     */
    protected $startTime;

    /**
     *
     * @var Date
     */
    protected $endTime;

    /**
     *
     * @var String
     */
    protected $venue;
    
    
    /**
     *
     * @var Number
     */
    protected $venueId;
    
    /**
     *
     * @var Date
     */
    protected $updatedTime;
    


    /**
     *
     * @param Number $id
     * @param String $title
     * @param String $description
     * @param Date $startTime
     * @param Date $endTime
     * @param String $venue
     * @param Number $venueId
     * @param Date $updatedTime  
     * @param \Facebook\Api $api
     */
    public function __construct($id, $title, $description, $startTime, $endTime, $venue, $venueId, $updatedTime, $api = null) {
        $this->id = new Number($id);
        $this->title = new String($title);
        $this->description = new String($description);        
        $this->startTime = new Date($startTime);
        $this->endTime = new Date($endTime);
        $this->venue = new String($venue);
        $this->venueId = new Number($venueId);
        $this->updatedTime = new Date($updatedTime);
        $this->api = $api ? : \Facebook\Injector::injectFacebookApi();
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
     * @return String
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
     * @return Date
     */
    public function getEndTime() {
        return $this->endTime;
    }

    /**
     *
     * @return \Type\Url
     */
    public function getUrl() {
        return new \Type\Url('http://www.facebook.com/events/'.$this->getId().'/');
    }

    /**
     *
     * @return String
     */
    public function getVenue() {
        return $this->venue;
    }
    
    /**
     *
     * @return Number
     */
    public function getVenueId() {
        return $this->venueId;
    }

    /**
     *
     * @return Date
     */
    public function getUpdatedTime() {
        return $this->updatedTime;
    }
    

    /**
     *
     * @return User\Collection
     */
    public function getAttending() {
        $users = $this->api->api('/'.$this->getId().'/attending');
        return User\Collection\Factory::fromArray($users['data']);
    }




    public function getNativeValue() {
        return (object) array(
            'id' => (string)$this->getId(),
            'title' => (string)$this->getTitle(),
            'description' => (string)$this->getDescription(),
            'startTime' => $this->getStartTime()->getNativeValue(),
            'endTime' => $this->getEndTime()->getNativeValue(),
            'venue' => (string)$this->getVenue(),
            'venueId' => (string)$this->getVenueId(),
            'updateTime' => $this->getUpdatedTime()->getNativeValue(),
            'url' => (string)$this->getUrl(),
        );
    }


}
?>