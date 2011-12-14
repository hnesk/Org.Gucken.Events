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

use Org\Gucken\Events\Domain\Model\Type;
use Org\Gucken\Events\Domain\Model\TypeKeyword;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Standard controller for the Events package 
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class TypeController extends BaseController {
    	
    /**
     *
     * @var \Org\Gucken\Events\Domain\Repository\TypeRepository
     * @FLOW3\Inject
     */
    protected $typeRepository;
        

    /**
     * Index action
     *
     * @return void
     */
    public function indexAction() {
        $types = $this->typeRepository->findAll();
        $this->view->assign('types', $types);
    }
    
    /**
     *
     * @param Org\Gucken\Events\Domain\Model\Type $type
     * @FLOW3\IgnoreValidation("type")
     * @return void
     */
    public function addAction(Type $type = null) {
        $this->view->assign('type', $type);
    }

    /**
     *
     * @param Org\Gucken\Events\Domain\Model\Type $type
     */
    public function saveAction(Type $type) {
        $this->typeRepository->add($type);
        $this->redirect('edit',null, null, array('type' => $type));
    }

    /**
     *
     * @param Org\Gucken\Events\Domain\Model\Type $type
     * @return void
     */
    public function editAction($type) {
        $this->view->assign('type', $type);
    }

	
    public function initializeUpdateAction() {		
		$this->allowForProperty('type', 'keywords.*', self::CREATION);
    }
    
    /**
     *
     * @param Org\Gucken\Events\Domain\Model\Type $type 
     */
    public function updateAction(Type $type) {
		$type->removeEmptyRelations();
        $this->typeRepository->update($type);
        $this->redirect('index');
    }
    
}

?>