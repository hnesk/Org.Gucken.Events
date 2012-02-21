<?php
namespace ImpulseDB\Table;
use ImpulseDB\Table;

/**
 * 
 */
class Column {
    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var Table
     */
    protected $table;

    /**
     *
     * @var boolean
     */
    protected $null;

    /**
     *
     * @var string
     */
    protected $default;

    /**
     *
     * @var string
     */
    protected $type;

    /**
     *
     * @var int
     */
    protected $length;
    
}
?>
