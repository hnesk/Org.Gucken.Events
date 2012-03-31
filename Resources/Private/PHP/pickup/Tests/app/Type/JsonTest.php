<?php
namespace Tests\Type;
use Type\Json;

/**
 * Description of JsonTest
 *
 * @author jk
 */
class JsonTest extends \PHPUnit_Framework_TestCase {

    public function testConstructorSetsValueWithNumericKeys() {
        $json = new Json(array('1','2','3'));
        $this->assertEquals('1',(string)$json[0]);
        $this->assertEquals('2',(string)$json[1]);
        $this->assertEquals('3',(string)$json[2]);
    }

    public function testConstructorSetsValueWithAlphaKeys() {
        $json = new Json(array('a' => '1','b' => '2','c' => '3'));
        $this->assertEquals('1',(string)$json['a']);
        $this->assertEquals('2',(string)$json['b']);
        $this->assertEquals('3',(string)$json['c']);
    }

    public function testConstructorSets() {
        $value = array(1,2,3);
        $json = new Json($value);
        $this->assertEquals($value,$json->getNativeValue());
    }

    public function testCountZero() {
        $json = new Json();
        $this->assertEquals(0,count($json));
    }

    public function testCountMore() {
        $json = new Json(array(1,2,3));
        $this->assertEquals(3,count($json));
    }

    public function testIsset() {
        $json = new Json(array('a' => 1,'b' => 2,'c' => 3));
        $this->assertTrue(isset ($json['b']));
        $this->assertFalse(isset ($json['x']));
    }

    public function testUnset() {
        $json = new Json(array('a' => 1,'b' => 2,'c' => 3));
        $this->assertTrue(isset ($json['b']));
        unset($json['b']);
        $this->assertFalse(isset ($json['b']));
    }

    public function testSet() {
        $json = new Json(array('a' => 1,'b' => 2,'c' => 3));
        $this->assertEquals('2',(string)$json['b']);
        $json['b'] = '3';
        $this->assertEquals('3',(string)$json['b']);
    }

    public function testJpathSimpleValues() {
        $values = array(
            'a' => 1,
            'b' => array(
                'x' => 11,
                'y' => 12,
                'z' => 13
            ),
            'c' => 3
        );
        $json = new Json($values);
        $this->assertInstanceOf('\Type\Number', $json->jpath('a'));
        $this->assertEquals('1', (string)$json->jpath('a'));
    }

    public function testJpathReturnsNullOnEmptyResult() {
        $values = array(
            'a' => 1,
            'b' => array(
                'x' => 11,
                'y' => 12,
                'z' => 13
            ),
            'c' => 3
        );
        $json = new Json($values);
        $this->assertNull($json->jpath('d'));
        $this->assertNull($json->jpath('d/x'));
        $this->assertNull($json->jpath('b/q'));
        $this->assertNull($json->jpath('b/x/a'));                
    }

    public function testJpathJsonValues() {
        $values = array(
            'a' => 1,
            'b' => array(
                'x' => 11,
                'y' => 12,
                'z' => 13
            ),
            'c' => 3
        );
        $json = new Json($values);

        $this->assertInstanceOf('\Type\Json', $json->jpath('b'));
        $this->assertInstanceOf('\Type\Number', $json->jpath('b/y'));
        $this->assertEquals('12', (string)$json->jpath('b/y'));
    }

    public function testIteration() {
        $values = array('a' => 1,'b' => 2,'c' => 3);
        $json = new Json($values);
        foreach ($json as $k => $value) {
            $this->assertEquals($values[$k], $value);
        }
    }

}
?>