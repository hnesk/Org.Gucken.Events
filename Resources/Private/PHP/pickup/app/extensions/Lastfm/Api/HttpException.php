<?php
namespace Lastfm\Api;

class HttpException extends \RuntimeException {
    /**
     *
     * @var \Zend_Http_Response
     */
    protected $response;

    /**
     *
     * @param \Zend_Http_Response $response
     */
    public function __construct(\Zend_Http_Response $response) {        
        parent::__construct($response->getMessage(), $response->getStatus());
        $this->response = $response;
    }

    public function getResponse() {
        return $this->response;
    }


}

?>
