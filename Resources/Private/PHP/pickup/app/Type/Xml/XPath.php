<?php


namespace Type\Xml;



use \Type;
use \Type\String;
use \Type\Url;
use \Type\Xml;

/**
 * A convinience wrapper around \DOMXPath
 * Provides a construction time Syntax-Check
 * Automatically registers the php namespace
 * Already bound to an element
 * @TODO: methods for useful return types
 *
 * @author Johannes Künsebeck <kuensebeck@googlemail.com>
 */
class XPath extends \Type\Base implements \IteratorAggregate {
    /**
     *
     * @var \Type\Xml
     */
    protected $element;

    /**
     *
     * @var string
     */
    protected $xpath;

    /**
     * Namespace-Prefix for php functions
     * @var string
     */
    protected $phpPrefix = 'php';


    /**
     *
     * @param \Type\Xml $element         the element the XPath is applied to
     * @param string        $xpath          the XPath
     * @param boolean        $recursiveNamespaces  get namespace declarartions from whole document
     * @param string        $phpPrefix      [optional, defauls to 'php']
     */
    public function __construct(\Type\Xml $element, $xpath = '', $recursiveNamespaces = false, $phpPrefix = 'php') {        
        $this->element= $element;
        $this->phpPrefix = $phpPrefix;        
        $this->setXpath($xpath, $recursiveNamespaces);
        
    }

    /**
     *
     * @return \DOMNodeList
     */
    protected function _query() {        
        return $this->_getDomXpath()->query($this->xpath, $this->element->getNativeValue());
    }

    /**
     * Builds a XPath by a CSS selector
     * @param string $selector CSS Selector
     * @return Xml\XPath
     */
    public function css($selector,$join = '//') {
        $xpath = Xml\CssSelectorParser::cssToXpath($selector,$join, $this->element->getDefaultNamespacePrefix());
        return new Xml\XPath($this->element,  $this->xpath.$xpath,$this->phpPrefix);
    }

    /**
     * Builds a XPath by a CSS selector
     * @param string $selector CSS Selector
     * @return Xml\XPath
     */
    public function xpath($xpath) {
		$xpathPrefix = $this->xpath;
		$xpath = implode('|', array_map(function($xpathPart) use($xpathPrefix) {
			return $xpathPrefix.$xpathPart;			
		},explode('|', $xpath)));
        return new Xml\XPath($this->element, $xpath,$this->phpPrefix);
    }
	
 
    /**
     * Builds a XPath by a CSS selector
     * @param string $selector CSS Selector
     * @return Xml\XPath
     */
    public function contains($string) {
        return $this->xpath('[contains(.,"'.$string.'")]');
    }


    /**
     *
     * @return \Type\Xml\Collection
     */
    public function asXml() {
        $xmlList = new Collection();
        $list = $this->_query();
        $listLength = $list->length;
        for ($t=0; $t < $listLength; $t++) {
            $xmlList->add(new Xml($list->item($t)));
        }
        return $xmlList;
    }    

    /**
     * @return \Type\String\Collection
     */
    public function asString() {
        $stringList = new String\Collection();
        $list = $this->_query();
        $listLength = $list->length;
        for ($t=0; $t < $listLength; $t++) {
            $stringList->add(new String($list->item($t)->textContent));
        }
        return $stringList;
    }

    /**
     * @return \Type\Url\Collection
     */
    public function asUrl($baseUrl = null) {
        $urlList = new Url\Collection();
        $list = $this->_query();
        $listLength = $list->length;
        if (\is_null($baseUrl)) {
            $baseUrl = $this->element->getBaseUri();
        }
        for ($t=0; $t < $listLength; $t++) {
            $node = $list->item($t);
            $relativeUrl = $node->textContent;
            /* @var $node \DOMNode */
            if ($node instanceof \DOMAttr) {
                $relativeUrl = $node->value;
            } else if ($node instanceof \DOMElement) {
                $a = $node->attributes;
                $relativeUrl = pick(
                    ($n = $a->getNamedItem('href')) ? $n->value : null,
                    ($n = $a->getNamedItem('src')) ? $n->value : null,
                    ($n = $a->getNamedItem('action')) ? $n->value : null,
                    $relativeUrl
               );
            }
            $url = new Url($baseUrl, $relativeUrl);
            $urlList->add($url);
        }
        return $urlList;
    }


    /**
     * @return string
     */
    public function __toString() {
        return (string)$this->toString();
    }

    /**
     * @return string
     */
    public function toString() {
        return $this->asString()->join();
    }

    
    /**
     * 
     * @param boolean $recursiveNamespaces
     * @return \DOMXPath 
     */
    protected function _getDomXpath($recursiveNamespaces = false) {
        $xpath = new \DOMXPath($this->element->getDomDocument());
        /** @TODO: getDocumentNamespaces(false) should be true, but there are segfaults */
        foreach ($this->element->getDocumentNamespaces($recursiveNamespaces) as $prefix => $namespaceURI) {
            $xpath->registerNamespace($prefix, $namespaceURI);
        }
        if (method_exists($xpath,'registerPhpFunctions')) {
                $xpath->registerNamespace($this->phpPrefix, "http://php.net/xpath");
                $xpath->registerPhpFunctions();
        }
        return $xpath;
    }

    /**
     *
     * 
     * @param string $xpath
     * @param boolean $recursiveNamespaces 
     */
    protected function setXpath($xpath, $recursiveNamespaces = false) {
        $this->xpath = $xpath;
        set_error_handler(array($this, '_handleError'));
        $result = $this->_getDomXpath($recursiveNamespaces)->evaluate('/wrtlfsdfls-does-not-exits'.$xpath, $this->element->getDomDocument());
        restore_error_handler();
    }

    /**
     * XPath-Errors to Exception
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param string $errline
     *
     * @throws \InvalidArgumentException
     */
    public function _handleError($errno, $errstr, $errfile, $errline) {
        $xpath = $this->xpath;
        $this->xpath = '';
        restore_error_handler();
        throw new \InvalidArgumentException($errstr . '  in XPath "' . $xpath . '"', $errno);
    }

    /**
     *
     * @return Collection
     */
    public function getIterator() {
        return $this->asXml();
    }


    public function getNativeValue() {
        return $this->_query();
    }
}
?>