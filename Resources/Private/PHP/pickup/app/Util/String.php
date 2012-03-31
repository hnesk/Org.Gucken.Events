<?php

namespace Util;

class String {

    /**
     * A particular bad enconding I've seen some times (UTF-8 encoded XML Entities in ISO-8859-1 or something like that)
     * @see 
     * 
     * @var array
     */
    protected static $badEncoding = array();

    /**
     * Build a separated_string from a camelCasedString 
     *
     * @param string $camelCase
     * @param string $separator
     * @return string
     */
    public static function camelCaseToSeparated($camelCase, $separator = '_') {
        return trim(preg_replace_callback('/([A-Z])/', function($c) use ($separator) {
                            return $separator . \lcfirst($c[1]);
                        }, $camelCase), $separator);
    }

    /**
     * Build a camelCasedString from a separated_string
     *
     * @param string $separated
     * @param string $separator
     * @return string
     */
    public static function separatedToCamelCase($separated, $separator = '_') {
        return lcfirst(implode('', \array_map('\ucfirst', explode($separator, $separated))));
        #return preg_replace_callback('/'.\preg_quote($separator).'([a-z])/', function($c) {return \ucfirst($c[1]);}, $separated);
    }

    /**
     * Build a php\namespace from a camelCasedString
     * 
     * @param string $camelCase
     * @return string
     */
    public static function camelCaseToNamespace($camelCase) {
        $separator = '\\';
        return trim(preg_replace_callback('/([A-Z])/', function($c) use ($separator) {
                            return $separator . $c[1];
                        }, $camelCase), $separator);
    }

    /**
     * Splits ranges in a string to an array of the range values
     *
     * simplified wrapper around @see expandRange for people who don't like regexp
     *
     * @param string $string
     * @param string $expression an expression to match the range, the matching parts must be tagged as "_from" and "_to"
     * @param string $replace (defaults to "_item")
     * @return array
     */
    public static function expandRangeSimple($string, $expression, $replace = '_item') {
        if (strpos($expression, '_from') === false || strpos($expression, '_to') === false) {
            throw new \InvalidArgumentException('Expression string must contain _from and _to', 1312046524);
        }
        $expression = '#' . \preg_quote($expression, '#') . '#';
        $expression = \str_replace('_from', '(?<from>\d+)', $expression);
        $expression = \str_replace('_to', '(?<to>\d+)', $expression);

        return self::expandRange($string, $expression, $replace);
    }

    /**
     * Splits ranges in a string to an array of the range values
     *
     * Given a string with a numeric range (e.g.: "1.-3.") and
     * a regular expression (e.g. "(?<from>\d+)\.-(?<to>\d+).") to match that string
     * returns an array of strings where the part matching the regular expression part
     * is replaced with each numeric value the range contains: (e.g "1.","2.", "3.")
     *
     * @param string $string
     * @param string $regularExpression a regexp to match the range, the matching parts must be tagged as ?<from> and ?<to>
     * @param string $replace (defaults to "_item")
     * @return array
     */
    public static function expandRange($string, $regularExpression, $replace='_item') {
        $result = array();

        if (strpos($regularExpression, '?<from>') === false) {
            throw new \InvalidArgumentException('Regular Expression must contain (?<from>...)', 1312046524);
        }

        if (strpos($regularExpression, '?<to>') === false) {
            throw new \InvalidArgumentException('Regular Expression must contain (?<to>...)', 1312046524);
        }

        if (preg_match($regularExpression, $string, $matches)) {
            $start = intval($matches['from']);
            $end = intval($matches['to']);
            if ($start > $end) {
                throw new LogicException('Can\'t parse ."' . $this->text . '" with "' . $regularExpression . '" because start("' . $start . '") is greater then end("' . $end . '")', 1312046806);
            }
            for ($t = $start; $t <= $end; $t++) {
                $replacement = str_replace('_item', $t, $replace);
                $result[] = preg_replace($regularExpression, $replacement, $string);
            }
        }
        return $result;
    }

    /**
     * Returns a string where each occurence of a range is replace by the expansion of the range
     *
     * simplified wrapper around @see expandRanges for people who don't like regexp
     *
     * @param string $string
     * @param string $expression an expression to match the range, the matching parts must be tagged as "_from" and "_to"
     * @param string $replace (defaults to "_item")
     * @param string $separator
     * @param string $replacementStartMarker
     * @param string $replacementEndMarker
     * @return string
     */
    public static function expandRangesSimple($string, $expression, $replace = '_item', $separator=' ', $replacementStartMarker = '', $replacementEndMarker = '') {
        if (strpos($expression, '_from') === false || strpos($expression, '_to') === false) {
            throw new \InvalidArgumentException('Expression string must contain _from and _to', 1312046524);
        }
        $expression = '#' . \preg_quote($expression, '#') . '#';
        $expression = \str_replace('_from', '(?<from>\d+)', $expression);
        $expression = \str_replace('_to', '(?<to>\d+)', $expression);

        return self::expandRanges($string, $expression, $replace, $separator, $replacementStartMarker, $replacementEndMarker);
    }

    /**
     * Returns a string where each occurence of a range is replace by the expansion of the range
     *
     * @param string $string
     * @param string $regularExpression
     * @param string $replace
     * @param string $separator
     * @param string $replacementStartMarker
     * @param string $replacementEndMarker
     * @return string
     */
    public static function expandRanges($string, $regularExpression, $replace='_item', $separator=' ') {
        $text = $string;

        if (strpos($regularExpression, '?<from>') === false) {
            throw new \InvalidArgumentException('Regular Expression must contain (?<from>...)', 1312046524);
        }

        if (strpos($regularExpression, '?<to>') === false) {
            throw new \InvalidArgumentException('Regular Expression must contain (?<to>...)', 1312046524);
        }

        if (preg_match_all($regularExpression, $string, $matchesCollection, PREG_SET_ORDER)) {
            foreach ($matchesCollection as $matches) {
                $start = intval($matches['from']);
                $end = intval($matches['to']);
                $operator = isset($matches['op']) ? $matches['op'] : '-';
                if ($start > $end) {
                    throw new LogicException('Can\'t parse ."' . $string . '" with "' . $regularExpression . '" because start("' . $start . '") is greater then end("' . $end . '")');
                }
                $markers = array();
                foreach ($matches as $key => $value) {
                    $markers['_' . $key] = $value;
                }

                $replacements = array();
                if ($operator === '-') {
                    for ($t = $start; $t <= $end; $t++) {
                        $markers['_item'] = $t;
                        $replacements[] = str_replace(array_keys($markers), array_values($markers), $replace);
                    }
                } else {
                    $markers['_item'] = $start;
                    $replacements[] = str_replace(array_keys($markers), array_values($markers), $replace);
                    $markers['_item'] = $end;
                    $replacements[] = str_replace(array_keys($markers), array_values($markers), $replace);
                }
                $text = preg_replace('#' . preg_quote($matches[0], '#') . '#', join($separator, $replacements), $text);
            }
        }

        return $text;
    }

    /**
     * Multi-Byte safe str_replace
     * 
     * from http://www.php.net/manual/de/ref.mbstring.php#86120
     * 
     * @param string $haystack
     * @param string $needle
     * @param string $replacement
     * @param int $limit
     * @return string
     */
    public static function replace($haystack, $needle, $replacement = '', $limit = null) {
        if (\mb_strlen($needle) === 0) {
            return $haystack;
        }

        if (is_null($limit)) {
            $limit = PHP_INT_MAX;
        }

        $needle_len = mb_strlen($needle);
        $replacement_len = mb_strlen($replacement);

        $pos = mb_strpos($haystack, $needle);
        while ($pos !== false && $limit > 0) {
            $haystack = mb_substr($haystack, 0, $pos) .
                    $replacement .
                    mb_substr($haystack, $pos + $needle_len);
            $pos = mb_strpos($haystack, $needle, $pos + $replacement_len);
            $limit--;
        }
        return $haystack;
    }

    /**
     * Fixes a particular bad enconding I've seen some times (UTF-8 encoded XML Entities in ISO-8859-1 or something like that)
     * 
     * @return string
     */
    public static function fixPseudoWindows1252Enconding($string) {
        $badEncoding = self::getBadEncodingTable();
        return \str_replace(\array_keys($badEncoding), \array_values($badEncoding), $string);
    }

    public static function getBadEncodingTable() {
        if (!self::$badEncoding) {
            self::$badEncoding = array(
                chr(0xc2) . chr(0x80) => chr(0xE2) . chr(0x82) . chr(0xAC), // €
                chr(0xc2) . chr(0x82) => chr(0xE2) . chr(0x80) . chr(0x9a), // ‚
                chr(0xc2) . chr(0x83) => chr(0xC6) . chr(0x92), // ƒ
                chr(0xc2) . chr(0x84) => chr(0xE2) . chr(0x80) . chr(0x9E), // „
                chr(0xc2) . chr(0x85) => chr(0xE2) . chr(0x80) . chr(0xA6), // …
                chr(0xc2) . chr(0x86) => chr(0xE2) . chr(0x80) . chr(0xA0), // †
                chr(0xc2) . chr(0x87) => chr(0xE2) . chr(0x80) . chr(0xA1), // ‡
                chr(0xc2) . chr(0x88) => chr(0xCB) . chr(0x86), // ˆ
                chr(0xc2) . chr(0x89) => chr(0xE2) . chr(0x80) . chr(0xB0), // ‰
                chr(0xc2) . chr(0x8A) => chr(0xC5) . chr(0xA0), // Š
                chr(0xc2) . chr(0x8B) => chr(0xE2) . chr(0x80) . chr(0xB9), // ‹
                chr(0xc2) . chr(0x8C) => chr(0xC5) . chr(0x92), // Œ
                chr(0xc2) . chr(0x8E) => chr(0xC5) . chr(0xBD), // Ž
                chr(0xc2) . chr(0x91) => chr(0xE2) . chr(0x80) . chr(0x98), // ‘
                chr(0xc2) . chr(0x92) => chr(0xE2) . chr(0x80) . chr(0x99), // ’
                chr(0xc2) . chr(0x93) => chr(0xE2) . chr(0x80) . chr(0x9C), // “
                chr(0xc2) . chr(0x94) => chr(0xE2) . chr(0x80) . chr(0x9D), // ”
                chr(0xc2) . chr(0x95) => chr(0xE2) . chr(0x80) . chr(0xA2), // •
                chr(0xc2) . chr(0x96) => chr(0xE2) . chr(0x80) . chr(0x93), // –
                chr(0xc2) . chr(0x97) => chr(0xE2) . chr(0x80) . chr(0x94), // —
                chr(0xc2) . chr(0x98) => chr(0xCB) . chr(0x9c), // ˜
                chr(0xc2) . chr(0x99) => chr(0xE2) . chr(0x84) . chr(0xA2), // ™
                chr(0xc2) . chr(0x9A) => chr(0xC5) . chr(0xA1), // š
                chr(0xc2) . chr(0x9B) => chr(0xE2) . chr(0x80) . chr(0xBA), // ›
                chr(0xc2) . chr(0x9C) => chr(0xC5) . chr(0x93), // œ
                chr(0xc2) . chr(0x9E) => chr(0xC5) . chr(0xBE), // ž
                chr(0xc2) . chr(0x9F) => chr(0xC5) . chr(0xB8), // Ÿ
                #chr(0xc2) . chr(0xA0) => chr(0xA0)
            );
        }
        return self::$badEncoding;
    }

    /**
     * hexdump like formating of a string
     *
     * http://stackoverflow.com/questions/1057572/how-can-i-get-a-hex-dump-of-a-string-in-php
     * 
     * @param string $string
     * @return string
     */
    public static function hexdump($data) {
        static $from = '';
        static $to = '';

        $width = 16; # number of bytes per line
        $pad = '.'; # padding for non-visible characters
        $result= ''; # padding for non-visible characters

        if ($from === '') {
            for ($i = 0; $i <= 0xFF; $i++) {
                $from .= chr($i);
                $to .= ( $i >= 0x20 && $i <= 0x7E) ? chr($i) : $pad;
            }
        }

        $hex = str_split(bin2hex($data), $width * 2);
        $chars = str_split(strtr($data, $from, $to), $width);

        $offset = 0;
        foreach ($hex as $i => $line) {
            $result .= sprintf('%6X', $offset) . ' : ' . implode(' ', str_split($line, 2)) . ' [' . $chars[$i] . ']' . PHP_EOL;
            $offset += $width;
        }
        return $result;
    }
    
    
    /**
     * Pretty prints xml
     * 
     * @see http://gdatatips.blogspot.com/2008/11/xml-php-pretty-printer.html
     * @author Eric (Google) http://www.blogger.com/profile/13033421744122011068
     * @license Apache 2.0 License
     * 
     * @param string $xml
     * @return string  
     */
    public static function prettyPrintXml($xml) {
        $xml_obj = new \SimpleXMLElement($xml);  
        $level = 4;  
        $indent = 0; // current indentation level  
        $pretty = array();  
          
        // get an array containing each XML element  
        $xml = explode("\n", preg_replace('/>\s*</', ">\n<", $xml_obj->asXML()));  
      
        // shift off opening XML tag if present  
        if (count($xml) && preg_match('/^<\?\s*xml/', $xml[0])) {  
          $pretty[] = array_shift($xml);  
        }  
      
        foreach ($xml as $el) {  
          if (preg_match('/^<([\w])+[^>\/]*>$/U', $el)) {  
              // opening tag, increase indent  
              $pretty[] = str_repeat(' ', $indent) . $el;  
              $indent += $level;  
          } else {  
            if (preg_match('/^<\/.+>$/', $el)) {              
              $indent -= $level;  // closing tag, decrease indent  
            }  
            if ($indent < 0) {  
              $indent += $level;  
            }  
            $pretty[] = str_repeat(' ', $indent) . $el;  
          }  
        }     
        $xml = implode("\n", $pretty);     
        return $xml;  
    }

}

?>
