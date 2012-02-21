<?php

namespace Util\Lookup\Source;

use Util\Lookup\Source;
use Type\Record;

class RecordCollection implements Source {

    /**
     *
     * @var \Type\Record\Collection
     */
    protected $data = array();

    /**
     *
     * @var array
     */
    protected $valueColumns = array();

    /**
     *
     * @param array $data
     */
    public function __construct(Record\Collection $data, $valueColumns = '*') {
        $this->data = $data;
        if (is_string($valueColumns)) {
            if ($valueColumns === '*') {
                $this->valueColums = $data->current()->keys();
            } else {
                $this->valueColumns = explode(',', $valueColumns);
            }
        } else if (is_array($valueColumns)) {
            $this->valueColumns = array_map('trim', $valueColumns);
        } else {
            throw new \InvalidArgumentException('$valueColumns ("' . print_r($valueColumns, 1) . '") is not a valid column definition');
        }
    }

    /**
     *
     * @param string $value
     * @return array
     */
    public function getData($value='') {
        $lookup = array();
        foreach ($this->data as $key => $record) {
            /* @var $record Record */
            foreach ($this->valueColumns as $valueColumn) {
                $lookup[(string) $record->get($valueColumn)] = $key;
            }
        }
        return $lookup;
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