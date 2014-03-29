<?php

namespace Org\Gucken\Events\Controller;

/* *
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
use Org\Gucken\Events\Domain\Model\Event;
use Org\Gucken\Events\Domain\Model\EventFactoid;
use Org\Gucken\Events\Domain\Model\EventFactoidIdentity;

use Org\Gucken\Events\Domain\Model\EventLink;
use TYPO3\Flow\Annotations as Flow;
use Lastfm\Type\Venue as Venue;
use TYPO3\Flow\Error\Message;

/**
 * Standard controller for the Events package
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class FactoidConvertController extends AbstractAdminController {


	/**
     *
     * @var \Org\Gucken\Events\Domain\Repository\EventFactoidIdentityRepository
     * @Flow\Inject
     */
    protected $identityRepository;

    /**
     *
     * @var \Org\Gucken\Events\Domain\Repository\EventRepository
     * @Flow\Inject
     */
    protected $eventRepository;


	/**
	 *
	 * @var \Org\Gucken\Events\Service\ConvertEventFactoidService
	 * @Flow\Inject
	 */
	protected $convertService;

	/**
	 *
	 * @var \Org\Gucken\Events\Domain\Model\BackendSession
	 * @Flow\Inject
	 */
	protected $backendSession;

    /**
     * Index action
	 *
	 * @param string $startDateTime
	 * @param string $endDateTime
	 * @return void
	 */
    public function indexAction($startDateTime = 'today', $endDateTime = null) {

		$endDateTime = $endDateTime ?: '+21 days';
		$eventsAndIdentities = $this->getEventsAndIdentitiesGroupedByDay($startDateTime, $endDateTime);

		$this->view->assign('eventsAndIdentities', $eventsAndIdentities);
    }


	/**
	 *
	 * @param \DateTime $date
	 * @param \DateTime $referenceDate
	 * @return \DateTime
	 */
	protected function createDate($date, \DateTime $referenceDate = null) {
		if (is_object($date) && $date instanceof \DateTime) {
			return $date;
		} else if (is_string($date) && is_null($referenceDate)) {
			return new \DateTime($date);
		} else {
			$result = clone $referenceDate;
			$result->modify($date);
			return $result;
		}
	}
	/**
	 *
	 * @param string $startDateTime
	 * @param string $endDateTime
	 * @return array
	 */
	protected function getEventsAndIdentitiesGroupedByDay($startDateTime, $endDateTime = null) {
		$startDateTime = $this->createDate($startDateTime);
		$endDateTime = $this->createDate($endDateTime, $startDateTime);

		$result = array();
		$dateTime = clone $startDateTime;
		while ($dateTime->getTimestamp() < $endDateTime->getTimestamp()) {
			$tomorrow = clone $dateTime;
			$tomorrow->modify('+1 day');
			$result[$dateTime->format('Ymd')] = array(
				'date' => clone $dateTime,
				'startDateTime' => $dateTime->format('Y-m-d'),
				'endDateTime' => $tomorrow->format('Y-m-d'),
				'identities' => array(),
				'events' => array(),
			);
			$dateTime = $tomorrow;
		}

		foreach ($this->identityRepository->findUnassignedBetween($startDateTime, $endDateTime) as $identity) {
			/* @var $identity EventFactoidIdentity */
			$result[$identity->getStartDateTime()->format('Ymd')]['identities'][] = $identity;
		}

		foreach ($this->eventRepository->findBetween($startDateTime, $endDateTime) as $event) {
			/* @var $event Event */
			$result[$event->getStartDateTime()->format('Ymd')]['events'][] = $event;
		}
		return $result;
	}


	/**
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoidIdentity $identity
	 */
	public function deleteAction(EventFactoidIdentity $identity) {
		$this->identityRepository->remove($identity);
        $this->addFlashMessage('Faktoid-ID "'.$identity.'" gelöscht', 'Obacht!', Message::SEVERITY_NOTICE);
		$this->redirect('index');
	}

	/**
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoidIdentity $identity
	 */
	public function skipAction(EventFactoidIdentity $identity) {
		$identity->setShouldSkip(true);
		$this->identityRepository->update($identity);
        $this->addFlashMessage('Faktoid-ID "'.$identity.'" wird ignoriert', 'Obacht!', Message::SEVERITY_NOTICE);
		$this->view->assign('identity',$identity);
		$this->redirectIfHtml('index');
	}

	/**
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoidIdentity $identity
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoid $factoid
	 */
	public function detailFactoidAction(EventFactoidIdentity $identity, EventFactoid $factoid) {
		$this->view->assign('factoid', $factoid);
		$this->view->assign('identity', $identity);
	}

	/**
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoidIdentity $identity
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoid $factoid
	 */
	public function deleteFactoidAction(EventFactoidIdentity $identity, EventFactoid $factoid) {
		$identity->removeFactoid($factoid);
        $this->addFlashMessage('Faktoid "'.$factoid.'" gelöscht', 'Obacht!', Message::SEVERITY_NOTICE);
		if ($identity->hasFactoids()) {
			$this->identityRepository->update($identity);
		} else {
			$this->identityRepository->remove($identity);
            $this->addFlashMessage('Zugehörige Faktoid-ID auch', 'Obacht!', Message::SEVERITY_NOTICE);
		}

		$this->view->assign('factoid', $factoid);
		$this->view->assign('identity', $identity);

		$this->redirectIfHtml('index');
	}

	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoidIdentity $identity
	 */
    public function convertAction(EventFactoidIdentity $identity) {
		$event = $this->convertService->convert($identity);
        $this->addFlashMessage('Veranstaltung "'.$event.'" erstellt', 'Obacht!', Message::SEVERITY_NOTICE);
		$currentDate = $identity->getStartDateTime();
		$this->view->assign('events',$this->eventRepository->findOn($currentDate));
		$this->view->assign('identities',$this->identityRepository->findUnassignedOn($currentDate));
		$this->view->assign('currentDate',$currentDate);

		$this->redirectIfHtml('index');
    }

	/**
	 * @param \Org\Gucken\Events\Domain\Model\Event $event
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoidIdentity $identity
	 */
    public function mergeAction(Event $event, EventFactoidIdentity $identity) {
		$event = $this->convertService->merge($event, $identity);
        $this->addFlashMessage('Faktoid-ID "'.$identity.'" mit Event "'.$event.'" zusammengefasst', 'Obacht!', Message::SEVERITY_NOTICE);
		$this->view->assign('event',$event);
		$this->view->assign('identity',$identity);

		$this->redirectIfHtml('index');

    }

	/**
	 *
	 * @param EventLink $link
	 */
    public function unlinkAction(EventLink $link) {
		$unlinkedIdentity = $this->convertService->unlink($link);
        $this->addFlashMessage('Faktoid-ID "'.$unlinkedIdentity.'" von Event gelöst', 'Obacht!', Message::SEVERITY_NOTICE);
		$currentDate = $link->getEvent()->getStartDateTime();
		$this->view->assign('events',$this->eventRepository->findOn($currentDate));
		$this->view->assign('identities',$this->identityRepository->findUnassignedOn($currentDate));
		$this->view->assign('currentDate',$currentDate);

		$this->redirectIfHtml('index');
    }


}

?>