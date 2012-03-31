<?php
namespace Type\Document\Metadata;

use Type\Document;
use Type\Date;
/**
 * Description of Http
 *
 * @author jk
 */
class File extends Document\Metadata {

    protected function postConstruct() {
        $this->parseFileinfo();        
    }
    /**
     * @return void
     */
    protected function parseFileinfo() {
        $file = $this->getFetchedUrl()->getPath();
        $fileInfoObject = new \finfo(FILEINFO_MIME);
        $mimeType = $fileInfoObject->file($file);
        $this->setByMimeType($mimeType)
            ->setDocumentTime($this->getLocalTime())
            ->setUpdatedTime('@'.\filemtime($file))
            ->setCreatedTime('@'.\filectime($file))
            ->setSize(\filesize($file))
            ->setOwner(\fileowner($file))
            ->setGroup(\filegroup($file));
    }


    /**
     *
     * @return \Type\Date
     */
    public function getCreatedTime() {
        return $this->get('created-time');
    }
    /**
     *
     * @param \Type\Date $time
     * @return File
     */
    public function setCreatedTime($time) {
        return $this->set('created-time', $time instanceof Date ? $time : new Date($time));
    }


    /**
     *
     * @return string
     */
    public function getOwner() {
        return $this->get('owner');
    }
    /**
     *
     * @param string $owner
     * @return File
     */
    protected function setOwner($owner) {
        return $this->set('owner', $owner);
    }

    /**
     *
     * @return string
     */
    public function getGroup() {
        return $this->get('group');
    }
    /**
     *
     * @param string $owner
     * @return File
     */
    protected function setGroup($group) {
        return $this->set('group', $group);
    }


}
?>
