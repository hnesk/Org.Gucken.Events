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

use Org\Gucken\Events\Domain\Model\Type;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;

/**
 * Standard controller for the Events package
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class TypeController extends AbstractAdminController {

    /**
     *
     * @var \Org\Gucken\Events\Domain\Repository\TypeRepository
     * @Flow\Inject
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
     * @param Type $type
     * @Flow\IgnoreValidation("type")
     * @return void
     */
    public function addAction(Type $type = null) {
        $this->view->assign('type', $type);
    }
	/**
	 * @return void
	 */
    public function initializeSaveAction() {
		$this->allowForProperty('type', 'keywords.*', self::CREATION);
		$this->preprocessProperty('type', 'keywords.*', 'keyword');
    }

    /**
     *
     * @param Type $type
     */
    public function saveAction(Type $type) {
        $this->typeRepository->add($type);
        $this->redirect('edit',null, null, array('type' => $type));
    }

    /**
     *
     * @param Type $type
     * @return void
     */
    public function editAction(Type $type) {
        $this->view->assign('type', $type);
    }


	/**
	 * @return void
	 */
    public function initializeUpdateAction() {
		$this->allowForProperty('type', 'keywords.*', self::CREATION);
		$this->preprocessProperty('type', 'keywords.*', 'keyword');
    }

    /**
     *
     * @param Type $type
     */
    public function updateAction(Type $type) {
        $this->typeRepository->update($type);
        $this->redirect('index');
    }


    /**
     *
     * @param Type $type
     */
    public function deleteAction(Type $type) {
        $this->typeRepository->remove($type);
        $this->addFlashMessage($type . ' wurde gelöscht', 'Obacht!', Message::SEVERITY_NOTICE);
        $this->redirect('index');
    }

}

?>
