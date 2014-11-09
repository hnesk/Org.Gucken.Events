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

use Org\Gucken\Events\Domain\Model;

use TYPO3\Flow\Annotations as Flow;

/**
 * A user entered event proposal
 *
 * @Flow\Scope("prototype")
 * @Flow\Entity
 */
class EventProposal extends EventFactoid
{

    /**
     *
     * @var string
     * @Flow\Validate(type="NotEmpty")
     */
    protected $locationText;

    /**
     *
     * @var \Org\Gucken\Events\Domain\Repository\EventSourceRepository
     * @Flow\Inject
     */
    protected $sourceRepository;

    /**
     *
     */
    public function initializeObject()
    {
        $this->setSource($this->sourceRepository->findOneByCode('manual'));

    }

    /**
     *
     * @return string
     */
    public function getLocationText()
    {
        return $this->locationText;
    }

    /**
     *
     * @param string $locationText
     */
    public function setLocationText($locationText)
    {
        $this->locationText = $locationText;
    }

}
