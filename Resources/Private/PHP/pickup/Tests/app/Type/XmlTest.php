<?php
namespace Tests\Type;
use Type\Xml;

/**
 * Description of StringTest
 *
 * @author jk
 */
class XmlTest extends \PHPUnit_Framework_TestCase {

    public function testCreationFromDomDocument() {
        $document = self::createDOMDocument('<root><node/></root>');
        $xml = new Xml($document);
        $this->assertSame($document, $xml->getNativeValue());
    }

    public function testCreationFromDomElement() {
        $document = self::createDOMDocument('<root><node/></root>');
        $element = $document->getElementsByTagName('node')->item(0);
        $xml = new Xml($element);
        $this->assertSame($element, $xml->getNativeValue());
    }

    public function testDomElementKeepsReferenceToDocument() {
        $document = self::createDOMDocument('<root><node/></root>');
        $element = $document->getElementsByTagName('node')->item(0);
        $xml = new Xml($element);
        $this->assertSame($document, $xml->getDomDocument());
    }

    public function testDomNodeTypeStaysTheSameForDomDocument() {
        $document = self::createDOMDocument('<root><node/></root>');
        $xml = new Xml($document);
        $this->assertInstanceOf('DOMDocument', $xml->getNativeValue());
    }

    public function testDomNodeTypeStaysTheSameForDomElement() {
        $document = self::createDOMDocument('<root><node/></root>');
        $xml = new Xml($document->getElementsByTagName('node')->item(0));
        $this->assertInstanceOf('DOMElement', $xml->getNativeValue());
    }

    public function testEmptyNamespaceArrayWhenThereAreNoNamespaces() {
        $document = self::createDOMDocument('<root><node/></root>');
        $xml = new Xml($document);
        $this->assertEquals(array(), $xml->getDocumentNamespaces());
    }

    public function testNamespaceArrayWithRootNamespaces() {
        $document = self::createDOMDocument('<root xmlns:svn="svn:" xmlns:n="node:"><node /></root>');
        $xml = new Xml($document);        
        $this->assertEquals(array('svn' => 'svn:', 'n' => 'node:'), $xml->getDocumentNamespaces());
    }

    public function testNamespaceArrayWithDefaultNamespace() {
        $document = self::createDOMDocument('<root xmlns="default:" xmlns:n="node:"><node /></root>');
        $xml = new Xml($document);
        $this->assertEquals(array('default' => 'default:', 'n' => 'node:'), $xml->getDocumentNamespaces());
    }

    public function testNamespaceArrayWithDefaultNamespaceSetToOther() {
        $document = self::createDOMDocument('<root xmlns="default:" xmlns:n="node:"><node /></root>');
        $xml = new Xml($document,'other');
        $this->assertEquals(array('other' => 'default:', 'n' => 'node:'), $xml->getDocumentNamespaces());
    }

    public function testNamespaceArrayWithDefaultNamespacesFlat() {
        $document = self::createDOMDocument('<root xmlns:n="node:"><node xmlns:svn="svn:" /></root>');
        $xml = new Xml($document);
        $this->assertEquals(array('n' => 'node:'), $xml->getDocumentNamespaces(false));
    }

    public function testNamespaceArrayWithDefaultNamespacesRecursive() {
        $document = self::createDOMDocument('<root xmlns:n="node:"><node xmlns:svn="svn:" /></root>');
        $xml = new Xml($document);
        $this->assertEquals(array('n' => 'node:','svn'=>'svn:'), $xml->getDocumentNamespaces(true));
    }


    public function testGetDefaultNamespace() {
        $xml = new Xml(self::createDOMDocument('<root xmlns="http://gucken.org/default"><node /></root>'));
        $this->assertEquals('http://gucken.org/default',$xml->getDefaultNamespace());
    }

    public function testGetDefaultNamespacePrefix() {
        $xml = new Xml(self::createDOMDocument('<root xmlns="http://gucken.org/default"><node /></root>'));
        $this->assertEquals('default',$xml->getDefaultNamespacePrefix());
    }

    public function testGetOtherDefaultNamespacePrefix() {
        $xml = new Xml(self::createDOMDocument('<root xmlns="http://gucken.org/default"><node /></root>'),'other');
        $this->assertEquals('other',$xml->getDefaultNamespacePrefix());
    }


    public function testXpathSelectorWithoutNamespaces() {
        $xml = new Xml(self::createDOMDocument('<root ><node /></root>'));
        $result = $xml->xpath('//root/node')->asXml();
        $this->assertInstanceOf('\Type\Xml\Collection', $result);
        $this->assertEquals(1, count($result));
    }

    public function testXpathSelectorWithDefaultNamespace() {
        $xml = new Xml(self::createDOMDocument('<root xmlns="default:" xmlns:n="node:"><node /></root>'));
        $result = $xml->xpath('//default:root/default:node')->asXml();
        $this->assertInstanceOf('\Type\Xml\Collection', $result);
        $this->assertEquals(1, count($result));
    }

    public function testXpathSelectorWithNamespace() {
        $xml = new Xml(self::createDOMDocument('<root xmlns="default:" xmlns:n="node:"><n:node /></root>'));
        $result = $xml->xpath('//default:root/n:node')->asXml();
        $this->assertInstanceOf('\Type\Xml\Collection', $result);
        $this->assertEquals(1, count($result));
    }

    public function testCssSelectorWithDefaultNamespaces() {
        $xml = new Xml(self::createDOMDocument('<root xmlns="http://gucken.org/default"><node /></root>'));
        $result = $xml->css('default|root > default|node')->asXml();
        $this->assertInstanceOf('\Type\Xml\Collection',$result);
        $this->assertEquals(1,count($result));
    }

    public function testCssSelectorWithNonDefaultNamespaces() {
        $xml = new Xml(self::createDOMDocument('<root xmlns:n="http://gucken.org/node"><n:node /></root>'));
        $result = $xml->css('root > n|node')->asXml();
        $this->assertInstanceOf('\Type\Xml\Collection',$result);
        $this->assertEquals(1,count($result));
    }

    /**
     * @depends testCssSelectorWithDefaultNamespaces
     * @depends testCssSelectorWithNonDefaultNamespaces
     */
    public function testCssSelectorWithDefaultAndNonDefaultNamespaces() {
        $xml = new Xml(self::createDOMDocument('<root xmlns="http://gucken.org/default" xmlns:n="http://gucken.org/node"><n:node /></root>'));
        $result = $xml->css('default|root > n|node')->asXml();
        $this->assertInstanceOf('\Type\Xml\Collection',$result);
        $this->assertEquals(1,count($result));
    }

    public function testCssSelectorWithoutNamespaces() {
        $xml = new Xml(self::createDOMDocument('<root ><node /></root>'));
        $result = $xml->css('root > node')->asXml();
        $this->assertInstanceOf('\Type\Xml\Collection', $result);
        $this->assertEquals(1, count($result));
    }
    

    protected static function createDOMDocument($content) {
        $document = new \DOMDocument('1.0', 'utf-8');
        $document->loadXML($content);
        return $document;
    }  
}
?>
