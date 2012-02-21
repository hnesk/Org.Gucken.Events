<?php
namespace Type\Document;
use \Type\Document;
/**
 * Description of Html
 *
 * @author jk
 */
class Json extends Document {

    /**
     *
     * @var \Type\Json;
     */
    protected $json;
    
    /**
     * @autocomplete
     * @param array $options
     * @return \Type\Json
     */
    public function getContent($options = array()) {
        if (!$this->json) {
            $this->json = \Type\Json\Factory::fromString($this->getRawContent());
        }
        return $this->json;
    }
}
?>
