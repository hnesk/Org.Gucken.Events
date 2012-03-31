<?php

namespace Util\Lookup\Strategy;

use Util\Lookup\Strategy;

class Exact implements Strategy {

    /**
     *
     * @param string $value
     * @param array $data
     * @param string $default
     * @return string
     */
    public function lookup($value, $data, $default=false) {
        return isset($data[$value]) ? array($data[$value],1.0) : array($default, 0.0);
    }

}