<?php
namespace Type\Record;
use \Type;

/**
 * Record\Collection: A type-safe array for String objects
 *
 * @author jk
 */
class Collection extends \Type\BaseCollection {
    protected $itemDataType = '\Type\Record';

    public function getNativeValue() {
        $data = array();
        $this->each(function (\Type\Record $record) use(&$data) {
            $data[] = $record->getNativeValue();
        });
        return $data;
    }
}
?>
