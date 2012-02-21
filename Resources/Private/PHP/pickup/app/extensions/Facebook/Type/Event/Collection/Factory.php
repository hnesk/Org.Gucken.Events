<?php

namespace Facebook\Type\Event\Collection;
use \Facebook\Type\Event;

class Factory {

    /**
     *
     * @param array
     * @return Event\Collection
     */
    public static function fromArray($data) {
        $collection = new Event\Collection();
        foreach ($data as $singleData) {
            $collection->addOne(Event\Factory::fromArray($singleData));
        }
        return $collection;
    }

}
?>
