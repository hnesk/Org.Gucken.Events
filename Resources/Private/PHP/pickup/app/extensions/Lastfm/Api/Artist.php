<?php

namespace Lastfm\Api;

class Artist extends Base {


    /**
     * namespace for last.fm api call
     * @return string
     */
    protected function  getPrefix() {
        return 'artist';
    }

    /**
     * artist.getTopFans
     * Get the top fans for an artist on Last.fm, based on listening data.
     *
     * e.g. http://ws.audioscrobbler.com/2.0/?method=artist.gettopfans&artist=cher&api_key=b25b959554ed7605...
     *
     * @param $artist (Required (unless mbid)] : The artist name
     * @param $mbid (Optional) : The musicbrainz id for the artist
     * @param $autocorrect[0|1] (Optional) : Transform misspelled artist names into correct artist names, returning the correct version instead. The corrected artist name will be returned in the response.
     */
    public function getTopFans($artist, $mbid = null, $autocorrect = 0) {
        return $this->callMethod(__FUNCTION__,$this->getReflectedParameters(\func_get_args()));
    }



}
?>
