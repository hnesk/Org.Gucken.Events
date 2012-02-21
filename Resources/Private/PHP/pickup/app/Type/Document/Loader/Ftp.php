<?php
namespace Type\Document\Loader;

use Type\Url;
use Type\Document;
use Type\Document\Metadata;
use Type\Document\Loader;
/**
 * Description of File
 *
 * @author jk
 */
class Ftp implements Loader {

    public function canLoad(Url $url, $options = array()) {
        return $url->getScheme() == 'ftp';
    }

    /**
     *
     * @param Url $url
     * @return Document
     */
    public function load(Url $url, $options = array()) {
		throw new \LogicException('not implemented yet');
    }
}
?>
