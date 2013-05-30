<?php

namespace Org\Gucken\Events\Domain\Model\EventSource;

use Org\Gucken\Events\Domain\Model\EventSource\AbstractEventSource,
	Org\Gucken\Events\Domain\Model\EventSource\EventSourceInterface;
use Type\Url,
	Type\Xml;
use Org\Gucken\Events\Annotations as Events,
	TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("prototype")
 */
class Ical extends AbstractEventSource implements EventSourceInterface {

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
	 * @return \Type\Record\Collection
	 */
	public function getEvents() {
		return $this->getUrl()->load()->getContent()
			->getEvents()->filterByDate(\Type\Date::ago(DAY), \Type\Date::in(4 * WEEK))
			->map(array($this,'getEvent'));
	}

	/**
	 *
	 * @param \Type\Calendar\Event $xml
	 * @return \Type\Record
	 */
	public function getEvent(\Type\Calendar\Event $event) {
		return new \Type\Record(array(
				'title' => $event->title(),
				'date' => $event->startDate(),
				'end' => $event->endDate(),
				'location' => $this->getLocation(),
				'type' => $this->getType(),
				'description' => $event->description(),
			));
	}
}

?>