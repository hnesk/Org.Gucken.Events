<?php
namespace Util;


class Options implements \ArrayAccess, \IteratorAggregate, \Countable {

    const KEY_SEPARATOR = '.';

    /**
     *
     * @var array
     */
    protected $data;

    /**
     *
     * @param array|string|object|Options $data
     * @return \Type\Options
     */
    public static function factory($data=array()) {
        if (\is_object($data) && $data instanceof Options) {
            $options = $data;
        } else {
            $options = new Options($data);
        }
        return $options;
    }

    /**
     *
     * @param array|string|object $data
     */
    public function __construct($data=array()) {
        if (!$data) {
            $this->setByArray(array());
        } else if (is_array($data)) {
            $this->setByArray($data);
        } else if (\is_string($data)) {
            $this->setByString($data);
        } else if (is_object($data)) {
            $this->setByObject($data);
        }
    }

    /**
     *
     * @param Options|string|array|stdClass $o
     * @return Options
     */
    public function merge($o) {
        if (!is_object($o) || !$o instanceof Options) {
            $o = new Options($o);
        }
        return new Options(\array_merge_recursive($this->toArray(), $o->toArray()));
    }

    /**
     *
     * @param array $data
     */
    protected function setByArray(array $data) {
        $this->data = $data;
    }

    /**
     *
     * @param string $data
     */
    protected function setByString($data) {
        $dataObject = \json_decode($data);
        if ($dataObject) {
            $this->setByObject($dataObject);
        } else if (\preg_match('#^([\w+\.-]+)(?:=(.+))?$#', $data,$matches)) {
            $array = array();
            $current = &$array;
            foreach (explode(self::KEY_SEPARATOR, $matches[1]) as $key) {
                $current[$key] = array();
                $current = &$current[$key];
            }
            $current = isset($matches[2]) ? trim($matches[2]) : 1;
            $this->setByArray($array);
        } else {
            throw new \InvalidArgumentException('If $data is a string it has to be json encoded or a "'.self::KEY_SEPARATOR.'"-separated php.ini like entry, '.$data.' given', 1311360004);
        }
        
    }

    /**
     *
     * @param object $data
     */
    protected function setByObject($data) {
        if ($data instanceof Options) {
            $this->data = $data->toArray();
        } else {
            $this->data = self::convertToArray($data);
        }
    }

    /**
     *
     * @param object|array $data
     * @return array
     */
    protected static function convertToArray($data) {
        $data = (array)$data;
        foreach ($data as $k => &$v) {
            if (\is_object($v)) {
                $v = self::convertToArray($v);
            }
        }
        return $data;
    }

    /**
     *
     * @return array
     */
    public function toArray() {
        return $this->data;
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return \json_encode($this->data);
    }
    /**
     *
     * @return boolean
     */
    public function isEmpty() {
        return count($this->data) === 0;
    }


    /* implements \IteratorAggregate */

    public function getIterator() {
        return new \ArrayIterator($this->data);
    }


    public function get($key) {
        if (strpos($key, self::KEY_SEPARATOR) === false) {
            if (isset($this->data[$key])) {
                $value = $this->data[$key];
                return is_array($value) ? new Options($value) : $value;
            } else {
                return null;
            } 
        } else {
            list($currentKey, $restKey) = \explode(self::KEY_SEPARATOR, $key, 2);
            if (isset($this->data[$currentKey]) && is_array($this->data[$currentKey])) {
                $options = new Options($this->data[$currentKey]);
                return $options->get($restKey);
            } else {
                return null;
            }
        }        
    }

    /* implements \ArrayAccess */

    public function offsetGet($key) {
        return $this->get($key);
    }

    public function offsetExists($key) {
        return $this->get($key) !== null;
    }

    public function offsetSet($key, $value) {
        throw new \BadMethodCallException('Options is readonly', 1311360503);
    }

    public function offsetUnset($key) {
        throw new \BadMethodCallException('Options is readonly', 1311360503);
    }

    /* implements \Countable */
    /**
     * Number of options in this level
     *
     * @return int
     */
    public function count() {
        return count($this->data);
    }
}
?>
