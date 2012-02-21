<?php

namespace Facebook\Type\User\Collection;
use \Facebook\Type\User;

class Factory {

    /**
     *
     * @param array
     * @return User\Collection
     */
    public static function fromArray($data) {
        $collection = new User\Collection();
        foreach ($data as $singleData) {
            $collection->addOne(User\Factory::fromArray($singleData));
        }
        return $collection;
    }

}
?>
