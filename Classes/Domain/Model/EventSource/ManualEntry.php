<?php

namespace Org\Gucken\Events\Domain\Model\EventSource;

use Type\Url;
use Org\Gucken\Events\Domain\Model;
use Org\Gucken\Events\Annotations as Events;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Null Implementation that does nothing
 * @FLOW3\Scope("prototype")
 */
class ManualEntry implements EventSourceInterface {
	/**
	 *
	 * @param Model\EventFactoidIdentity $factoidIdentity
	 * @param \Org\Gucken\Events\Domain\Model\EventLink if set link will be updated else created
	 * @return \Org\Gucken\Events\Domain\Model\WebEventLink
	 */
	public function convertLink(\Org\Gucken\Events\Domain\Model\EventFactoidIdentity $factoidIdentity, \Org\Gucken\Events\Domain\Model\EventLink $link = null) {
		$link = $link ?: new \Org\Gucken\Events\Domain\Model\WebEventLink();
		$link->setUrl($factoidIdentity->getFactoid()->getUrl());
		return $link;
	}


	/**
	 * @return \Type\Record\Collection
	 */
	public function getEvents() {
		return new \Type\Record\Collection();
	}

}

?>
