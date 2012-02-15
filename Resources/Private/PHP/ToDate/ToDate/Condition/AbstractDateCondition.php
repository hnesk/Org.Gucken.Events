<?php

namespace ToDate\Condition;

class AbstractDateCondition {
	
	
	protected static function toArray($stringOrArray) {
		if (is_array($stringOrArray)) {
			return $stringOrArray;
		} else {
			$result = array();
			$parts = array_map('trim', explode(',', $stringOrArray));
			foreach ($parts as $part) {				
				if (preg_match('/([^-]+)-([^-]+)/',$part, $matches)) {
					$start = $matches[1];
					$end = $matches[2];
					if (!is_numeric($start) || !is_numeric($end)) {
						throw new \InvalidArgumentException('"'.$part.'" has to  be an numeric range');
					} else {
						if ($start > $end) {
							list($start, $end) = array($end, $start);
						}
						for ($t = $start; $t <= $end; $t++) {
							$result[] = $t;
						}
					}
				} else {
					$result[] = $part;
				}
			}
			return $result;
		} 
	} 
	
	
	public function __invoke(\DateTime $date) {
		return $this->contains($date);
	}
	
	/**
	 *
	 * @param \DateTime $date
	 * @return \DateTime 
	 */
	protected static function normalizeDate(\DateTime $date, $modify = '') {
		$result = clone $date;
		$result->setTime(0,0,0);
		if ($modify) {
			$result->modify($modify);
		}
		return $result;
	}

	
	public function __toString() {
		return 'Never';
	}
	
}

?>
