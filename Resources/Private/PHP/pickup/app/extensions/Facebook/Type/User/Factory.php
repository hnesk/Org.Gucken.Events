<?php
namespace Facebook\Type\User;
use \Facebook\Type\User;

class Factory {
    /**
     *
     * @param array $array
     * @return User
     */
    public function fromArray($array) {
        return self::fromInt($array['id']);
    }


    /**
     *
     * @param \Type\Number $number
     * @return User
     */
    public function fromTypeNumber(\Type\Number $number) {
        return self::fromInt((int)(string)$number);
    }

    /**
     *
     * @param int $number
     * @return User
     */
    public function fromInt($number) {
        return new User((int)(string)$number, \Facebook\Injector::injectFacebookApi());
    }

   /**
     *
     * @param int $number
     * @return User
     */
    public function fromString($id) {
        return new User((string)$id, \Facebook\Injector::injectFacebookApi());
    }

  /**
     *
     * @param int $number
     * @return User
     */
    public function fromTypeString($id) {
        return new User((string)$id, \Facebook\Injector::injectFacebookApi());
    }

    

}


?>
