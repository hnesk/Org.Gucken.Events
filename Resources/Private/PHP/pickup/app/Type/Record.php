<?php
namespace Type;


/**
 * Description of Record
 *
 * @author jk
 */
class Record extends \Type\Base implements \ArrayAccess {
    public function __construct($data) {
        $this->value = $data;
    }


    public function get($key) {
        return isset($this->value[$key]) ? $this->value[$key] :null;
    }

    public function keys() {
        return array_keys($this->value);
    }


    public function getNative($key) {
        if (!isset($this->value[$key])) {
            return null;
        }
        $value = $this->value[$key];
        $value = $value instanceof \Type\Base ? $value->getNativeValue() : $value;
        return $value ?: null;
    }
    
    public function is() {
        return count($this->value) > 0;
    }

    public function offsetExists($offset) {
        return isset($this->value[$offset]);
    }

    public function offsetGet($offset) {
        return $this->getNative($offset);
    }

    public function offsetSet($offset, $value) {
        throw new \RuntimeException('Record modification is not permitted');
    }

    public function  offsetUnset($offset) {
        throw new \RuntimeException('Record modification is not permitted');
    }


    public function getNativeValue() {
        $data = array();
        foreach ($this->value as $key => $value) {
            $nativeValue = $value instanceof \Type\Base ? $value->getNativeValue() : $value;
            if (is_object($nativeValue) && method_exists($nativeValue, '__toString')) {
                $nativeValue = get_class($nativeValue).': "'.$nativeValue.'"';
            }
            $data[$key] = $nativeValue;
        }
        return $data;
    }
}
?>
