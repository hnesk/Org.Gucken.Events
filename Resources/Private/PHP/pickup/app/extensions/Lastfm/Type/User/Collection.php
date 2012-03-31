<?php
namespace Lastfm\Type\User;
use Lastfm\Type\User;

class Collection extends \Type\BaseCollection {
    protected $itemDataType = '\Lastfm\Type\User';

    /**
     *
     * @return \Lastfm\Type\Track\Collection
     */
    public function lovedTracks() {
        return $this->map(function (User $user) {return $user->lovedTracks();}, '\Lastfm\Type\Track\Collection');
    }


}

?>
