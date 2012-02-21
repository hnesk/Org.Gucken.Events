<?php

namespace Util;
class ArrayFunctions {

    /**
     * Guarentees the argument as an array
     * @param array|string $string
     * @param string $separator optional default ','
     * @return array
     */
    public static function toArray($string, $separator = ',') {
        if (!is_array($string)) {
            return self::trimExplode($string, $separator);
        } else {
            return $string;
        }
    }


    /**
     *
     *
     * @param string $string
     * @param string $separator
     * @return array
     */
    public static function trimExplode($string, $separator = ',') {
        return array_filter(array_map('trim',\explode($separator, (string)$string)));
    }


}
?>
