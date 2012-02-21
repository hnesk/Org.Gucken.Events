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


/**
 * Standard controller for the Events package 
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
abstract class AbstractAdminController extends BaseController {
	/**
	 * Overridden to allow template switching
	 * 
	 * $this->initializeView($view) has to be called before $view->canRender()
	 *
	 * @return \TYPO3\FLOW3\MVC\View\ViewInterface the resolved view
	 * @api
	 */
	protected function resolveView() {
		$viewObjectName = $this->resolveViewObjectName();		
		if ($viewObjectName !== FALSE) {
			$view = $this->objectManager->get($viewObjectName);
			$this->initializeView($view);
			if ($view->canRender($this->controllerContext) === FALSE) {
				unset($view);
			}
		}
		if (!isset($view) && $this->defaultViewObjectName != '') {
			$view = $this->objectManager->get($this->defaultViewObjectName);
			$this->initializeView($view);
			if ($view->canRender($this->controllerContext) === FALSE) {
				unset($view);
			}
		}
		if (!isset($view)) {
			$view = $this->objectManager->get('TYPO3\FLOW3\MVC\View\NotFoundView');
			$view->assign('errorMessage', 'No template was found. View could not be resolved for action "' . $this->request->getControllerActionName() . '"');
		}
		$view->setControllerContext($this->controllerContext);
		
		return $view;
	}
	
	
	
	/**
	 * Overriden to allow template switching 
	 * 
	 * @param \TYPO3\FLOW3\MVC\View\ViewInterface $view 
	 */
	public function initializeView(\TYPO3\FLOW3\MVC\View\ViewInterface $view) {
		/* @var $view \TYPO3\Fluid\View\TemplateView */		
		$currentView = $this->settings['view'];
		$view->setLayoutRootPath($this->settings['views'][$currentView]['layoutRootPath']);
		$view->setTemplateRootPath($this->settings['views'][$currentView]['templateRootPath']);
		$view->setPartialRootPath($this->settings['views'][$currentView]['partialRootPath']);
	}
	
	/**
	 */
	public function redirectIfHtml($actionName, $controllerName = NULL, $packageKey = NULL, array $arguments = NULL, $delay = 0, $statusCode = 303, $format = NULL) {
		if (in_array('text/html',$this->environment->getAcceptedFormats())) {
			$this->redirect($actionName, $controllerName, $packageKey, $arguments, $delay, $statusCode, $format);
		}
		
	}
		
}
?>