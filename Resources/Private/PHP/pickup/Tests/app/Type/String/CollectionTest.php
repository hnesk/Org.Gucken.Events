<?php
namespace Tests\Type\String;
use Type\String;
use Type;

/**
 * Tests for the String\Collection class
 *
 * @author jk
 */
class CollectionTest extends \PHPUnit_Framework_TestCase {

    /**
     *
     * @var \Type\String\Collection
     */
    protected $list;

    public function setUp() {
        $this->list = new String\Collection(array(
            new String('Test'),
            new String('Post'),
            new String('Rest')
        ));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNonTypesafeCreationWithIntegersThrowsInvalidArgumentException() {
        new String\Collection(array(1,5),true,false);
    }

    
    public function testTypeCastedCreationWithIntegersThrowsNoInvalidArgumentException() {
        try {
            new String\Collection(array(1,5),true,true);
            $this->assertTrue(true, 'This can\t happen');
        } catch (Exception $e) {
            $this->fail('array<int> could not be casted to String\\Collection');
        }
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNonTypesafeCreationWithObjectsThrowsInvalidArgumentException() {
        new String\Collection(array((object)array('a'=>1), (object)array('a'=>2)),true,false);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTypeCastedCreationWithUncastableObjectsThrowsInvalidArgumentException() {
        new String\Collection(array((object)array('a'=>1), (object)array('a'=>2)),true,false);
    }


    public function testEmptyCollectionThrowsNoException() {
        $goodList = new String\Collection();
        $this->assertFalse(!$goodList);
    }

    public function testExplicitCountWorks() {
        $this->assertEquals(3, $this->list->count());
    }

    public function testImplicitCountWorks() {
        $this->assertEquals(3, count($this->list));
    }

    public function testLastWorks() {
        $this->assertEquals('Rest', (string)$this->list->last());
    }

    public function testFirstWorks() {
        $this->assertEquals('Test', (string)$this->list->first());
    }


    public function testIterationWorks() {
        $expected = array(0=>'Test',1=>'Post',2=>'Rest');
        $actual = array();
        foreach ($this->list as $key=>$value) {
            $actual[$key] = (string)$value;
        }
        $this->assertEquals($expected, $actual);
    }

    public function testAddWorks() {
        $this->list->add(new String('Peter'));
        $this->assertEquals(4, count($this->list));
        $this->assertEquals('Peter', (string)$this->list[3]);
    }

    public function testImplicitAddWorks() {

        $this->list[]  = new String('Peter');
        $this->assertEquals(4, count($this->list));
        $this->assertEquals('Peter', (string)$this->list[3]);
    }


    public function testSetWorks() {
        $this->list[1] = new String('Rost');
        $this->assertEquals('Test', (string)$this->list[0]);
        $this->assertEquals('Rost', (string)$this->list[1]);
        $this->assertEquals('Rest', (string)$this->list[2]);        
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetWithWrongTypeThrowsInvalidArgumentException() {
        $this->list[1] = new \stdClass();
    }

    public function testUnsetWorks() {
        unset($this->list[1]);
        $this->assertFalse(isset($this->list[1]));
        $this->assertFalse(array_key_exists(1, $this->list));
    }


    public function testItemWorks() {
        $this->assertEquals('Post',(string)$this->list->item(1));
    }

    public function testGetItemDataType() {
        $this->assertEquals('\Type\String',$this->list->getItemDataType());
    }


    public function testFilterAcceptAll() {
        $filtered = $this->list->filter(function(String $item) {return true;});
        $this->assertEquals($this->list, $filtered);
    }

    public function testFilterRejectAll() {
        $filtered = $this->list->filter(function(String $item) {return false;});
        $this->assertEquals(new String\Collection(), $filtered);
    }

    public function testFilterAcceptByKey() {
        $expected = new String\Collection();
        $expected[1] = new String('Post');
        $filtered = $this->list->filter(function(String $item,$key) {return $key % 2;});
        $this->assertEquals($expected, $filtered);
    }

    public function testFilterAcceptByValue() {
        $expected = new String\Collection();
        $expected[0] = new String('Test');
        $expected[2] = new String('Rest');
        $filtered = $this->list->filter(function(String $item,$key) {return (string)$item->substring(1) == 'est';});
        $this->assertEquals($expected, $filtered);
    }

    public function testMap() {
        $expected = new String\Collection(array(
            new String('est'),
            new String('ost'),
            new String('est')
        ));
        $mapped = $this->list->map(function(String $item,$key) {return $item->substring(1);});
        $this->assertEquals($expected, $mapped);
    }

    public function testMapWithEmptyListAndTypeHint() {
        $expected = new String\Collection(array(null,null,null));
        $mapped = $this->list->map(function(String $item,$key) {return null;},'\Type\String\Collection');
        $this->assertEquals($expected, $mapped);
    }

    public function testMapWithEmptyListAndNoTypeHint() {
        $expected = new Type\BaseCollection(array(null,null,null));
        $mapped = $this->list->map(function(String $item,$key) {return null;});
        $this->assertEquals($expected, $mapped);
    }

    public function testEach() {
        $expected = array('Test','Post','Rest');
        $actual = array();
        $this->list->each(function ($item,$key) use (&$actual) {
            $actual[$key] = (string)$item;
        });
        $this->assertEquals($expected, $actual);
    }

    public function testGetNativeValue() {
        $expected = array('Test','Post','Rest');
        $this->assertEquals($expected, $this->list->getNativeValue());
    }

    public function testImplicitCasting() {
        $actual = new String\Collection(array('est','ost','est'));

        $expected = new String\Collection(array(
            new String('est'),
            new String('ost'),
            new String('est')
        ));

        $this->assertEquals($expected, $actual);

    }

}
?>
