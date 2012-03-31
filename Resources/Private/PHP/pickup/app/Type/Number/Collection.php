<?php
namespace Type\Number;
use \Type;
use \Type\Number;

/**
 * String\Collection: A type-safe array for String objects
 *
 * @author jk
 */
class Collection extends \Type\BaseCollection {
    protected $itemDataType = '\Type\Number';

    /**
     *
     * @param string $separator
     * @return String
     */
    public function join($separator = ' ') {
        return new String(\join((string)$separator, $this->getNativeValue()));
    }
}
?>
