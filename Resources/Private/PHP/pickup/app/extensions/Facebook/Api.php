<?php

namespace Facebook;

require_once 'Api/facebook-php-sdk/src/base_facebook.php';

class Api extends \BaseFacebook {

    /**
     *
     * @var \App\PersistentRegistry 
     */
    protected $registry;

    /**
     * Supported keys
     * @var array
     */
    protected static $kSupportedKeys = array('state', 'code', 'access_token', 'user_id');

    /**
     *
     * @param array $config
     * @param \App\PersistentRegistry $registry
     */
    public function __construct($config, \App\PersistentRegistry $registry) {
        $this->registry = $registry;
        parent::__construct($config);
    }


    /**
     * Provides the implementations of the inherited abstract
     * methods.  The implementation uses PHP sessions to maintain
     * a store for authorization codes, user ids, CSRF states, and
     * access tokens.
     */
    protected function setPersistentData($key, $value) {
        if (!in_array($key, self::$kSupportedKeys)) {
            self::errorLog('Unsupported key passed to setPersistentData.');
            return;
        }
        $this->registry[$key] = $value;
        #$this->table->insertOrUpdate(array('value' => $value), 'key = ' . $this->prepareKey($key));
    }

    public function createStore() {
        foreach (self::$kSupportedKeys as $key) {
            $this->registry[$key] = '';
            #$this->table->insert(array('key'=> $this->prepareKey($key), 'value' => ''));
        }
    }

    /**
     *
     * @param string $key
     * @return string
     */
    public function prepareKey($key) {
        return $this->table->quote(\str_replace('\\', '_', __CLASS__).'_'.$key);
    }

    protected function getPersistentData($key, $default = false) {
        if (!in_array($key, self::$kSupportedKeys)) {
            self::errorLog('Unsupported key passed to getPersistentData.');
            return $default;
        }
        return isset($this->registry[$key]) ? $this->registry[$key] : $default;
        #$row = $this->table->findOne('`key` = "' . $this->prepareKey($key).'"');
        #return $row['value'] ? : $default;
    }

    protected function clearPersistentData($key) {
        if (!in_array($key, self::$kSupportedKeys)) {
            self::errorLog('Unsupported key passed to clearPersistentData.');
            return;
        }
        unset($this->registry[$key]);
        #$this->table->delete('key =' . $this->prepareKey($key));
    }

    protected function clearAllPersistentData() {
        foreach (self::$kSupportedKeys as $key) {
            $this->clearPersistentData($key);
        }
    }

}

?>
