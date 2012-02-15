<?php

namespace ToDate\Iterator;

class DayIterator extends AbstractDateRangeIterator implements \Iterator {
	
	
	protected function doNext() {
		$this->current->modify('+1 day');
	}
}

?>
