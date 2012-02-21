<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Facebook;

class Injector {
    /**
     *
     * @return \Facebook\Api
     */
    public static function injectFacebookApi() {
        return new \Facebook\Api(
            self::injectFacebookConfig(),
            \App\Injector::injectRegistry()
        );
    }




    /**
     * @return string
     */
    public static function injectFacebookConfig() {
        return array(
          'appId'  => '251646161514029',
          'secret' => '8378329a6f68c5d75310cfb33885b501',            
        );
    }



}

?>
