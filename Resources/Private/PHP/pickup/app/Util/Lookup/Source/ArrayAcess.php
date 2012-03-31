<?php

namespace Util\Lookup\Source;

use Util\Lookup\Source;

class ArrayAccess implements Source {
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
		return array_flip($this->data);
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