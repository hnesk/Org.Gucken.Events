<?php
namespace Type\String;
use \Type;
use \Type\String;

/**
 * String\Collection: A type-safe array for String objects
 *
 * @author jk
 */
class Collection extends \Type\BaseCollection {
    protected $itemDataType = '\Type\String';

    /**
     *
     * @param string $separator
     * @return \Type\String
     */
    public function join($separator = ' ') {
        return new String(\join((string)$separator, $this->getNativeValue()));
    }

    /**
     *
     * @param string $separator
     * @return \Type\String
     */
    public function normalizeSpace($space = ' ') {
        return $this->join($space)->normalizeSpace();
    }

    /**
     *
     * @param string $separator
     * @return \Type\String
     */
    public function normalizeParagraphs($separator = \PARAGRAPH_SEPARATOR) {
        return $this->join($separator)->normalizeParagraphs();
    }

}
?>
