<?php
namespace Tests\Type\Xml;
use Type\Xml;


/**
 * Test cases for Xml\Collection
 *
 * @author jk
 */
class CollectionTest extends \PHPUnit_Framework_TestCase {

    public function testCreationWithXml() {
        $collection = new Xml\Collection(array(
            new Xml(self::createDOMDocument('<root />')),
            new Xml(self::createDOMDocument('<w00t />'))
        ));
        $this->assertEquals(2, count($collection));
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNonTypesafeCreationWithIntegersThrowsInvalidArgumentException() {
        new Xml\Collection(array(1,5));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNonTypesafeCreationWithObjectsThrowsInvalidArgumentException() {
        new Xml\Collection((object)array('a'=>1), (object)array('a'=>2));
    }

    protected static function createDOMDocument($content) {
        $document = new \DOMDocument('1.0', 'utf-8');
        $document->loadXML($content);
        return $document;
    }
}
?>
