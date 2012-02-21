<?php
namespace Tests\Type;
use Type\Url;

/**
 * Description of UrlTest
 *
 * @author jk
 */
class UrlTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var    Url
     * @access protected
     */
    protected $object;

    protected function setUp() {
        $this->object = new Url('http://www.test.de:8080/sub1/sub2/post.php?a=1&ar[1]=0&ar[2]=5#top');
    }

    protected function tearDown() {
        unset($this->object);
    }

    public function testOneArgumentConstructorBuildsUrl() {
        $url = new Url('http://www.test.de:8080/sub1/sub2/post.php?a=1&ar[1]=0&ar[2]=5#top');
        $this->assertEquals('http://www.test.de:8080/sub1/sub2/post.php?a=1&ar[1]=0&ar[2]=5#top', $url->__toString());
    }

    public function testGettersWork() {
        $url = new Url('http://hnes:geheim@www.test.de:8080/sub1/sub2/post.php?a=1&ar[1]=0&ar[2]=5#top');
        $this->assertEquals('http', (string)$url->getScheme());
        $this->assertEquals('hnes', (string)$url->getUser());
        $this->assertEquals('geheim', (string)$url->getPass());
        $this->assertEquals('www.test.de', (string)$url->getHost());
        $this->assertEquals('8080', (string)$url->getPort());
        $this->assertEquals('/sub1/sub2/post.php', (string)$url->getPath());
        $this->assertEquals('a=1&ar[1]=0&ar[2]=5', (string)$url->getQuery());
        $this->assertEquals('top', (string)$url->getFragment());
    }



    public function testResolveRelativeUrlGoUp() {
        $url = new Url($this->object,'../rast.php?a=666');
        $this->assertEquals('http://www.test.de:8080/sub1/rast.php?a=666', $url->__toString());
    }

    public function testResolveRelativeUrlGoUpTwice() {
        $url = new Url($this->object,'../../rast.php?a=666');
        $this->assertEquals('http://www.test.de:8080/rast.php?a=666', $url->__toString());
    }

    public function testResolveRelativeUrlGoUpThrice() {
        $url = new Url($this->object,'../../../rast.php?a=666');
        $this->assertEquals('http://www.test.de:8080/rast.php?a=666', $url->__toString());
    }

    public function testResolveRelativeUrlWithDot() {
        $url = new Url($this->object,'./rast.php?a=666');
        $this->assertEquals('http://www.test.de:8080/sub1/sub2/rast.php?a=666', $url->__toString());
    }

    public function testResolveRelative() {
        $url = new Url($this->object,'rast.php?a=666');
        $this->assertEquals('http://www.test.de:8080/sub1/sub2/rast.php?a=666', $url->__toString());
    }

    public function testResolveAbsoluteUrlOnSameServer() {
        $url = new Url($this->object,'/rast.php?a=666');
        $this->assertEquals('http://www.test.de:8080/rast.php?a=666', $url->__toString());
    }

    public function testResolveAbsoluteUrlOnAnotherServer() {
        $url = new Url($this->object,'http://mist.de/rast.php#bottom');
        $this->assertEquals('http://mist.de/rast.php#bottom', $url->__toString());
    }

    public function testResolveFromDirectory() {
        $url = new Url('http://www.test.de/sub1/sub2/','..');
        $this->assertEquals('http://www.test.de/sub1', $url->__toString());
    }

    public function testResolveAnchor() {
        $url = new Url('http://www.theaterlabor.de/index.php?option=com_content&view=article&id=71&Itemid=2', '#Branko');
        $this->assertEquals('http://www.theaterlabor.de/index.php?option=com_content&view=article&id=71&Itemid=2#Branko', $url->__toString());
    }

    public function testExportAll() {
        $this->assertEquals('http://www.test.de:8080/sub1/sub2/post.php?a=1&ar[1]=0&ar[2]=5#top', (string)$this->object->toString(Url::ALL));
    }

    public function testExportDomain() {
        $this->assertEquals('http://www.test.de:8080', (string)$this->object->toString(Url::DOMAIN));
    }

    public function testExportNoFragment() {
        $this->assertEquals('http://www.test.de:8080/sub1/sub2/post.php?a=1&ar[1]=0&ar[2]=5', (string)$this->object->toString(Url::NOFRAGMENT));
    }

    public function testExportNoQuery() {
        $this->assertEquals('http://www.test.de:8080/sub1/sub2/post.php', (string)$this->object->toString(Url::NOQUERY));
    }

    public function testUnderDomainAsString() {
        $domainUrl = $this->object->toString(Url::DOMAIN);
        $this->assertTrue($this->object->under($domainUrl));
    }


    public function testTrim() {
        $result = $this->object->trim(Url::DOMAIN);
        $expected = new Url('http://www.test.de:8080');
        $this->assertEquals($expected, $result);
    }


    public function testSameDomain() {
        $url1 = new Url('http://www.test.de:8080/rest/post/mist.php?a=1&r=3#id');
        $url2 = new Url('http://www.test.de:8080/fast/laesst.php?v=1&e=3#id2');
        $this->assertTrue($url1->sameDomain($url2));
    }

    public function testSameDomainWrongPort() {
        $url1 = new Url('http://www.test.de:80/rest/post/mist.php?a=1&r=3#id');
        $url2 = new Url('http://www.test.de:8080/fast/laesst.php?v=1&e=3#id2');
        $this->assertFalse($url1->sameDomain($url2));
    }

    public function testSameDomainWrongScheme() {
        $url1 = new Url('http://www.test.de:8080/rest/post/mist.php?a=1&r=3#id');
        $url2 = new Url('https://www.test.de:8080/fast/laesst.php?v=1&e=3#id2');
        $this->assertFalse($url1->sameDomain($url2));
    }

    public function testSameDomainWrongDomain() {
        $url1 = new Url('http://www.test.de:8080/rest/post/mist.php?a=1&r=3#id');
        $url2 = new Url('http://www2.test.de:8080/fast/laesst.php?v=1&e=3#id2');
        $this->assertFalse($url1->sameDomain($url2));
    }

    public function testSamePartsScheme() {
        $url1 = new Url('http://www.test.de:8080/rest/post/mist.php?a=1&r=3#id');
        $url2 = new Url('http://www.test.de:8080/fast/laesst.php?v=1&e=3#id2');
        $this->assertTrue($url1->sameParts($url2, Url::SCHEME));
    }

    public function testSamePartsWrongScheme() {
        $url1 = new Url('http://www.test.de:8080/rest/post/mist.php?a=1&r=3#id');
        $url2 = new Url('https://www.test.de:8080/fast/laesst.php?v=1&e=3#id2');
        $this->assertFalse($url1->sameParts($url2, Url::SCHEME));
    }

    public function testResolvingKeepsAuthentication() {
        $url = new Url('http://hnes:geheim@www.theaterlabor.de/index.php?option=com_content&view=article&id=71&Itemid=2', 'test.php');
        $this->assertEquals('http://hnes:geheim@www.theaterlabor.de/test.php', $url->__toString());
    }

}
?>
