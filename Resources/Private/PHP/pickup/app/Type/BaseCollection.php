<?php

namespace Type;
use Type;
/**
 * A type-safe array
 *
 * @author jk
 */
class BaseCollection extends \Type\Base implements \Iterator, \Countable, \ArrayAccess {

    const ASCENDING = 1;
    const DESCENDING = -1;

    /**
     *
     * @var string
     */
    protected $itemDataType = null;

    protected $flatten = true;
    
    protected $tryCast = true;
    

    /**
     *
     * @var array
     */
    protected $elements;

    public function __construct($elements = array(),$flatten=true,$tryCast = true) {
        $this->elements = array();
        $this->flatten = $flatten;
        $this->tryCast = $tryCast;
        $this->add($elements);
    }

    public function validElement($element) {
        $class = $this->itemDataType;
        if (!is_null($element) && $class && !$element instanceof $class) {
            if ($this->tryCast) {
                try {                    
                    $element = $this->validElement($class::cast($element));
                } catch (Exception $e) {
                    throw new \InvalidArgumentException('Collection expects elements to be "' . $this->itemDataType . '", ' . (\is_object($element) ? get_class($element) : \gettype($element)) . ' given and unable to cast', null, $e);
                }                
            }
            if (!is_null($element) && $class && !$element instanceof $class) {
                throw new \InvalidArgumentException('Collection expects elements to be "' . $this->itemDataType . '", ' . (\is_object($element) ? get_class($element) : \gettype($element)) . ' given');
            }
        }
        return $element;
    }

    /**
     *
     * @param \Type\BaseCollection|array
     * @param boolean should a Collection of Collections be flattened?
     * @return void
     */
    public function add($elements) {
        if (is_array($elements) || $elements instanceof \Type\BaseCollection) {
            foreach ($elements as $element) {
                if ($this->flatten) {
                    $this->add($element);
                } else {
                    $this->addOne($element);
                }
            }
        } else {
            $this->addOne($elements);
        }
    }

    public function addOne($element) {
        $element = $this->validElement($element);
        $this->elements[] = $element;
    }


    /**
     * @param string $separator
     * @throws \Exception
     * @return mixec
     */
    public function join($separator = ', ') {
        throw new \Exception('You need to implement join in subclasses');
    }


    /**
     *
     * @return \Type\Collection
     */
    public function values() {
        $newCollection = $this->newSelf();
        foreach ($this->elements as $element) {
            $newCollection->addOne($element);
        }
        return $newCollection;
    }

    /**
     *
     * @return \Type\String\Collection
     */
    public function keys() {
        $newCollection = new \Type\String\Collection();
        foreach ($this->elements as $key => $dummy) {
            $newCollection->addOne($key);
        }
        return $newCollection;
    }

    public function  __toString() {
        $string = '';
        foreach ($this->elements as $key => $element) {
            $string .= ' * '. $key .': '.$element.PHP_EOL;
        }
        return $string;
    }

    /**
     *
     * @return BaseCollection
     */
    public function sort($comparisonFunction = null) {
        $comparisonFunction = $comparisonFunction ?: function ($a,$b) {
            return (\strcmp((string)$a, (string)$b));
        };
        usort($this->elements, $comparisonFunction);
        return $this;
    }



    /**
     * removes subsequent duplicates, for complete unique collections use sort first
     *
     * @return BaseCollection
     */
    public function unique($equalityFunction = null) {
        $equalityFunction = $equalityFunction ?: function ($a,$b) {
            return (string)$a === (string)$b;
        };
        $newCollection = $this->newSelf();
        $lastElement = null;
        foreach ($this->elements as $element) {
            if (!$equalityFunction($element, $lastElement)) {
                $newCollection->addOne($element);
                $lastElement = $element;
            }                        
        }
        return $newCollection;
    }



    /**
     *
     * @param callable $c
     * @return BaseCollection
     */
    public function map($c,$newElementClass = null,$flatten = true) {
        $newElementsArray = array();
        foreach ($this->elements as $key => $element) {
            $newElement = call_user_func_array($c, array($element,$key));
            if (!$newElementClass && !is_null($newElement) && $newElement instanceof \Type\Base) {
                $newElementClass = get_class($newElement).'\Collection';
            }
            /*
            if ($newElement && !$newElement->is()) {
                var_dump($newElement);
                die();
            }
             
             */
            if ($newElement) {
                $newElementsArray[] = $newElement;
            }
        }
        if ($newElementClass) {
            return new $newElementClass($newElementsArray, $flatten);
        } else {
            return new BaseCollection($newElementsArray, $flatten);
        }
    }

    /**
     * name of the item function to call
     * @param string $name 
     * @return BaseCollection
     */
    public function mapItem($name, $args = array(), $newElementClass = null,$flatten = true) {
        $args = is_array($args) ? $args : array();
        return $this->map(
                function ($element,$key) use ($name, $args) {return \call_user_func_array(array($element,$name),$args);},
                $newElementClass,
                $flatten
        );
    }

    /**
     *
     * @param int $start
     * @param int $length
     * @return BaseCollection
     */
    public function slice($start=0, $length = null) {
        $length = \is_null($length) ? count($this->elements) - $start : $length;
        return $this->newSelf(\array_slice($this->elements, $start, $length ));
    }

    /**
     *
     * @param string $name
     * @param array $args
     * @return BaseCollection
     */
    public function __call($name, $args=array()) {
        return $this->mapItem($name, $args);
    }


    /**
     *
     * @param callable $c  function returning a boolish value
     * @return \Type\BaseCollection
     */
    public function filter($c = null) {
        $c = $c ?: function ($element, $key) {return $element && $element->is();};
        $newElements = $this->newSelf();
        foreach ($this->elements as $key => $element) {
            if ($c($element, $key)) {
                $newElements[$key] = $element;
            }
        }
        return $newElements;
    }


    public function each($c) {
        foreach ($this->elements as $key => $element) {
            $c($element, $key);
        }
    }

    /**
     *
     * @param int $key
     * @return \Type
     */
    public function item($key) {
        return isset($this->elements[$key]) ? $this->elements[$key] : null;
    }

    /**
     *
     * @param int $key
     * @return \Type
     */
    public function safeItem($key) {
        return isset($this->elements[$key]) ? $this->elements[$key] : $this->newItem();
    }


    /**
     * @return \Type
     */
    public function first() {
        return $this->item(0);
    }

    /**
     * @return \Type
     */
    public function last() {
        return $this->item($this->count() - 1);
    }

    // implements Iterator
    public function current() {
        return current($this->elements);
    }

    public function next() {
        next($this->elements);
    }

    public function key() {
        return key($this->elements);
    }

    public function valid() {
        return key($this->elements) !== null;
    }

    public function rewind() {
        reset($this->elements);
    }

    // implements Countable
    public function count() {
        return count($this->elements);
    }

    // implements ArrayAccess
    public function offsetExists($key) {
        return array_key_exists($key, $this->elements);
    }

    public function &offsetGet($key) {
        $value = &$this->elements[$key];
        return $value;
    }

    public function offsetSet($key, $value) {
        $this->validElement($value);        
        $this->elements[$key ?: $this->count()] = &$value;
    }

    public function offsetUnset($key) {
        unset($this->elements[$key]);
    }

    public function getItemDataType() {
        return $this->itemDataType;
    }


    public function store($name,$callback) {
        foreach ($this as $element) {
            /* @var Type $element */
            $element->store($name, $callback);
        }
        return $this;
    }

    /**
     *
     * @return BaseCollection
     */
    public function newSelf($elements = array()) {
            $class = get_class($this);
            return new $class($elements);
    }

    /**
     *
     * @return \Type
     */
    public function newItem() {
        $className = $this->itemDataType;
        return new $className();
    }


    /**
     *
     * @param \Closure $converter
     * @param \Closure $keyConverter
     * @return array
     */
    public function toArray($converter = null, $keyConverter = null) {
        if ($converter || $keyConverter) {
            $values = array();
            foreach ($this->elements as $index=>$element) {
                $newIndex = $index;
                if ($keyConverter) {
                    $newIndex = $keyConverter($element);
                }
                $values[$newIndex] = $converter($element);
            }            
            return $values;
        } else {
            return $this->getNativeValue();
        }
        
    }
    
    public function getNativeValue() {        
        $value = array();
        foreach ($this->elements as $k=>$element) {
            $value[$k] = $element->getNativeValue();
        }
        return $value;
    }
    
    /**
	 *
	 * @return boolean
	 */
	public function is() {
		return $this->count() > 0;
	}

}
?>