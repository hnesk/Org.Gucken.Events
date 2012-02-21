<?php
namespace Type\Document;

use Type\Document;
use Type\Url;

/**
 * Description of Loader
 *
 * @author jk
 */
interface Loader {
    /**
     * @param Url $url
     * @param $options
     * @return Document
     */
    public function load(Url $url,$options = array());

    /**
     * @param Url $url
     * @param $options
     * @return boolean
     */
    public function canLoad(Url $url,$options = array());

}
?>
