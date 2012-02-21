<?php
namespace Type\Number;
use \Type\String;
use \Type\Number;

class Factory {
    /**
     * @param string $string
     * @param string $locale
     * @return Number
     */
    public static function fromString($string='',$locale = 'de_DE.utf8') {                
        if ($locale) {
            $oldLocale = setlocale(LC_ALL, $locale);
        }
        $l = localeconv();
        $replace = array(
            trim($l['thousands_sep']) => '',
            trim($l['mon_thousands_sep']) => '',
            trim($l['decimal_point']) => '.',
            trim($l['mon_decimal_point']) => '.',            
            trim($l['currency_symbol']) => '',
            trim($l['int_curr_symbol']) => ''
        );
        $string = str_replace(array_keys($replace), array_values($replace), $string);       
        $string = str_replace('-', '0', (string)$string);
        
        if (strpos($string,'.') !== false) {
            $number = floatval($string);
        } else {
            $number = intval($string);
        }
        if ($oldLocale) {
            setlocale(LC_ALL, $oldLocale);
        }
        
        return new Number($number);
    }
    
    /**
     *
     * @param String $string
     * @param string $locale
     * @return Number
     */
    public static function fromTypeString($string, $locale = 'de_DE.utf8') {
        return self::fromString($string, $locale);
    }



}

?>
