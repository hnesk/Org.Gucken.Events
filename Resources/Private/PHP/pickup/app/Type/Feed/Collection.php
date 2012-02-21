<?php
namespace Type\Feed;
use \Type;

/**
 * Feed\Collection: A type-safe array for Feed objects
 *
 * @author jk
 */
class Collection extends \Type\BaseCollection {
    protected $itemDataType = '\Type\Feed';

    /**
     *
     * @return \Type\Feed\Item\Collection
     */
    public function getItems() {
        return $this->map(function (\Type\Feed $feed) {return $feed->getItems();}, '\Type\Feed\Item\Collection');
    }

}
?>
