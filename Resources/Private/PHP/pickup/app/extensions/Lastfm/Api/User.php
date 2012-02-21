<?php

namespace Lastfm\Api;

class User extends Base {


    /**
     * namespace for last.fm api call
     * @return string
     */
    protected function getPrefix() {
        return 'user';
    }

    /**
     *
     * @param string $user
     * @param int $limit
     * @param int $page
     * @return \SimpleXMLElement
     */
    public function getLovedTracks($user, $limit = 50, $page = 1) {
        return $this->callMethod(__FUNCTION__,$this->getReflectedParameters(\func_get_args()));
    }

    /**
     * user.getArtistTracks
     * Get a list of tracks by a given artist scrobbled by this user, including scrobble time. Can be limited to specific timeranges, defaults to all time.

     * e.g. http://ws.audioscrobbler.com/2.0/?method=user.getartisttracks&user=rj&artist=metallica&api_key=...
     *
     * @param string $user
     * @param string $artist
     * @param int $startTimestamp
     * @param int $endTimestamp
     * @param int $page
     * @return \SimpleXMLElement
     */
    public function getArtistTracks($user, $artist, $startTimestamp = null, $endTimestamp = null, $page = 1) {
        return $this->callMethod(__FUNCTION__,$this->getReflectedParameters(\func_get_args()));
    }

    /**
     * user.getBannedTracks
     * Returns the tracks banned by the user
     *
     * e.g. http://ws.audioscrobbler.com/2.0/?method=user.getbannedtracks&user=rj&api_key=b25b959554ed76058...
     *
     * @param string $user
     * @param int $limit (Optional) : The number of results to fetch per page. Defaults to 50.
     * @param int $page (Optional) : The page number to fetch. Defaults to first page.
     * @return \SimpleXMLElement
     */
    public function getBannedTracks($user, $limit = 50, $page = 1) {
        return $this->callMethod(__FUNCTION__,$this->getReflectedParameters(\func_get_args()));
    }


    /**
     * user.getEvents
     * Get a list of upcoming events that this user is attending. Easily integratable into calendars, using the ical standard (see 'more formats' section below).
     *
     * e.g. http://ws.audioscrobbler.com/2.0/?method=user.getevents&user=mokele&api_key=b25b959554ed76058ac...
     *
     * @param string $user
     * @param int $limit (Optional) : The number of results to fetch per page. Defaults to 50.
     * @param int $page (Optional) : The page number to fetch. Defaults to first page.
     * @return \SimpleXMLElement
     */
    public function getEvents($user, $limit = 50, $page = 1) {
        return $this->callMethod(__FUNCTION__,$this->getReflectedParameters(\func_get_args()));
    }

}
?>
