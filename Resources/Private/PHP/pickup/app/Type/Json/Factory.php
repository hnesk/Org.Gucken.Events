<?php
namespace Type\Json;
/**
 * Description of Factory
 *
 * @author jk
 */
class Factory {
    /**
     * @param \Type\Xml $content Xml
     * @return \Type\Json
     */
    public static function fromTypeXml($content) {
        #return new \Type\Json($content);
    }

    /**
     * @param string $content Xml
     * @return \Type\Json
     */
    public static function fromString($content) {
        return new \Type\Json((array)\json_decode($content));
    }
    /**
     * @param array $content Xml
     * @return \Type\Json
     */
    public static function fromArray($content) {
        return new \Type\Json($content);
    }

}
?>
