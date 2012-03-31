<?php

namespace Lastfm\Api;

class Track extends Base {


    /**
     * namespace for last.fm api call
     * @return string
     */
    protected function getPrefix() {
        return 'track';
    }

  /**
     * user.getBannedTracks
     * Returns the tracks banned by the user
     *
     * e.g. http://ws.audioscrobbler.com/2.0/?method=user.getbannedtracks&user=rj&api_key=b25b959554ed76058...
     *
     * @param string $track (Required (unless mbid)] : The track name
     * @param string $artist (Required (unless mbid)] : The artist name
     * @param int $autocorrect [0|1] (Optional) : Transform misspelled artist and track names into correct artist and track names, returning the correct version instead. The corrected artist and track name will be returned in the response.
     * @return \SimpleXMLElement
     */
    public function getTopFans($track, $artist, $autocorrect = 0) {
        return $this->callMethod(__FUNCTION__,$this->getReflectedParameters(\func_get_args()));
    }


}
?>
