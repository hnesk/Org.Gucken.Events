<?php
namespace Type\Number;
use \Type;
use \Type\Number;

/**
 * String\Collection: A type-safe array for String objects
 *
 * @author jk
 */
class Range extends Number\Collection {
    protected $itemDataType = '\Type\Number';

    public function __construct($start,$end, $step = 1) {
        $current = (string)$start;
        $end = (string)$end;
        $step = (string)$step;
        while($current <= $end) {
            $this->addOne($current);
            $current += $step;
        }
    }
}
?>
