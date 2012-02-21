<?php
namespace Type\Xml;

/**
 * Description of Factory
 *
 * @author jk
 */
class Factory {
    /**
     * @see http://www.w3.org/TR/xml11/#charsets 
     * Character Range 
     * [2a]RestrictedChar
     * 
     * @var array
     */
    static $restrictedXmlChararcters = array(
        // [#x1-#x8]
        0x01 => true,
        0x02 => true,
        0x03 => true,
        0x04 => true,
        0x05 => true,
        0x06 => true,
        0x07 => true,
        0x08 => true,
        // [#xB-#xC]
        0x0B => true,
        0x0C => true,
        // [#xE-#x1F]
        0x0E => true,
        0x0F => true,
        0x10 => true,
        0x11 => true,
        0x12 => true,
        0x13 => true,
        0x14 => true,
        0x15 => true,
        0x16 => true,
        0x17 => true,
        0x18 => true,
        0x19 => true,
        0x1A => true,
        0x1B => true,
        0x1C => true,
        0x1D => true,
        0x1E => true,
        0x1F => true,
        // [#x7F-#x84]
        0x7F => true,
        0x80 => true,
        0x81 => true,
        0x82 => true,
        0x83 => true,
        0x84 => true,
        // [#x86-#x9F]
        0x86 => true,
        0x87 => true,
        0x88 => true,
        0x89 => true,
        0x8A => true,
        0x8B => true,
        0x8C => true,
        0x8D => true,
        0x8E => true,
        0x8F => true,
        0x90 => true,
        0x91 => true,
        0x92 => true,
        0x93 => true,
        0x94 => true,
        0x95 => true,
        0x96 => true,
        0x97 => true,
        0x98 => true,
        0x99 => true,
        0x9A => true,
        0x9B => true,
        0x9C => true,
        0x9D => true,
        0x9E => true,
        0x9F => true        
    );
    
    
    /**
     * @param string $content html string
     * @param string $defaultNamespacePrefix
     * @param string $baseUrl
     * @return \Type\Xml
     */
    public static function fromHtmlString($content, $defaultNamespacePrefix='default', $baseUrl=null, $options = null) {
        $content = self::prepareContent($content, $options);
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = true;
        $dom->strictErrorChecking = false;
        $dom->substituteEntities = true;
        \libxml_use_internal_errors(true);
        $dom->loadHTML($content);
        $errors = \libxml_get_errors();
        \libxml_clear_errors();
        \libxml_use_internal_errors(false);
        
        if (count($errors) > 0) {
            #throw new \Type\Xml\WellformednessException('HTML contained errors',1312397305,$errors,$content);
        }
        #$dom->normalizeDocument();

        return new \Type\Xml($dom, $defaultNamespacePrefix, $baseUrl);
    }

    /**
     *
     * @param \Type\Json $json
     * @return \Type\Xml 
     */
    public static function fromTypeJson(\Type\Json $json) {
        $dom =  new \DOMDocument('1.0', 'utf-8');
        self::buildDomFromArray($dom, $json);
        return new \Type\Xml($dom);
    }


    protected static function buildDomFromArray(\DOMElement $dom, $value) {
        $document = $dom->getDomDocument();
        /* @var $document \DOMDocument */
        $value = \is_object($value) ? (array)$value : $value;
        $newElement = null;
        if (\is_array($value) || $value instanceof \Traversable) {            
            foreach ($value as $k=>$v) {
                $tagName = self::isValidTagName($k) ? $k : 'item';
                $newElement = $document->createElement($tagName);
                $newElement->setAttribute('index', $k);
                self::buildDomFromArray($newElement, $v);                
            }
        } else if (\is_scalar($value)) {
            $newElement = $document->createTextNode($value);
        }
        if ($newElement) {
            $dom->appendChild($newElement);
        }
    }

    protected static function isValidTagName($name)  {
        if (strpos($name,'xml') === 0) {
            return false;
        }
        try {
            new DOMElement($name);
        } catch (DOMException $e) {
            return false;
        }
        return true;
    }


    /**
     * @param string $content xml string
     * @param string $defaultNamespacePrefix
     * @param string $baseUrl
     * @return \Type\Xml
     */
    public static function fromXmlString($content, $defaultNamespacePrefix='default', $baseUrl=null, $options = array()) {
        $content = self::prepareContent($content, $options);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->strictErrorChecking = false;
        $dom->substituteEntities = true;
        $dom->preserveWhiteSpace = true;
        \libxml_use_internal_errors(true);
        $dom->loadXML($content);
        $errors = \libxml_get_errors();
        \libxml_clear_errors();
        \libxml_use_internal_errors(false);
        if (count($errors) > 0) {
            throw new \Type\Xml\WellformednessException('XML contained errors',1312397305,$errors,$content);
        }
        #$dom->normalizeDocument();
        return new \Type\Xml($dom, $defaultNamespacePrefix, $baseUrl);
    }

    /**
     *
     * @param string $content
     * @param Options|string $options
     * @return string
     */
    protected static function prepareContent($content, $options=array()) {
        $options = \Util\Options::factory($options);

        if (isset($options['clean']['htmlEntityDecode'])) {            
            $chars = array();
            foreach (get_html_translation_table(HTML_ENTITIES, ENT_NOQUOTES, 'UTF-8') as $char => $entity) {
                $char = iconv('UTF-8','UTF-16LE',$char);
                $entityCode = 0;
                for ($t=0; $t<strlen($char); $t++) {                    
                    $entityCode += ord($char[$t]) * (1 << ($t*8));
                }                
                $chars[$entity] = '&#'.$entityCode.';';
            }
            foreach (self::$restrictedXmlChararcters as $code => $dummy) {
                $chars[sprintf('&#%02d;',$code)] = ' ';
            }
            
            $content = str_replace(array_keys($chars), array_values($chars), $content);
        }
        
        $outCharset = 'UTF-8';
        if (isset($options['iconv'])) {
            foreach ($options['iconv'] as $inCharset => $outCharset) {
                $content = \iconv($inCharset, $outCharset.'//TRANSLIT', $content);
            }
        }
        
        if (isset($options['clean']['nonXmlChars'])) {
            foreach (self::$restrictedXmlChararcters as $code => $dummy) {
                $chars[chr($code)] = (string)$options['clean']['nonXmlChars'];
            }
            
            $content = str_replace(array_keys($chars), array_values($chars), $content);
        }
        
        /*
        if (isset($options['replace'])) {
            foreach ($options['replace'] as $char => $replacement) {
                if (substr($char,0,1) === '_') {
                    $char = chr(substr($char,1));
                }
                $content = str_replace($char, $replacement, $content);
            }
        } 
        
         */       

        
        
        if (isset($options['clean']['singleAmpersand'])) {
            $content = \preg_replace(
                    '/&(?!([a-zA-Z][a-zA-Z0-9]*|(#\d+));)/',
                    '&amp;',
                    $content
            );
        }        
        
        
        return $content;
    }


    /**
     *
     * @deprecated for self::fromXmlString
     * @param string $content xml string
     * @param string $defaultNamespacePrefix
     * @param string $baseUrl
     * @return \Type\Xml
     */
    public static function fromXml($content, $defaultNamespacePrefix='default', $baseUrl=null, $options = array()) {
        return self::fromXmlString($content, $defaultNamespacePrefix, $baseUrl, $options);
    }


    public static function fromXmlFile($content, $defaultNamespacePrefix='default', $baseUrl=null) {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->load($content);
        return new \Type\Xml($dom, $defaultNamespacePrefix, $baseUrl);
    }

    public static function fromHtmlFile($content, $defaultNamespacePrefix='default', $baseUrl=null) {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTMLFile($content);
        return new \Type\Xml($dom, $defaultNamespacePrefix, $baseUrl);
    }

    /**
     * @param \SimpleXMLElement $simpleXml
     * @param string $defaultNamespacePrefix
     * @param string $baseUrl
     * @return \Type\Xml
     */
    public static function fromSimpleXml($simpleXml, $defaultNamespacePrefix='default', $baseUrl=null) {
        return new \Type\Xml(\dom_import_simplexml($simpleXml), $defaultNamespacePrefix, $baseUrl);
    }

    /**
     * @param \DOMNode $simpleXml
     * @param string $defaultNamespacePrefix
     * @param string $baseUrl
     * @return \Type\Xml
     */
    public static function fromDom($dom, $defaultNamespacePrefix='default', $baseUrl=null) {
        return new \Type\Xml($dom, $defaultNamespacePrefix, $baseUrl);
    }
}
?>
