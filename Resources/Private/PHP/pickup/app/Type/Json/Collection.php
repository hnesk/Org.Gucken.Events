<?php 
namespace Type\Json;

use \Type\Json;

/**
 * Description of Json\Collection
 *
 * @author jk
 */
class Collection extends \Type\BaseCollection {
    protected $itemDataType = '\Type\Json';

    /**
     *
     * @param string $jpath
     * @return \Type\Json\Collection
     */
    public function jpath($jpath) {
        return $this->mapItem('jpath', array($jpath), '\Type\Json\Collection');
    }

    /**
     *
     * @param string $rootEnclosingTag
     * @param string $elementEnclosingTag
     * @return Xml
     */
    public function join($elementEnclosingTag = 'item') {
        $content = array();
        foreach ($this->elements as $k=> $element) {
            /* @var $element Json */
            $content[$k] = $element;
        }
        return Json\Factory::fromArray($content);
    }


}
?>
