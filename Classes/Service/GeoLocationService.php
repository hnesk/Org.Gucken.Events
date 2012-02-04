<?php

namespace Org\Gucken\Events\Service;

use TYPO3\FLOW3\Annotations as FLOW3;
use Org\Gucken\Events\Domain\Model\PostalAddress;
use Org\Gucken\Events\Domain\Model\GeoCoordinates;

interface GeoLocationService {
	/**
	 * fetches data from a geolocation service about an address
	 * 
	 * @param \Org\Gucken\Events\Domain\Model\PostalAddress $address
	 * @return \Org\Gucken\Events\Domain\Model\GeoCoordinates 
	 */
	public function locate(PostalAddress $address);
	
	
}

?>
