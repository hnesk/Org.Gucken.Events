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

use TYPO3\Flow\Error\Error;
use TYPO3\Flow\Error\Notice;
use TYPO3\Flow\Mvc\ActionRequest;
use TYPO3\Flow\Mvc\View\ViewInterface;

use TYPO3\Flow\Security\Authentication\Controller\AbstractAuthenticationController;
use TYPO3\Flow\Security\Exception\AuthenticationRequiredException;

/**
 * Login controller for the Events package
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class AccountController extends AbstractAuthenticationController
{

    /**
     * Index action shows login form
     *
     * @return void
     */
    public function indexAction()
    {
    }

    /**
     *
     */
    public function logoutAction()
    {
        parent::logoutAction();
        $this->flashMessageContainer->addMessage(new Notice('Du hast Dich abgemeldet.'));
        $this->redirect('index', 'standard');
    }

    /**
     * Overriden to allow template switching
     *
     * @param ViewInterface $view
     */
    public function initializeView(ViewInterface $view)
    {
        /* @var $view \TYPO3\Fluid\View\TemplateView */
        $currentView = $this->settings['currentView'];
        $view->setLayoutRootPath($this->settings['views'][$currentView]['layoutRootPath']);
        $view->setTemplateRootPath($this->settings['views'][$currentView]['templateRootPath']);
        $view->setPartialRootPath($this->settings['views'][$currentView]['partialRootPath']);
        $view->assign('skinPackage', $this->settings['views'][$currentView]['skinPackage']);
    }

    protected function onAuthenticationFailure(AuthenticationRequiredException $exception = null)
    {
        $this->flashMessageContainer->addMessage(
            new Error(
                'Falscher Benutzername und/oder Passwort!',
                ($exception === null ? 1347016771 : $exception->getCode())
            )
        );
    }

    /**
     * Is called if authentication was successful.
     *
     * @param  ActionRequest $originalRequest The request that was intercepted by the security framework, NULL if there was none
     * @return void
     */
    protected function onAuthenticationSuccess(ActionRequest $originalRequest = null)
    {
        if ($originalRequest !== null) {
            $this->redirectToRequest($originalRequest);
        }
        $this->redirect('index', 'admin');
    }
}
