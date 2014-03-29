<?php

namespace Org\Gucken\Events\Domain\Model\EventSource;

use Org\Gucken\Events\Domain\Model\EventFactoidIdentity;
use Org\Gucken\Events\Domain\Model\EventLink;
use Org\Gucken\Events\Domain\Model\WebEventLink;
use Type\Url,
	Type\Xml,
	Type\String,
	Type\Feed;

use Org\Gucken\Events\Annotations as Events,
	TYPO3\Flow\Annotations as Flow;

/**
 */
class AbstractEventSource  {

    /**
	 * @Events\Configurable
     * @var \Type\Url
     */
    protected $url;


    /**
     * @Flow\Inject
     * @var \Org\Gucken\Events\Domain\Repository\LocationRepository
     */
    protected $locationRepository;


    /**
     * @Flow\Inject
     * @var \Org\Gucken\Events\Domain\Repository\TypeRepository
     */
    protected $typeRepository;

    /**
     *
     * @return \Type\Url
     */
    public function getUrl() {
        return url($this->url);
    }

    /**
     * @param Url|string $url
     */
    public function setUrl($url) {
        $this->url = new Url($url);
    }

	/**
	 *
	 * @return \Org\Gucken\Events\Domain\Repository\TypeRepository
	 */
	public function getTypeRepository() {
		return $this->typeRepository;
	}

	/**
	 *
	 * @return \Org\Gucken\Events\Domain\Repository\TypeRepository
	 */
	public function getLocationRepository() {
		return $this->locationRepository;
	}

    /**
     *
     * @param EventFactoidIdentity $factoidIdentity
     * @param \Org\Gucken\Events\Domain\Model\EventLink $link if set link will be updated else created
     * @return WebEventLink
     */
	public function convertLink(EventFactoidIdentity $factoidIdentity, EventLink $link = null) {
		$link = $link ?: new WebEventLink();
		$link->setUrl($factoidIdentity->getFactoid()->getUrl());
		return $link;
	}



}

?>
