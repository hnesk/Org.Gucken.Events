<?php

namespace Org\Gucken\Events\Domain\Model;

/* *
 * This script belongs to the Flow package "Org.Gucken.Events".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License as published by the Free   *
 * Software Foundation, either version 3 of the License, or (at your      *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        *
 * You should have received a copy of the GNU General Public License      *
 * along with the script.                                                 *
 * If not, see http://www.gnu.org/licenses/gpl.html                       *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A HTTP Document or a cache entry
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @Flow\Scope("prototype")
 * @Flow\Entity
 * @ORM\Table(name="org_gucken_events_domain_model_document",indexes={@ORM\Index(name="url_idx", columns={"requestedUrl"})})
 */
class Document  {


    /**
     * The requested url
     * @var string
     */
    protected $requestedUrl;

    /**
     * The fetched url (after redirects)
     * @var string
     */
    protected $fetchedUrl;
	
    /**
     * The Date of retrieval
     * @var \DateTime
	 * @ORM\Column(type="datetime", name="localdate")
     */
    protected $localTime;

    /**
     * The Server Date 
     * @var \DateTime
	 * @ORM\Column(type="datetime", name="serverdate")
     */	
	protected $date;
	
    /**
     * The last modified Date 
     * @var \DateTime
     * @ORM\Column(nullable=true)
     */		
	protected $lastModified;

    /**
     * The expire date
     * @var \DateTime
     * @ORM\Column(nullable=true)
     */		
	protected $expires;
	
	/**
     * The etag
     * @var string
     * @ORM\Column(nullable=true)
     */
    protected $etag;	
	
	
    /**
     * Meta data array
     * @var array
     */
    protected $meta;

    /**
     * The content as text
	 * @ORM\Column(type="text", columnDefinition="LONGBLOB NOT NULL") 
     * @var string
     */
    protected $content;
	
	
	/**
	 * Build an document from content and meta data
	 * 
	 * @param \Type\Document
	 * @return \Org\Gucken\Events\Domain\Model\Document
	 */
	public function setFromDocument(\Type\Document $typeDocument) {
		
		$meta = $typeDocument->getMeta();
		
		$this->setContent($typeDocument->getRawContent());
		$this->setDate(self::toDate($meta->getDocumentTime()));
		$this->setLastModified(self::toDate($meta->getUpdatedTime()));
		$this->setLocalTime(self::toDate($meta->getLocalTime()));
		$this->setExpires(self::toDate($meta->get('expires')));
		
		$this->setEtag($meta->get('etag'));
		$this->setFetchedUrl($meta->getFetchedUrl());
		$this->setRequestedUrl($meta->getRequestedUrl());
		
		$plainMetaData = $meta->getData();
		foreach ($plainMetaData as $key => $value) {
			if ($value instanceof \Type\Date) {
				$plainMetaData[$key] = $value->getNativeValue();
			}
		}
		
		$this->setMeta($plainMetaData);
		
		return $this;
	}

	
	/**
	 * Returns self as a plain array for the Type\Document builder
	 * 
	 * @return array
	 */
	public function asArray() {
		
		
		$dateProperties = array('local-time', 'last-modified', 'expires', 'document-time', 'updated-time');
		
		$data = $this->getMeta();
		foreach ($data as $key => $value) {
			if (in_array($key,$dateProperties)) {
				$value = self::toDate($value);
				if ($value) {
					$data[$key] = $value;
				} else {
					unset($data[$key]);
				}
			}
		}
		$data['content'] = $this->getContent();
		return $data;
	}
	
	
	/**
	 *
	 * @param string $date
	 * @return \DateTime|null 
	 */
	protected static function toDate($date) {
		if ($date instanceof \DateTime) {
			return $date;
		} else if ($date instanceof \Type\Date) {
			return $date->getNativeValue();
		} else if (is_string($date)) {
			$dateObject = null;
			try {
				$dateObject = new \DateTime($date);				
			} catch (\Exception $e) {				
			}
			return $dateObject;
		} else {
			return null;
		}
	}
	

	/**
	 *
	 * @return string
	 */
	public function getRequestedUrl() {
		return $this->requestedUrl;
	}

	/**
	 *
	 * @param string $requestedUrl 
	 */
	public function setRequestedUrl($requestedUrl) {
		$this->requestedUrl = $requestedUrl;
	}

	/**
	 *
	 * @return string
	 */
	public function getFetchedUrl() {
		return $this->fetchedUrl;
	}

	/**
	 *
	 * @param string $fetchedUrl 
	 */
	public function setFetchedUrl($fetchedUrl) {
		$this->fetchedUrl = $fetchedUrl;
	}

	/**
	 *
	 * @return \DateTime
	 */
	public function getLocalTime() {
		return $this->localTime;
	}

	
	/**
	 *
	 * @param \DateTime $localTime 
	 */
	public function setLocalTime(\DateTime $localTime = null) {
		$this->localTime = $localTime;
	}

	/**
	 *
	 * @return \DateTime
	 */
	public function getDate() {
		return $this->date;
	}

	
	/**
	 *
	 * @param \DateTime $date 
	 */
	public function setDate(\DateTime $date = null) {
		$this->date = $date;
	}

	/**
	 *
	 * @return \DateTime
	 */
	public function getLastModified() {
		return $this->lastModified;
	}

	/**
	 *
	 * @param \DateTime $lastModified 
	 */
	public function setLastModified(\DateTime $lastModified = null) {
		$this->lastModified = $lastModified;
	}

	/**
	 *
	 * @return \DateTime
	 */
	public function getExpires() {
		return $this->expires;
	}

	/**
	 *
	 * @param \DateTime $expires 
	 */
	public function setExpires(\DateTime $expires = null) {
		$this->expires = $expires;
	}

	/**
	 *
	 * @return string
	 */
	public function getEtag() {
		return $this->etag;
	}

	/**
	 *
	 * @param string $etag 
	 */
	public function setEtag($etag) {
		$this->etag = $etag;
	}

	/**
	 *
	 * @return array
	 */
	public function getMeta() {
		return $this->meta;
	}

	/**
	 *
	 * @param array $meta 
	 */
	public function setMeta($meta) {
		$this->meta = $meta;
	}

	/**
	 *
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 *
	 * @param string $content 
	 */
	public function setContent($content) {
		$this->content = $content;
	}

	
	public function toArray() {
		$result = $this->meta;
		$result['content'] = $this->content;
		return $result;
	}
	
    /**
     * 
     * @return string
     */
    public function __toString() {
        return $this->requestedUrl . ' on ' . $this->date->format('Y-m-d H-i');
    }

}

?>