<?php
namespace Type\Document;
use \Type\Document;
/**
 * Description of Feed
 *
 * @author jk
 */
class Ical extends Document {

    /**
     *
     * @var Type\Feed
     */
    protected $calendar;

    /**
     *
     * @param array $options
     * @return \Type\Feed
     */
    public function getContent($options=array()) {
        if (!$this->calendar) {
            try {
                $this->calendar = \Type\Calendar\Factory::fromString($this->getRawContent(),$options);
            } catch (Exception $e) {
                throw new Exception('Can\'t parse calendar'.$this->getRawContent());
            }
         }
        return $this->calendar;
    }
}
?>
