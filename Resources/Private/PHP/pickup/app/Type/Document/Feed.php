<?php
namespace Type\Document;
use \Type\Document;
/**
 * Description of Feed
 *
 * @author jk
 */
class Feed extends Document {

    /**
     *
     * @var Type\Feed
     */
    protected $feed;

    /**
     *
     * @param array $options
     * @return \Type\Feed
     */
    public function getContent($options=array()) {
        if (!$this->feed) {            
            try {
                $xml = \Type\Xml\Factory::fromXmlString($this->getRawContent(),'default',$this->getMeta()->getFetchedUrl(),$options);
                $this->feed = new \Type\Feed($xml);
            } catch (Exception $e) {
                throw new Exception('Can\'t parse feed '.$this->getRawContent());
            }
         }
        return $this->feed;
    }
}
?>
