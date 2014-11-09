<?php
namespace Org\Gucken\Events\Domain\Model;

/*                                                                        *
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

use TYPO3\Flow\Annotations as Flow;

/**
 * A Postal address
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @Flow\Scope("prototype")
 * @Flow\Entity
 */
class PostalAddress
{

    /**
     * The address country
     * @var string
     */
    protected $addressCountry;

    /**
     * The address locality
     * @var string
     */
    protected $addressLocality;

    /**
     * The address region
     * @var string
     */
    protected $addressRegion;

    /**
     * The post office box number
     * @var string
     */
    protected $postOfficeBoxNumber;

    /**
     * The postal code
     * @var string
     */
    protected $postalCode;

    /**
     * The street address
     * @var string
     */
    protected $streetAddress;

    public function __construct(
        $streetAddress,
        $postalCode,
        $addressLocality,
        $addressRegion = 'NRW',
        $addressCountry = 'DE',
        $postOfficeBoxNumber = ''
    ) {
        $this->streetAddress = $streetAddress;
        $this->postalCode = $postalCode;
        $this->addressLocality = $addressLocality;
        $this->addressRegion = $addressRegion;
        $this->addressCountry = $addressCountry;
        $this->postOfficeBoxNumber = $postOfficeBoxNumber;
    }

    /**
     * Get the Postal address's address country
     *
     * @return string The Postal address's address country
     */
    public function getAddressCountry()
    {
        return $this->addressCountry;
    }

    /**
     * Sets this Postal address's address country
     *
     * @param  string $addressCountry The Postal address's address country
     * @return void
     */
    public function setAddressCountry($addressCountry)
    {
        $this->addressCountry = $addressCountry;
    }

    /**
     * Get the Postal address's address locality
     *
     * @return string The Postal address's address locality
     */
    public function getAddressLocality()
    {
        return $this->addressLocality;
    }

    /**
     * Sets this Postal address's address locality
     *
     * @param  string $addressLocality The Postal address's address locality
     * @return void
     */
    public function setAddressLocality($addressLocality)
    {
        $this->addressLocality = $addressLocality;
    }

    /**
     * Get the Postal address's address region
     *
     * @return string The Postal address's address region
     */
    public function getAddressRegion()
    {
        return $this->addressRegion;
    }

    /**
     * Sets this Postal address's address region
     *
     * @param  string $addressRegion The Postal address's address region
     * @return void
     */
    public function setAddressRegion($addressRegion)
    {
        $this->addressRegion = $addressRegion;
    }

    /**
     * Get the Postal address's post office box number
     *
     * @return string The Postal address's post office box number
     */
    public function getPostOfficeBoxNumber()
    {
        return $this->postOfficeBoxNumber;
    }

    /**
     * Sets this Postal address's post office box number
     *
     * @param  string $postOfficeBoxNumber The Postal address's post office box number
     * @return void
     */
    public function setPostOfficeBoxNumber($postOfficeBoxNumber)
    {
        $this->postOfficeBoxNumber = $postOfficeBoxNumber;
    }

    /**
     * Get the Postal address's postal code
     *
     * @return string The Postal address's postal code
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Sets this Postal address's postal code
     *
     * @param  string $postalCode The Postal address's postal code
     * @return void
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * Get the Postal address's street address
     *
     * @return string The Postal address's street address
     */
    public function getStreetAddress()
    {
        return $this->streetAddress;
    }

    /**
     * Sets this Postal address's street address
     *
     * @param  string $streetAddress The Postal address's street address
     * @return void
     */
    public function setStreetAddress($streetAddress)
    {
        $this->streetAddress = $streetAddress;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->streetAddress . ', ' . $this->postalCode . ' ' . $this->addressLocality;
    }

}
