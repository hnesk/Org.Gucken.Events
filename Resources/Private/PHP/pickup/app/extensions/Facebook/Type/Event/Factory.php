<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Facebook\Type\Event;
use \Facebook\Type\Event;
use \Type\Number;
use \Type\String;
use \Type\Date;

class Factory {

    /**
     *
     * @param array $data
     * @return \Facebook\Type\Event
     */
    public static function fromArray($data) {
        return new \Facebook\Type\Event(
            $data['id'],
            $data['name'],
            @$data['description'],
            $data['start_time'],               
            @$data['end_time'],
            $data['location'], 
            $data['venue']['id'],
            $data['updated_time'],
            \Facebook\Injector::injectFacebookApi()
        );
    }

}

?>
