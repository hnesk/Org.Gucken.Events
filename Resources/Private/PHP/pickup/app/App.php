<?php

class App {
    /**
     * @var App
     */
    static protected $app;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $baseDirectory;

    /**
     *
     * @var string
     */
    protected $configDirectory;


    /**
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $db;

    /**
     *
     * @var \DateTime
     */
    protected $runDate;
    

    /**
     *
     * @return App
     */
    public static function instance($configDirectory = null) {
        if (!self::$app) {
            self::$app = new self($configDirectory);
        }
        return self::$app;
    }

    /**
     * @return mixed
     */
    public static function config($key=null) {
        return self::instance()->getConfig($key);
    }

    /**
     * @return mixed
     */
    public static function baseDirectory() {
        return self::instance()->getBaseDirectory();
    }

    /**
     * @return mixed
     */
    public static function configChoices($key=null) {
        return self::instance()->getConfigChoices($key);
    }

    /**
     *
     * @return \Zend_Db_Adapter_Abstract
     */
    public static function db() {
        return self::instance()->getDb();
    }




    protected function getBaseDirectory() {
        return $this->baseDirectory;
    }

    /**
     *
     * @return \Zend_Db_Adapter_Abstract
     */
    public function getDb() {
        if (!$this->db) {
            $this->db = \Zend_Db::factory($this->getConfig('db/adapter'),$this->getConfig('db/params'));
        }
        return $this->db;
    }


    public function getConfigChoices($key = null) {
        $config = $this->getConfig($key);
        return is_array($config) ? array_keys($config) : array();
    }

    public function getConfig($key = null, $default = null) {
        if (!$key) {
            return $this->config;
        }
        $config = $this->config;
        $keys = explode('/',$key);
        foreach($keys as $key) {
            if (!isset($config[$key])) {
                return $default;
            }
            $c = $config[$key];
            if (is_array($c)) {
                $config = $c;
            } else {
                return $c;
            }
        }
        return $config;
    }


    protected function __construct($configDirectory = null) {
        $this->runDate = new \DateTime();
        if (!defined('BASE_PATH')) {
            define('BASE_PATH',  dirname(__FILE__).DIRECTORY_SEPARATOR);
        }
        $this->baseDirectory = BASE_PATH;
        $this->configDirectory = $configDirectory ?: realpath($this->baseDirectory.'config');

        $this->init();
    }

    protected function init() {
        $this->initConfiguration();
        $this->configure();
        $this->initAutoloader();
        $this->initErrorHandler();        
    }

    /**
     *
     * @return \DateTime
     */
    public function date() {
        return $this->runDate;
    }

    protected function initConfiguration() {
        // $this->config = App\Config::createFromDirectory($this->configDirectory)
        $this->config = array();
        foreach (glob($this->configDirectory.'/*.php') as $configFile) {
            $this->config = array_merge_recursive($this->config, require_once $configFile);
        }
    }

    protected function configure() {
        // timezone
        date_default_timezone_set($this->getConfig('locale/default_timezone'));

        // includes & include_path
        foreach ($this->getConfig('includes') as $include) {
            require_once $include;
        }
        $includePath = array(BASE_PATH);
        $includePath = array_merge($includePath,$this->getConfig('include_path'));
        if (!$this->getConfig('ignore_include_path')) {
            $includePath[] = get_include_path();
        } 
        set_include_path(implode(PATH_SEPARATOR,$includePath));
    }
    
    protected function initErrorHandler() {
        // setup error handling
        error_reporting($this->getConfig('errors/reporting',0));
        ini_set('display_errors',$this->getConfig('errors/display',0));
        if ($errorHandler = $this->getConfig('errors/handler')) {
            set_error_handler($errorHandler);
        }
    }

    public static function errorHandler($errno, $errstr, $errfile, $errline) {
        if ( E_RECOVERABLE_ERROR===$errno ) {
            throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
        }
        return false;
    }

    protected function initAutoloader() {

        // for Zend Framework
        require_once 'Zend/Loader/Autoloader.php';
        $loader = Zend_Loader_Autoloader::getInstance();
        $loader->setFallbackAutoloader(false);
        $loader->pushAutoloader(function($className) {
            $corePath = BASE_PATH.DIRECTORY_SEPARATOR.str_replace('\\',DIRECTORY_SEPARATOR,$className).'.php';
            $extensionPath = BASE_PATH.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.str_replace('\\',DIRECTORY_SEPARATOR,$className).'.php';
            $libraryPath = BASE_PATH.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.str_replace('\\',DIRECTORY_SEPARATOR,$className).'.php';
            if (file_exists($corePath)) {
                require_once ($corePath);
            } else if (file_exists($extensionPath)) {
                require_once ($extensionPath);
            } else if (file_exists($libraryPath)) {
                require_once ($libraryPath);
            }
        });


        
    }

}



?>