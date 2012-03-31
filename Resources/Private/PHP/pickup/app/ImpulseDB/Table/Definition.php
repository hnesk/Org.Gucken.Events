<?php

namespace ImpulseDB\Table;

class Definition {
    /**
     * @var \Zend_Db_Adapter_Abstract
     */
    protected $db;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var int
     */
    protected $id;

    /**
     *
     * @var array
     */
    protected $columns;

    /**
     *
     * @var array
     */
    protected $columnById;

    /**
     *
     * @var array
     */
    protected $columnByName;
    

    public function __construct($db, $name) {
        $this->db = $db;
        $this->name = $name;
        $this->id = $this->_getId();
    }

    public function name() {
        return $this->name;
    }

    public function id() {
        return $this->id;
    }

    protected function _getId() {
        $id = $this->db->fetchOne('SELECT id FROM `table` WHERE name = ?',$this->name);
        if (!$id) {
            $this->db->insert('table', array('name'=>$this->name));
            $id = $this->db->lastInsertId();
        }
        return $id;
    }

    public function getColumns() {
        if (!$this->columns) {
            $this->columns = $this->db->select()->from('column')->where('tableid = ?',$this->id)->query()->fetchAll();
            foreach ($this->columns as $column) {
                $this->columnById[$column['id']] = $column;
                $this->columnByName[$column['name']] = $column;
            }
        }
        return $this->columns;
    }

    public function getColumnsByIds() {
        $this->getColumns();
        return $this->columnById;
    }

    public function getColumnsByNames() {
        $this->getColumns();
        return $this->columnByName;
    }

    public function getColumnIds() {
        return array_keys($this->columnById);
    }

    public function getColumnByName($name) {
        $this->getColumns();
        return isset($this->columnByName[$name]) ? $this->columnByName[$name] : null;
    }

    public function getColumnById($id) {
        $this->getColumns();
        return isset($this->columnById[$id]) ? $this->columnById[$id] : null;
    }

    public function getColumn($name) {
        $column = $this->getColumnByName($name);
        if (!$column) {
            $column = $this->createColumn($name);
        }
        return $column;
    }

    public function createColumn($name) {
        $this->db->insert('column', array('tableid'=>$this->id, 'name' => $name));
        $this->resetColumns();
        return $this->getColumnByName($name);
    }

    public function deleteColumn($name) {
        $this->db->delete('column', array('tableid = ?' => $this->id, 'name = ?'=> $name));
        $this->resetColumns();
    }

    protected function resetColumns() {
        $this->columns = array();
        $this->columnById = array();
        $this->columnByName = array();
    }
}
?>