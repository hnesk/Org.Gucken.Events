<?php
namespace Type;

/**
 * Description of Data
 *
 * @author jk
 */
class Base {
    /**
     *
     * @var mixed
     */
    protected $value;

    /**
     *
     * @return mixed
     */
    public function getNativeValue() {
        return $this->value;
    }

	public function equals($other) {
		return $this->__toString() == (string)$other;
	} 
	
    /**
     * 
     * @return boolean
     */
    public function is() {
        return !!$this->getNativeValue();
    }

    /**
     *
     * @param Base $value
     * @return Base
     */
    public function defaultTo($value) {
        return $this->is() ? $this : $value;
    }


    public function __toString() {
        return (string)$this->getNativeValue();
    }

    public static function cast($value) {
        return self::fromNativeValue($value);
    }

    public static function fromNativeValue($value) {
        return new self($value);
    }

    public function __call($method,$args) {
        $method = string($method);
        if (!$method->startsWith('to')) {
            throw new \BadMethodCallException('Unknown method '.get_class($this).'::'.$method, 1306797134);
        }
        return $this->castTo($method->substring(2));
    }

    /**
     *
     * @param Type\String $className
     * @return Type
     */
    public function castTo($className) {
        $factoryClassName = $className->toNamespace()->append('\\Factory');
        $factoryMethod = string('from')->append(string(\lcfirst(\get_class($this)))->toCamelCase('\\'));

        return \call_user_func($factoryClassName.'::'.$factoryMethod, $this);

    }

}
?>
