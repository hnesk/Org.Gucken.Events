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

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Login controller for the Events package 
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class AccountController extends AbstractAdminController {

	/**
	 * The authentication manager
	 * @var \TYPO3\FLOW3\Security\Authentication\AuthenticationManagerInterface
	 */
	protected $authenticationManager;
	
	
	/**
	 * @var \TYPO3\FLOW3\Security\Context
	 */
	protected $securityContext;	
	
	/**
	 *
	 * @param \TYPO3\FLOW3\Security\Authentication\AuthenticationManagerInterface $authenticationManager 
	 */
	public function injectAuthenticationManager(\TYPO3\FLOW3\Security\Authentication\AuthenticationManagerInterface $authenticationManager) {
		$this->authenticationManager = $authenticationManager;
	}
	
	/**
	 *
	 * @param \TYPO3\FLOW3\Security\Context $securityContext 
	 */
	public function injectSecurityContext(\TYPO3\FLOW3\Security\Context $securityContext) {
		$this->securityContext = $securityContext;
	}

		
	/**
	 * Index action show login form
	 *
	 * @return void
	 */
	public function indexAction() {
	}
	
	/**
	 * Calls the authentication manager to authenticate all active tokens
	 * and redirects to the original intercepted request on success if there
	 * is one stored in the security context. If no intercepted request is
	 * found, the function simply returns.
	 *
	 * If authentication fails, the result of calling the defined
	 * $errorMethodName is returned.
	 *
	 * @return string
	 */
	public function authenticateAction() {
		$authenticated = FALSE;
		try {
			$this->authenticationManager->authenticate();
			$authenticated = TRUE;
		} catch (\TYPO3\FLOW3\Security\Exception\AuthenticationRequiredException $exception) {
		}
		

		if ($authenticated) {
			$storedRequest = $this->securityContext->getInterceptedRequest();
			if ($storedRequest !== NULL) {
				$packageKey = $storedRequest->getControllerPackageKey();
				$subpackageKey = $storedRequest->getControllerSubpackageKey();
				if ($subpackageKey !== NULL) $packageKey .= '\\' . $subpackageKey;
				$this->redirect($storedRequest->getControllerActionName(), $storedRequest->getControllerName(), $packageKey, $storedRequest->getArguments());
			} else {
				$this->redirect('index','admin');
			}
		} else {
			#$this->getControllerContext()->getRequest();
			return call_user_func(array($this, $this->errorMethodName));
		}
	}
	public function getErrorFlashMessage() {
		return new \TYPO3\FLOW3\Error\Error('Falscher Benutzername und/oder Passwort');
		
	}




	public function logoutAction() {
		$this->authenticationManager->logout();
		$this->addNotice('Du hast Dich abgemeldet.');
		$this->redirect('index', 'standard');
	}
}
?>
