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
class File implements Loader {

    public function canLoad(Url $url, $options = array()) {
        return $url->getScheme() == 'file';
    }

    /**
     *
     * @param Url $url
     * @return Document
     */
    public function load(Url $url, $options = array()) {
        $fetched = 'file://'.\realpath($url->getPath());

        if (!\file_exists($fetched) || !\is_readable($fetched)) {
            return null;
        }
		
		
        $metaData = new Metadata\File(
                $url, 
                $fetched, 
                \App::instance()->date(), 
                array(),
                isset($options['metadata'])? $options['metadata'] : array()
        );

        return Document\Builder::build(
            $metaData,
            \file_get_contents($fetched),
			$options
        );

    }
}
?>
