<?php

namespace Type;

/**
 * A String Class working with utf-8 characters
 *
 * @author jk
 */
class String extends \Type\Base {

    public function __construct($string = '', $normalizeLinefeed = true) {
        $this->value = $normalizeLinefeed ? self::normalizeLinefeed((string) $string) : (string) $string;
    }
    
    /**
     * Normalizes the line feed to *nix
     */
    protected static function normalizeLinefeed($value) {
        // shortcut when everythings ok
        if (strpos($value , "\r") === false) {
            return $value;
        }
        $lineFeeds = array(
            "\r\n", // Microsoft Windows, DEC TOPS-10, RT-11 and most other early non-Unix and non-IBM OSes, CP/M, MP/M, DOS (MS-DOS, PC-DOS, etc.), Atari TOS, OS/2, Symbian OS, Palm OS
            "\n\r", // Acorn BBC and RISC OS spooled text output.
            "\r",   // Commodore 8-bit machines, Acorn BBC, TRS-80, Apple II family, Mac OS up to version 9 and OS-9
            #"\n",   // Multics, Unix and Unix-like systems (GNU/Linux, AIX, Xenix, Mac OS X, FreeBSD, etc.), BeOS, Amiga, RISC OS, and others.
        );
        $lineFeed = "\n";
        foreach ($lineFeeds as $lineFeed) {
            if (strpos($value, $lineFeed) !== false) {
                return str_replace($lineFeed, "\n", $value);
            }
        }
        throw new \LogicException('No linefeed found, This can not happen', 1320279851);
    }

    /**
     *
     * @param int $start
     * @param int $length
     * @return String
     */
    public function substring($start, $length=null) {
        $value = \mb_substr($this->value, $start, !\is_null($length) ? $length : \PHP_INT_MAX, 'utf-8');
        return new String($value);
    }

    /**
     *
     * @param string $charlist
     * @return String
     */
    public function trim($charlist = null) {
        $value = $charlist ? \trim($this->value, (string) $charlist) : \trim($this->value);
        return new String($value);
    }

    /**
     * Multi-Byte safe str_replace
     * 
     * @param string $from
     * @param string $to
     * @param int|null $limit
     * @return String
     */
    public function replace($from, $to, $limit = null) {
        $limit = \is_null($limit) ? PHP_INT_MAX : $limit;
        return new String(\Util\String::replace($this->value, (string) $from, (string) $to, $limit));
    }

    /**
     * Removes a string (if existing)
     *
     * @param string $from
     * @param int|null $limit
     * @return String
     */
    public function remove($from, $limit = null) {
        return $this->replace($from, '', $limit);
    }

    /**
     * Implements normalize-space like in xpath
     * @see http://www.w3.org/TR/xpath/#function-normalize-space
     * @return \Type\String
     */
    public function normalizeSpace() {
        return new String(\trim(\preg_replace('#[\s\p{Zs}]+#u', ' ', $this->value)));
    }
    
    /**
     * Implements normalize-space like in xpath
     * @see http://www.w3.org/TR/xpath/#function-normalize-space
     * @return String
     */
    public function normalizeSpaceKeepBreaks() {
        return new String(trim(preg_replace('#[\n\r][\s\p{Zs}]+#',PHP_EOL,preg_replace('#[\xA0\x20\t]+#', ' ', $this->value))));
    }    

    /**
     * The idea of normalizeSpace taken to paragraph level 
     * @see http://www.w3.org/TR/xpath/#function-normalize-space
     * @return String
     */
    public function normalizeParagraphs() {
        return $this->explode("\n")->map(function(String $string) {
            return $string->normalizeSpace();                  
        })->join("\n")->removeExcessiveEmptyLines();
    }

    /**
     *
     * @return String 
     */
    public function hexdump() {
        return new String(\Util\String::hexdump($this->value));
    }
    
    /**
     * replaces 3 or more linefeeds with 2 linefeeds
     * @return String
     */
    public function removeExcessiveEmptyLines() {
        return $this->pregReplace("#\n{2,}#", "\n\n")->trim();
    }
    
    /**
     *
     * @param string $string
     * @return boolean
     */
    public function startsWith($string) {
        return $this->indexOf($string) === 0;
    }

    /**
     *
     * @param string $string
     * @return boolean
     */
    public function contains($string) {
        return $this->indexOf($string) !== false;
    }

    /**
     *
     * @param string $string
     * @return boolean
     */
    public function endsWith($string) {
        $string = self::createString($string);
        return $this->indexOf($string) === $this->length() - $string->length();
    }

    /**
     *
     * @return \Type\String
     */
    public function toUpper() {
        return new String(\mb_strtoupper($this->value));
    }

    /**
     *
     * @return \Type\String
     */
    public function toLower() {
        return new String(\mb_strtolower($this->value, 'UTF-8'));
    }

    /**
     *
     * @param string $separator
     * @return \Type\String
     */
    public function toCamelCase($separator = '_') {
        return new String(\Util\String::separatedToCamelCase($this->value, $separator));
    }

    /**
     *
     * @param string $separator
     * @return String
     */
    public function toSeparated($separator = '_') {
        return new String(\Util\String::camelCaseToSeparated($this->value, $separator));
    }

    /**
     *
     * @return String
     */
    public function toNamespace() {
        return new String(\Util\String::camelCaseToNamespace($this->value));
    }

    /**
     * 
     * @param String $needle
     * @param int $offset optional
     * @return int
     */
    public function indexOf($needle, $offset=0) {
        return \mb_strpos($this->value, (string) $needle, $offset, 'utf-8');
    }

    /**
     *
     * @return int
     */
    public function length() {
        return \mb_strlen($this->value, 'utf-8');
    }

    /**
     *
     * @return int
     */
    public function byteLength() {
        return \strlen($this->value);
    }

    /**
     * Implements substring-before like in xpath
     * @see http://www.w3.org/TR/xpath/#function-substring-before
     * @param String $delimiter
     * @param boolean $keepThisNotFound should the original string be returned if there is no match for the delimiter
     * @return String
     */
    public function substringBefore($delimiter, $keepThisIfNotFound = false) {
        $delimiter = self::createString($delimiter);
        if (($index = $this->indexOf($delimiter)) !== false) {
            return $this->substring(0, (int) $this->indexOf($delimiter));
        } else {
            return $keepThisIfNotFound ? $this : new String();
        }

        return new String($this->substring(0, (int) $this->indexOf($delimiter)));
    }

    /**
     * Implements substring-after like in xpath
     * @see http://www.w3.org/TR/xpath/#function-substring-before
     * @param String $delimiter
     * @param boolean $keepThisNotFound should the original string be returned if there is no match for the delimiter
     * @return \Type\String
     */
    public function substringAfter($delimiter, $keepThisIfNotFound = false) {
        $delimiter = self::createString($delimiter);
        if (($index = $this->indexOf($delimiter)) !== false) {
            return $this->substring($index + (int) $delimiter->length());
        } else {
            return $keepThisIfNotFound ? $this : new String();
        }
    }

    /**
     * Implements delimiter separated fields like the *nix tool "cut"
     * 
     * @param string $delimiter the delimiter used for splitting and merging
     * @param int $offset
     * @param int $length
     * @return String
     */
    public function cut($delimiter=',', $offset=0, $length = null) {
        $parts = \explode($delimiter, $this->value);
        return new String(\implode($delimiter, array_slice($parts, $offset, $length)));
    }

    /**
     * wrapper for php's explode 
     *
     * @param string $delimiter
     * @param int|null $limit
     * @return \Type\String\Collection
     */
    public function explode($delimiter = ',', $limit = null) {
        return String\Collection\Factory::fromArray(explode($delimiter, $this->value, is_null($limit) ? \PHP_INT_MAX : $limit));
    }

    /**
     *
     * @param \Type\Url $url
     * @return \Type\Url
     */
    public function buildUrl(Data\Url $url) {
        return $url->sprintf($this->__toString());
    }

    /**
     *
     * @param string $string
     * @return String
     */
    public function append($string) {
        return new String($this->value . $string);
    }

    /**
     * Returns a human-readable and human-useful ascii representation (locale dependent "ä" => "ae")
     * 
     * @param string $transliterationLocale locale to use for transliteration 
     * @return String
     */
    public function ascii($transliterationLocale = 'de_DE.UTF-8') {
        return $this->iconv('UTF-8', 'ASCII//TRANSLIT', $transliterationLocale);
    }

    /**
     * Wrapper around php iconv
     * 
     * @param string $inCharset
     * @param string $outCharset
     * @param string $transliterationLocale locale to use if transliteration is needed
     * @return String
     */
    public function iconv($inCharset, $outCharset, $transliterationLocale ='de_DE.UTF-8') {
        $oldLocale = setlocale(LC_ALL, $transliterationLocale);
        $string = new String(iconv($inCharset, $outCharset, $this->value));
        setlocale(LC_ALL, $oldLocale);
        return $string;
    }

    /**
     * Returns the subpart of the string that is in $set
     * @param String|string|array $set Set of phrases to match (array or comma separated)
     * @return String
     */
    public function find($set) {
        $set = \array_map(
                function($e) {return \preg_quote($e, '#');},
                \Util\ArrayFunctions::toArray($set, ',')
        );
        $expression = '#('.join('|',$set).')#i';

        $match = '';
        if (\preg_match($expression, $this->value, $matches)) {
            $match = $matches[1];
        }
        return new String($match);
    }

    /**
     *
     * @param string $pattern
     * @param string $replacement
     * @return \Type\String 
     */
    public function pregReplace($pattern, $replacement) {
        return new String(preg_replace($pattern, $replacement, $this->value));
    }

    /**
     *
     * @param string $replacement
     * @return String
     */
    public function replaceNonTokenCharacters($replacement = ' ') {
        return $this->pregReplace('/[^a-z0-9]+/i', $replacement);
    }

    /**
     *
     * @param string $string
     * @return String
     */
    public function translate($from, $to) {
        return new String(\strtr($this->value, $from, $to));
    }

    /**
     *
     * @param string $joinChar
     * @return String
     */
    public function tokenize($joinChar = '-') {
        return $this->ascii()->toLower()->replaceNonTokenCharacters()->normalizeSpace()->translate(' ', $joinChar);
    }

    /**
     *
     * @param string $string
     * @return String
     */
    public function prepend($string) {
        return new String($string . $this->value);
    }



    public function fixPseudoWindows1252Encoding() {
        return new String(\Util\String::fixPseudoWindows1252Enconding($this->value));
    }

    public function asNumber() {
        return \Type\Number\Factory::fromTypeString($this);
        
    }
    /**
     *
     * @param string $format
     * @return Date
     */
    public function asDate($format = '%Y-%m-%dT%H:%M:%S', $defaults = null, $offset = 0, $midnightHour = 0, $rangeStart=PHP_INT_MAX, $rangeEnd=PHP_INT_MAX) {
        $parser = new Date\Parser((string) $format, $defaults, $offset, $midnightHour, $rangeStart, $rangeEnd);
        if ($parser->match($this->value)) {
            return new Date($parser->getDate());
        } else {
            return new String($this->value . ' does not match ' . $parser->getCompiledPattern());
            #throw new \Exception($this->value . ' does not match ' . $parser->getCompiledPattern());
            #return null;
        }
    }

    /**
     * Returns a string where each occurence of a range is replace by the expansion of the range
     *
     * @param string $expression an regular expression to match the range, the matching parts must be tagged as "(?<from>) and "(?<to>)"
     * @param string $replace replaces a single match expansion, you can use the markers _item and _1, _2, _n ... for the nth capture group (defaults to "_item")
     * @param string $separator separator between the expandend matches (defaults to ' ')
     * @param string $replacementStartMarker start marker for a match
     * @param string $replacementEndMarker end marker for a match
     * @return String
     */
    public function expandRanges($expression, $replace = '_item', $separator=' ') {
        return new String(\Util\String::expandRanges(
                        $this->value,
                        $expression,
                        $replace,
                        $separator
        ));
    }
    
    /**
     *
     * @return String\Collection
     */
    public function asKeywords() {
        return $this->toLower()->pregReplace('#[^[:alpha:]]+#ui', ' ')->normalizeSpace()->explode(' ')->unique();
    }

    /**
     *
     * @return \Type\String
     */
    public function entityDecode() {
        return $this->asHtml()->text();
    }

    /**
     *
     * @return \Type\Xml
     */
    public function asXml() {
        return \Type\Xml\Factory::fromXmlString('<div>' . $this->value . '</div>');
    }

    /**
     *
     * @return \Type\Xml
     */
    public function asHtml() {
        return \Type\Xml\Factory::fromHtmlString('<?xml version="1.0" encoding="utf-8"?><div>' . $this->value . '</div>');
    }

    /**
     * Returns a collection of substrings matching the given regular expression
     *
     * @param string $regularExpression
     * @param string $replace
     * @return String\Collection
     */
    public function eachMatch($regularExpression, $replace='$0') {
        $result = new String\Collection();
        if (preg_match_all($regularExpression, $this->value, $matchesCollection, PREG_SET_ORDER)) {
            foreach ($matchesCollection as $matches) {
                $result->addOne(new String(preg_replace($regularExpression, $replace, $matches[0])));
            }
        }
        return $result;
    }

    /**
     * @param String|string $string
     * @return String
     */
    public static function createString($string) {
        if ($string instanceof String) {
            return $string;
        } else {
            return new String($string);
        }
    }
	
	public function equals($string) {
		return $this->value == (string)$string;
	}
	
	public function is() {
		return $this->normalizeSpace()->length() > 0;
	}

    /**
     *
     * @param string $string
     * @return String
     */
    public static function cast($string) {
        try {
            return new String((string) $string);
        } catch (\ErrorException $e) {
            throw new \InvalidArgumentException('Could not convert ' . (\is_object($string) ? get_class($string) : \gettype($string)) . ' to as String', NULL, $e);
        }
    }

}

?>
