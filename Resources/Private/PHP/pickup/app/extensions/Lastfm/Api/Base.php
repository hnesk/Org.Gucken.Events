<?php

namespace Lastfm\Api;

abstract class Base {

    const URL = 'http://ws.audioscrobbler.com/2.0/';

    /**
     *
     * @var string
     */
    protected $key = null;


    /**
     *
     * @var \Zend_Http_Client
     */
    protected $httpClient = null;

    /**
     *
     * @param string $key the last.fm api key
     */
    public function __construct($key, $httpClient = null) {
        $this->key = $key;
        $this->httpClient = $httpClient;
    }

    protected abstract function getPrefix();

    protected function getDefaultParameters() {
        return array();
    }

    /**
     *
     * @param \Zend_Http_Client $httpClient
     * @return Lastfm\Api\Base
     */
    public function setHttpClient(\Zend_Http_Client $httpClient) {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     *
     * @return \Zend_Http_Client
     */
    public function getHttpClient() {
        return $this->httpClient ?: new \Zend_Http_Client(NULL, array('timeout'=>10));
    }

    /**
     *
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     *
     * @param string $key
     * @return Base
     */
    public function setKey($key) {
        $this->key = $key;
        return $this;
    }

    
    /**
     *
     * @param array $parameters
     * @param int $backTraceLevel
     */
    protected function getReflectedParameters(array $arguments,  $backTraceLevel = 1) {
        $backTrace = \debug_backtrace();
        $function = $backTrace[$backTraceLevel]['function'];
        $class = $backTrace[$backTraceLevel]['class'];
        $reflectionMethod = new \ReflectionMethod($class, $function);
        
        $parameters = array();
        foreach ($reflectionMethod->getParameters() as $position => $parameter) {
            /* @var $parameter \ReflectionParameter */
            if (isset ($arguments[$position])) {
                $parameters[$parameter->getName()] = $arguments[$position];
            } else {
                if ($parameter->isDefaultValueAvailable()) {
                    $parameters[$parameter->getName()] = $parameter->getDefaultValue();
                }
            }
        }
        return $parameters;
    }

    /**
     *
     * @param string $method
     * @param array $parameters
     * @return \SimpleXMLElement
     */
    protected function callMethod($method, $parameters=array()) {
        $parameters = \array_merge($parameters, $this->getDefaultParameters());
        $parameters['method'] = $this->getPrefix().'.'.$method;
        if ($this->key) {
            $parameters['api_key'] = $this->key;
        }
        foreach ($parameters as $key => $value) {
            if (\is_null($value)) {
                unset($parameters[$key]);
            }
        }
        $url = self::URL.'?'.\http_build_query($parameters);


        $httpClient = $this->getHttpClient()->setUri($url);
        $response = $httpClient->request();
        if ($response->isError()) {
            throw new HttpException($response);
        }

        $result = \simplexml_load_string($response->getBody());
                
        return $result;
        
    }
}
?>
