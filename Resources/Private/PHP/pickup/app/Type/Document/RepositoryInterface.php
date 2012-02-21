<?php

namespace Type\Document;

use Type\Date;
use Type\Url;
/**
 * Description of Repository
 *
 * @author jk
 */
interface RepositoryInterface {
    
    /**
     *
     * @param \Type\Document $document
     * @return mixed
     */
    public function store(\Type\Document $document);

	
    /**
     *
     * @param mixed $id
     * @return \Type\Document 
     */	
    public function retrieveById($id);
	
    /**
     *
     * @param Url $url
     * @param int $maxAge
     * @return \Type\Document
     */
    public function retrieveLatestByUrl($url, $maxAge=null,$options=array());

    /**
     *
     * @param Url $url
     * @param int $maxAge
     * @param int $limit
	 * @param array $options for DocumentBuilder
     * @return array<\Type\Document>
     */
    public function retrieveByUrl($url,$maxAge = null,$limit=5, $options =array()); 
    
}
?>
