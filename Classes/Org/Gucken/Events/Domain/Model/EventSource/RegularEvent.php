<?php

namespace Org\Gucken\Events\Domain\Model\EventSource;

use Type\Url;
use Org\Gucken\Events\Domain\Model;
use Org\Gucken\Events\Annotations as Events;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("prototype")
 */
class RegularEvent implements EventSourceInterface {


	/**
	 * @Events\Configurable
	 * @var \Org\Gucken\Events\Domain\Model\Event
	 */
	protected $baseEvent;
	

	/**
	 * @Events\Configurable
	 * @Flow\Validate(type="\Org\Gucken\Events\Domain\Validator\DateConditionValidator")
	 * @Flow\Validate(type="NotEmpty")
	 * @var \ToDate\Condition\AbstractDateCondition
	 */
	protected $dateCondition;
	

	/**
	 * @param Model\Event $event
	 */
	public function setBaseEvent($baseEvent) {
		
		$this->baseEvent = $baseEvent;
	}

	/**
	 *
	 * @return Model\Event
	 */
	public function getBaseEvent() {
		return $this->baseEvent;
	}

	/**
	 *
	 * @return \ToDate\Condition\AbstractDateCondition
	 */
	public function getDateCondition() {
		return $this->dateCondition;
	}
	
	/**
	 * @param \ToDate\Condition\AbstractDateCondition $dateCondition 
	 */
	public function setDateCondition(\ToDate\Condition\AbstractDateCondition $dateCondition) {
		$this->dateCondition = $dateCondition;
	}

	/**
	 *
	 * @param Model\EventFactoidIdentity $factoidIdentity
	 * @param \Org\Gucken\Events\Domain\Model\EventLink if set link will be updated else created
	 * @return \Org\Gucken\Events\Domain\Model\LastFmEventLink 
	 */
	public function convertLink(Model\EventFactoidIdentity $factoidIdentity, $link = null) {
		$link = $link ? : new Model\RegularEventLink();
		$link->setUrl($this->getBaseEvent()->getUrl());
		return $link;
	}		
	
	/**
	 *
	 * @return \ToDate\Iterator\AbstractDateRangeIterator
	 */
	public function getDateIterator($days = 50) {
		$now = new \DateTime();
		$later = clone $now;
		$later->modify($days.' days');		
		return  new \ToDate\Iterator\ConditionIterator(new \ToDate\Iterator\DayIterator($now, $later), $this->getDateCondition());
	}
	
		
	/**
	 * @return \Type\Record\Collection
	 */
	public function getEvents() {
		$result = new \Type\Record\Collection();
		foreach ($this->getDateIterator() as $date) {
			$result->addOne($this->getEvent($date));
		}
		return $result;
	}

	/**
	 *
	 * @param \DateTime $date
	 * @return \Type\Record 
	 */
	public function getEvent($date) {
		$thisDate = clone $date;
		$baseDate = $this->getBaseEvent()->getStartDateTime();
		$thisDate->setTime($baseDate->format('H'), $baseDate->format('i'), $baseDate->format('s'));
		
		return new \Type\Record(array(
			'title' => $this->baseEvent->getTitle(),
			'date' => $thisDate,
			'short' => (string) $this->baseEvent->getShortDescription(),
			'description' => $this->baseEvent->getDescription(),
			'type' => $this->baseEvent->getTypes()->first(),
			'location' => $this->baseEvent->getLocation(),
			'url' => $this->baseEvent->getUrl(),
			#'proof' => $this->baseEvent
		));
	}

}

?>
