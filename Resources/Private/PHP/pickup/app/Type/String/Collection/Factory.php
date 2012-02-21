<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Type\String\Collection;
use \Type\String;

class Factory {
    /**
     *
     * @param array $strings
     * @return String\Collection
     */
    public static function fromArray($strings) {
        $collection = new String\Collection();
        foreach ($strings as $string) {
            $collection->addOne((string)$string);
        }
        return $collection;
    }

}
?>
