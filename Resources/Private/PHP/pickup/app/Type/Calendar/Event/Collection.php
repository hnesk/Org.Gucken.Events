<?php
namespace Type\Calendar\Event;
use \Type;

/**
 * Feed\Item\Collection: A type-safe array for Feed Item objects
 *
 * @author jk
 */
class Collection extends \Type\BaseCollection {
    protected $itemDataType = '\Type\Calendar\Event';

	public function filterByDate(\Type\Date $start = null,\Type\Date $end = null) {
		return $this->filter(
			function (\Type\Calendar\Event $event) use ($start, $end) {
				return
					(!$start || $event->startDate()->isAfter($start)) &&
					(!$end || $event->startDate()->isBefore($end));
			}
		);
	}

}
?>
