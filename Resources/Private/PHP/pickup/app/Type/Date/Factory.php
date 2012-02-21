<?php
namespace \Type\Date;
use \Type\Date;

class Factory {
    /**
     *
     * @param string $string
     * @return Date
     */
    public static function fromString($string) {
        return new Date($string);
    }
    
    /**
     *
     * @param String $string
     * @return Date 
     */
    public static function fromTypeString($string) {
        return new Date((string)$string);
    }

    /**
     *
     * @param String $string
     * @return Date
     */
    public static function fromDateTime(\DateTime $datetime) {
        return new Date($datetime);
    }


}
?>
