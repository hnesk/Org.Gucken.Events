<?php

namespace Org\Gucken\Events\Controller;

/* *
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

use Org\Gucken\Events\Domain\Model\EventFactoid;

use TYPO3\FLOW3\Annotations as FLOW3;
use Lastfm\Type\Venue as Venue;

/**
 * Standard controller for the Events package 
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class FactoidController extends BaseController {
        
	/**
	 * @FLOW3\Inject
	 * @var \TYPO3\FLOW3\Reflection\ReflectionService
	 */
	protected $reflectionService;

    /**
     *
     * @var \Org\Gucken\Events\Domain\Repository\EventFactoidRepository
     * @FLOW3\Inject
     */
    protected $factoidRepository;
        

    /**
     * Index action
     *
     * @return void
     */
    public function indexAction() {        
        $this->view->assign('factoids', $this->factoidRepository->findAll());
    }
	
	/**
	 *
	 * @param string $action
	 * @param \Doctrine\Common\Collections\ArrayCollection<\Org\Gucken\Events\Domain\Model\EventFactoid> $arguments
	 */
	public function multipleAction($action, $arguments) {		
		$actionName = $action.'Action';
		$controllerName = get_class($this);		
		if (!$this->reflectionService->hasMethod($controllerName, $actionName)) {
			throw new \InvalidArgumentException('Missing action "'.$actionName.'"');
		}		
		
		$parameters = $this->reflectionService->getMethodParameters($controllerName, $actionName);
		if (count($parameters) === 1) {
			$parameter = current($parameters);			
			if ($parameter['array'] || strpos($parameter['type'],'Doctrine\\Common\\Collections\\') === 0  ) {
				$this->forward($action, NULL, NULL, array(key($parameters) => $arguments));
			} else {
				foreach ($arguments as $argument) {
					call_user_func(array($this,$actionName), $argument, true);
				}
				$this->redirect('index');
			} 
		} else {
			$this->addNotice($controllerName.'::'.$actionName.' not callable');
		}
		
		
	}
	
	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoid $factoid 
	 */
	public function detailAction(\Org\Gucken\Events\Domain\Model\EventFactoid $factoid) {
		$this->view->assign('factoid',$factoid);
	}
	
	/**
	 *
	 * @param \Doctrine\Common\Collections\ArrayCollection<\Org\Gucken\Events\Domain\Model\EventFactoid> $factoids	
	 */	
	public function diffAction($factoids) {
		$this->view->assign('factoids',$factoids);
	}
	
	
	/**
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventFactoid $factoid 
	 */
	public function deleteAction(\Org\Gucken\Events\Domain\Model\EventFactoid $factoid, $massAction = false) {
		$this->factoidRepository->remove($factoid);		
		$this->addNotice('Removed "'.$factoid.'"');
		if (!$massAction) {
			$this->redirect('index');
		}
	}	
	


	/**
	 * 
	 */
	public function identityAction() {
		foreach ($this->factoidRepository->findAll() as $factoid) {
			/* @var $factoid EventFactoid */
			$factoid->regenerateFuzzyIdentity();
			#$this->factoidRepository->update($factoid);
		}
	}
	
}

?>