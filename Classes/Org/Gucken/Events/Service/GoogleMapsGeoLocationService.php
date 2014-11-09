<?php
namespace Org\Gucken\Events\Service;

use TYPO3\Flow\Annotations as Flow;
use Org\Gucken\Events\Domain\Model\PostalAddress;
use Org\Gucken\Events\Domain\Model\GeoCoordinates;

/**
 * @Flow\Scope("singleton")
 */
class GoogleMapsGeoLocationService implements GeoLocationService
{

    /**
     *
     * @param  \Org\Gucken\Events\Domain\Model\PostalAddress  $address
     * @return \Org\Gucken\Events\Domain\Model\GeoCoordinates
     */
    public function locate(PostalAddress $address)
    {
        return new GeoCoordinates(0, 0, 0);
    }

    /**
     * fetches data from googlemaps about an address
     *
     * @param  string $thoroughfare address = street and housenumber
     * @param  string $postalCode   zip code
     * @param  string $locality     city
     * @param  string $country      political entity the address is located in
     * @param  array  $conf         config array with possible keys: baseUrl, apiKey, defaultCountryCode
     * @return array  address data with keys: latitude, longitude, dunnoWhat (the 3rd parameter in the coordinates),countryCode,country,administrativeArea,subAdministrativeArea,locality,dependentLocality,thoroughfare,postalCode or null
     */
    /*
        protected function _locate($thoroughfare='',$postalCode='',$locality='',$country='',$conf=array())
        {
                $baseUrl = $conf['baseUrl'] ? $conf['baseUrl'] : ($this->staticConfig['baseUrl'] ? $this->staticConfig['baseUrl'] : 'http://maps.google.com/maps/geo');
                $apiKey  = $conf['apiKey']  ? $conf['apiKey']  : $this->staticConfig['apiKey'];
                $country = $country ? $country : $conf['defaultCountryCode'];

                $params = array(
                        'output'=> 'xml',
                        'key'   => $apiKey,
                        'q'             => implode(',', array($thoroughfare, $postalCode, $city, $country))
                );

                $responseXml = $this->fetchXmlResponse($baseUrl .'?'. http_build_query($params));

                if (!$responseXml) {
                        return null;
                }

                $coordinateString = self::nodesToString($responseXml->xpath('/k:kml/k:Response/k:Placemark/k:Point/k:coordinates'));
                $coordinates = t3lib_div::trimExplode(',',$coordinateString);

                #$addressDetailsXml = $responseXml->xpath('/k:kml/k:Response/k:Placemark/a:AddressDetails');
                #$localityDetailsXml = $addressDetailsXml->xpath('a:Country/a:AdministrativeArea/a:SubAdministrativeArea/a:Locality');

                return array(
                        'latitude'                              => (float) $coordinates[0],
                        'longitude'                             => (float) $coordinates[1],
                        'dunnoWhat'                             => (float) $coordinates[2],
                #       'countryCode'                   => self::nodesToString($addressDetailsXml->xpath('a:Country/a:CountryNameCode')),
                #       'country'                               => self::nodesToString($addressDetailsXml->xpath('a:Country/a:CountryName')),
                #       'administrativeArea'    => self::nodesToString($addressDetailsXml->xpath('a:Country/a:AdministrativeArea/a:AdministrativeAreaName')),
                #       'subAdministrativeArea' => self::nodesToString($addressDetailsXml->xpath('a:Country/a:AdministrativeArea/a:SubAdministrativeArea/a:SubAdministrativeAreaName')),
                #       'locality'                              => self::nodesToString($localityDetailsXml->xpath('a:LocalityName')),
                #       'dependentLocality'             => self::nodesToString($localityDetailsXml->xpath('a:DependentLocality/a:DependentLocalityName')),
                #       'thoroughfare'                  => self::nodesToString($localityDetailsXml->xpath('a:DependentLocality/a:Thoroughfare/a:ThoroughfareName')),
                #       'postalCode'                    => self::nodesToString($localityDetailsXml->xpath('a:DependentLocality/a:PostalCode/a:PostalCodeNumber')),
                );
        }

        protected function fetchXmlResponse($requestUrl,$maxTries = 5)
        {
                $statusCode = self::STATUS_TOO_MANY_REQUESTS;
                $trys = 0;
                $delay = 100000;
                while ($statusCode == self::STATUS_TOO_MANY_REQUESTS && $trys < 5) {
                        $response = t3lib_div::getUrl($requestUrl);
                        $responseXml = new SimpleXmlElement(utf8_encode($response));
                        $responseXml->registerXpathNamespace('k','http://earth.google.com/kml/2.0');
                        $responseXml->registerXpathNamespace('a','urn:oasis:names:tc:ciq:xsdschema:xAL:2.0');

                        $statusCode = self::nodesToString($responseXml->xpath('/k:kml/k:Response/k:Status/k:code'));
                        $trys++;
                        if ($statusCode == self::STATUS_TOO_MANY_REQUESTS) {
                                $delay += 100000;
                                usleep($delay);
                        }
                }
                if ($statusCode == self::STATUS_OK) {
                        return $responseXml;
                } else {
                        return null;
                }
        }

        protected static function nodesToString($nodes)
        {
                if (count($nodes) > 0) {
                        return join('',$nodes);
                }

                return '-';
        }
    */
}
