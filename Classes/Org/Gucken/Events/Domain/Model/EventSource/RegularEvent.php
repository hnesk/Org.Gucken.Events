<?php

namespace Org\Gucken\Events\Domain\Model\EventSource;

use Type\Record;
use Type\Url;
use Org\Gucken\Events\Domain\Model;
use Org\Gucken\Events\Annotations as Events;
use TYPO3\Flow\Annotations as Flow;

use ToDate\Condition\AbstractDateCondition;
use ToDate\Condition\DateConditionInterface;
use ToDate\Iterator\ConditionIterator;
use ToDate\Iterator\DayIterator;


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
	 * @var DateConditionInterface
	 */
	protected $dateCondition;


    /**
     * @param Model\Event $baseEvent
     */
	public function setBaseEvent(Model\Event $baseEvent) {
		
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
	 * @return AbstractDateCondition
	 */
	public function getDateCondition() {
		return $this->dateCondition;
	}
	
	/**
	 * @param AbstractDateCondition $dateCondition
	 */
	public function setDateCondition(AbstractDateCondition $dateCondition) {
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
     * @param int $days
     * @return \ToDate\Iterator\AbstractDateRangeIterator
     */
	public function getDateIterator($days = 50) {
		$now = new \DateTime();
		$later = clone $now;
		$later->modify($days.' days');
		return  new ConditionIterator(new DayIterator($now, $later), $this->getDateCondition());
	}
	
		
	/**
	 * @return \Type\Record\Collection
	 */
	public function getEvents() {
		$result = new Record\Collection();
		foreach ($this->getDateIterator() as $date) {
			$result->addOne($this->getEvent($date));
		}
		return $result;
	}

	/**
	 *
	 * @param \DateTime $date
	 * @return Record
	 */
	public function getEvent($date) {
		$thisDate = clone $date;
		$baseDate = $this->getBaseEvent()->getStartDateTime();
		$thisDate->setTime($baseDate->format('H'), $baseDate->format('i'), $baseDate->format('s'));
		
		return new Record(array(
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
