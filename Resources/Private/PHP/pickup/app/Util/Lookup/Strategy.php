<?php

namespace Util\Lookup;

interface Strategy {

    /**
     *
     * @param string $value
     * @param array $data
     * @param string  $default
     * @return string
     */
    public function lookup($value, $data);
}