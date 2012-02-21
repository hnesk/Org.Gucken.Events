<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Lastfm\Type\User\Collection;
use Lastfm\Type\User;
use Type\Xml;

class Factory {

    /**
     *
     * @param \Type\Xml $xml
     * @return User\Collection
     */
    public static function fromTypeXml(Xml $xml) {
        $users = new User\Collection();
        foreach ($xml->css('user') as $userXml) {
            $users->addOne(User\Factory::fromTypeXml($userXml));
        }
        return $users;
    }

    /**
     *
     * @param \SimpleXMLElement $xml
     * @return User\Collection
     */
    public static function fromSimpleXmlElement(\SimpleXMLElement $xml) {
        return self::fromTypeXml(\Type\Xml\Factory::fromSimpleXml($xml));
    }


}
?>
