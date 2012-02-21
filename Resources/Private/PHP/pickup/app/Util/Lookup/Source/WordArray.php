<?php

namespace Util\Lookup\Source;

use Util\Lookup\Source;


class WordArray implements Source {
	/**
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 *
	 * @param array $data
	 */
	public function __construct($data) {
		$this->data = $data;
	}

	/**
	 *
	 * @param string $value
	 * @return array
	 */
	public function getLookupData($value='') {
		$result = array();
		foreach ($this->data as $key => $values) {
			if (!is_array($values)) {
				$values = array_filter(preg_split('#[^\wÄÖÜäöüß]#',strtolower(trim($values))));
			}
			foreach ($values as $value) {
				$result[$value] = $key;
			}
		}
		return $result;
	}

	/**
	 *
	 * @param string $value
	 * @return array
	 */
	public function getRecord($key) {
		return $this->data[$key];
	}


}