<?php

namespace Lastfm\Api;

class Geo extends Base {


    /**
     * namespace for last.fm api call
     * @return string
     */
    protected function  getPrefix() {
        return 'geo';
    }

    /**
     * geo.getEvents
     * Get all events in a specific location by country or city name.

     * e.g. http://ws.audioscrobbler.com/2.0/?method=geo.getevents&location=madrid&api_key=b25b959554ed7605...
     * @param $lat (Optional) : Specifies a latitude value to retrieve events for (service returns nearby events by default)
     * @param $long (Optional) : Specifies a longitude value to retrieve events for (service returns nearby events by default)*
     * @param $location (Optional) : Specifies a location to retrieve events for (service returns nearby events by default)
     * @param $distance (Optional) : Find events within a specified radius (in kilometres)
     * @param $limit (Optional) : The number of results to fetch per page. Defaults to 10.
     * @param $page (Optional) : The page number to fetch. Defaults to first page.
     * @return \SimpleXMLElement
     */
    public function getEvents($lat=0.0, $long=0.0, $location='', $distance = 20, $limit = 50, $page = 1) {
        return $this->callMethod(__FUNCTION__,$this->getReflectedParameters(\func_get_args()));
    }

    /**
     * geo.getEvents
     * Get all events in a specific location by country or city name.

     * e.g. http://ws.audioscrobbler.com/2.0/?method=geo.getevents&location=madrid&api_key=b25b959554ed7605...
     * @param $lat (Optional) : Specifies a latitude value to retrieve events for (service returns nearby events by default)
     * @param $long (Optional) : Specifies a longitude value to retrieve events for (service returns nearby events by default)*
     * @param $distance (Optional) : Find events within a specified radius (in kilometres)
     * @param $limit (Optional) : The number of results to fetch per page. Defaults to 10.
     * @param $page (Optional) : The page number to fetch. Defaults to first page.
     * @return \SimpleXMLElement
     */
    public function getEventsByCoords($lat=0.0, $long=0.0, $distance = 20, $limit = 50, $page = 1) {
        return $this->getEvents($lat, $long, $location=null, $distance, $limit, $page);
    }

    /**
     * geo.getEvents
     * Get all events in a specific location by country or city name.

     * e.g. http://ws.audioscrobbler.com/2.0/?method=geo.getevents&location=madrid&api_key=b25b959554ed7605...
     * @param $lat (Optional) : Specifies a latitude value to retrieve events for (service returns nearby events by default)
     * @param $long (Optional) : Specifies a longitude value to retrieve events for (service returns nearby events by default)*
     * @param $distance (Optional) : Find events within a specified radius (in kilometres)
     * @param $limit (Optional) : The number of results to fetch per page. Defaults to 10.
     * @param $page (Optional) : The page number to fetch. Defaults to first page.
     * @return \SimpleXMLElement
     */
    public function getEventsByLocation($location, $distance = 20, $limit = 50, $page = 1) {
        return $this->getEvents($lat = null, $long = null, $location, $distance, $limit, $page);
    }


}
?>
