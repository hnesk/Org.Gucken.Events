<?php
namespace Type\Document\Metadata;

use Type\Document\Metadata;
use Type\Url;

/**
 * Description of Builder
 *
 * @author jk
 */
class Builder  {

    /**
     *
     * @param array $data
     * @return Document\Metadata;
     */
    public static function build($data,$options=array()) {
        $url = new Url($data['requested-url']);
        $metaDataClass = '\\Type\\Document\\Metadata\\'.\ucfirst($url->getScheme());

        return new $metaDataClass(
                $url,
                new Url($data['fetched-url']),
                $data['local-time'],
                $data,
                isset($options['metadata'])? $options['metadata'] : array()
        );
    }
}
?>
