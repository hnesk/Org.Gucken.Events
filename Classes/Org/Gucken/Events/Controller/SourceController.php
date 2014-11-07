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

use Org\Gucken\Events\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Reflection\ReflectionService;

use Org\Gucken\Events\Domain\Repository\EventSourceRepository;
use Org\Gucken\Events\Domain\Repository\TypeRepository;
use Org\Gucken\Events\Domain\Model\EventSource\EventSourceInterface;

/**
 * Standard controller for the Events package 
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class SourceController extends AbstractAdminController {

    /**
     *
     * @var ReflectionService
     * @Flow\Inject
     */
    protected $reflectionService;

    /**
     *
     * @var EventSourceRepository
     * @Flow\Inject
     */
    protected $sourceRepository;
        
    /**
     *
     * @var TypeRepository
     * @Flow\Inject
     */
    protected $typeRepository;


    /**
     * Index action
     *
     * @return void
     */
    public function indexAction() {
        $sources = $this->sourceRepository->findAll();
        $this->view->assign('sources', $sources);
    }

    /**
     *
     * @param Model\EventSource $source
     */
    public function viewAction(Model\EventSource $source) {
        $eventFactoids = array();
        try {
            $eventFactoids = $source->getEventFactoids();
            $this->persistenceManager->persistAll();
        } catch (\Exception $e) {
            $this->addFlashMessage($e->getMessage(), $e->getCode(), Message::SEVERITY_ERROR);
        }

        $this->view->assign('eventFactoids', $eventFactoids);
        $this->view->assign('source', $source);         
    }

    /**
     *
     * @param Model\EventSource $source
     * @Flow\IgnoreValidation("source")
     * @return void
     */
    public function addAction(Model\EventSource $source = null) {
		$source = $source ?: new Model\EventSource();
        $this->view->assign('source', $source);
        $this->view->assign('types', $this->typeRepository->findAll());
        $this->view->assign('implementations',$this->getEventSourceImplementations());
    }

    /**
     *
     * @param Model\EventSource $source
     */
    public function saveAction(Model\EventSource $source) {
		$errors = $source->validate();
		/* @var $errors \TYPO3\Flow\Error\Result */
		if ($errors->hasErrors()) {
			foreach ($errors->getFlattenedErrors() as $errorArray) {
				foreach ($errorArray as $error) {
					$this->flashMessageContainer->addMessage($error);
				}
			}
			$this->errorAction();
		} else {		
			$this->sourceRepository->add($source);
			$this->redirect('edit', NULL, NULL, array('source'=>$source));
		}
    }


    /**
     *
     * @param Model\EventSource $source
     * @return void
     */
    public function editAction(Model\EventSource $source) {
        $this->view->assign('source', $source);
        $this->view->assign('types', $this->typeRepository->findAll());
        $this->view->assign('implementations',$this->getEventSourceImplementations());
    }

    /**
     *
     * @param Model\EventSource $source
     */
    public function updateAction(Model\EventSource $source) {
		$errors = $source->validate();
		/* @var $errors \TYPO3\Flow\Error\Result */
		if ($errors->hasErrors()) {
			foreach ($errors->getFlattenedErrors() as $errorArray) {
				foreach ($errorArray as $error) {
					$this->flashMessageContainer->addMessage($error);
				}
			}
			$this->errorAction();
		} else {
			$this->sourceRepository->update($source);
			$this->redirect('index');
		}         
    }

    /**
     * @param Model\EventSource $source
     */
    public function deleteAction(Model\EventSource $source) {
        $this->sourceRepository->remove($source);
        $this->addFlashMessage($source . ' wurde gelöscht', 'Obacht!', Message::SEVERITY_NOTICE);
        $this->redirect('index');
    }	

	
	
	
    /**
     * gets an array of class names implementing EventSourceImplementation
     *
     * @param array $implementations Initial implementation array
     * @return array
     */
    protected function getEventSourceImplementations($implementations = array('' => '---')) {
        return $this->getImplementationSelect(
            EventSourceInterface::class,
            $implementations
        );        
    }

    /**
     * gets an array of class names implementing an given interface
     * 
     * @param string $interface
     * @param array $implementations Initial implementation array
     * @return array
     */
    protected function getImplementationSelect($interface, $implementations = array()) {
        foreach ($this->reflectionService->getAllImplementationClassNamesForInterface($interface) as $className) {
            $readableClassName = $className;
            if (($modelNameStartPosition = \strpos($className, 'Domain\Model')) !== false) {
                $readableClassName = \substr($className, $modelNameStartPosition + 13);
            }
            $implementations[$className] = $readableClassName;
        }
        return $implementations;
    }

}

?>