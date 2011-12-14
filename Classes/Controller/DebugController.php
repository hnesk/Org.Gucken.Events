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

use Org\Gucken\Events\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Standard controller for the Events package 
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class DebugController extends BaseController {

    /**
     *
     * @var \TYPO3\FLOW3\Configuration\ConfigurationManager
     * @FLOW3\Inject
     */
    protected $configurationManager;

    /**
     * Index action
     * @param array $key
     * @return void
     */
    public function settingsAction($key = array()) {
        $settings = array();
        $configurationClass = new \ReflectionClass('TYPO3\FLOW3\Configuration\ConfigurationManager');
        foreach ($configurationClass->getConstants() as $constantName => $constantValue) {
            if (\strpos($constantName, 'CONFIGURATION_TYPE_') === 0) {
                try {
                    $settings[$constantName] = $this->configurationManager->getConfiguration($constantValue);
                } catch (\TYPO3\FLOW3\Configuration\Exception\InvalidConfigurationTypeException $e) {
                    $settings[$constantName] = 'Exception: ' . $e->getCode() . ' ' . $e->getMessage();
                } catch (\InvalidArgumentException $e) {
                    $settings[$constantName] = 'Exception: ' . $e->getCode() . ' ' . $e->getMessage();
                }
            }
        }
        
        $this->view->assign('settings', $settings);
        $this->view->assign('key',$key);
    }

}

?>