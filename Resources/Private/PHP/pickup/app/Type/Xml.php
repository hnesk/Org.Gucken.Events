<?php

namespace Type;

/**
 * A Xml class as a ultra convinient wrapper around a DomDocument
 *
 * @author jk
 */
class Xml extends \Type\Base implements \ArrayAccess {

    /**
     *
     * @var \DOMElement
     */
    protected $element;

    /**
     *
     * @var string
     */
    protected $defaultNamespacePrefix = 'default';

    /**
     *
     * @param \DOMElement $dom
     */
    public function __construct(\DOMNode $dom, $defaultNamespacePrefix = 'default', $baseUrl = null) {
        $this->element = $dom;
        if ($baseUrl) {
            $this->getDomDocument()->documentURI = (string) $baseUrl;
        }
        $this->defaultNamespacePrefix = $defaultNamespacePrefix;
    }

    /**
     * Builds a XPath
     *
     * @autocomplete
     * @param string $xpath
     * @param boolean $recursiveNamespaces
     * @return Xml\XPath
     */
    public function xpath($xpath, $recursiveNamespaces = false) {
        return new \Type\Xml\XPath($this, $xpath, $recursiveNamespaces);
    }

    /**
     *
     * @param string $text
     * @return boolean
     */
    public function containsText($text) {
        return strpos($this->element->textContent, $text) !== false;
    }

    /**
     * Builds a XPath by a CSS selector
     *
     * @autocomplete
     * @param string $selector CSS Selector
     * @param boolean $recursiveNamespaces
     * @return Xml\XPath
     */
    public function css($selector, $recursiveNamespaces = false) {
        try {
            $xpath = Xml\CssSelectorParser::cssToXpath($selector, './/', $this->getDefaultNamespacePrefix());
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Unable to parse "' . $selector . '"', 1305834972, $e);
        }
        return $this->xpath($xpath, $recursiveNamespaces);
    }

    /**
     *
     * @param string $id
     * @return Xml
     */
    public function id($id) {
        return $this->css('#' . trim($id))->asXml()->first();
    }

    /**
     *
     * @param string $name
     * @return Xml
     */
    public function fragment($name) {
        return $this->xpath('.//*[@name="' . trim($name) . '"]')->asXml()->first();
    }

    /**
     *
     * @return array<Namespace-Prefix => Namespace>
     */
    public function getDocumentNamespaces($recursive = false) {
        $sxml = \simplexml_import_dom($this->getDomDocument());
        $namespaces = array();
        foreach ($sxml->getDocNamespaces($recursive) as $prefix => $namespaceURI) {
            $namespaces[$prefix ? $prefix : $this->defaultNamespacePrefix] = $namespaceURI;
        }
        return $namespaces;
    }

    /**
     *
     * @return string Default Namespace Prefix
     */
    public function getDefaultNamespacePrefix() {
        $namespaces = $this->getDocumentNamespaces();
        return isset($namespaces[$this->defaultNamespacePrefix]) ? $this->defaultNamespacePrefix : '*';
    }

    /**
     *
     * @return string Default Namespace
     */
    public function getDefaultNamespace() {
        $namespaces = $this->getDocumentNamespaces();
        return isset($namespaces[$this->defaultNamespacePrefix]) ? $namespaces[$this->defaultNamespacePrefix] : '';
    }

    /**
     *
     * @return Xml
     */
    public function semantify() {
        $stylesheet = Xml\Factory::fromXmlFile(\BASE_PATH . 'transformations/semantic.xsl');
        return $this->transform($stylesheet);
    }

    /**
     *
     * @return String
     */
    public function markdown($parameters = array()) {
        $stylesheet = Xml\Factory::fromXmlFile(\BASE_PATH . 'transformations/markdown.xsl');
        return $this->transformToString($stylesheet, $parameters);
    }

    /**
     *
     * @return String
     */
    public function formattedText($parameters = array()) {
        $stylesheet = Xml\Factory::fromXmlFile(\BASE_PATH . 'transformations/text.xsl');
        return $this->transformToString($stylesheet, $parameters);
    }


    /**
     *
     * @param Xml $stylesheet
     * @param array<string> $parameters
     * @return String
     */
    public function transformToString($stylesheet, $parameters = array()) {
        $xsl = $this->prepareXSLTProcessor($stylesheet, $parameters);
        return new String($xsl->transformToXml($this->detach()->getDomDocument()));
    }

    /**
     *
     * @param Xml $stylesheet
     * @param array<string> $parameters
     * @return Xml
     */
    public function transform(Xml $stylesheet, $parameters = array()) {
        $xsl = $this->prepareXSLTProcessor($stylesheet, $parameters);
        return new Xml(
                        $xsl->transformToDoc($this->detach()->getDomDocument()),
                        $this->getDefaultNamespacePrefix(),
                        $this->getBaseUri()
        );
    }

    /**
     *
     * @param Xml $stylesheet
     * @param array<string> $parameters
     * @return \XSLTProcessor
     */
    protected function prepareXSLTProcessor(Xml $stylesheet, $parameters = array()) {
        $xsl = new \XSLTProcessor();
        $xsl->importStylesheet($stylesheet->getDomDocument());

        foreach ($parameters as $parameterName => $parameterValue) {
            $xsl->setParameter('', $parameterName, $parameterValue);
        }
        $xsl->setParameter('', 'baseUri', $this->getBaseUri());
        return $xsl;
    }

    /**
     * detaches this element from document and returns a document only consisting of this element
     * @return Scrape_Xml
     */
    public function detach() {
        return Xml\Factory::fromXmlString($this->asXmlString(), $this->getDefaultNamespacePrefix(), $this->getBaseUri());
    }

    /**
     * @autocomplete
     * @return Url
     */
    public function getBaseUri() {
        return new Url($this->getDomDocument()->documentURI);
    }

    /**
     * @return \DOMDocument
     */
    public function getDomDocument() {
        if ($this->element instanceof \DOMDocument) {
            return $this->element;
        } else {
            return $this->element->ownerDocument;
        }
    }

	/**
	 *
	 * @return \Type\Xml
	 */
	public function getDocument() {
		return new Xml($this->getDomDocument(), $this->defaultNamespacePrefix, $this->getBaseUri());
	}

    /**
     *
     * @return \DOMElement
     */
    public function getNativeValue() {
        return $this->element;
    }

    public function __toString() {
        return $this->element->textContent;
    }

    /**
     * decapsulate a <[CDATA[]> encapsulated piece of html
     *
     * @return Xml
     */
    public function decapsulateCdataAsHtml() {
        return $this->text()->asHtml()->css('div')->asXml()->first();
    }

    /**
     * decapsulate a <[CDATA[]> encapsulated piece of xml
     *
     * @return Xml
     */
    public function decapsulateCdataAsXml() {
        return $this->text()->asXml()->css('div')->asXml()->first();
    }

    /**
     * Returns this element as an XML string
     *
     * @param array $options
     * @return string
     */
    public function asXmlString($options = null) {
        return $this->getDomDocument()->saveXML($this->element, $options);
    }

    /**
     * Returns this element as an XML string
     *
     * @param array $options
     * @return string
     */
    public function asPrettyXmlString($options = null) {
        return \Util\String::prettyPrintXml($this->asXmlString($options));
    }

	public function debug($return = false) {
		$output = '<pre>'.htmlspecialchars($this->asPrettyXmlString()).'</pre>';
		if ($return) {
			return $output;
		} else {
			echo $output;
		}
	}

    /**
     * Returns this element as a XML-String Object

     * @autocomplete
     * @param int $options
     * @return \Type\String
     */
    public function asString($options = null) {
        return new String($this->asXmlString($options));
    }

    /**
     * @autocomplete
     * @param int $options
     * @return String
     */
    public function text() {
        return new String((string) $this);
    }

    public function getAttribute($key) {
        return $this->element->getAttribute($key);
    }

    public function hasAttribute($key) {
        return $this->element->hasAttribute($key);
    }

    public function setAttribute($key, $value) {
        $this->element->setAttribute($key, $value);
        return $this;
        #throw new \Exception('Xml is readonly');
    }

    public function removeAttribute($key) {
        $this->element->removeAttribute($key);
        return $this;
        #throw new \Exception('Xml is readonly');
    }

    public function offsetGet($key) {
        return $this->getAttribute($key);
    }

    public function offsetExists($key) {
        return $this->hasAttribute($key);
    }

    public function offsetSet($key, $value) {
        return $this->setAttribute($key, $value);
    }

    public function offsetUnset($key) {
        return $this->removeAttribute($key);
    }

    public static function cast($dom) {
        try {
            return new Xml($dom);
        } catch (\ErrorException $e) {
            throw new \InvalidArgumentException('Could not convert ' . (\is_object($dom) ? get_class($dom) : \gettype($dom)) . ' to as String', NULL, $e);
        }
    }

}

?>
