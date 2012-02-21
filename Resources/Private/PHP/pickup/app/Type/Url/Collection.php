<?php
namespace Type\Url;
use \Type;

/**
 * Url\Collection: A type-safe array for Url objects
 *
 * @author jk
 */
class Collection extends \Type\BaseCollection {
    protected $itemDataType = '\Type\Url';


    /**
     *
     * @return \Type\Document\Collection
     */
    public function load($options = array()) {
        return $this->map(function (\Type\Url $url) use($options) {
            return $url->load($options);
        },'\Type\Document\Collection');
    }

    /**
     *
     * @return \Type\Document\Collection
     */
    public function loadBadHtml($options = array()) {
        $options['badhtml'] = true;
        return $this->load();
    }


}
?>
