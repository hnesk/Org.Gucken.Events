<?php

namespace Type\Document;

use Type\Date;
use Type\Url;
/**
 * Description of Repository
 *
 * @author jk
 */
class ImpulseDbRepository implements RepositoryInterface {
    
    /**
     *
     * @var ImpulseDB\Table
     */
    protected $table;
    
    /**
     *
     * @param ImpulseDB\Table $table
     */
    public function __construct(\ImpulseDB\Table $table) {
        $this->table = $table;
    }

    /**
     *
     * @param \Type\Document $document
     * @return int
     */
    public function store(\Type\Document $document) {
        $data = $document->getMeta()->getData();
        $data['content'] = $document->getRawContent();    
        return $this->table->insert($data);
    }

    public function retrieveById($id) {
        return \Type\Document\Builder::buildFromArray($this->table->fetch($id));
    }

    /**
     *
     * @param Url $url
     * @param int $maxAge
     * @return \Type\Document
     */
    public function retrieveLatestByUrl($url, $maxAge=null,$options=array()) {
        $conditions = array(
            '`requested-url` = "'.(string)$url.'"'
        );
        if (!is_null($maxAge)) {
            $conditions[] = '`local-time` > "'. Date::ago($maxAge) . '"';
        }
        $data = $this->table->findOne($conditions, null, '`local-time` DESC');
        return \Type\Document\Builder::buildFromArray($data,$options);
    }

    /**
     *
     * @param Url $url
     * @param int $maxAge
     * @param int $limit
	 * @param array $options
     * @return array<\Type\Document>
     */
    public function retrieveByUrl($url,$maxAge = null,$limit=5, $options = array()) {
        $conditions = array(
            '`requested-url` = "'.(string)$url.'"'
        );
        if (!is_null($maxAge)) {
            $conditions[] = '`local-time` > "'. Date::ago($maxAge) . '"';
        }
        
        $results = array();
        $statement = $this->table->find($conditions, null, '`local-time` DESC',null, $limit);
        foreach ($statement->fetchAll() as $result) {
            $results[] = \Type\Document\Builder::buildFromArray($result);
        }
        return $results;
    }    
    
}
?>
