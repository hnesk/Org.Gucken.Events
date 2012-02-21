<?php
namespace Type\Feed\Item;
use \Type;

/**
 * Feed\Item\Collection: A type-safe array for Feed Item objects
 *
 * @author jk
 */
class Collection extends \Type\BaseCollection {
    protected $itemDataType = '\Type\Feed\Item';

    /**
     * @return \Type\Url\Collection
     */
    public function url() {
        return $this->link();
    }


    /**
     *
     * @return \Type\Url\Collection
     */
    public function link() {
        return $this->map(function (\Type\Feed\Item $item) {return $item->link();}, '\Type\Url\Collection');
    }

    /**
     *
     * @return \Type\Url\Collection
     */
    public function links() {
        return $this->map(function (\Type\Feed\Item $item) {return $item->links();}, '\Type\Url\Collection');
    }

}
?>
