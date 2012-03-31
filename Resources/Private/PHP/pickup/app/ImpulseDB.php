<?php

/**
 * Description of ImpuleDB
 *
 * @author jk
 */
class ImpulseDB {
    /**
     * @var \Zend_Db_Adapter_Abstract
     */
    protected $db;

    public function  __construct(\Zend_Db_Adapter_Abstract $db) {
        $this->db = $db;
        $this->setUp();
    }

    /**
     *
     * @param string $name
     * @return ImpulseDB\Table
     */
    public function __get($name) {
        return $this->getTable($name);
    }

    /**
     *
     * @param string $name
     * @return ImpulseDB\Table
     */
    public function getTable($name) {
        return new ImpulseDB\Table($this->db, $name);
    }

    public function reset() {
        $this->tearDown();
        $this->setUp();
    }

    public function setUp() {
        if (count($this->db->listTables()) == 0) {
            $this->applySqlFile('up');
        }
    }
    public function tearDown() {
        $this->applySqlFile('down');
    }

    protected function applySqlFile($script) {
        $type = strtolower(substr(get_class($this->db),strlen('Zend_Db_Adapter_')));
        $sqlFile = realpath(BASE_PATH.'../data/dbsetup/'.$type.'/'.$script.'.sql');

        if ($sqlFile) {
            $queries = explode(';',file_get_contents($sqlFile));
            foreach ($queries as $query) {
                $this->db->query($query);
            }
        }
    }
}
?>
