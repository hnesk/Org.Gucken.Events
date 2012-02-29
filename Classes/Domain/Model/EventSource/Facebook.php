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
    protected $page;
    
    
    /**
     * @Events\Configurable
     * @var \Org\Gucken\Events\Domain\Model\Type
     */
    protected $type;



    /**
     * @param string $page
     */
    public function setPage($page) {
        $this->page = $page;
    }

    /**
     *
     * @return \Facebook\Type\Page
     */
    public function getPage() {
        return new \Facebook\Type\Page($this->page);
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
	 * @param Model\EventFactoid $factoid 
	 */
	public function convertLocation(Model\EventFactoid $factoid) {
		$location = null;
		return $location;
	}
	
	/**
	 *
	 * @param Model\EventFactoidIdentity $factoidIdentity
	 * @param \Org\Gucken\Events\Domain\Model\EventLink if set link will be updated else created
	 * @return \Org\Gucken\Events\Domain\Model\EventLink 
	 */
	public function convertLink(Model\EventFactoidIdentity $factoidIdentity, $link = null) {
		$link = $link ? : new Model\FacebookEventLink();
		$link->setUrl($factoidIdentity->getFactoid()->getUrl());
		return $link;
	}	
	
    /**
     * @return \Type\Record\Collection
     */
    public function getEvents() {
		return $this->getPage()->getEvents('-1 days','+28 days')->map(array($this,'getEvent'));
    }
	    
	/**
	 *
	 * @param \Facebook\Type\Event $event
	 * @return \Type\Record 
	 */
	public function getEvent(\Facebook\Type\Event $event) {
		return new \Type\Record(array(
			'title' => $event->getTitle(),
			#'image' => $xml->css('img')->asUrl()->first(),
			#'short' => 
			'description' => $event->getDescription(),
			'date' => $event->getStartTime(),
			'url' =>  $event->getUrl(),
			'type' => $this->getType(),
			'location' => $this->locationRepository->findOneByExternalId('org_gucken_events_facebooklocationidentifier', $event->getVenueId())
		));
	} 	
}

?>
