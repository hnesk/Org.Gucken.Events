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
class Factory {

    /**
     *
     * @var array
     */
    protected $loaders = array();
    
    public function __construct() {
        $this->loaders = array();
        foreach (\func_get_args() as $loader) {
            $this->registerLoader($loader);
        }
    }


    /**
     *
     * @param string $key
     * @param Loader $loader
     */
    public function registerLoader(Loader $loader) {
        $this->loaders[] = $loader;
    }

    /**
     *
     * @param Url $url
     * @param array $options
     * @return Document
     */
    public function load(Url $url,$options=array()) {
        $options = \Util\Options::factory($options);
        $loader = $this->get($url, $options);
        return $loader->load($url,$options);
    }

    /**
     *
     * @param Url $url
     * @param array $options
     * @return Loader
     */
    public function get(Url $url,$options=array()) {
        foreach ($this->loaders as $loader) {
            /* @var $loader Loader */
            if ($loader->canLoad($url,$options)) {
                return $loader;
            }
        }
        throw new \RuntimeException('No suitable loader found for '.$url);
    }

}
?>
