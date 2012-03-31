<?php

namespace Facebook\Type;

use \Type\Number;
use \Type\String;
use \Type\Date;
use \Type\Url;

/**
 * An Class representing a Facebook User
 *
 * @author jk
 */
class Page extends \Type\Base {

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
     * @var String
     */
    protected $name;

    /**
     * @var String
     */
    protected $category;



    public function __construct($id = 'me', $name = '', $category = '',$api = null) {
        $this->id = new String($id);
        $this->name = new String($name);
        $this->category = new String($category);
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
    public function getName() {
        $this->name;
    }

    /**
     *
     * @return String
     */
    public function getCategory() {
        $this->category;
    }
    
    public function __toString() {
        return $this->name.' ('.$this->category.')';
    }
    
    
  /**
     * @param string $since
     * @param string $until
     * @return \Facebook\Type\Event\Collection
     */
    public function getEvents($since = 'today', $until = '+7 days') {
        $parameters = array(
            'fields'    =>  'id,name,description,start_time,end_time,updated_time,venue,privacy,location',
        );
        
        if ($since) {
            $parameters['since'] = $since;
        }
        if ($until) {
            $parameters['until'] = $until;
        }
        
        $events = $this->api->api('/' . $this->getId() . '/events','GET',$parameters);
        return Event\Collection\Factory::fromArray($events['data']);
    }    

}

?>