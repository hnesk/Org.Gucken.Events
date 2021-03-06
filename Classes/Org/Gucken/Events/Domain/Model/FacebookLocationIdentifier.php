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

use TYPO3\Flow\Annotations as Flow;

use Facebook\Type\Page\Collection\Factory as FacebookPagesFactory;

/**
 * An identifier for a location on an external website or service
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @Flow\Scope("prototype")
 * @Flow\Entity
 */
class FacebookLocationIdentifier extends ExternalLocationIdentifier
{

    public function getSchemeLabel()
    {
        return 'Facebook-ID';
    }

    public function getCandidates(Location $location)
    {
        $results = $this->getSearchResults(
            $location,
            $location->getName() . ' ' . $location->getAddress()->getAddressLocality()
        );
        if (count($results) === 0) {
            $results = $this->getSearchResults($location, $location->getName());
        }

        return $results;
    }

    /**
     *
     * @param  Location $location
     * @param  string   $searchString
     * @return array
     */
    protected function getSearchResults(Location $location, $searchString)
    {
        $results = array();
        foreach (FacebookPagesFactory::fromString($searchString) as $venue) {
            /* @var $venue \Facebook\Type\Page */
            $results[] = new self($venue->getId(), $location, (string) $venue);
        }

        return $results;
    }

    public function getUrl()
    {
        return 'http://www.facebook.com/' . $this->getId();
    }

}
