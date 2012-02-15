<?php

namespace ToDate\Condition;

class FeatureInSetCondition extends AbstractDateCondition implements DateConditionInterface  {

	/**
	 * @var array
	 */
	protected $set;
	
	/**
	 *
	 * @var string
	 */
	protected $features;
	
	/**
	 * All allowed date related format strings 
	 * @see http://www.php.net/manual/en/function.date.php	  
	 */
	const ALLOWED_DATE_FEATURES = 'dDjlNSwzWFmMntLoYyeIOPTZ';
	
	/**
	 *
	 * @param string $features			Date format characters for php date()
	 * @param int|string|array $set		Allowed value(s) for feature
	 * @throws \InvalidArgumentException 
	 */
	public function __construct($features, $set) {
		if (!preg_match('/['.self::ALLOWED_DATE_FEATURES.']+/', $features)) {
			throw new \InvalidArgumentException('Features need to be one or more of "'.self::ALLOWED_DATE_FEATURES.'", but  "'.$features.'" given');
		}
		$this->features = $features;
		$this->set = array_flip(self::toArray($set));
	}
	
	/**
	 *
	 * @param \DateTime $date
	 * @return boolean
	 */
	public function contains(\DateTime $date) {
		return isset($this->set[$date->format($this->features)]);
	}
	
	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return  self::formatSet('"'.$this->features.'"', $this->set);
	}	
	
	protected static function formatSet($features, $set) {
		return $features.' = '.implode(',', array_flip($set));
	}
}

?>