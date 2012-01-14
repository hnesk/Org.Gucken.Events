<?php

namespace Org\Gucken\Events\Domain\Model\EventSource;

use Org\Gucken\Events\Domain\Model\EventSource\AbstractEventSource,
	Org\Gucken\Events\Domain\Model\EventSource\EventSourceInterface;

use Type\Url,
    Type\Xml;

use Org\Gucken\Events\Annotations as Events,
    TYPO3\FLOW3\Annotations as FLOW3;

/**
 * @FLOW3\Scope("prototype")
 */
class JoomlaEventList extends AbstractEventSource implements EventSourceInterface {
            

    /**
     * @Events\Configurable
     * @var \Org\Gucken\Events\Domain\Model\Location
     */
    protected $location;
	
    /**
     * @Events\Configurable
     * @var \Org\Gucken\Events\Domain\Model\Type
     */
    protected $type;
	

    /**
     * @param \Org\Gucken\Events\Domain\Model\Location $location
     */
    public function setLocation($location) {
        $this->location = $location;
    }

    /**
     * @return \Org\Gucken\Events\Domain\Model\Location
     */
    public function getLocation() {
        return $this->location;
    }		        
	
    /**
     * @param \Org\Gucken\Events\Domain\Model\Type $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return \Org\Gucken\Events\Domain\Model\Type
     */
    public function getType() {
        return $this->type;
    }		        
	
	/**
	 *
	 * @return \Type\Url\Collection
	 */
	public function getUrls() {
		return $this->getUrl()->load('badHtml')->getContent()
            ->css('div#eventlist table.eventtable td[headers="el_title"] a')->asUrl();		
	}
		
    /**
     * @return \Type\Record\Collection
     */
    public function getEvents() {
        return $this->getUrls()->load('badHtml')->getContent()
			->css('div#eventlist')->asXml()->map(array($this, 'getEvent'));
    }
	
	/**
	 *
	 * @param Xml $xml
	 * @return \Type\Record 
	 */
	public function getEvent(Xml $xml) {
		$description = $xml->css('div.description')->asXml()->formattedText()->normalizeParagraphs();
		$tickets = $description->eachMatch('#[\s:]+([\d,.-]+)\s*(euro?|€)#i','$1')->first();
		return new \Type\Record(array(
			'title'         => $xml->css('dl.event_info dd.title')->asString()->first()->normalizeSpace(),
			'date'          => $xml->css('dl.event_info dd.when')->asString()->first()->normalizeSpace(),
			'location'      => $this->getLocation(),
			'type'          => $this->getType(),
			'description'   => $xml->css('div.description')->asXml()->formattedText()->normalizeParagraphs(),
			'url'           => $xml->getBaseUri(),
			'cost_box_office'=> is($tickets) ? $tickets->asNumber() : null,
		));						
	}
    
}

?>
