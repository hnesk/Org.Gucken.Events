<?php
namespace Tests\Lastfm\Type;
use Lastfm\Type\Event;
use Lastfm\Type\Geo;

/**
 * Description of ArtistTest
 *
 * @author jk
 */
class GeoTest extends \PHPUnit_Framework_TestCase {
    public function testGetEventsByLocationReturnsEventCollection() {
        $geo = new Geo('Bielefeld, Germany', $this->getApiStub('getEventsByLocation'));
        $this->assertInstanceOf('\Lastfm\\Type\\Event\\Collection', $geo->getEvents());        
    }
    
    public function testGetEventsByLocationReturnsEventCollectionWithDescription() {
        $geo = new Geo('Bielefeld, Germany', $this->getApiStub('getEventsByLocation'));
        $eventDescriptionFound = false;
        foreach ($geo->getEvents() as $event) {
            /* @var $event Event */
            if (trim($event->getDescription())) {
                $eventDescriptionFound = true;
            }
        }
        if (!$eventDescriptionFound) {
            $this->fail('No event description found');
        }
        
    }
    


    protected function getApiStub($method,$filename = null) {
        if (!$filename) {
            $filename = $method.'.xml';
        }
        $apiStub = $this->getMock('\\Lastfm\\Api\\Geo',array($method),array(),'',false);
        $apiStub->expects($this->once())
                ->method($method)
                ->will($this->returnXml($filename));
        return $apiStub
        ;
    }

    protected function returnXml($filename) {
        $content = file_get_contents(BASE_PATH.'../Tests/fixtures/Lastfm/Geo/'.$filename);
        return $this->returnValue(new \SimpleXMLElement($content));
    }

}
