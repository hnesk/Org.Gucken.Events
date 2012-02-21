<?php
namespace Type\Document\Metadata;

use Type\Document;
use Type\Date;
/**
 * Description of Http
 *
 * @author jk
 */
class Http extends Document\Metadata {

    protected function postConstruct() {
        $this->parseHeaders();
    }
    /**
     * parses the HTTP response headers and fills documentTime, lastModified, contentType, charset
     * @return void
     */
    protected function parseHeaders() {
        $dateParser = new Date\Parser('%d %b %Y %H:%M:%S %Z');
        if ($this->has('date') && $dateParser->match($this->get('date'))) {
            $this->setDocumentTime($dateParser->getDate());
        }

        if ($this->has('last-modified') && $dateParser->match($this->get('last-modified'))) {
            $this->setUpdatedTime($dateParser->getDate());
        }

        if ($this->has('content-type'))  {
            $this->setByMimeType($this->get('content-type'));
        }
        
        if ($this->has('content-length'))  {
            $this->setSize($this->get('content-length'));
        }

        if ($this->has('set-cookie'))  {
            $this->setCookie($this->get('set-cookie'));
        }
    }

    public function getCookie() {
        return $this->get('cookie');
    }

    public function setCookie($value) {
        return $this->set('cookie',$value);
    }

}
?>
