<?php
namespace Util\Lookup;

interface Source {
    /**
     * @param \Type $value
     * @return \Type
     */
    public function getData($value='');

    /**
     * @param string $key
     * @return \Type\Record
     */
    public function getRecord($key);
}