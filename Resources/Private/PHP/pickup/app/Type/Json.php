<?php
namespace Type;


/**
 * A Json class 
 *
 * @author jk
 */
class Json extends \Type\Base implements \ArrayAccess, \IteratorAggregate, \Countable {
    public function __construct($value = array()) {
        $this->value = $value;
    }

    public function jpath($path) {
        if (!is_array($path)) {
            $path = explode('/',$path);
        }
        $first = \array_shift($path);

        $element = $this->offsetGet($first);
        if (count($path) == 0) {
            return $element;
        } else if ($element instanceof Json) {
            return $element->jpath($path);
        } else {
            return null;
        }
    }

    public function  count() {
        return count($this->value);
    }

    public function getIterator() {
        return new \ArrayIterator($this->value);
    }

    public function offsetGet($offset) {
        $element = null;
        if (isset($this->value[$offset])) {
            $element = \Type\Factory::fromSimpleType($this->value[$offset]);
        }
        return $element;
    }

    public function offsetExists($offset) {
        return isset ($this->value[$offset]);
    }

    public function offsetSet($offset,$value) {
        $this->value[$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($this->value[$offset]);
    }


}
?>
