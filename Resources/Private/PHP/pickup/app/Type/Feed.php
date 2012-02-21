<?php
namespace Type;
/**
 * Description of Feed
 *
 * @author jk
 */
class Feed extends \Type\Base {

    /**
     *
     * @var \Zend_Feed_Abstract
     */
    protected $feed;

    public function __construct(Xml $xml) {
        $this->feed = \Zend_Feed_Reader::importString($xml->asXmlString());
    }

    /**
     *
     * @return Feed\Item\Collection
     */
    public function getItems() {
        $collection = new Feed\Item\Collection();
        foreach ($this->feed as $item) {
            /* @var $item \Zend_Feed_Reader_EntryAbstract */
            $collection->addOne(new Feed\Item($item));
        }
        return $collection;
    }
    
}
?>
