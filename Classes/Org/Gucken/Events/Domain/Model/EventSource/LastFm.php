<?php

namespace Org\Gucken\Events\Domain\Model\EventSource;

use TYPO3\Flow\Annotations as Flow;
use Org\Gucken\Events\Annotations as Events;

use Org\Gucken\Events\Domain\Model\Type;
use Org\Gucken\Events\Domain\Model\EventFactoid;
use Org\Gucken\Events\Domain\Model\EventFactoidIdentity;
use Org\Gucken\Events\Domain\Model\EventLink;
use Org\Gucken\Events\Domain\Model\GeoCoordinates;
use Org\Gucken\Events\Domain\Model\LastFmEventLink;
use Org\Gucken\Events\Domain\Model\LastFmLocationIdentifier;
use Org\Gucken\Events\Domain\Model\Location;
use Org\Gucken\Events\Domain\Model\PostalAddress;
use Org\Gucken\Events\Domain\Repository\LocationRepository;

use Lastfm\Type\Event;
use Lastfm\Type\Geo;
use Lastfm\Type\Venue\Factory as VenueFactory;
use Type\Record;
use Type\Url;


/**
 * @Flow\Scope("prototype")
 */
class LastFm implements EventSourceInterface
{

    /**
     * @Flow\Inject
     * @var LocationRepository
     */
    protected $locationRepository;

    /**
     * @Events\Configurable
     * @var string
     */
    protected $city;

    /**
     * @Events\Configurable
     * @var Type
     */
    protected $type;

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     *
     * @return Geo
     */
    public function getGeo()
    {
        return new Geo($this->city);
    }

    /**
     *
     * @param Type $type
     */
    public function setType(Type $type)
    {
        $this->type = $type;
    }

    /**
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @return LocationRepository
     */
    public function getLocationRepository()
    {
        return $this->locationRepository;
    }

    /**
     *
     * @param  EventFactoidIdentity                      $factoidIdentity
     * @param EventLink if set link will be updated else created
     * @return LastFmEventLink
     */
    public function convertLink(EventFactoidIdentity $factoidIdentity, $link = null)
    {
        $link = $link ?: new LastFmEventLink();
        $link->setUrl($factoidIdentity->getFactoid()->getUrl());

        return $link;
    }

    /**
     *
     * @param  EventFactoid  $factoid
     * @return null|Location
     */
    public function convertLocation(EventFactoid $factoid)
    {
        $location = null;
        $proofXml = $factoid->getProofAsXml();
        if (!empty($proofXml)) {
            $venue = VenueFactory::fromTypeXml($proofXml->css('venue')->asXml()->first());
            /* @var $venue \Lastfm\Type\Venue */

            $location = new Location();
            $location->setName($venue->getName());
            $location->setUrl((string) $venue->getUrl());
            $location->setAddress(
                new PostalAddress(
                    (string) $venue->getStreet(),
                    (string) $venue->getPostalCode(),
                    (string) $venue->getCity(),
                    'NRW',
                    (string) $venue->getCountry()
                )
            );

            $location->setGeo(
                new GeoCoordinates(
                    (string) $venue->getLatitude(),
                    (string) $venue->getLongitude()
                )
            );

            $location->addExternalIdentifier(
                new LastFmLocationIdentifier($venue->getId(), $location, (string) $venue)
            );
            $location->addKeywordAsString($venue->getName());
        }

        return $location;
    }

    /**
     * @return \Type\Record\Collection
     */
    public function getEvents()
    {
        return $this->getGeo()->getEvents(20, 20)->map(array($this, 'getEvent'));
    }

    /**
     *
     * @param  Event $event
     * @return Record
     */
    public function getEvent(Event $event)
    {
        $date = $event->getStartTime();
        // if no time is given, last.fm has random times, an ok indicator is that there are seconds (with a failure rate of 1/60)
        if (intval((string)$date->getSecond())!=0) {
            // concerts start at 8 pm, don't they?
            $date = $date->timed('20:00:00');
        }

        return new Record(
            array(
                'title' => $event->getTitle(),
                'date' => $date,
                'short' => (string) $event->getArtists()->join(', '),
                'description' => $event->getDescription()->formattedText()->normalizeParagraphs(),
                'type' => $this->type,
                'location' => $this->locationRepository->findOneByExternalId(
                    'org_gucken_events_lastfmlocationidentifier',
                    $event->getVenue()->getId()
                ),
                'url' => $event->getUrl(),
                'website' => $event->getWebsite(),
                'proof' => $event->getProof()
            )
        );
    }

}
