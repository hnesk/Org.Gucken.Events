<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Lastfm;

class Injector {
    /**
     *
     * @return \Lastfm\Api\User
     */
    public static function injectLastfmUserApi() {
        return new \Lastfm\Api\User(
                self::injectLastfmKey(),
                \App\Injector::injectHttpClient()
        );
    }

    /**
     *
     * @return \Lastfm\Api\Track
     */
    public static function injectLastfmTrackApi() {
        return new \Lastfm\Api\Track(
                self::injectLastfmKey(),
                \App\Injector::injectHttpClient()
        );
    }

    /**
     *
     * @return \Lastfm\Api\Geo
     */
    public static function injectLastfmGeoApi() {
        return new \Lastfm\Api\Geo(
                self::injectLastfmKey(),
                \App\Injector::injectHttpClient()
        );
    }

    /**
     *
     * @return \Lastfm\Api\Event
     */
    public static function injectLastfmEventApi() {
        return new \Lastfm\Api\Event(
                self::injectLastfmKey(),
                \App\Injector::injectHttpClient()
        );
    }

    
    /**
     *
     * @return \Lastfm\Api\Venue
     */
    public static function injectLastfmVenueApi() {
        return new \Lastfm\Api\Venue(
                self::injectLastfmKey(),
                \App\Injector::injectHttpClient()
        );
    }
    

    /**
     *
     * @return \Lastfm\Api\Artist
     */
    public static function injectLastfmArtistApi() {
        return new \Lastfm\Api\Artist(
                self::injectLastfmKey(),
                \App\Injector::injectHttpClient()
        );
    }
    /**
     * @return string
     */
    public static function injectLastfmKey() {
        return '8d4e3af8d3cec235a8452d438369b068';
    }
}

?>
