<?php

namespace Type\Calendar;
use \Type\String;


/**
 *
 * @author Johannes Künsebeck <kuensebeck@googlemail.com>
 */
class Event extends \Type\Base {

    /**
     *
     * @var \SG_iCal_VEvent
     */
    protected $event;

	/**
	 *
	 * @param \SG_iCal_VEvent $event
	 */
    public function __construct(\SG_iCal_VEvent $event) {
        $this->event = $event;
    }

    /**
     * @return \Type\String
     */
    public function summary() {
		return new \Type\String($this->event->getSummary());
    }

    /**
     * @return \Type\String
     */
    public function title() {
		return $this->summary();
    }

    /**
     * @return \Type\String
     */
    public function description() {
        return new \Type\String($this->event->getDescription());
    }

    /**
	 * alias for description
     * @return \Type\String
     */
    public function content() {
        return $this->description();
    }

    /**
     * @return \Type\Date
     */
    public function startDate() {
        return new \Type\Date($this->event->getStart());
    }

    /**
     * @return \Type\Date
     */
    public function endDate() {
        return new \Type\Date($this->event->getEnd());
    }


    /**
     * @return \Type\String
     */
    public function id() {
        return new \Type\String($this->event->getUID());
    }

    /**
     *
     * @return mixed
     */
    public function getNativeValue() {
        return $this->event;
    }

	/**
	 *
	 * @return string
	 */
    public function __toString() {
        return (string)$this->startDate()->format().' '.$this->title();
    }

}
?>