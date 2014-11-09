<?php
namespace Org\Gucken\Events\Controller;

/*                                                                        *
 * This script belongs to the Flow package "Org.Gucken.Events".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */
use TYPO3\Flow\Annotations as Flow;

use Org\Gucken\Events\Domain\Model\EventFactoidIdentity;
use Org\Gucken\Events\Domain\Model\EventProposal;
use Org\Gucken\Events\Domain\Model\Location;

/**
 * Proposal controller to submit Events
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class ProposalController extends BaseController
{
    /**
     *
     * @var \Org\Gucken\Events\Domain\Repository\LocationRepository
     * @Flow\Inject
     */
    protected $locationRepository;

    /**
     *
     * @var \Org\Gucken\Events\Domain\Repository\TypeRepository
     * @Flow\Inject
     */
    protected $typeRepository;

    /**
     *
     * @var \Org\Gucken\Events\Domain\Repository\EventFactoidIdentityRepository
     * @Flow\Inject
     */
    protected $factoidIdentityRepository;

    public function indexAction()
    {
        $this->redirect('propose');
    }

    /**
     * @param EventProposal $proposal
     * @Flow\IgnoreValidation("proposal")
     */
    public function proposeAction(EventProposal $proposal = null)
    {
        if (is_null($proposal)) {
            $tomorrowEvening = new \DateTime('1 day');
            $tomorrowEvening->setTime(20, 0, 0);
            $proposal = new EventProposal();
            $proposal->setStartDateTime($tomorrowEvening);
        }

        $locations = $this->locationRepository->findAll();
        $this->view->assign('locations', $locations);
        $this->view->assign(
            'locationJson',
            json_encode(
                array_map(
                    function (Location $location) {
                        return $location->getName() . ' - ' . $location->getAddress();
                    },
                    $locations->toArray()
                )
            )
        );
        $this->view->assign('types', $this->addDummyEntry($this->typeRepository->findAll(), ' - Bitte wählen - '));
        $this->view->assign('proposal', $proposal);
    }

    /**
     *
     * @param EventProposal $proposal
     *
     * @Flow\Validate(argumentName="proposal.startDateTime",type="Org\Gucken\Events\Domain\Validator\FutureValidator")
     * @Flow\Validate(argumentName="proposal.startDateTime",type="NotEmpty")
     * @Flow\Validate(argumentName="proposal.title",type="NotEmpty")
     * @Flow\Validate(argumentName="proposal.type",type="NotEmpty")
     */
    public function validateAction(EventProposal $proposal)
    {
        $location = $this->locationRepository->findOneByExactAdress($proposal->getLocationText());

        if (!$location) {
            $location = new Location();
            $location->setName($proposal->getLocationText());
            $location->setReviewed(false);
            $this->locationRepository->add($location);
        }
        $proposal->setLocation($location);

        $proposal->setImportDateTime(new \DateTime());

        $factoidIdentity = $this->factoidIdentityRepository->findOrCreateByEventFactoid($proposal);
        $this->factoidIdentityRepository->add($factoidIdentity);

        $this->redirect('displayFinished', null, null, array('factoidIdentity' => $factoidIdentity));
    }

    /**
     * No Error Flash Messages here
     * @return boolean
     */
    protected function getErrorFlashMessage()
    {
        return false;
    }

    /**
     *
     * @param EventFactoidIdentity $factoidIdentity
     */
    public function displayFinishedAction(EventFactoidIdentity $factoidIdentity)
    {
        $this->view->assign('identity', $factoidIdentity);
    }
}
