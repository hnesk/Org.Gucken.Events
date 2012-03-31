<?php
namespace Type\Xml;

use \Type\Xml;

/**
 * Description of Xml\Collection
 *
 * @author jk
 */
class Collection extends \Type\BaseCollection {
    protected $itemDataType = '\Type\Xml';

    /**
     *
     * @param string $xpath
     * @return \Type\Xml\XPath\Collection
     */
    public function xpath($xpath) {
        return $this->mapItem('xpath', array($xpath), '\Type\Xml\XPath\Collection');
    }
    /**
     *
     * @param string $selector
     * @return \Type\Xml\XPath\Collection
     */
    public function css($selector) {
        return $this->mapItem('css', array($selector), '\Type\Xml\XPath\Collection');
    }

    /**
     *
     * @param string $selector
     * @return \Type\Xml\XPath\Collection
     */
    public function id($id) {
        return $this->mapItem('id', array($id), '\Type\Xml\XPath\Collection');
    }


    /**
     *
     * @param string $rootEnclosingTag
     * @param string $elementEnclosingTag
     * @return Xml
     */
    public function join($rootEnclosingTag = 'div', $elementEnclosingTag = null) {
        $content = '';
        foreach ($this->elements as $element) {
            /* @var $element Xml */
            if ($elementEnclosingTag) {
                $content .= '<'.$elementEnclosingTag.'>'.$element->asXmlString().'</'.$elementEnclosingTag.'>';
            } else {
                $content .= $element->asXmlString();
            }
        }
        return Xml\Factory::fromXmlString('<'.$rootEnclosingTag.'>'.$content.'</'.$rootEnclosingTag.'>');
    }


}
?>
