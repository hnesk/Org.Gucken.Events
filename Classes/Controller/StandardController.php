<?php
namespace Org\Gucken\Events\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "Events".                     *
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

use \Org\Gucken\Events\Domain\Model\Event;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Standard controller for the Events package 
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class StandardController extends BaseController {
	/**
	 *
	 * @var \Org\Gucken\Events\Domain\Repository\EventRepository
	 * @FLOW3\Inject
	 */
	protected $eventRepository;

	/**
	 *
	 * @var \Org\Gucken\Events\Domain\Repository\LocationRepository
	 * @FLOW3\Inject
	 */
	protected $locationRepository;

	/**
	 *
	 * @var \TYPO3\FLOW3\Reflection\ReflectionService
	 * @FLOW3\Inject
	 */
	protected $reflectionService;


	/**
	 *
	 * @var \TYPO3\FLOW3\Object\ObjectManager
	 * @FLOW3\Inject
	 */
	protected $objectManager;

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$events = $this->eventRepository->findAll();
		$this->view->assign('events', $events);
	}
	

	/**
	 *
	 * @param Org\Gucken\Events\Domain\Model\Event $event 
	 * @return void
	 */
	public function showAction(Event $event) {
		$this->view->assign('event', $event);
	}
	

	/**
	 *
	 * @param Org\Gucken\Events\Domain\Model\Event $event 
	 * @FLOW3\IgnoreValidation({"event"})         
	 * @return void
	 */
	public function addAction(Event $event = null) {
		$this->view->assign('event', $event);
		$this->view->assign('locations', $this->locationRepository->findAll());
	}

	/**
	 *
	 * @param Org\Gucken\Events\Domain\Model\Event $event
	 * @FLOW3\IgnoreValidation({"event"})         
	 * @return void
	 */
	public function editAction(Event $event = null) {
		$this->view->assign('event', $event);
		$this->view->assign('locations', $this->locationRepository->findAll());
	}


	/**
	 *
	 * @param Org\Gucken\Events\Domain\Model\Event $event
	 */
	public function createAction(Event $event) {
		$this->eventRepository->add($event);
		$this->flashMessageContainer->add('Event created');
		$this->redirect('index');
	}

	/**
	 *
	 * @param Org\Gucken\Events\Domain\Model\Event $event
	 */
	public function updateAction(Event $event) {
		$this->eventRepository->update($event);
		$this->flashMessageContainer->add('Event updated');
		$this->redirect('index');
	}


}

?>