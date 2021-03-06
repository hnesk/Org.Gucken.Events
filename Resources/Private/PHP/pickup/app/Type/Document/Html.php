<?php
namespace Type\Document;
use \Type\Document;
/**
 * Description of Html
 *
 * @author jk
 */
class Html extends Document {

    /**
     *
     * @var \Type\Xml;
     */
    protected $xml;
    
    /**
     * @autocomplete
     * @param array $options
     * @return \Type\Xml
     */
    public function getContent($options = array()) {
        if (!$this->xml) {
            $this->xml = \Type\Xml\Factory::fromHtmlString(
                $this->getRawContent(),
                isset($options['defaultNamespace']) ? $options['defaultNamespace'] : 'default',
                $this->getMeta()->getFetchedUrl(),
                $options
            );
        }
        return $this->xml;
    }


}
?>
