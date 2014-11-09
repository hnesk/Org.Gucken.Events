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
class JoomlaEventListAncient extends AbstractEventSource implements EventSourceInterface
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
     * @return Url\Collection
     */
    public function getUrls()
    {
        return $this->getUrl()->load('badHtml')->getContent()
            ->css('div#eventlist')->xpath('//table//tr/td[2]/a')->asUrl();
    }

    /**
     * @return Record\Collection
     */
    public function getEvents()
    {
        return $this->getUrls()->load('badHtml')->getContent()
            ->css('div#eventlist_details')->asXml()->map(array($this, 'getEvent'));
    }

    /**
     *
     * @param  Xml    $xml
     * @return Record
     */
    public function getEvent(Xml $xml)
    {

        $description = $xml->xpath('.//tr[8]/td')->asXml()->formattedText()->normalizeParagraphs();
        $date = $xml->xpath('./table[@class="details"][1]//tr[2]/td[2]')->asString()->first()->normalizeSpace();
        $title = $xml->xpath('./table[@class="details"][1]//tr[3]/td[2]')->asString()->first()->normalizeSpace();

        return new Record(
            array(
                'title' => $title,
                'date' => $date->asDate('%d.%m.%Y \| %H[:.]%M'),
                #'end' => $date->asDate('%d.%m.%Y \| [^-]+- %H.%M'),
                'location' => $this->getLocation(),
                'type' => $this->getType(),
                'description' => $description,
                'url' => $xml->getBaseUri(),
            )
        );
    }

}
