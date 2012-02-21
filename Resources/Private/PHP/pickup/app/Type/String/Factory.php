<?php
namespace Type\String;
use \Type\String;
use \Type\Xml;

class Factory {
    /**
     * @param string $string
     * @return String
     */
    public static function fromString($string='') {
        return new String($string);
    }


    /**
     * @param string $format
     * @param array<string>
     * @return String
     */
    public static function fromFormat($format='%s', $args = array()) {
        if (!\is_array($args)) {
            $args = \array_slice(\func_get_args(),1);
        }
        
        $stringArgs = array();
        foreach ($args as $k => $v) {
            $stringArgs[$k] = (string)$v;
        }

        return new String(\vsprintf($format, $stringArgs));
    }


    /**
     * @param Xml $xml
     * @return String
     */
    public static function fromTypeXml(Xml $xml) {
        return new String($xml->text());
    }

}

?>
