<?php
namespace Type\Document\Loader;

use Type\Url;
use Type\Document;
use Type\Document\Metadata;
use Type\Document\Loader;
/**
 * Description of Http
 *
 * @author jk
 */
class Http implements Loader {

    /**
     *
     * @var \Zend_Http_Client
     */
    protected $client;

    /**
     *
     * @var Document\RepositoryInterface
     */
    protected $repository;


    /**
     * 
     * @param \Zend_Http_Client $client
     */
    public function __construct(\Zend_Http_Client $client, Document\RepositoryInterface $repository) {
        $this->client = $client;
        $this->repository = $repository;
    }

    /**
     *
     * @return \Zend_Http_Client
     */
    public function getClient() {
        return $this->client;
    }

    /**
     *
     * @return Document\Repository
     */
    public function getRepository() {
        return $this->repository;
    }



    /**
     *
     * @param Url $url
     * @param array $options
     * @return boolean
     */
    public function canLoad(Url $url, $options = array()) {
        return 
            ($url->getScheme() == 'http' || $url->getScheme() == 'https') &&
            !(isset($options['badhtml']) && $options['badhtml']);
    }

    /**
     *
     * @param Url $url
     * @param $options
     * @return Document
     */
    public function load(Url $url, $options = array()) {
        $maxAge = isset($options['maxAge']) ? $options['maxAge'] : 7200;


        if (!$document = $this->repository->retrieveLatestByUrl($url,$maxAge,$options)) {
            $document = $this->doLoad($url,$options);
            $this->repository->store($document);
        } 
        return $document;
    }


    protected function doLoad(Url $url, $options = array()) {
        
        $client = $this->getClient();
        $client->setUri((string)$url);

        if (isset($options['headers'])) {
            $client->setHeaders($options['headers']->toArray());
        }

        $response = $client->request();
        if ($response->isError()) {
            return null;
        }

        #echo $client->getLastRequest();

        $fetched = $client->getUri();
        $fetched->setPort($fetched->getPort() != '80' ? $fetched->getPort() : '');               

        $metaData = new Metadata\Http(
                $url, 
                $fetched, 
                \App::instance()->date(), 
                $response->getHeaders(),
                isset($options['metadata'])? $options['metadata'] : array()
        );


        return Document\Builder::build(
            $metaData,
            $response->getBody(),
            $options
        );
    }
}
?>