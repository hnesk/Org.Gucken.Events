<?php
namespace Tests\Type\Xml;

use Type\Xml;
use Type\String;

/**
 * Description of XPathTest
 *
 * @author jk
 */
class XPathTest extends \PHPUnit_Framework_TestCase {

    /**
     *
     * @var \Type\Xml
     */
    protected $document;

    public function setUp() {
        $this->document = new Xml(self::createDOMDocument('<root xmlns="test:"><node>Hi</node></root>'));
    }

    public function testConstructorWithValidXpathWorks() {
        $xpath = new \Type\Xml\XPath($this->document,'//default:root/default:node');
        $this->assertEquals('//default:root/default:node', (string)$xpath);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructorWithInvalidXpathThrowsInvalidArgumentException() {
        $xpath = new \Type\Xml\XPath($this->document,'//default:ro ot#default:node');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructorWithUndefinedNamespaceXpathThrowsInvalidArgumentException() {
        $xpath = new \Type\Xml\XPath($this->document,'//shnefault:root/default:node');
    }


    public function testJuicyXpathFindsNode() {
        $xpath = new \Type\Xml\XPath($this->document,'//default:root/default:node');

        $list = $xpath->asXml();
        $this->assertInstanceOf('Type\Xml\Collection', $list);
        $this->assertEquals(1, \count($list));
        $this->assertInstanceOf('Type\Xml', $list->first());
    }

    public function testUnjuicyXpathFindsNoNode() {
        $xpath = new \Type\Xml\XPath($this->document,'//default:root/default:shnode');
        $list = $xpath->asXml();
        $this->assertInstanceOf('Type\Xml\Collection', $list);
        $this->assertEquals(0, \count($list));
    }

    public function testXpathReturnsStringCollection() {
        $xpath = new \Type\Xml\XPath($this->document,'//default:root/default:node');
        $list = $xpath->asString();
        $this->assertInstanceOf('\Type\String\Collection', $list);
        $this->assertEquals(1, count($list));
        $this->assertEquals('Hi', (string)$list[0]);
    }

    public function testXpathReturnsXmlCollection() {
        $xpath = new \Type\Xml\XPath($this->document,'//default:root/default:node');
        $list = $xpath->asXml();
        $this->assertInstanceOf('\Type\Xml\Collection', $list);
        $this->assertEquals(1, count($list));
        $this->assertEquals('node', $list[0]->getNativeValue()->tagName);
        $this->assertEquals('node', $list[0]->getNativeValue()->tagName);
        $this->assertEquals('Hi', $list[0]->__toString());
    }

    protected static function createDOMDocument($content) {
        $document = new \DOMDocument('1.0', 'utf-8');
        $document->loadXML($content);
        return $document;
    }
}
?>
