<?php

namespace Org\Gucken\Events\Domain\Model\EventSource;

use Type\Url;
use Org\Gucken\Events\Domain\Model;


use Org\Gucken\Events\Annotations as Events;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * @FLOW3\Scope("prototype")
 */
class LastFm implements EventSourceInterface {

    /**
     * @FLOW3\Inject
     * @var \Org\Gucken\Events\Domain\Repository\LocationRepository
     */
    protected $locationRepository;
    
    /**
     * @Events\Configurable
     * @var string
     */
    protected $city;
    
    
    /**
     * @Events\Configurable
     * @var \Org\Gucken\Events\Domain\Model\Type
     */
    protected $type;



    /**
     * @param string $city
     */
    public function setCity($city) {
        $this->city = $city;
    }

    /**
     *
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     *
     * @param \Org\Gucken\Events\Domain\Model\Type $type
     */
    public function setType(\Org\Gucken\Events\Domain\Model\Type $type) {
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
     * @return \Org\Gucken\Events\Domain\Repository\LocationRepository
     */
    public function getLocationRepository() {
        return $this->locationRepository;
    }
    
	/**
	 *
	 * @param Model\EventFactoid $factoid 
	 */
	public function convertLocation(Model\EventFactoid $factoid) {
		$location = null;
		$proof = $factoid->getProof();
		if (!empty($proof)) {
			$proofXml = \Type\Xml\Factory::fromXmlString($proof);
			$venueXml = $proofXml->css('venue')->asXml()->first();
			$venue = \Lastfm\Type\Venue\Factory::fromTypeXml($venueXml);
			/* @var $venue \Lastfm\Type\Venue */
			
			$location = new Model\Location();
			$location->setName($venue->getName());
			$location->setUrl($venue->getUrl());
			$location->setAddress(new Model\PostalAddress(
					(string)$venue->getStreet(),
					(string)$venue->getPostalCode(),
					(string)$venue->getCity(),
					'NRW',
					(string)$venue->getCountry()
			));
			
			$location->setGeo(new Model\GeoCoordinates(
					(string)$venue->getLatitude(),
					(string)$venue->getLongitude()
			));			
			
			$location->addExternalIdentifier(new Model\ExternalLocationIdentifier(Model\ExternalLocationIdentifier::LASTFM,  $venue->getId()));
			$location->addKeywordAsString($venue->getName());						
		}
		return $location;
	}
	

    /**
     * @return \Type\Record\Collection
     */
    public function getEvents() {
        $self = $this;
        $geo = new \Lastfm\Type\Geo($this->city);
        return $geo->getEvents(20, 10)->map(function (\Lastfm\Type\Event $event) use ($self) {                        
                return new \Type\Record(array(
                    'title'         => $event->getTitle(),
                    'date'          => $event->getStartTime(),
                    'short'         => (string)$event->getArtists()->join(', '),
                    'description'   => $event->getDescription()->formattedText()->normalizeParagraphs(),
                    'type'          => $self->getType(),
                    'location'      => $self->getLocationRepository()->findOneByExternalId('lastfm', $event->getVenue()->getId()),
                    'url'           => $event->getUrl(),
                    'website'       => $event->getWebsite(),
                    'proof'         => $event->getProof()
                ));
            }
        );
        
    }
}

?>
