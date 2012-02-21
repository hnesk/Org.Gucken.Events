<?php
namespace App;

use Type\Document\Loader;
/**
 * Description of Injector
 *
 * @author jk
 */
class Injector {

    /**
     *
     * @var Loader\Factory
     */
    protected static $loaderFactory;

    /**
     *
     * @return Loader\Factory
     */
    public static function injectLoaderFactory() {
        if (!self::$loaderFactory) {
            self::$loaderFactory = new Loader\Factory(
                self::injectLoaderHttp(),
                self::injectLoaderGecko(),
                self::injectLoaderFile()
            );
        }
        return self::$loaderFactory;
    }

    /**
     *
     * @return Loader\File
     */
    public static function injectLoaderFile() {
        return new Loader\File();
    }

    /**
     *
     * @return Loader\Http
     */
    public static function injectLoaderHttp() {
        return new Loader\Http(
            self::injectHttpClient(),
            self::injectDocumentRepository()
        );
    }

    /**
     *
     * @return \App\PersistentRegistry
     */
    public static function injectRegistry() {
        return new \App\PersistentRegistry();
    }


    /**
     *
     * @return Loader\Gecko
     */
    public static function injectLoaderGecko() {
        return new Loader\Gecko(
            self::injectHttpClient(),
            self::injectDocumentRepository(),
            self::injectProxyUrl()
        );
    }

    /**
     *
     * @return \Type\Document\RepositoryInterface
     */
    public static function injectDocumentRepository() {		
		if (class_exists('Org\Gucken\Events\Service\DocumentRepositoryService')) {
			return new \Org\Gucken\Events\Service\DocumentRepositoryService();
		} else {
			return new \Type\Document\ImpulseDbRepository(self::injectDocumentTable());
		}
    }


    /**
     *
     * @return \ImpulseDB
     */
    public static function injectImpulseDb() {
        return new \ImpulseDB(self::injectDbAdapter());
    }

    /**
     *
     * @return \Zend_Db_Adapter_Abstract
     */
    public static function injectDbAdapter() {
        return \App::db();
    }

    /**
     *
     * @return \ImpulseDB\Table
     */
    public static function injectDocumentTable() {
        $db = self::injectImpulseDb();
        return $db->getTable('document');
    }

    /**
     *
     * @return \ImpulseDB\Table
     */
    public static function injectRegistryTable() {
        $db = \App\Injector::injectImpulseDb();
        return $db->getTable('registry');
    }


    /**
     *
     * @return \Zend_Http_Client
     */
    public static function injectHttpClient() {
        return new \Zend_Http_Client(null, self::injectHttpClientConfig());
    }

    /**
     *
     * @return array
     */
    public static function injectHttpClientConfig() {
        return array('timeout'=>10);
    }


    /**
     *
     * @return string
     */
    public static function injectProxyUrl() {
        return 'http://84.19.182.2:10011/?url=%s';
    }
}
?>
