<?php
namespace Type;
/**
 * Description of Calendar
 *
 * @author jk
 */
class Calendar extends \Type\Base {

    /**
     *
     * @var \SG_iCal
     */
    protected $calendar;

    public function __construct($content) {
		$this->calendar = new \SG_iCal();
		\SG_iCal_Parser::ParseString($content, $this->calendar);
    }

	/**
	 *
	 * @return SG_iCal_VCalendar
	 */
	public function getInformation() {
		return $this->calendar->getCalendarInfo();
	}

    /**
     *
     * @return Calendar\Event\Collection
     */
    public function getEvents() {
        $collection = new Calendar\Event\Collection();
        foreach ($this->calendar->getEvents() as $event) {
            /* @var $event \SG_iCal_VEvent */
            $collection->addOne(new \Type\Calendar\Event($event));
        }
        return $collection;
    }

}
?>
