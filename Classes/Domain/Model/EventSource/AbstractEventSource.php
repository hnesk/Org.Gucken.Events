<?php

namespace Org\Gucken\Events\Domain\Model\EventSource;

use Type\Url,
	Type\Xml,
	Type\String,
	Type\Feed;

use Org\Gucken\Events\Annotations as Events,
	TYPO3\FLOW3\Annotations as FLOW3;

/**
 */
class AbstractEventSource  {

    /**
	 * @Events\Configurable
     * @var \Type\Url
     */
    protected $url;	
	
	
    /**
     * @FLOW3\Inject
     * @var \Org\Gucken\Events\Domain\Repository\LocationRepository
     */
    protected $locationRepository;	

	
    /**
     * @FLOW3\Inject
     * @var \Org\Gucken\Events\Domain\Repository\TypeRepository
     */
    protected $typeRepository;	

    /**
     *
     * @return \Type\Url
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param Url $url
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
	
}

?>
