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

use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Standard controller for the Events package 
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class ExtController extends \TYPO3\FLOW3\MVC\Controller\ActionController {

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
     * @var string
     */
    protected $viewObjectNamePattern = 'Org\Gucken\Events\View\ExtView';

    /**
     * Select special error action
     *
     * @return void
     * @author Robert Lemke <robert@typo3.org>
     */
    protected function initializeAction() {
        $this->errorMethodName = 'extErrorAction';
    }


    /**
     * Return events
     *
     */
    public function listAction() {
        $data = $this->locationRepository->findAll();
        $this->view->assign('value', array('data' => $data, 'success' => TRUE));
    }
 
    /**
     * A preliminary error action for handling validation errors
     * by assigning them to the ExtDirect View that takes care of
     * converting them.
     *
     * @return void
     */
    public function extErrorAction() {
        $this->view->assignErrors($this->arguments->getValidationResults());
    }

}