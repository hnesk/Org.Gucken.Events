<?php

namespace Lastfm\Type\Artist;

class Collection extends \Type\BaseCollection {
    protected $itemDataType = '\Lastfm\Type\Artist';

    /**
     *
     * @param \Type\String $separator
     * @return \Type\String
     */
    public function join($separator=', ') {
        $values = array();
        foreach ($this->elements as $artist) {
            /* @var $artist \Lastfm\Type\Artist */
            $values[] = $artist->getArtist();
        }
        return new \Type\String(\join((string)$separator,$values));
    }
}

?>
