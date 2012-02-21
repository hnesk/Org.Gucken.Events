<?php

namespace Type;

/**
 * A Number Class for int and float values
 *
 * @author jk
 */
class Number extends \Type\Base {

    /**
     *
     * @param int|double $value
     */
    public function __construct($value = 0) {
        if ($value instanceof Number || $value instanceof String) {
            $this->value = $value->getNativeValue();
        } else if (\is_numeric($value)) {
            $this->value = $value;
        } 
    }
    

    /**
     *
     * @param Number $number
     * @return Number
     */
    public function add($number) {
        return new Number($this->value + (string)$number);
    }

    /**
     *
     * @param Number $number
     * @return Number
     */
    public function subtract($number) {
        return new Number($this->value - (string)$number);
    }

    /**
     *
     * @param Number $number
     * @return Number
     */
    public function multiply($number) {
        return new Number($this->value * (string)$number);
    }

    /**
     *
     * @param Number $number
     * @return Number
     */
    public function divide($number) {
        $divisor = (string)$number;
        if ($divisor == 0) {
            throw new \InvalidArgumentException('Division by zero', 1310841128);
        }
        return new Number($this->value / $divisor);
    }

    public function isGreaterThan($number) {
        return ($this->value > (string)$number);
    }


    public function isLessThan($number) {
        return ($this->value < (string)$number);
    }

    public function isNegative() {
        return $this->isLessThan(0);
    }

    public function isPositive() {
        return $this->isGreaterThan(0);
    }



    /**
     *
     * @param int|Number  $base
     * @return String
     */
    public function toString($base = 10) {
        $base = (int)(string)$base;
        if ($base !== 10) {
            throw new \InvalidArgumentException('Not implemented for base != 10', 1310841245);
        }
        return new String($this->getNativeValue());
    }

    public function sprintf($format = '%d') {
        return new String(\vsprintf($format, array($this->value)));
    }


    public function toExponential($length = null) {
        throw new \Exception('Not implemented', 1310841245);
    }

    public function toFixed($length = null) {
        throw new \Exception('Not implemented', 1310841245);
    }

    public function toPrecision($length = null) {
        throw new \Exception('Not implemented', 1310841245);
    }
    public function toLocaleString($locale = null) {
        throw new \Exception('Not implemented', 1310841245);
    }

}
?>
