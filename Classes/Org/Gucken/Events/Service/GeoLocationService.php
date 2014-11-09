<?php

namespace Org\Gucken\Events\Service;

use Org\Gucken\Events\Domain\Model\PostalAddress;
use Org\Gucken\Events\Domain\Model\GeoCoordinates;

interface GeoLocationService
{
    /**
     * fetches data from a geolocation service about an address
     *
     * @param  PostalAddress  $address
     * @return GeoCoordinates
     */
    public function locate(PostalAddress $address);

}
