<?php
namespace Type\Document;
use Type\Document\Metadata;

/**
 * Description of Loader
 *
 * @author jk
 */
class Builder {

    /**
     *
     * @param array $data
     * @return \Type\Document
     */
    public static function buildFromArray($data,$options=array()) {
        $content = $data['content'];
        if (!$content) {
            return null;
        }
        unset($data['content']);
        
        return self::build(
            Metadata\Builder::build($data,$options),
            $content
        );
    }

    /**
     *
     * @param Metadata $metaData
     * @param string $content
     * @return \Type\Document
     */
    public static function build(Metadata $metaData, $content, $options = array()) {
        switch ($metaData->getContentType()) {
            case 'application/rss+xml':
            case 'application/rdf+xml':
            case 'application/atom+xml':
                return new Feed($metaData, $content);
                break;

            
            case 'application/json':
                return new Json($metaData, $content);
                break;


            case 'text/xml':
            case 'application/xml':
                return new Xml($metaData, $content);
                break;
            
            
            case 'text/html':
            case 'application/xhtml+xml':
            default:
                return new Html($metaData, $content);
                break;            
        }
    }
}
?>