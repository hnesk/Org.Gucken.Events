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
class Gecko extends Loader\Http {

    /**
     * proxy url with marker %s for the real url
     * @var string
     */
    protected $proxyUrl;

    /**
     *
     * @param \Zend_Http_Client $client
     * @param Document\Repository $repository
     * @param string $proxyUrl
     */
    public function __construct(\Zend_Http_Client $client, Document\RepositoryInterface $repository, $proxyUrl) {
        parent::__construct($client, $repository);
        $this->proxyUrl = $proxyUrl;
    }

    /**
     *
     * @return string
     */
    public function getProxyUrl() {
        return $this->proxyUrl;
    }

    public function canLoad(Url $url, $options = array()) {
        return
            $url->getScheme() == 'http' &&
            (isset($options['badhtml']) && $options['badhtml']);
    }

    /**
     *
     * @param Url $url
     * @return Document
     */
    public function doLoad(Url $url, $options = array()) {
        $proxyUrl = sprintf($this->getProxyUrl(), rawurlencode((string) $url));
        $client = $this->getClient();
        $client->setUri($proxyUrl);


        $response = $client->request();
        if ($response->isError()) {
            return null;
        }

        $headers = $this->rewriteHeaders($response->getHeaders());

		$metaData = new Metadata\Http(
			new Url($headers['requested-url']), 
			new Url($headers['fetched-url']), 
			\App::instance()->date(), 
			$headers,
			isset($options['metadata'])? $options['metadata'] : array()
		);
		
        return Document\Builder::build(
			$metaData,
            $response->getBody(),
			$options
        );
    }


    protected function rewriteHeaders($responseHeaders) {
        $headers = array();
        foreach ($responseHeaders as $header => $value) {
            if (strpos($header, 'Orig') === 0) {
                $headers[\mb_strtolower(\trim(\substr($header, 4), '- '))] = \trim($value);
            }
        }
        return $headers;
    }
}

?>
