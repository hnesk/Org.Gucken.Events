<?php
namespace Tests\Lastfm\Type;
use Lastfm\Type\Artist;

/**
 * Description of ArtistTest
 *
 * @author jk
 */
class ArtistTest extends \PHPUnit_Framework_TestCase {
    public function testGetTopFansReturnsUserCollection() {
        $artist = new Artist('Sunday Chocolade Club', $this->getApiStub('getTopFans'));
        $this->assertInstanceOf('\Lastfm\\Type\\User\\Collection', $artist->getTopFans());        
    }

    public function testGetTopFansReturnsSomeUsers() {
        $artist = new Artist('Sunday Chocolade Club', $this->getApiStub('getTopFans'));
        $this->assertGreaterThan(1, count($artist->getTopFans()));
    }


    protected function getApiStub($method) {
        $apiStub = $this->getMock('\\Lastfm\\Api\\Artist',array($method),array(),'',false);
        $apiStub->expects($this->once())
                ->method($method)
                ->will($this->returnXml($method.'.xml'));
        return $apiStub
        ;
    }

    protected function returnXml($filename) {
        $content = file_get_contents(BASE_PATH.'../Tests/fixtures/Lastfm/Artist/'.$filename);
        return $this->returnValue(new \SimpleXMLElement($content));
    }

}
