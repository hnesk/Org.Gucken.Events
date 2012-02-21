<?php

namespace Facebook\Type\Page\Collection;

use \Facebook\Type\Page;

class Factory {

    /**
     *
     * @param array
     * @return User\Collection
     */
    public static function fromString($q) {
        $api = \Facebook\Injector::injectFacebookApi();
        $result = $api->api('search', 'GET', array('q' => $q, 'type' => 'page'));

        $collection = new Page\Collection();
        foreach ($result['data'] as $data) {
            $collection->addOne(Page\Factory::fromArray($data));
        }
        return $collection;
    }

}

?>
