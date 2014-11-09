<?php

namespace Org\Gucken\Events\Domain\Model\EventSource;

use Org\Gucken\Events\Domain\Model\Location;
use Org\Gucken\Events\Domain\Model\Type;
use Type\Record;
use Type\Url,
    Type\Xml;
use Org\Gucken\Events\Annotations as Events,
    TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("prototype")
 */
class JoomlaEventList extends AbstractEventSource implements EventSourceInterface
{

    /**
     * @Events\Configurable
     * @var Location
     */
    protected $location;

    /**
     * @Events\Configurable
     * @var Type
     */
    protected $type;

    /**
     * @param Location $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Type $type
     */
    public function setType($type)
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
     * @return \Type\Url\Collection
     */
    public function getUrls()
    {
        return $this->getUrl()->load('badHtml')->getContent()
            ->css('div#eventlist table.eventtable td[headers="el_title"] a')->asUrl();
    }

    /**
     * @return \Type\Record\Collection
     */
    public function getEvents()
    {
        return $this->getUrls()->load('badHtml')->getContent()
            ->css('div#eventlist')->asXml()->map(array($this, 'getEvent'));
    }

    /**
     *
     * @param  Xml          $xml
     * @return Record
     */
    public function getEvent(Xml $xml)
    {
        try {
            $description = $xml->css('div.description')->asXml()->formattedText()->normalizeParagraphs();
        } catch (\Exception $e) {
            // completely fucked up word comments crashed the xml
            $description = $xml->css('div.description')->asString()->normalizeParagraphs();
        }
        $tickets = $description->eachMatch('#[\s:]+([\d,.-]+)\s*(euro?|€)#i', '$1')->first();
        $date = $xml->css('dl.event_info dd.when')->asString()->first()->normalizeSpace();
        $title = $xml->css('dl.event_info dd.title')->asString()->first()->normalizeSpace();

        return new Record(
            array(
                'title' => $title,
                'date' => $date->asDate('%d.%m.%Y( %H[:.]%M h)?'),
                #'end' => $date->asDate('%d.%m.%Y.+\s*-\s*%H[:.]%\s*h$'),
                'location' => $this->getLocation(),
                'type' => $this->getType(),
                'description' => $description,
                'url' => $xml->getBaseUri(),
                'cost_box_office' => is($tickets) ? $tickets->asNumber() : null,
            )
        );
    }
}
