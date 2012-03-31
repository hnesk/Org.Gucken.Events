<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Lastfm\Type\User;
use Lastfm\Type\User;
use Type\Xml;
use Type\String;

class Factory {
    /**
     *
     * @param string $user
     * @return User
     */
    public static function fromString($username) {
        return new User(
            $username,
            \Lastfm\Injector::injectLastfmUserApi()
        );
    }
  
    /**
     *
     * @param String $user
     * @return \Lastfm\Type\User
     */
    public static function fromTypeString(String $username) {
        return self::fromString((string)$username);
    }


    /**
     *
     * @param Xml $xml
     * @return \Lastfm\Type\User
     */
    public static function fromTypeXml(Xml $xml) {
        return self::fromTypeString($xml->css('name')->asString()->first());
    }


}

?>
