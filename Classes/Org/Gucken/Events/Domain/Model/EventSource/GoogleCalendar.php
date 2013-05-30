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
class GoogleCalendar extends Ical implements EventSourceInterface {

	/**
	 * @Events\Configurable
	 * @var string
	 */
	protected $calendar;

	/**
	 *
	 * @param string $calendar
	 */
	public function setCalendar($calendar) {
		$this->calendar = $calendar;
	}

	public function getUrl() {
		return 'http://www.google.com/calendar/ical/'.  rawurlencode($this->calendar).'/public/basic.ics';
	}


}

?>