<?php
namespace Tests\Lastfm\Api;

/**
 * Description of ArtistTest
 *
 * @author jk
 */
class ArtistTest extends \PHPUnit_Framework_TestCase {


    public function testGetTopFansSimpleEncoding() {
        $uri = 'http://ws.audioscrobbler.com/2.0/?artist=U2&autocorrect=0&method=artist.getTopFans&api_key=abc';
        $api = new \Lastfm\Api\Artist('abc', $this->getClientExpecting($uri));
        $api->getTopFans('U2');
    }

    public function testGetTopFansAutocorrect() {
        $uri = 'http://ws.audioscrobbler.com/2.0/?artist=U2&autocorrect=1&method=artist.getTopFans&api_key=abc';
        $api = new \Lastfm\Api\Artist('abc', $this->getClientExpecting($uri));
        $api->getTopFans('U2', null, true);
    }

    public function testGetTopFansMbid() {
        $uri = 'http://ws.audioscrobbler.com/2.0/?mbid=12345&autocorrect=0&method=artist.getTopFans&api_key=abc';        
        $api = new \Lastfm\Api\Artist('abc', $this->getClientExpecting($uri));
        $api->getTopFans(null, 12345);
    }



    public function testGetTopFansWeirdEncoding() {
        $uri = 'http://ws.audioscrobbler.com/2.0/?artist=%D0%92%D0%B0%D1%81%D1%8F+%D0%9E%D0%B1%D0%BB%D0%BE%D0%BC%D0%BE%D0%B2&autocorrect=0&method=artist.getTopFans&api_key=abc';
        $api = new \Lastfm\Api\Artist('abc', $this->getClientExpecting($uri));
        $api->getTopFans('Вася Обломов');
    }
    


    protected function getClientExpecting($uri) {
        $client = $this->getMock('Zend_Http_Client');
        $client->expects($this->once())->method('setUri')->with($uri)->will($this->returnValue($client));
        $client->expects($this->once())->method('request')->will($this->returnValue(new \Zend_Http_Response(200, array('Content-Type'=>'text/xml'), '<dummy />')));
        return $client;
    }

}
