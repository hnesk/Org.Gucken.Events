<?php
namespace Type\Calendar;
/**
 * Description of Factory
 *
 * @author jk
 */
class Factory {

    /**
     * @param string $content Xml
     * @return \Type\Calendar
     */
    public static function fromString($content) {
        return new \Type\Calendar($content);
    }

}
?>
