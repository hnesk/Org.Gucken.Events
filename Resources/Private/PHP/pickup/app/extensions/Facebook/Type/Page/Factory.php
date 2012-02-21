<?php
namespace Facebook\Type\Page;
use \Facebook\Type\Page;

class Factory {

    /**
     *
     * @param array $data
     * @return \Facebook\Type\Page 
     */
    public static function fromArray($data) {
        return new Page($data['id'], $data['name'], $data['category']);
    }

    

}


?>
