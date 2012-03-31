<?php
namespace App;

class Config implements \ArrayAccess, \Iterator {
    /**
     *
     * @var array
     */
    protected $_data;

    /**
     *
     * @param array $data
     */
    protected function __construct($data=array()) {
        $this->_data = $data;
    }

    /**
     *
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset) {
        return isset($this->_data[$offset]);
    }

    /**
     *
     * @param string $offset
     * @return Config
     */
    public function offsetGet($offset) {
        if (!isset($this->_data[$offset])) {
            return null;
        }
        $value = $this->_data[$offset];
        if (is_array($value)) {
            $this->_data[$offset] = new Config($value);
        }
        return $this->_data[$offset];
    }

    /**
     *
     * @param string $offset
     * @param string $value
     */
    public function offsetSet($offset, $value) {
        throw new \RuntimeException('configuration is readonly');
    }

    /**
     *
     * @param string $offset
     */
    public function offsetUnset($offset) {
        throw new \RuntimeException('configuration is readonly');
    }

    /**
     *
     * @return Config
     */
    public function current() {
        if (is_array(\current($this->_data))) {
            $this->_data[key($this->_data)] = new Config(\current($this->_data));
        }
        return \current($this->_data);
    }

    /**
     *
     * @return string
     */
    public function key() {
        return \key($this->_data);
    }

    public function next() {
        \next($this->_data);
    }

    public function valid() {
        return key($this->_data) !== null;
    }

    public function rewind() {
        \reset($this->_data);
    }

    /**
     *
     * @return array
     */
    public function choices() {
        return is_array($this->_data) ? array_keys($this->_data) : array();
    }

    /**
     *
     * @param string $key
     * @param mixed $default
     * @return Config
     */
    public function get($key = null, $default = null) {
        if (!$key) {
            return $this->_data;
        }
        if (is_null($default)) {
            $default = new Config();
        }
        $keys = explode('/',$key,2);
        if (count($keys) === 1) {
            $value = $this[$key];
            return $value ? $value : $default;
        } else {
            $config = $this[$keys[0]];
            $value = $config->get($keys[1]);
            return $value ? $value : $default;
        }
    }

    /**
     *
     * @param string $name
     * @return Config
     */
    public function __get($name) {
        return $this[$name];
    }

    public function toArray() {
        return $this->_data;
    }

    /**
     *
     * @param array $data
     * @return Config
     */
    public static function createFromArray($data = array()) {
        return new self($data);
    }

    /**
     *
     * @param string $configDirectory
     * @return Config
     */
    public static function createFromDirectory($configDirectory) {
        $data = array();
        foreach (glob($configDirectory.'/*.php') as $configFile) {
            $data = array_merge_recursive($data, require $configFile);
        }
        return self::createFromArray($data);
    }    
}
?>
