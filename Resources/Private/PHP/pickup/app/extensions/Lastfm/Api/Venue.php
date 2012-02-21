<?php

namespace Lastfm\Api;

class Venue extends Base {


    /**
     * namespace for last.fm api call
     * @return string
     */
    protected function  getPrefix() {
        return 'venue';
    }
    
    
    /**
     * venue.getEvents
     * Get a list of upcoming events at this venue.
     * 
     * e.g. http://ws.audioscrobbler.com/2.0/?method=venue.getevents&api_key=b25b959554ed76058ac220b7b2e0a0...
     *
     * @param int $venue (Required) :The id for the venue you would like to fetch event listings for.
     * @param boolean $festivalsonly (Optional) : Whether only festivals should be returned, or all events.
     */   
    public function getEvents($venue, $festivalsonly = 0) {
        return $this->callMethod(__FUNCTION__,$this->getReflectedParameters(\func_get_args()));
    }
    

    /**
     * venue.getPastEvents
     * Get a paginated list of all the events held at this venue in the past.
     *
     * e.g. http://ws.audioscrobbler.com/2.0/?method=venue.getpastevents&api_key=b25b959554ed76058ac220b7b2...
     * 
     * @param int $venue int (Required) :The id for the venue you would like to fetch event listings for.
     * @param boolean $festivalsonly boolean (Optional) : Whether only festivals should be returned, or all events.
     * @param int $page (Optional) :The page of results to return.
     * @param int $limit (Optional) : The number of results to fetch per page. Defaults to 50.     *
     */   
    public function getPastEvents($venue, $festivalsonly = 0,$page = 1, $limit = 50) {
        return $this->callMethod(__FUNCTION__,$this->getReflectedParameters(\func_get_args()));
    }

    /**
     *
     * @param string $venue The venue name you would like to search for.
     * @param string $country Filter your results by country. Expressed as an ISO 3166-2 code.
     * @param int $page The results page you would like to fetch
     * @param int $limit The number of results to fetch per page. Defaults to 50.
     */
    public function search($venue, $country = 'DE', $page = 1, $limit = 50) {
        return $this->callMethod(__FUNCTION__,$this->getReflectedParameters(\func_get_args()));
    }

}
?>
