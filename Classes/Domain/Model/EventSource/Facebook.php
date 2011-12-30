<?php

namespace Org\Gucken\Events\Domain\Model\EventSource;

use Type\Url;
use Org\Gucken\Events\Domain\Model;


use Org\Gucken\Events\Annotations as Events;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * @FLOW3\Scope("prototype")
 */
class Facebook implements EventSourceInterface {

    /**
     * @FLOW3\Inject
     * @var \Org\Gucken\Events\Domain\Repository\LocationRepository
     */
    protected $locationRepository;
    
    /**
     * @Events\Configurable
     * @var string
     */
    protected $user;
    
    
    /**
     * @Events\Configurable
     * @var \Org\Gucken\Events\Domain\Model\Type
     */
    protected $type;



    /**
     * @param string $city
     */
    public function setUser($user) {
        $this->user = $user;
    }

    /**
     *
     * @return string
     */
    public function getUser() {
        return $this->user;
    }

    /**
     *
     * @param \Org\Gucken\Events\Domain\Model\Type $type
     */
    public function setType(\Org\Gucken\Events\Domain\Model\Type $type) {
        $this->type = $type;
    }

    /**
     * @return \Org\Gucken\Events\Domain\Model\Type
     */
    public function getType() {
        return $this->type;
    }

    /**
     *
     * @return \Org\Gucken\Events\Domain\Repository\LocationRepository
     */
    public function getLocationRepository() {
        return $this->locationRepository;
    }
    
	/**
	 *
	 * @param Model\EventFactoid $factoid 
	 */
	public function convertLocation(Model\EventFactoid $factoid) {
		$location = null;
		return $location;
	}
	

    /**
     * @return \Type\Record\Collection
     */
    public function getEvents() {
        #$self = $this;
		return new \Type\Record\Collection();
    }
}

?>
