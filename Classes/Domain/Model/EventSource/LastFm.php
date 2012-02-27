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
	 * @return \Lastfm\Type\Geo 
	 */
	public function getGeo() {
		return new \Lastfm\Type\Geo($this->city);
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
	 * @param Model\EventFactoidIdentity $factoidIdentity
	 * @param \Org\Gucken\Events\Domain\Model\EventLink if set link will be updated else created
	 * @return \Org\Gucken\Events\Domain\Model\LastFmEventLink 
	 */
	public function convertLink(Model\EventFactoidIdentity $factoidIdentity, $link = null) {
		$link = $link ? : new Model\LastFmEventLink();
		$link->setUrl($factoidIdentity->getFactoid()->getUrl());
		return $link;
	}

	/**
	 *
	 * @param Model\EventFactoid $factoid 
	 */
	public function convertLocation(Model\EventFactoid $factoid) {
		$location = null;
		$proofXml = $factoid->getProofAsXml();
		if (!empty($proofXml)) {
			$venue = \Lastfm\Type\Venue\Factory::fromTypeXml($proofXml->css('venue')->asXml()->first());
			/* @var $venue \Lastfm\Type\Venue */

			$location = new Model\Location();
			$location->setName($venue->getName());
			$location->setUrl((string)$venue->getUrl());
			$location->setAddress(new Model\PostalAddress(
					(string) $venue->getStreet(),
					(string) $venue->getPostalCode(),
					(string) $venue->getCity(),
					'NRW',
					(string) $venue->getCountry()
			));

			$location->setGeo(new Model\GeoCoordinates(
					(string) $venue->getLatitude(),
					(string) $venue->getLongitude()
			));

			$location->addExternalIdentifier(new Model\LastFmLocationIdentifier($venue->getId(), $location, (string)$venue));
			$location->addKeywordAsString($venue->getName());
		}
		return $location;
	}

	/**
	 * @return \Type\Record\Collection
	 */
	public function getEvents() {
		return $this->getGeo()->getEvents(20, 20)->map(array($this, 'getEvent'));
	}

	/**
	 *
	 * @param \Lastfm\Type\Event $event
	 * @return \Type\Record 
	 */
	public function getEvent(\Lastfm\Type\Event $event) {		
		$date = $event->getStartTime();
		// if no time is given, last.fm has random times, an ok indicator is that there are seconds (with a failure rate of 1/60)
		if (!$date->getSecond()->equals(0)) {
			// concerts start at 8 pm, don't they?
			$date = $date->timed('20:00:00');
		}
		return new \Type\Record(array(
				'title' => $event->getTitle(),
				'date' => $date,
				'short' => (string) $event->getArtists()->join(', '),
				'description' => $event->getDescription()->formattedText()->normalizeParagraphs(),
				'type' => $this->type,
				'location' => $this->locationRepository->findOneByExternalId('org_gucken_events_lastfmlocationidentifier', $event->getVenue()->getId()),
				'url' => $event->getUrl(),
				'website' => $event->getWebsite(),
				'proof' => $event->getProof()
			));
	}

}

?>
