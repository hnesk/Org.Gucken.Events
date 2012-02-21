<?php

namespace ImpulseDB;

class Table {
    /**
     * @var \Zend_Db_Adapter_Abstract 
     */
    protected $db;

    /**
     *
     * @var Table\Definition
     */
    protected $definition;

    /**
     * @param \Zend_Db_Adapter_Abstract $db
     * @param string $name
     */
    public function __construct(\Zend_Db_Adapter_Abstract $db, $name) {
        $this->db = $db;
        $this->definition = new Table\Definition($db, $name);
    }

    /**
     * Inserts a record 
     *
     * @param array $data
     * @return int the id of the inserted row
     */
    public function insert($data) {
        $this->db->beginTransaction();
        $rowId = $this->insertRow();
        foreach ($data as $key => $value) {
            $column = $this->definition->getColumn($key);
            $this->db->insert('data', array(
                    'columnid' => $column['id'],
                    'rowid' => $rowId,
                    'data' => $value
                )
            );
        }
        $this->db->commit();
        return $rowId;
    }

    /**
     * If the records doesn't exist (by id) inserts, else updates the record
     *
     * @param array $data
     * @return int the id of the affected record
     */
    public function insertOrUpdate($data, $conditions = array()) {
        if (count($conditions)) {
            $row = $this->findOne($conditions);           
        } else {
            if (!$data['id']) {
                return $this->insert($data);
            }
            $row = $this->fetch($data['id']);
        }
        if (!$row) {
            return $this->insert($data);
        }
        $this->update($row['id'], $data);
        return $row['id'];

    }


    public function delete($row) {
        $rowId = 0;
        if (is_array($row)) {
            if (isset($row['id'])) {
                $rowId = $row['id'];             
            } else {
                $row = $this->findOne($row);
                if ($row) {
                    $rowId = $row['id'];
                }
            }
        } else if (\is_int($row)) {
            $rowId = $row;
        }

        if ($rowId) {
            $this->deleteRow($rowId);
        }
    }

    /**
     *
     * @param int $rowId
     * @param array $data
     * @return int number of affected values
     */
    public function update($rowId, $data) {
        $affectedValues = 0;
        $this->db->beginTransaction();
        foreach ($data as $key => $value) {
            $column = $this->definition->getColumn($key);
            $affectedValues += $this->db->update(
                    'data',
                    array(
                        'data' => $value
                    ),
                    array(
                        'columnid = ?' => $column['id'],
                        'rowid = ?' => $rowId,
                    )
            );
        }
        $this->db->commit();
        return $affectedValues;
    }


    public function fetch($rowId) {
        $result = $this->find(array('row.id = ' . $this->db->quote($rowId)));
        return $result->fetch();
    }

    public function fetchAll($columns = null) {
        return $this->find(array(),$columns);
    }


    public function findOne($conditions= array(),$columns = null,$order = '',$group = ''){
        $result = $this->find($conditions, $columns, $order, $group, 1);
        return $result->fetch();
    }

    public function quote($value) {
        return $this->db->quote($value);
    }
    

    /**
     *
     * @param array $conditions
     * @param array|null $columns
     * @return \Zend_Db_Statement_Pdo
     */
    public function find($conditions= array(),$columns = null,$order = array(),$group = array(), $limit='') {

        $tables = array();
        $fields = array('`row`.`id` AS `id`');

        // cast to array if needed
        $conditions = \Util\ArrayFunctions::toArray($conditions);
        $group = \Util\ArrayFunctions::toArray($group);
        $order = \Util\ArrayFunctions::toArray($order);


        $conditions[] = '`row`.`tableid` = '.$this->db->quote($this->definition->id());

        if ($columns) {
            $columns= \Util\ArrayFunctions::toArray($columns);
            foreach ($columns as $k=>$columnName) {
                $columns[$k] = $this->definition->getColumnByName($columnName);
            }
        } else {
            $columns = $this->definition->getColumns();
        }

        $aliasMap = array();
        foreach ($columns as $column) {
            $tempTable = $this->db->quoteIdentifier('value_'.$column['name']);
            $aliasMap[$this->db->quoteIdentifier($column['name'])] = $tempTable.'.`data`';
            $fields[] = $tempTable.'.`data` AS '.$this->db->quoteIdentifier($column['name']);
            $tables[] = 'LEFT JOIN `data` AS '.$tempTable.' ON ('.$tempTable.'.`rowid` = `row`.`id` AND '.$tempTable.'.`columnid` = '.$this->db->quote($column['id']).')';
        }

        foreach ($aliasMap as $alias => $field)  {
            $conditions = \str_replace($alias, $field, $conditions);
            $order = \str_replace($alias, $field, $order);
            $group = \str_replace($alias, $field, $group);
        }

        $sql =  'SELECT ' . \implode(',', $fields) . ' ' .
                'FROM row ' . \implode(PHP_EOL, $tables) . ' ' .
                'WHERE '.\implode(' AND ', $conditions) . ' ' .
                (count($group) ? 'GROUP BY '.\implode(', ', $group) .' ' : '') .
                (count($order) ? 'ORDER BY '.\implode(', ', $order) .' ' : '') .
                ($limit ? 'LIMIT '.$limit  : '')
                ;

    
                
        return $this->db->query($sql);
    }

    protected function insertRow() {
        $this->db->insert('row', array('tableid'=>$this->definition->id()));
        return $this->db->lastInsertId();
    }

    protected function deleteRow($id) {
        $this->db->delete('row', array('tableid'=>$this->definition->id(), 'id'=>$id));
        $this->db->delete('data', array('rowid'=>$id));
    }


    /**
     *
     * @return Table\Definition
     */
    public function getDefinition() {
        return $this->definition;
    }

}
?>
