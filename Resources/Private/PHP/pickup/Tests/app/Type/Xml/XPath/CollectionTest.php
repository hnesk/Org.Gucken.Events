<?php
namespace Tests\Type\Xml\XPath;
use Type\Xml;
use Type\String;

/**
 * Test cases for Xml\Xpath\Collection
 *
 * @author jk
 */
class CollectionTest extends \PHPUnit_Framework_TestCase {

    /**
     *
     * @var \Type\Xml
     */
    protected $document;

    public function setUp() {
        $this->document = new Xml(self::createDOMDocument('<root><node attr="first" href="./test.php">Hi</node><node attr="second" href="http://rest.de">World</node></root>'));
    }

    public function testAsString() {
        $expected = new String\Collection(array(new String('first'),new String('second')));
        $actual = $this->document->xpath('//node/@attr')->asString();
        $this->assertEquals($expected,$actual);
    }
    

    protected static function createDOMDocument($content) {
        $document = new \DOMDocument('1.0', 'utf-8');
        $document->loadXML($content);
        return $document;
    }
}
?>
