<?php
namespace App;

/**
 * Description of Scope
 *
 * @author jk
 */
class Scope {

    /**
     *
     * @var Config
     */
    protected $configuration;

    /**
     *
     * @param string $configurationDirectory
     */
    public function __construct($configurationDirectory = null) {
        $this->configuration = Config::createFromDirectory(
            $configurationDirectory ? $configurationDirectory : dirname(__FILE__).'/config'
        );
    }


    /**
     *
     * @return Config
     */
    public function getConfiguration() {
        return $this->configuration;
    }
}
?>
