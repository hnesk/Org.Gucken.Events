<?php
namespace App;

class PersistentRegistry implements \ArrayAccess {

    /**
     *
     * @var string
     */
    protected $filename;

    /**
     *
     * @var array
     */
    protected $data = array();

    public function __construct($filename = null) {        
        $this->filename = $filename ?: \BASE_PATH.'../data/registry.php';
        $this->read();
    }


    protected function read() {
        if (!\file_exists($this->filename)) {
            $this->data = array();
            $this->write();
        }
        $this->data = require $this->filename;
    }

    protected function write() {
        \file_put_contents($this->filename, '<?php return '.\var_export($this->data, true).'; ?>');
        \chmod($this->filename, 0777);
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value) {
        if (!isset($this->data[$offset]) || $this->data[$offset] != $value) {
            $this->data[$offset] = $value;
            $this->write();
        }
    }

    public function offsetUnset($offset) {
        if (isset($this->data[$offset])) {
            unset($this->data[$offset]);
            $this->write();
        }
    }
}
?>