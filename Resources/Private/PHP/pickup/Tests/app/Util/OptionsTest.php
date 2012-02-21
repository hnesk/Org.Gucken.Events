<?php
namespace Tests\Util;
use Util\Options;

/**
 * Description of JsonTest
 *
 * @author jk
 */
class OptionsTest extends \PHPUnit_Framework_TestCase {

    /**
     *
     * @var Options
     */
    protected $options;

    public function  setUp() {
        $this->options = new Options('{"a":12,"b":{"b1":"hallo","d2":{"e":11,"f":13},"c1":"test"}}');
    }

    public function testConstructorWithArray() {
        $data = array(
            'a' => 12,
            'b' => array(
                'b1' => 'hallo',
                'c1' => 'test'
            )
        );
        $options = new Options($data);
        $this->assertEquals($data, $options->toArray());
    }

    public function testConstructorWithObject() {
        $data = array(
            'a' => 12,
            'b' => array(
                'b1' => 'hallo',
                'c1' => 'test'
            )
        );

        $object = (object)array(
            'a' => 12,
            'b' => (object)array(
                'b1' => 'hallo',
                'c1' => 'test'
            )
        );
        $options = new Options($object);
        $this->assertEquals($data, $options->toArray());
    }


    /**
     * @depends testConstructorWithObject
     */
    public function testConstructorWithJson() {
        $data = array(
            'a' => 12,
            'b' => array(
                'b1' => 'hallo',
                'c1' => 'test'
            )
        );

        $json = '{"a":12,"b":{"b1":"hallo","c1":"test"}}';
        $options = new Options($json);
        $this->assertEquals($data, $options->toArray());
    }

    /**
     * @depends testConstructorWithArray
     */
    public function testConstructorWithSimpleIniString() {
        $data = array('b' => 5);
        $options = new Options('b=5');
        $this->assertEquals($data, $options->toArray());
    }

    /**
     * @depends testConstructorWithArray
     */
    public function testConstructorWithDottedIniString() {
        $data = array(
            'b' => array(
                'b1' => array(
                    'd' => 15
                 )
            )
        );

        $options = new Options('b.b1.d=15');
        $this->assertEquals($data, $options->toArray());
    }

    /**
     * @depends testConstructorWithArray
     */
    public function testConstructorWithIniStringWithoutEqualSign() {
        $data = array('test' => array('on'=>1));
        $options = new Options('test.on');
        $this->assertEquals($data, $options->toArray());
    }


    public function testGetWithNonexistingKeyReturnsNull() {
        $this->assertNull($this->options->get('x'));
    }

    public function testGetWithNonexistingParentKeyReturnsNull() {
        $this->assertNull($this->options->get('x/y'));
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testOffsetSetThrowsBadMethodCallException() {
        $this->options['x'] = 17;
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testOffsetUnsetThrowsBadMethodCallException() {
        unset($this->options['x']);        
    }



    public static function keysAndValues() {
        return array(
            array('a', 12),
            array('b', array('b1'=>'hallo','d2'=>array('e'=>11,'f'=>13),'c1'=>'test')),
            array('b.b1', 'hallo'),
            array('b.d2' , array('e'=>11,'f'=>13)),
            array('b.d2.e' , 11),
            array('b.d2.f' , 13),
            array('b.c1' , 'test')
        );
    }

    /**
     * @dataProvider keysAndValues
     */
    public function testGet($key, $value) {
        $this->assertEquals($value, $this->options->get($key));
    }

    /**
     * @dataProvider keysAndValues
     */
    public function testOffsetGet($key, $value) {
        $this->assertEquals($value, $this->options[$key]);
    }

    /**
     * @dataProvider keysAndValues
     */
    public function testOffsetExists($key, $value) {
        $this->assertTrue(isset($this->options[$key]));
    }

}
?>