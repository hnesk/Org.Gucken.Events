<?php
namespace Type;
class Factory {

    /**
     *
     * @param mixed $data
     * @return Type
     */
    public static function fromSimpleType($data) {
        $type = \gettype($data);
        switch ($type) {
            case 'integer':
            case 'double':
            case 'float':
                return self::fromNumber($data);
            case 'boolean':
            case 'string':
                return self::fromString($data);
            case 'array':
                return self::fromArray($data);
            case 'object':             
                return self::fromObject($data);
            case 'resource':
            case 'NULL':
            case 'unknown type':
                return NULL;

        }
    }

    /**
     *
     * @param string $data
     * @return String
     */
    public static function fromString($data) {
        return new String($data);
    }

    /**
     *
     * @param array $data
     * @return Json
     */
    public static function fromArray($data) {
        return new Json($data);
    }

    /**
     *
     * @param object $data
     * @return Base
     */
    public static function fromObject($data) {
        if ($data instanceof Base || $data instanceof BaseCollection) {
            return $data;
        } else if ($data instanceof \Traversable) {
            $array = array();
            foreach ($data as $k => $v) {
                $array[$k] = $v;
            }
            return self::fromArray($array);
        } else {
            return self::fromArray((array)$data);
        }
    }

    /**
     *
     * @param double|int|number $number
     * @return Number
     */
    public static function fromNumber($number) {
        return new Number((string)$number);
    }


}

?>
