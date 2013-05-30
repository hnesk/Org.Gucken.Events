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
class JoomlaEventListAncient extends AbstractEventSource implements EventSourceInterface {

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
			->css('div#eventlist')->xpath('//table//tr/td[2]/a')->asUrl();
	}

	/**
	 * @return \Type\Record\Collection
	 */
	public function getEvents() {
		return $this->getUrls()->load('badHtml')->getContent()
				->css('div#eventlist_details')->asXml()->map(array($this, 'getEvent'));
	}

	/**
	 *
	 * @param Xml $xml
	 * @return \Type\Record 
	 */
	public function getEvent(Xml $xml) {

		$description = $xml->xpath('.//tr[8]/td')->asXml()->formattedText()->normalizeParagraphs();
		$date = $xml->xpath('./table[@class="details"][1]//tr[2]/td[2]')->asString()->first()->normalizeSpace();
		$title = $xml->xpath('./table[@class="details"][1]//tr[3]/td[2]')->asString()->first()->normalizeSpace();
		return new \Type\Record(array(
			'title' => $title,
			'date' => $date->asDate('%d.%m.%Y \| %H[:.]%M'),
			#'end' => $date->asDate('%d.%m.%Y \| [^-]+- %H.%M'),
			'location' => $this->getLocation(),
			'type' => $this->getType(),
			'description' => $description,
			'url' => $xml->getBaseUri(),
		));
	}

}

?>