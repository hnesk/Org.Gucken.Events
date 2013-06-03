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

use \Org\Gucken\Events\Domain\Model\Event;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Standard controller for the Events package
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class StandardController extends BaseController {
	/**
	 *
	 * @var \Org\Gucken\Events\Domain\Repository\EventRepository
	 * @Flow\Inject
	 */
	protected $eventRepository;


	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$events = $this->eventRepository->findBetween(new \DateTime('today'), new \DateTime('+14 days'));
		$this->view->assign('events', $events);
	}


	/**
	 *
	 * @param Org\Gucken\Events\Domain\Model\Event $event
	 * @return void
	 */
	public function showAction(Event $event) {
		$this->view->assign('event', $event);
		if ($this->isHtmlRequest()) {
			return $this->view->render('show');
		} else {
			return $this->view->render('single');
		}

	}



}

?>