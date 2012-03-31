<?php
namespace Tests\Type;
use Type\String;

/**
 * Description of StringTest
 *
 * @author jk
 */
class StringTest extends \PHPUnit_Framework_TestCase {
    public function testConstructorSetsValue() {
        $string = new String('Stuff');
        $this->assertEquals('Stuff',$string->getNativeValue());
    }

    public function testTrimWithNoArgumentsWorksLikePhpTrim() {
        $testString = "  \n  \t \v      Stuff   padded       \n\n \t ";
        $string = new String($testString);
        $this->assertEquals(trim($testString),(string)$string->trim());
    }

    public function testTrimWithArgumentsWorksLikePhpTrim() {
        $testString = "Stuff   padded";
        $string = new String($testString);
        $this->assertEquals(trim($testString,'Sde'),(string)$string->trim('Sde'));
    }

    public function testNormalizeSpace() {
        $expected = 'A sentence aböut stüff padded';
        $string = new String("\n   A\tsentence\naböut stüff      padded     ");
        $this->assertEquals($expected,(string)$string->normalizeSpace());
    }

    public function testNormalizeSpaceDoesntChangeSpaceNormalizedString() {
        $testString = "A sentence aböut stüff padded";
        $string = new String($testString);
        $this->assertEquals($testString,(string)$string->normalizeSpace());
    }

    public function testSubstring() {
        $testString = "Cütting Ümläüts";
        $string = new String($testString);
        $this->assertEquals(' Ümläüts',(string)$string->substring(7));
    }

    public function testSubstringWithStartAfterEndOfString() {
        $testString = "Cütting Ümläüts";
        $string = new String($testString);
        $this->assertEquals('',(string)$string->substring(27));
    }


    public function testSubstringWithLength() {
        $testString = "Cütting Ümläüts";
        $string = new String($testString);
        $this->assertEquals('Cüt',(string)$string->substring(0,3));
    }

    public function testSubstringWithZeroLength() {
        $testString = "Cütting Ümläüts";
        $string = new String($testString);
        $this->assertEquals('',(string)$string->substring(7,0));
    }

    public function testSubstringBefore() {        
        $string = new String("Cütting Ümläüts");
        $delimiter = new String("läü");
        $this->assertEquals('Cütting Üm',(string)$string->substringBefore($delimiter));
    }

    public function testSubstringBeforeWithNoMatch() {
        $string = new String("Cütting Ümläüts");
        $delimiter = new String("täü");
        $this->assertEquals('',(string)$string->substringBefore($delimiter));
    }

    public function testSubstringAfter() {
        $string = new String("Cütting Ümläütsä");
        $delimiter = new String("läü");
        $this->assertEquals('tsä',(string)$string->substringAfter($delimiter));
    }

    public function testSubstringAfterWithNoMatch() {
        $string = new String("Cütting Ümläüts");
        $delimiter = new String("täü");
        $this->assertEquals('',(string)$string->substringAfter($delimiter));
    }

    public function testByteLengthReturnsLengthInUtf8Bytes() {
        $string = new String("Cütting Ümläüts");
        $this->assertEquals(19,$string->byteLength());
    }

    public function testLengthReturnsLengthInUtf8Characters() {
        $string = new String("Cütting Ümläüts");
        $this->assertEquals(15,$string->length());
    }

    public function testAppend() {
        $string = new String("Cütting Ümläüts");
        $append = new String("ööö");
        $this->assertEquals('Cütting Ümläütsööö',(string)$string->append($append));
    }

    public function testPrependWorks() {
        $string = new String("Cütting Ümläüts");
        $prepend = new String("ööö");
        $this->assertEquals('öööCütting Ümläüts',(string)$string->prepend($prepend));
    }

    public function testStaticCreateString() {
        $testString = "Cütting Ümläüts";
        $this->assertEquals($testString,(string)String::createString($testString));
    }




}
?>
