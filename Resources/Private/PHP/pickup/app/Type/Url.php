<?php

namespace Type;

/**
 * An Url Class
 *
 * @author jk
 */
class Url extends \Type\Base {
    const SCHEME = 1;
    const HOST = 2;
    const PORT = 4;
    const USER = 8;
    const PASS = 16;
    const PATH = 32;
    const QUERY = 64;
    const FRAGEMENT = 128;

    const DOMAIN = 31;
    const NOQUERY = 63;
    const NOFRAGMENT = 127;
    const ALL = 255;

    const NODOMAIN = 224; // = PATH + QUERY + FRAGEMENT

    /**
     * @var string
     */
    protected $scheme;
    /**
     * @var string
     */
    protected $host;
    /**
     *
     * @var int
     */
    protected $port;
    /**
     *
     * @var string
     */
    protected $user;

    /**
     *
     * @var string
     */
    protected $pass;

    /**
     *
     * @var string
     */
    protected $path;

    /**
     *
     * @var <type>
     */
    protected $query;
    /**
     *
     * @var string
     */
    protected $fragment;

    /**
     *
     * @param Url|String|string $context
     * @param Url|String|string $relative
     */
    public function __construct($context='', $relative=null) {
        $this->set($context);
        if ($relative) {
            $this->resolve(self::cast($relative));
        }
    }

    protected function set($url) {
        if ($url instanceof Url) {
            $this->setByParts($url->getParts());
        } else if (\is_string($url)) {
            $this->setByString($url);
        }
        return $this;
    }

    protected function resolve(Url $newUrl) {
        if (($newUrl->getScheme()->is() && !$this->sameParts($newUrl,self::SCHEME)) || ($newUrl->getHost()->is() && !$this->sameParts($newUrl,self::HOST))) {
            $this->set($newUrl);
        } else {
            if ($newUrl->getHost()->is()) {
                $this->host = $newUrl->getHost();
            }
            if ($newUrl->getPath()->is()) {
                if (substr($newUrl->getPath(), 0, 1) == '/')
                    $this->path = $newUrl->getPath();
                else {
                    $myPath = $this->path;
                    if (substr($myPath, strlen($myPath) - 1) != '/') {
                        $myPath = substr($myPath, 0, strrpos($myPath, '/'));
                    }
                    $this->path = self::resolvePath($myPath . '/' . $newUrl->getPath());
                }
            }
            if ($newUrl->getQuery()->is() || $newUrl->getPath()->is()) {
                $this->query = $newUrl->getQuery();
            }

            $this->fragment = $newUrl->getFragment();
        }
    }

    /**
     * Resolves //, ../ and ./ from a path and returns
     * the result. Eg:
     *
     * /foo/bar/../boo.php    => /foo/boo.php
     * /foo/bar/../../boo.php => /boo.php
     * /foo/bar/.././/boo.php => /foo/boo.php
     *
     * @param  string $url URL path to resolve
     * @return string      The result
     */
    public static function resolvePath($path) {
        $path = explode('/', str_replace('//', '/', $path));
        for ($i = 0; $i < count($path); $i++) {
            if ($path[$i] == '.') {
                unset($path[$i]);
                $path = array_values($path);
                $i--;
            } elseif ($path[$i] == '..' && ($i > 1 || ($i == 1 && $path[0] != '') )) {
                unset($path[$i]);
                unset($path[$i - 1]);
                $path = array_values($path);
                $i -= 2;
            } elseif ($path[$i] == '..' && $i == 1 && $path[0] == '') {
                unset($path[$i]);
                $path = array_values($path);
                $i--;
            } else {
                continue;
            }
        }
        return implode('/', $path);
    }

    protected function setByString($url) {
        #if (!\filter_var($url, \FILTER_VALIDATE_URL)) {
        #    throw new \InvalidArgumentException('"' . $url . '" is not a valid URL');
        #}
        $parts = \parse_url($url);
        $this->setByParts($parts);
    }

    protected function setByParts($parts) {
        $this->scheme = isset($parts['scheme']) ? (string)$parts['scheme'] : 'http';
        $this->user = isset($parts['user']) ? (string)$parts['user'] : '';
        $this->pass = isset($parts['pass']) ? (string)$parts['pass'] : '';
        $this->host = isset($parts['host']) ? (string)$parts['host'] : '';
        $this->port = isset($parts['port']) ? (string)$parts['port'] : '';
        $this->path = isset($parts['path']) ? (string)$parts['path'] : '';
        $this->query = isset($parts['query']) ? (string)$parts['query'] : '';
        $this->fragment = isset($parts['fragment']) ? (string)$parts['fragment'] : '';
    }

    /**
     *
     * @param String $data
     * @return Url
     */
    public function sprintf($data) {
        return new self(sprintf($this->toString(),(string)$data));
    }

    public function getParts() {
        return array(
            'scheme' => $this->scheme,
            'user' => $this->user,
            'pass' => $this->pass,
            'host' => $this->host,
            'port' => $this->port,
            'path' => $this->path,
            'query' => $this->query,
            'fragment' => $this->fragment
        );
    }

    public function toString($what = self::ALL) {
        return new String(
            ($what & self::SCHEME ? ((string)$this->scheme ? $this->scheme . ':' . ((strtolower($this->scheme) == 'mailto') ? '' : '//') : '') : '')
            . ($what & self::USER ? ((string)$this->user ? $this->user . ($this->pass ? ':' . $this->pass : '') . '@' : '') : '')
            . ($what & self::HOST ? ((string)$this->host ? $this->host : '') : '')
            . ($what & self::PORT ? ((string)$this->port ? ':' . $this->port : '') : '')
            . ($what & self::PATH ? ((string)$this->path ? $this->path : '') : '')
            . ($what & self::QUERY ? ((string)$this->query ? '?' . $this->query : '') : '')
            . ($what & self::FRAGEMENT ? ((string)$this->fragment ? '#' . $this->fragment : '') : '')
        );
    }

    public function __toString() {
        return (string)$this->toString();
    }

    public function getScheme() {
        return new String($this->scheme);
    }

    public function getHost() {
        return new String($this->host);
    }

    public function getPort() {
        return $this->port;
    }

    public function getUser() {
        return new String($this->user);
    }

    public function getPass() {
        return new String($this->pass);
    }

    /**
     * @return String
     */
    public function getPath() {
        return new String($this->path);
    }

    /**
     * @return String
     */
    public function getPathPart($position) {
        return $this->getPath()->cut('/',$position, 1);
    }


    /**
     * @return String
     */
    public function getQuery() {
        return new String($this->query);
    }

    public function getQueryVar($k) {
        \parse_str($this->query,$values);
        if (!isset($values[$k])) {
            return new String();
        }
        if (is_array($values[$k])) {
            return new String\Collection($values[$k]);
        } else {
            return new String($values[$k]);
        }
    }
	
	/**
	 *
	 * @return \Type\Json 
	 */
    public function getQueryObject() {
        \parse_str($this->query,$values);
		return new Json($values);
    }
	


    public function getFragment() {
        return new String($this->fragment);
    }

    /**
     * @autocomplete
     * @param array $options
     * @return \Type\Document
     */
    public function load($options = array()) {
        $factory = \App\Injector::injectLoaderFactory();
        return $factory->load($this, $options);
    }

    /**
     * @autocomplete
     * @param array $options
     * @return \Type\Document
     */
    public function loadBadHtml($options = array()) {
        $options = options($options)->merge('badhtml=1');
        return $this->load($options);
    }


    public static function cast($url) {
        return $url instanceof Url ? $url : new Url((string)$url);
    }

    public function under($url) {
        $url = self::cast($url)->toString();
        return $this->toString()->substring(0, $url->byteLength()) == $url;
    }

    public function sameParts($url, $what = self::DOMAIN) {
        $url = self::cast($url);
        return strcasecmp($url->toString($what), $this->toString($what)) == 0;
    }

    public function sameDomain($url) {
        return $this->sameParts($url, self::DOMAIN);
    }

    public function otherDomain($url) {
        return !$this->sameDomain($url);
    }

    /**
     *
     * @param int $what
     * @return Url
     */
    public function trim($what) {
        return self::cast($this->toString($what));
    }

    /**
     * @autocomplete 
     * @param int $what
     * @return String
     */
    public function asString($what = Url::ALL) {
        return new String($this->toString($what));
    }

    public function getNativeValue() {
        return (string)$this;
    }

}
?>