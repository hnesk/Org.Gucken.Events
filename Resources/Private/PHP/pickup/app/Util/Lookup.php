<?php

namespace Util;

use Util\Lookup;
use Util\Lookup\Strategy;
use Util\Lookup\Source;

class Lookup {

    /**
     * @var \Util\Lookup\Strategy
     */
    protected $strategy;
    /**
     *
     * @var \Util\Lookup\Source
     */
    protected $source;

    /**
     *
     * @param \Util\Lookup\Source  $source
     * @param \Util\Lookup\Strategy $strategy
     */
    public function __construct($source, $strategy=null) {
        if ($source instanceof Source) {
            $this->source = $source;
        } else if (is_array($source) || (is_object ($source) && $source instanceof ArrayAccess)) {
            $this->source = new Source\ArrayAccess($source);
        } else {
            throw new InvalidArgumentException('$source is not a \Util\Lookup\Source or an array', 1312072034);
        }

        if ($strategy instanceof Strategy) {
            $this->strategy = $strategy;
        } else if (is_null($strategy)) {
            $this->strategy = new Strategy\Exact();
        } else {
            throw new InvalidArgumentException('$strategy is not a \Util\Lookup\Strategy', 1312072285);
        }
    }

    /**
     *
     * @param string $value
     * @return string
     */
    public function lookup($value, $relevance = 0.75, $default = false) {
        $data = $this->source->getData($value);
        $lookupResult = $this->strategy->lookup((string)$value, $data);

        return ($lookupResult[1] > $relevance) ? $lookupResult[0] : $default;
    }

    public function lookupRecord($value, $relevance = 0.75, $default=false) {
        $lookup = $this->lookup((string)$value, $relevance);
        return ($lookup !== false) ? $this->source->getRecord($lookup) : $default;
    }

    /**
     *
     * @return \Util\Lookup\Source
     */
    public function getSource() {
        return $this->source;
    }

    /**
     *
     * @return \Util\Lookup\Strategy
     */
    public function getStrategy() {
        return $this->strategy;
    }

}